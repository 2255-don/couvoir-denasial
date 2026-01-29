<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\EggBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    protected $hatcheryService;

    public function __construct(\App\Services\HatcheryService $hatcheryService)
    {
        $this->hatcheryService = $hatcheryService;
    }

    public function index()
    {
        $user = Auth::user();
        $batches = EggBatch::where('user_id', $user->id)->get();
        $prediction = $this->hatcheryService->getDeliveryPrediction($user);

        $events = [];

        if ($prediction) {
            $events[] = [
                'id' => 'delivery-prediction',
                'title' => '📦 COMMANDE : ' . number_format($prediction['quantity']) . ' œufs',
                'start' => $prediction['date']->format('Y-m-d'),
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => 'Warning'
                ]
            ];
        }

        foreach ($batches as $batch) {
            // Loading Event
            $events[] = [
                'id' => 'load-' . $batch->id,
                'title' => 'Chargement : ' . number_format($batch->quantity) . ' œufs',
                'start' => $batch->loading_date->format('Y-m-d'),
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => 'Holiday' // Colors defined in Vuexy calendar.js
                ]
            ];

            // Transfer Event
            $events[] = [
                'id' => 'transfer-' . $batch->id,
                'title' => 'Transfert vers Éclosoir',
                'start' => $batch->transfer_date->format('Y-m-d'),
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => 'Primary'
                ]
            ];

            // Hatching Event
            $events[] = [
                'id' => 'hatch-' . $batch->id,
                'title' => 'ÉCLOSION ! 🐣',
                'start' => $batch->hatching_date->format('Y-m-d'),
                'allDay' => true,
                'extendedProps' => [
                    'calendar' => 'Success'
                ]
            ];
        }

        return view('pages.calendar.index', compact('events'));
    }
}
