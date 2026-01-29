@extends('layouts/layoutMaster')

@section('title', 'Configuration du Couvoir')

@section('content')
<h4 class="fw-bold py-3 mb-4">
  <span class="text-muted fw-light">Hatchery /</span> État Actuel
</h4>

<form action="{{ route('info.store') }}" method="POST">
    @csrf
    <div class="row">
        <!-- Stock Card -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Stock d'œufs</h5>
                    <i class="ti ti-package ti-sm"></i>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label" for="egg_stock">Nombre d'œufs en stock</label>
                            <input type="number" id="egg_stock" name="egg_stock" class="form-control" value="{{ $user->egg_stock }}" placeholder="Ex: 57600">
                        </div>
                        @if($prediction)
                        <div class="col-md-8">
                            <div class="alert alert-warning d-flex align-items-center mb-0" role="alert">
                                <span class="alert-icon text-warning me-2">
                                  <i class="ti ti-bell ti-xs"></i>
                                </span>
                                <div>
                                    <strong>Conseil :</strong> Prévoir une livraison de <strong>{{ number_format($prediction['quantity']) }}</strong> œufs pour le <strong>{{ $prediction['date']->format('d/m/Y') }}</strong> (Libération de {{ $prediction['unit_name'] }}).
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Incubators Section -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-label-primary">
                    <h5 class="mb-0">Mes Incubateurs (18 jours)</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Capacité</th>
                                    <th>Rempli ?</th>
                                    <th>Date Chargement</th>
                                    <th>Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($incubators as $incubator)
                                @php $batch = $incubator->eggBatches->first(); @endphp
                                <tr>
                                    <td>
                                        <input type="text" name="incubators[{{ $incubator->id }}][name]" class="form-control form-control-sm" value="{{ $incubator->name }}">
                                    </td>
                                    <td>
                                        <input type="number" name="incubators[{{ $incubator->id }}][capacity]" class="form-control form-control-sm" value="{{ $incubator->capacity }}">
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="incubators[{{ $incubator->id }}][is_filled]" {{ $batch ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="date" name="incubators[{{ $incubator->id }}][loading_date]" class="form-control form-control-sm" value="{{ $batch ? $batch->loading_date->format('Y-m-d') : '' }}">
                                    </td>
                                    <td>
                                        <input type="number" name="incubators[{{ $incubator->id }}][quantity]" class="form-control form-control-sm" value="{{ $batch ? $batch->quantity : '' }}" placeholder="{{ $incubator->capacity }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hatchers Section -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-label-success">
                    <h5 class="mb-0">Mes Éclosoirs (3 jours)</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="table-responsive text-nowrap">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Capacité</th>
                                    <th>Rempli ?</th>
                                    <th>Quantité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hatchers as $hatcher)
                                @php $batch = $hatcher->eggBatches->first(); @endphp
                                <tr>
                                    <td>
                                        <input type="text" name="hatchers[{{ $hatcher->id }}][name]" class="form-control form-control-sm" value="{{ $hatcher->name }}">
                                    </td>
                                    <td>
                                        <input type="number" name="hatchers[{{ $hatcher->id }}][capacity]" class="form-control form-control-sm" value="{{ $hatcher->capacity }}">
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input" type="checkbox" name="hatchers[{{ $hatcher->id }}][is_filled]" {{ $batch ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="hatchers[{{ $hatcher->id }}][quantity]" class="form-control form-control-sm" value="{{ $batch ? $batch->quantity : '' }}" placeholder="{{ $hatcher->capacity }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-primary me-sm-3 me-1">Enregistrer les informations</button>
            <button type="reset" class="btn btn-label-secondary">Annuler</button>
        </div>
    </div>
</form>
@endsection
