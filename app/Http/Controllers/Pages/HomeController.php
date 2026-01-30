<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\EggBatch;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(\App\Services\HatcheryService $hatcheryService)
    {
        $user = Auth::user();
        $user->loadCount(['units as incubator_count' => function($q) {
            $q->where('type', 'incubator');
        }, 'units as hatcher_count' => function($q) {
            $q->where('type', 'hatcher');
        }]);

        $incubatorBatches = EggBatch::where('user_id', $user->id)
            ->where('status', 'incubating')
            ->with('currentUnit')
            ->get();

        $hatcherBatches = EggBatch::where('user_id', $user->id)
            ->where('status', 'hatching')
            ->with('currentUnit')
            ->get();

        $nextRemplissages = $hatcheryService->getNextIncubatorAvailability($user->id);

        return view('pages.home.index', compact('user', 'incubatorBatches', 'hatcherBatches', 'nextRemplissages'));
    }
}
