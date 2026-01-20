@extends('layouts.app')

@section('title', 'Liste des Pointages')

@section('header', 'Gestion des Pointages (Présences)')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6 border-top border-4 border-primary">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-xl font-bold text-primary">
            <i class="fas fa-clipboard-list me-2"></i>Enregistrements de Pointage
        </h2>
        <a href="{{ route('presences.create') }}" class="btn btn-success shadow-sm fw-bold">
            <i class="fas fa-plus-circle me-1"></i> Nouveau Pointage
        </a>
    </div>

    <!-- ZONE DE FILTRES COLORÉE -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-primary text-white py-2 fw-bold">
            <i class="fas fa-search me-2"></i> Filtres de recherche
        </div>
        <div class="card-body bg-light border border-top-0 rounded-bottom">
            <form action="{{ route('presences.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark">Nom de l'Agent</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-user text-primary"></i></span>
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Nom ou Prénom..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark">Statut</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-toggle-on text-primary"></i></span>
                        <select name="statut" class="form-select form-select-sm">
                            <option value="">Tous les statuts</option>
                            @foreach(['Présent', 'En Retard', 'Absent', 'permission'] as $s)
                                <option value="{{ $s }}" {{ request('statut') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark">Date</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-calendar-alt text-primary"></i></span>
                        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                    </div>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold shadow-sm">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('presences.index') }}" class="btn btn-dark btn-sm w-100 fw-bold shadow-sm">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive rounded shadow-sm">
        <table class="table table-hover align-middle border">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="py-3 px-3">ID</th>
                    <th>Nom & Prénoms</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presences as $presence)
                    @php
                        $rowColor = match($presence->statut) {
                            'Présent' => 'table-success',
                            'En Retard' => 'table-warning',
                            'Absent' => 'table-danger',
                            'permission' => 'table-info',
                            default => '',
                        };
                        $badgeClass = match($presence->statut) {
                            'Présent' => 'bg-success',
                            'En Retard' => 'bg-warning',
                            'Absent' => 'bg-danger',
                            'permission' => 'bg-info',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <tr class="{{ $rowColor }} opacity-transition">
                        <td class="fw-bold px-3">#{{ $presence->id }}</td>
                        <td>
                            <div class="fw-bold text-uppercase text-dark">{{ $presence->agent->name ?? '' }}</div>
                            <div class="small text-secondary">{{ $presence->agent->last_name ?? '' }} {{ $presence->agent->first_name ?? '' }}</div>
                        </td>
                        <td><span class="text-dark fw-medium">{{ \Carbon\Carbon::parse($presence->heure_arrivee)->format('d/m/Y H:i') }}</span></td>
                        <td>
                            @if($presence->heure_depart)
                                <span class="text-dark fw-medium">{{ \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') }}</span>
                            @else
                                <span class="badge bg-light text-dark border fw-normal"><i class="far fa-clock me-1"></i>En cours</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $badgeClass }} text-white px-3 py-2 shadow-sm w-100" style="min-width: 100px;">
                                {{ strtoupper($presence->statut) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <a href="{{ route('presences.show', $presence->id) }}" class="btn btn-sm btn-info text-white" title="Détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('presences.edit', $presence->id) }}" class="btn btn-sm btn-warning text-white" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted bg-light">
                            <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i><br>
                            Aucun enregistrement trouvé.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- PAGINATION PERSONNALISÉE (SANS TEXTE ANGLAIS) -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <div class="btn-group shadow-sm">
            @if($presences->onFirstPage())
                <span class="btn btn-secondary btn-sm disabled"><i class="fas fa-chevron-left me-1"></i> Précédent</span>
            @else
                <a href="{{ $presences->appends(request()->query())->previousPageUrl() }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-chevron-left me-1"></i> Précédent
                </a>
            @endif

            @if($presences->hasMorePages())
                <a href="{{ $presences->appends(request()->query())->nextPageUrl() }}" class="btn btn-primary btn-sm">
                    Suivant <i class="fas fa-chevron-right ms-1"></i>
                </a>
            @else
                <span class="btn btn-secondary btn-sm disabled">Suivant <i class="fas fa-chevron-right ms-1"></i></span>
            @endif
        </div>

        <div class="text-secondary small fw-bold bg-light px-3 py-2 rounded border shadow-sm">
            Page {{ $presences->currentPage() }} sur {{ $presences->lastPage() }}
            <span class="text-muted ms-2">({{ $presences->total() }} résultats)</span>
        </div>
    </div>
</div>

<style>
    .opacity-transition { transition: all 0.2s; }
    .opacity-transition:hover { filter: brightness(0.95); }
    .table-success { background-color: rgba(209, 231, 221, 0.4) !important; }
    .table-warning { background-color: rgba(255, 243, 205, 0.4) !important; }
    .table-danger { background-color: rgba(248, 215, 218, 0.4) !important; }
    .table-info { background-color: rgba(207, 244, 252, 0.4) !important; }
</style>
@endsection
