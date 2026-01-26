@extends('layouts.app')

@section('content')
<style>
    /* Styles pour l'impression */
    @media print {
        .no-print { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        thead { display: table-header-group; background-color: #000 !important; color: #fff !important; }
        tr { page-break-inside: avoid; }
        .table td, .table th { padding: 8px !important; border: 1px solid #dee2e6 !important; }
    }

    .extra-small { font-size: 0.75rem; }
    .table-header-custom { background-color: #000; color: #fff; text-transform: uppercase; font-size: 0.85rem; }

    /* Couleurs fixes pour les statuts */
    .badge-present { background-color: #198754 !important; color: #ffffff !important; } /* Vert */
    .badge-retard { background-color: #ffc107 !important; color: #000000 !important; }  /* Jaune (texte noir pour contraste) */
    .badge-absent { background-color: #dc3545 !important; color: #ffffff !important; }  /* Rouge */
    .badge-justifie { background-color: #0d6efd !important; color: #ffffff !important; } /* Bleu */
</style>

<div class="container-fluid py-4 px-4">
    <!-- EN-TÊTE -->
    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
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

    <!-- ZONE DE FILTRAGE (no-print) -->
    <div class="card shadow-sm border-0 mb-4 bg-dark text-white rounded-3 no-print">
        <div class="card-body p-4">
            <form action="{{ route('presences.listeFiltree') }}" method="GET" id="filterForm">
                <div class="row g-3 mb-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Période (Année 2026)</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-secondary border-0 text-white small">Du</span>
                            <input type="date" name="date_debut" class="form-control border-0" value="{{ request('date_debut') }}">
                            <span class="input-group-text bg-secondary border-0 text-white small">Au</span>
                            <input type="date" name="date_fin" class="form-control border-0" value="{{ request('date_fin') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Statut</label>
                        <select name="statut" class="form-select border-0 shadow-sm">
                            <option value="">Tous les statuts</option>
                            <option value="Présent" {{ request('statut') == 'Présent' ? 'selected' : '' }}>🟢 Présent</option>
                            <option value="En Retard" {{ request('statut') == 'En Retard' ? 'selected' : '' }}>🟡 En Retard</option>
                            <option value="Absent" {{ request('statut') == 'Absent' ? 'selected' : '' }}>🔴 Absent</option>
                        </select>
                    </div>
                    <div class="col-md-5 d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm">
                            <i class="fas fa-filter me-2"></i>Appliquer
                        </button>
                        <a href="{{ route('presences.listeFiltree') }}" class="btn btn-outline-light shadow-sm">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>

                <div class="row g-3 pt-3 border-top border-secondary">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Direction</label>
                        <select name="direction_id" id="direction_id" class="form-select border-0 shadow-sm">
                            <option value="">Toutes les Directions</option>
                            @foreach($directions as $dir)
                                <option value="{{ $dir->id }}" {{ request('direction_id') == $dir->id ? 'selected' : '' }}>
                                    {{ $dir->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold text-uppercase text-white-50">Service</label>
                        <select name="service_id" id="service_id" class="form-select border-0 shadow-sm">
                            <option value="">Tous les Services</option>
                            @foreach($services as $ser)
                                <option value="{{ $ser->id }}"
                                        data-direction="{{ $ser->direction_id }}"
                                        {{ request('service_id') == $ser->id ? 'selected' : '' }}
                                        style="{{ request('direction_id') && request('direction_id') != $ser->direction_id ? 'display:none' : '' }}">
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
                    <thead class="table-header-custom">
                        <tr>
                            <th class="py-3 ps-4">Agent (Nom & Prénoms)</th>
                            <th class="py-3">Service / Direction</th>
                            <th class="py-3 text-center">Date</th>
                            <th class="py-3 text-center">Heures</th>
                            <th class="py-3 text-center">Statut</th>
                            <th class="py-3 pe-4 text-end">Observations</th>
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
                                    <div class="extra-small text-muted text-uppercase">{{ $p->agent->service->direction->name ?? 'N/A' }}</div>
                                </td>
                                <td class="text-center fw-bold text-secondary">
                                    {{ \Carbon\Carbon::parse($p->heure_arrivee)->format('d/m/2026') }}
                                </td>
                                <td class="text-center">
                                    <span class="text-success fw-bold">{{ \Carbon\Carbon::parse($p->heure_arrivee)->format('H:i') }}</span>
                                    <span class="mx-1 text-muted small">-</span>
                                    <span class="text-danger fw-bold">{{ $p->heure_depart ? \Carbon\Carbon::parse($p->heure_depart)->format('H:i') : '--:--' }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $badgeClass = match($p->statut) {
                                            'Présent' => 'badge-present',
                                            'En Retard' => 'badge-retard',
                                            'Absent' => 'badge-absent',
                                            'Absence Justifiée' => 'badge-justifie',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} rounded-pill px-3 shadow-sm" style="min-width: 90px; font-weight: 600;">
                                        {{ $p->statut }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end small text-muted italic">
                                    {{ $p->note ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Aucune donnée trouvée pour cette période.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PAGINATION (Suivant / Précédent) -->
        @if($resultats->hasPages())
        <div class="card-footer bg-white border-top py-3 no-print">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted">
                    Affichage de {{ $resultats->firstItem() }} à {{ $resultats->lastItem() }} sur {{ $resultats->total() }} résultats
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        {{-- Bouton Précédent --}}
                        @if ($resultats->onFirstPage())
                            <li class="page-item disabled"><span class="page-link shadow-none">Précédent</span></li>
                        @else
                            <li class="page-item"><a class="page-link shadow-none" href="{{ $resultats->previousPageUrl() }}" rel="prev">Précédent</a></li>
                        @endif

                        {{-- Bouton Suivant --}}
                        @if ($resultats->hasMorePages())
                            <li class="page-item"><a class="page-link shadow-none" href="{{ $resultats->nextPageUrl() }}" rel="next">Suivant</a></li>
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

<script>
    // Script de filtrage dynamique Services -> Direction
    document.getElementById('direction_id').addEventListener('change', function() {
        const dirId = this.value;
        const serviceSelect = document.getElementById('service_id');
        const options = serviceSelect.querySelectorAll('option');

        options.forEach(opt => {
            if (opt.value === "") {
                opt.style.display = "block";
            } else if (dirId === "" || opt.getAttribute('data-direction') === dirId) {
                opt.style.display = "block";
            } else {
                opt.style.display = "none";
            }
        });

        if (dirId !== "" && serviceSelect.options[serviceSelect.selectedIndex].getAttribute('data-direction') !== dirId) {
            serviceSelect.value = "";
        }
    });
</script>
@endsection
