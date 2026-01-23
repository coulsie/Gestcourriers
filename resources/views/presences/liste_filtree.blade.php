@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- EN-TÊTE -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ url('/home') }}" class="btn btn-outline-danger me-3 shadow-sm border-2">
                <i class="fas fa-times"></i>
            </a>
            <h3 class="mb-0 fw-bold text-dark text-uppercase">Rapport Global des Présences 2026</h3>
        </div>
        <button onclick="window.print()" class="btn btn-secondary shadow-sm px-4">
            <i class="fas fa-print me-2"></i> Imprimer
        </button>
    </div>

    <!-- ZONE DE FILTRAGE RANGÉE -->
    <div class="card shadow-sm border-0 mb-4 bg-dark text-white rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('presences.listeFiltree') }}" method="GET">
                <!-- LIGNE 1 : TEMPS ET STATUT -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Période du Rapport</label>
                        <div class="input-group">
                            <span class="input-group-text bg-secondary border-0 text-white small">Du</span>
                            <input type="date" name="date_debut" class="form-control border-0" value="{{ request('date_debut') }}">
                            <span class="input-group-text bg-secondary border-0 text-white small">Au</span>
                            <input type="date" name="date_fin" class="form-control border-0" value="{{ request('date_fin') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Statut de présence</label>
                        <select name="statut" class="form-select border-0">
                            <option value="">Tous les statuts</option>
                            <option value="Présent" {{ request('statut') == 'Présent' ? 'selected' : '' }}>🟢 Présent</option>
                            <option value="En Retard" {{ request('statut') == 'En Retard' ? 'selected' : '' }}>🟡 En Retard</option>
                            <option value="Absent" {{ request('statut') == 'Absent' ? 'selected' : '' }}>🔴 Absent</option>
                        </select>
                    </div>
                    <div class="col-md-5 d-flex align-items-end justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                            <i class="fas fa-filter me-2"></i>Appliquer les filtres
                        </button>
                        <a href="{{ route('presences.listeFiltree') }}" class="btn btn-outline-light shadow-sm">
                            <i class="fas fa-sync-alt"></i> Réinitialiser
                        </a>
                    </div>
                </div>

                <!-- LIGNE 2 : STRUCTURE ORGANISATIONNELLE -->
                <div class="row g-3 pt-3 border-top border-secondary">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Filtrer par Direction</label>
                        <select name="direction_id" class="form-select border-0 shadow-sm">
                            <option value="">Toutes les Directions</option>
                            @foreach($directions as $dir)
                                <option value="{{ $dir->id }}" {{ request('direction_id') == $dir->id ? 'selected' : '' }}>
                                    {{ $dir->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Filtrer par Service</label>
                        <select name="service_id" class="form-select border-0 shadow-sm">
                            <option value="">Tous les Services</option>
                            @foreach($services as $ser)
                                <option value="{{ $ser->id }}" {{ request('service_id') == $ser->id ? 'selected' : '' }}>
                                    {{ $ser->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLEAU DE RÉSULTATS -->
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-black text-white">
                        <tr>
                            <th class="py-3 ps-4 border-0">
                                Agent
                                <div class="btn-group btn-group-sm ms-2">
                                    <a href="{{ request()->fullUrlWithQuery(['sort_agent' => 'asc']) }}" class="text-white opacity-75"><i class="fas fa-sort-alpha-up"></i></a>
                                    <a href="{{ request()->fullUrlWithQuery(['sort_agent' => 'desc']) }}" class="text-white opacity-75 ms-2"><i class="fas fa-sort-alpha-down"></i></a>
                                </div>
                            </th>
                            <th class="py-3 border-0">Service / Direction</th>
                            <th class="py-3 border-0 text-center">Date</th>
                            <th class="py-3 border-0 text-center">Pointage</th>
                            <th class="py-3 border-0 text-center">Statut</th>
                            <th class="py-3 pe-4 border-0 text-end">Note</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($resultats as $p)
                            <tr class="border-bottom">
                                <td class="ps-4">
                                    <div class="fw-bold text-dark">{{ strtoupper($p->agent->last_name) }}</div>
                                    <div class="small text-secondary">{{ $p->agent->first_name }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold small text-primary">{{ $p->agent->service->name ?? 'N/A' }}</div>
                                    <div class="extra-small text-muted">{{ $p->agent->service->direction->name ?? 'N/A' }}</div>
                                </td>
                                <td class="text-center fw-bold text-secondary">
                                    {{ \Carbon\Carbon::parse($p->heure_arrivee)->format('d/m/2026') }}
                                </td>
                                <td class="text-center">
                                    <span class="text-success fw-bold">{{ \Carbon\Carbon::parse($p->heure_arrivee)->format('H:i') }}</span>
                                    <i class="fas fa-long-arrow-alt-right mx-1 text-muted"></i>
                                    <span class="text-danger fw-bold">{{ $p->heure_depart ? \Carbon\Carbon::parse($p->heure_depart)->format('H:i') : '--:--' }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $bg = match($p->statut) {
                                            'Présent' => 'bg-success',
                                            'En Retard' => 'bg-warning text-dark',
                                            'Absent' => 'bg-danger',
                                            default => 'bg-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $bg }} text-white px-3 py-2 shadow-sm w-100" style="max-width: 110px;">
                                        {{ $p->statut }}
                                    </span>
                                </td>
                                <td class="pe-4 italic small text-muted text-end">{{ $p->notes ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">Aucune donnée trouvée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER PAGINATION -->
        @if($resultats->total() > 0)
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <div class="text-muted small">
                Page <b>{{ $resultats->currentPage() }}</b> sur <b>{{ $resultats->lastPage() }}</b>
                (<b>{{ $resultats->total() }}</b> entrées)
            </div>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item {{ $resultats->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link shadow-none" href="{{ $resultats->previousPageUrl() }}">Précédent</a>
                    </li>
                    <li class="page-item {{ !$resultats->hasMorePages() ? 'disabled' : '' }}">
                        <a class="page-link shadow-none" href="{{ $resultats->nextPageUrl() }}">Suivant</a>
                    </li>
                </ul>
            </nav>
        </div>
        @endif
    </div>
</div>

<style>
    .bg-black { background-color: #000000 !important; }
    .extra-small { font-size: 0.72rem; }
    .italic { font-style: italic; }
    .input-group-text { font-size: 0.75rem; }
    @media print { .btn, form, .card-footer, .btn-group { display: none !important; } }
</style>
@endsection
