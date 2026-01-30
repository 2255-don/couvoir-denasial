<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\EggBatch;
use App\Models\Unit;
use App\Models\User;
use App\Services\HatcheryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    protected $hatcheryService;

    public function __construct(HatcheryService $hatcheryService)
    {
        $this->hatcheryService = $hatcheryService;
    }

    public function quickFill(Unit $unit)
    {
        $user = Auth::user();

        // Security check: ensure the unit belongs to the user and is an incubator
        if ($unit->user_id !== $user->id || $unit->type !== 'incubator') {
            return back()->with('error', 'Action non autorisée.');
        }

        // Check availability: ensure the unit doesn't have an active batch
        $activeBatch = EggBatch::where('current_unit_id', $unit->id)
            ->whereIn('status', ['incubating', 'hatching'])
            ->first();

        if ($activeBatch) {
            return back()->with('error', 'Cet incubateur est déjà occupé.');
        }

        // Check stock
        if ($user->egg_stock <= 0) {
            return back()->with('error', 'Votre stock d\'œufs est vide.');
        }

        // Determine quantity to fill (min between stock and capacity)
        $quantity = min($user->egg_stock, $unit->capacity);

        // Calculate dates
        $dates = $this->hatcheryService->calculateDates(now());

        // Create the batch
        EggBatch::create([
            'user_id' => $user->id,
            'quantity' => $quantity,
            'loading_date' => $dates['loading_date'],
            'transfer_date' => $dates['transfer_date'],
            'hatching_date' => $dates['hatching_date'],
            'current_unit_id' => $unit->id,
            'status' => 'incubating',
        ]);

        // Update user stock
        User::where('id', $user->id)->decrement('egg_stock', $quantity);

        return back()->with('success', "Incubateur {$unit->name} rempli avec " . number_format($quantity) . " œufs.");
    }
}
