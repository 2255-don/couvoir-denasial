<?php

namespace App\Services;

use App\Models\EggBatch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    protected $hatcheryService;

    public function __construct(HatcheryService $hatcheryService)
    {
        $this->hatcheryService = $hatcheryService;
    }

    public function getActiveNotifications()
    {
        if (!Auth::check()) return [];

        $user = Auth::user();
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        $notifications = [];

        // 1. New batches to load
        // This is based on incubator availability
        $prediction = $this->hatcheryService->getDeliveryPrediction($user);
        if ($prediction && $prediction['date']->lessThanOrEqualTo($tomorrow)) {
            $notifications[] = [
                'type' => 'delivery',
                'title' => '📦 Commande d\'œufs',
                'message' => 'Commander ' . number_format($prediction['quantity']) . ' œufs pour ' . $prediction['unit_name'],
                'date' => $prediction['date'],
                'icon' => 'ti-package',
                'color' => 'warning'
            ];
        }

        // 2. Batches to transfer
        $transfers = EggBatch::where('user_id', $user->id)
            ->where('status', 'incubating')
            ->whereBetween('transfer_date', [$today, $tomorrow])
            ->get();

        foreach ($transfers as $batch) {
            $notifications[] = [
                'type' => 'transfer',
                'title' => '🔄 Transfert nécessaire',
                'message' => 'Transférer ' . number_format($batch->quantity) . ' œufs vers l\'éclosoir',
                'date' => $batch->transfer_date,
                'icon' => 'ti-arrows-transfer-down',
                'color' => 'primary'
            ];
        }

        // 3. Batches to hatch
        $hatchings = EggBatch::where('user_id', $user->id)
            ->where('status', 'hatching')
            ->whereBetween('hatching_date', [$today, $tomorrow])
            ->get();

        foreach ($hatchings as $batch) {
            $notifications[] = [
                'type' => 'hatching',
                'title' => '🐣 Éclosion prévue !',
                'message' => 'Fin de cycle pour ' . number_format($batch->quantity) . ' œufs',
                'date' => $batch->hatching_date,
                'icon' => 'ti-egg',
                'color' => 'success'
            ];
        }

        return $notifications;
    }
}
