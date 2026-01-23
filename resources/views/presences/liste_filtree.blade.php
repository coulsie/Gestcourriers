@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <!-- EN-TÊTE AVEC BOUTON FERMER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <a href="{{ url('/home') }}" class="btn btn-outline-danger me-3 shadow-sm border-2">
                <i class="fas fa-times"></i>
            </a>
            <h3 class="mb-0 fw-bold text-dark text-uppercase">Rapport Global des Présences 2026</h3>
        </div>
        <button onclick="window.print()" class="btn btn-secondary shadow-sm px-4">
            <i class="fas fa-print me-2"></i> Imprimer le rapport
        </button>
    </div>

    <!-- ZONE DE FILTRAGE AVANCÉE -->
    <div class="card shadow-sm border-0 mb-4 bg-dark text-white rounded-3">
        <div class="card-body p-4">
            <form action="{{ route('presences.listeFiltree') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Début</label>
                    <input type="date" name="date_debut" class="form-control border-0 shadow-sm" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Fin</label>
                    <input type="date" name="date_fin" class="form-control border-0 shadow-sm" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Direction</label>
                    <select name="direction_id" class="form-select border-0 shadow-sm">
                        <option value="">Toutes les Directions</option>
                        @foreach($directions as $dir)
                            <option value="{{ $dir->id }}" {{ request('direction_id') == $dir->id ? 'selected' : '' }}>
                                {{ $dir->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Service</label>
                    <select name="service_id" class="form-select border-0 shadow-sm">
                        <option value="">Tous les Services</option>
                        @foreach($services as $ser)
                            <option value="{{ $ser->id }}" {{ request('service_id') == $ser->id ? 'selected' : '' }}>
                                {{ $ser->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-uppercase text-white-50">Statut</label>
                    <select name="statut" class="form-select border-0 shadow-sm">
                        <option value="">Tous</option>
                        <option value="Présent" {{ request('statut') == 'Présent' ? 'selected' : '' }}>🟢 Présent</option>
                        <option value="En Retard" {{ request('statut') == 'En Retard' ? 'selected' : '' }}>🟡 En Retard</option>
                        <option value="Absent" {{ request('statut') == 'Absent' ? 'selected' : '' }}>🔴 Absent</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">Filtrer</button>
                    <a href="{{ route('presences.listeFiltree') }}" class="btn btn-light shadow-sm"><i class="fas fa-sync-alt"></i></a>
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
                            <th class="py-3 border-0">Service</th>
                            <th class="py-3 border-0">Direction</th>
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
                                    <div class="small text-secondary text-capitalize">{{ $p->agent->first_name }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-primary border px-2 py-2">
                                        {{ $p->agent->service->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted small fw-bold">
                                        {{ $p->agent->service->direction->name ?? 'N/A' }}
                                    </span>
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
                                        $bg = 'bg-secondary';
                                        if($p->statut == 'Présent') $bg = 'bg-success';
                                        elseif($p->statut == 'En Retard') $bg = 'bg-warning text-dark';
                                        elseif($p->statut == 'Absent') $bg = 'bg-danger';
                                    @endphp
                                    <span class="badge {{ $bg }} text-white px-3 py-2 shadow-sm w-100" style="max-width: 120px;">
                                        {{ $p->statut }}
                                    </span>
                                </td>
                                <td class="pe-4 italic small text-muted text-end">
                                    {{ $p->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted italic">Aucun résultat trouvé pour cette sélection.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- FOOTER AVEC PAGINATION ET INFOS PAGES -->
        @if($resultats->total() > 0)
        <div class="card-footer bg-white py-3 border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Info : Nombre de pages -->
                <div class="text-muted small">
                    Affichage de <b>{{ $resultats->firstItem() }}</b> à <b>{{ $resultats->lastItem() }}</b> sur <b>{{ $resultats->total() }}</b> présences
                    (Page <b>{{ $resultats->currentPage() }}</b> sur <b>{{ $resultats->lastPage() }}</b>)
                </div>

                <!-- Navigation : Précédent / Suivant -->
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <!-- Bouton Précédent -->
                        @if ($resultats->onFirstPage())
                            <li class="page-item disabled"><span class="page-link shadow-none">Précédent</span></li>
                        @else
                            <li class="page-item"><a class="page-link border-primary text-primary shadow-none" href="{{ $resultats->previousPageUrl() }}" rel="prev">Précédent</a></li>
                        @endif

                        <!-- Numéros de pages (Optionnel, masqués sur mobile) -->
                        <li class="page-item active d-none d-md-block"><span class="page-link bg-primary border-primary">{{ $resultats->currentPage() }}</span></li>

                        <!-- Bouton Suivant -->
                        @if ($resultats->hasMorePages())
                            <li class="page-item"><a class="page-link border-primary text-primary shadow-none" href="{{ $resultats->nextPageUrl() }}" rel="next">Suivant</a></li>
                        @else
                            <li class="page-item disabled"><span class="page-link shadow-none">Suivant</span></li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .bg-black { background-color: #000000 !important; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; transition: 0.2s; }
    .italic { font-style: italic; }
    .page-link:hover { background-color: #f8f9fa; }
    @media print {
        .btn, form, .card-footer, .btn-group { display: none !important; }
        .card { border: none !important; }
        .bg-black { background-color: #000 !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endsection
