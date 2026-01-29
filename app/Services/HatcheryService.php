<?php

namespace App\Services;

use App\Models\EggBatch;
use App\Models\Unit;
use Carbon\Carbon;

class HatcheryService
{
    /**
     * Calculate dates for a new egg batch.
     */
    public function calculateDates($loadingDate)
    {
        $loading = Carbon::parse($loadingDate);
        return [
            'loading_date' => $loading->copy(),
            'transfer_date' => $loading->copy()->addDays(18),
            'hatching_date' => $loading->copy()->addDays(21),
        ];
    }

    /**
     * Get next availability for incubators.
     */
    public function getNextIncubatorAvailability($userId)
    {
        $incubators = Unit::where('user_id', $userId)
            ->where('type', 'incubator')
            ->where('is_active', true)
            ->get();

        $availability = [];

        foreach ($incubators as $incubator) {
            $lastBatch = EggBatch::where('current_unit_id', $incubator->id)
                ->where('status', 'incubating')
                ->orderBy('transfer_date', 'desc')
                ->first();

            $availableFrom = $lastBatch ? $lastBatch->transfer_date : Carbon::now();
            
            $availability[] = [
                'unit' => $incubator,
                'available_from' => $availableFrom,
            ];
        }

        return collect($availability)->sortBy('available_from');
    }

    /**
     * Get next availability for hatchers.
     */
    public function getNextHatcherAvailability($userId)
    {
        $hatchers = Unit::where('user_id', $userId)
            ->where('type', 'hatcher')
            ->where('is_active', true)
            ->get();

        $availability = [];

        foreach ($hatchers as $hatcher) {
            $lastBatch = EggBatch::where('current_unit_id', $hatcher->id)
                ->where('status', 'hatching')
                ->orderBy('hatching_date', 'desc')
                ->first();

            $availableFrom = $lastBatch ? $lastBatch->hatching_date->addDay() : Carbon::now();
            
            $availability[] = [
                'unit' => $hatcher,
                'available_from' => $availableFrom,
            ];
        }

        return collect($availability)->sortBy('available_from');
    }

    /**
     * Get next recommended egg delivery.
     */
    public function getDeliveryPrediction($user)
    {
        $stock = $user->egg_stock ?? 0;
        $now = Carbon::now();
        
        // Get all incubators sorted by real transfer_date
        $availabilities = $this->getNextIncubatorAvailability($user->id);
        
        foreach ($availabilities as $item) {
            $unit = $item['unit'];
            $availableFrom = $item['available_from'];
            
            // If the incubator is already free or overdue (date <= now), 
            // we "consume" our stock to fill it, but we don't return a past prediction.
            if ($availableFrom->lessThanOrEqualTo($now)) {
                $stock = max(0, $stock - $unit->capacity);
                continue;
            }

            // For future availability slots, if stock is not enough:
            if ($stock < $unit->capacity) {
                $orderDate = $availableFrom->copy()->subDays(1);
                
                // Only return if the recommendation is for a FUTURE date
                if ($orderDate->isFuture()) {
                    return [
                        'date' => $orderDate,
                        'quantity' => $unit->capacity - $stock,
                        'unit_name' => $unit->name
                    ];
                }
            }
            
            // Use remaining stock for this future slot and continue
            $stock = max(0, $stock - $unit->capacity);
        }

        return null;
    }
}
