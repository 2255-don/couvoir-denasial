@extends('layouts/layoutMaster')

@section('title', 'Accueil - Tableau de Bord')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-lg-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    @if($user->logo)
                        <img src="{{ asset('storage/' . $user->logo) }}" alt="Logo" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid white;">
                    @else
                        <div class="avatar avatar-md me-3">
                            <span class="avatar-initial rounded-circle bg-white text-primary fw-bold">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-white mb-0">Bonjour, {{ $user->name }} ! 👋</h4>
                        <p class="mb-0">Bienvenue sur votre système de gestion de couvoir autonome.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="avatar avatar-md bg-label-info mb-3 mx-auto">
                    <i class="ti ti-package ti-md text-info"></i>
                </div>
                <h5 class="mb-1">Stock Actuel</h5>
                <p class="mb-0 fw-bold">{{ number_format($user->egg_stock) }} œufs</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="avatar avatar-md bg-label-primary mb-3 mx-auto">
                    <i class="ti ti-box ti-md text-primary"></i>
                </div>
                <h5 class="mb-1">Incubateurs</h5>
                <p class="mb-0 fw-bold">{{ $user->incubator_count }} unités</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="avatar avatar-md bg-label-success mb-3 mx-auto">
                    <i class="ti ti-layout-grid ti-md text-success"></i>
                </div>
                <h5 class="mb-1">Éclosoirs</h5>
                <p class="mb-0 fw-bold">{{ $user->hatcher_count }} unités</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body text-center">
                <div class="avatar avatar-md bg-label-warning mb-3 mx-auto">
                    <i class="ti ti-loader ti-md text-warning"></i>
                </div>
                <h5 class="mb-1">Cycles en cours</h5>
                <p class="mb-0 fw-bold">{{ $incubatorBatches->count() + $hatcherBatches->count() }} lots</p>
            </div>
        </div>
    </div>

    <!-- Incubators Table -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-box me-2 text-primary"></i>Incubateurs (Incubation)</h5>
                <span class="badge bg-primary">{{ $incubatorBatches->count() }} actif(s)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Unité</th>
                            <th>Quantité</th>
                            <th>Transfert</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($incubatorBatches as $batch)
                        <tr>
                            <td><span class="fw-bold">{{ $batch->currentUnit->name ?? 'N/A' }}</span></td>
                            <td>{{ number_format($batch->quantity) }}</td>
                            <td><span class="text-info">{{ $batch->transfer_date->format('d/m/Y') }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted small italic">Aucun incubateur en cours</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hatchers Table -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-layout-grid me-2 text-success"></i>Éclosoirs (Éclosion)</h5>
                <span class="badge bg-success">{{ $hatcherBatches->count() }} actif(s)</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Unité</th>
                            <th>Quantité</th>
                            <th>Éclosion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($hatcherBatches as $batch)
                        <tr>
                            <td><span class="fw-bold">{{ $batch->currentUnit->name ?? 'N/A' }}</span></td>
                            <td>{{ number_format($batch->quantity) }}</td>
                            <td><span class="text-success">{{ $batch->hatching_date->format('d/m/Y') }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4 text-muted small italic">Aucun éclosoir en cours</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
