<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use App\Models\EggBatch;
use App\Services\HatcheryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InfoController extends Controller
{
    protected $hatcheryService;

    public function __construct(HatcheryService $hatcheryService)
    {
        $this->hatcheryService = $hatcheryService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Ensure default 3 incubators and 3 hatchers if none exist
        if ($user->units()->count() == 0) {
            for ($i = 1; $i <= 3; $i++) {
                $user->units()->create(['name' => "Incubateur $i", 'type' => 'incubator', 'capacity' => 57600]);
                $user->units()->create(['name' => "Éclosoir $i", 'type' => 'hatcher', 'capacity' => 19200]);
            }
        }

        $user->load(['units.eggBatches' => function($q) {
            $q->whereIn('status', ['incubating', 'hatching']);
        }]);
        
        $incubators = $user->units()->where('type', 'incubator')->get();
        $hatchers = $user->units()->where('type', 'hatcher')->get();
        
        $prediction = $this->hatcheryService->getDeliveryPrediction($user);
        
        return view('pages.info.index', compact('user', 'incubators', 'hatchers', 'prediction'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Explicitly update user stock
        \App\Models\User::where('id', $user->id)->update(['egg_stock' => $request->egg_stock]);

        // Handle Incubators
        if ($request->has('incubators')) {
            foreach ($request->incubators as $id => $data) {
                $unit = Unit::updateOrCreate(
                    ['id' => $id, 'user_id' => $user->id, 'type' => 'incubator'],
                    ['name' => $data['name'], 'capacity' => $data['capacity']]
                );

                if (isset($data['is_filled']) && $data['is_filled'] == 'on') {
                    $dates = $this->hatcheryService->calculateDates($data['loading_date'] ?? now());
                    EggBatch::updateOrCreate(
                        ['current_unit_id' => $unit->id, 'status' => 'incubating'],
                        [
                            'user_id' => $user->id,
                            'quantity' => $data['quantity'] ?? $unit->capacity,
                            'loading_date' => $dates['loading_date'],
                            'transfer_date' => $dates['transfer_date'],
                            'hatching_date' => $dates['hatching_date'],
                        ]
                    );
                } else {
                    EggBatch::where('current_unit_id', $unit->id)->where('status', 'incubating')->delete();
                }
            }
        }

        // Handle Hatchers
        if ($request->has('hatchers')) {
            foreach ($request->hatchers as $id => $data) {
                $unit = Unit::updateOrCreate(
                    ['id' => $id, 'user_id' => $user->id, 'type' => 'hatcher'],
                    ['name' => $data['name'], 'capacity' => $data['capacity']]
                );

                if (isset($data['is_filled']) && $data['is_filled'] == 'on') {
                    // For hatchers, we assume they were moved from an incubator or just started there.
                    // If started there, we don't have a loading date in incubator, but we can set hatching date.
                    // User didn't specify loading date for hatcher, just "nombre d'eclosoir remplit".
                    EggBatch::updateOrCreate(
                        ['current_unit_id' => $unit->id, 'status' => 'hatching'],
                        [
                            'user_id' => $user->id,
                            'quantity' => $data['quantity'] ?? $unit->capacity,
                            'loading_date' => now()->subDays(18), // Approximation
                            'transfer_date' => now(),
                            'hatching_date' => now()->addDays(3),
                        ]
                    );
                } else {
                    EggBatch::where('current_unit_id', $unit->id)->where('status', 'hatching')->delete();
                }
            }
        }

        return redirect()->route('info')->with('success', 'Configuration mise à jour avec succès.');
    }
}
