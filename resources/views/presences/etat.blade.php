@extends('layouts.app')

@section('content')
<style>
    /* Styles pour l'affichage et l'impression */
    @media print {
        .no-print, .btn, .card-header, .form-control, .form-select { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
        .container { width: 100% !important; max-width: 100% !important; margin: 0; padding: 0; }

        .table { border-collapse: collapse !important; width: 100% !important; }
        .table border, .table th, .table td { border: 1px solid #000 !important; padding: 8px; }

        .badge { border: 1px solid #000 !important; color: #000 !important; background: transparent !important; text-transform: uppercase; }
        .print-only { display: block !important; }
    }

    .print-only { display: none; }
    .badge-status { color: white !important; font-weight: bold; min-width: 100px; font-size: 0.8rem; }

    .table-bordered-custom th, .table-bordered-custom td {
        border: 1px solid #dee2e6 !important;
    }
    .table-striped-custom tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }

    /* Harmonisation de la hauteur des composants de filtrage */
    .form-label { margin-bottom: 0.5rem; }
</style>

<div class="container py-4">
    <!-- Titre Impression (Invisible à l'écran) -->
    <div class="print-only text-center mb-4">
        <h2 class="mb-1 text-uppercase">État Récapitulatif des Présences</h2>
        <p class="mb-0">Période : {{ $mois ? \Carbon\Carbon::create()->month((int)$mois)->locale('fr')->translatedFormat('F') : 'Année' }} {{ $annee }}</p>
        <hr>
    </div>

    <!-- Section Filtres -->
    <div class="card shadow-sm border-0 mb-4 no-print">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i>Filtres de recherche</h5>
            <button onclick="window.print()" class="btn btn-success btn-sm fw-bold shadow-sm">
                <i class="fas fa-print me-1"></i> Imprimer l'état
            </button>
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('presences.etat') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">ANNÉE</label>
                    <select name="annee" class="form-select shadow-sm border-secondary-subtle">
                        @for($i = 2024; $i <= 2026; $i++)
                            <option value="{{ $i }}" {{ $annee == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">MOIS</label>
                    <select name="mois" class="form-select shadow-sm border-secondary-subtle">
                        <option value="">Tous les mois</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $mois == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->locale('fr')->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">SEMAINE (N°)</label>
                    <input type="number" name="semaine" class="form-control shadow-sm border-secondary-subtle"
                           value="{{ $semaine }}" placeholder="Ex: 4" min="1" max="53">
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 shadow-sm fw-bold">
                            <i class="fas fa-search me-1"></i> Filtrer
                        </button>
                        <a href="{{ route('presences.etat') }}" class="btn btn-outline-secondary shadow-sm" title="Réinitialiser">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Section Statistiques -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 border-top border-info border-3">
                <div class="card-header bg-white py-2">
                    <h6 class="mb-0 text-info fw-bold"><i class="fas fa-chart-pie me-2"></i>Résumé Statistique par Agent</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-bordered-custom mb-0">
                        <thead class="bg-light small">
                            <tr class="text-center align-middle">
                                <th class="text-start" style="width: 250px;">Agent</th>
                                <th class="text-success" style="width: 100px;">Présents</th>
                                <th class="text-warning" style="width: 100px;">Retards</th>
                                <th class="text-danger" style="width: 100px;">Absents</th>
                                <th class="text-primary" style="width: 100px;">Justifiés</th>
                                <th class="bg-dark text-white" style="width: 100px;">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statsAgents as $stat)
                            <tr class="text-center small align-middle">
                                <td class="text-start fw-bold">{{ $stat['nom'] }}</td>
                                <td class="table-success fw-bold">{{ $stat['presents'] }}</td>
                                <td class="table-warning fw-bold">{{ $stat['retards'] }}</td>
                                <td class="table-danger fw-bold">{{ $stat['absents'] }}</td>
                                <td class="table-primary fw-bold">{{ $stat['justifies'] }}</td>
                                <td class="fw-bold bg-light">{{ $stat['total'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau détaillé -->
    <div class="card shadow-sm border-0 border-top border-dark border-3">
        <div class="table-responsive">
            <table class="table table-bordered-custom table-striped-custom align-middle mb-0" id="tablePresences">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 250px;">Agent</th>
                        <th style="width: 200px;">Date & Heure Arrivée</th>
                        <th style="width: 150px;">Heure Départ</th>
                        <th class="text-center" style="width: 180px;">Statut</th>
                        <th class="no-print">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($presences as $p)
                        <tr>
                            <td>
                                <div class="fw-bold text-primary">{{ strtoupper($p->agent->last_name) }}</div>
                                <div class="small text-muted">{{ ucfirst(strtolower($p->agent->first_name)) }}</div>
                                <div class="badge bg-light text-dark border small no-print" style="font-size: 0.65rem;">
                                    {{ $p->agent->service->nom ?? ($p->agent->service->libelle ?? 'S/S') }}
                                </div>
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($p->heure_arrivee)->locale('fr')->translatedFormat('d M Y') }}
                                <br>
                                <span class="badge bg-light text-dark border shadow-xs">
                                    <i class="far fa-clock me-1 text-primary"></i>{{ \Carbon\Carbon::parse($p->heure_arrivee)->format('H:i') }}
                                </span>
                            </td>
                            <td>
                                @if($p->heure_depart)
                                    <span class="badge bg-light text-dark border">
                                        <i class="far fa-clock me-1 text-danger"></i>{{ \Carbon\Carbon::parse($p->heure_depart)->format('H:i') }}
                                    </span>
                                @else
                                    <span class="text-muted small">--:--</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $color = match($p->statut) {
                                        'Présent' => 'success',
                                        'En Retard' => 'warning',
                                        'Absent' => 'danger',
                                        'Absence Justifiée' => 'primary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }} badge-status shadow-sm">{{ $p->statut }}</span>
                            </td>
                            <td class="no-print small text-muted italic">{{ $p->note ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted italic">Aucune donnée trouvée pour cette période.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
