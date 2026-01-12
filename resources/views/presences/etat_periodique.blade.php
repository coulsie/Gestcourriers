@extends('layouts.app')

@section('content')
<style>
    /* STYLE POUR L'ÉCRAN */
    .print-only { display: none; }

    /* STYLE SPÉCIFIQUE POUR L'IMPRESSION */
    @media print {
        .no-print, .btn, .form-control, .form-select, .card-header form, .navbar, .sidebar, .card-footer {
            display: none !important;
        }
        thead tr:nth-child(2) { display: none !important; }
        .container-fluid { width: 100%; padding: 0; margin: 0; }
        .card { border: none !important; box-shadow: none !important; }
        .table-responsive { overflow: visible !important; }
        .badge { -webkit-print-color-adjust: exact; print-color-adjust: exact; border: 1px solid #ccc !important; }
        .print-only { display: block !important; text-align: center; margin-bottom: 20px; }
        body { background-color: white !important; }
        /* Garder les KPIs à l'impression mais simplifiés */
        .kpi-card { border: 1px solid #eee !important; margin-bottom: 10px; }
    }
</style>

<div class="container-fluid py-4">
    {{-- TITRE IMPRESSION --}}
    <div class="print-only">
        <h2>Rapport Analytique des Présences</h2>
        <p>Période : <strong>{{ \Carbon\Carbon::parse($debut)->format('d/m/Y') }}</strong> au <strong>{{ \Carbon\Carbon::parse($fin)->format('d/m/Y') }}</strong></p>
        <hr>
    </div>

    <div class="card shadow-lg border-0">
        {{-- EN-TÊTE --}}
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center no-print">
            <h4 class="mb-0"><i class="fas fa-chart-pie me-2"></i> État Périodique & Statistiques</h4>
            <div class="d-flex gap-3">
                <button onclick="window.print()" class="btn btn-primary btn-sm fw-bold">
                    <i class="fas fa-print me-1"></i> Aperçu & Imprimer
                </button>
                <form action="{{ route('rapports.presences.periodique') }}" method="GET" class="d-flex gap-2">
                    <input type="date" name="debut" class="form-control form-control-sm" value="{{ $debut }}">
                    <input type="date" name="fin" class="form-control form-control-sm" value="{{ $fin }}">
                    <button type="submit" class="btn btn-warning btn-sm fw-bold">Analyser</button>
                </form>
            </div>
        </div>

        <div class="card-body bg-light">
            {{-- BLOC DES POURCENTAGES (KPIs) --}}
            <div class="row g-3 mb-4">
                {{-- Taux de Présence --}}
                <div class="col-md-3">
                    <div class="card kpi-card border-0 shadow-sm text-center p-3">
                        <small class="text-muted fw-bold text-uppercase">Taux de Présence</small>
                        <h3 class="text-success fw-bold mb-0">{{ $analyses['taux_presence'] }}%</h3>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $analyses['taux_presence'] }}%"></div>
                        </div>
                    </div>
                </div>
                {{-- Taux de Retard --}}
                <div class="col-md-3">
                    <div class="card kpi-card border-0 shadow-sm text-center p-3">
                        <small class="text-muted fw-bold text-uppercase">Taux de Retard</small>
                        <h3 class="text-warning fw-bold mb-0">{{ $analyses['taux_retard'] }}%</h3>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-warning" style="width: {{ $analyses['taux_retard'] }}%"></div>
                        </div>
                    </div>
                </div>
                {{-- Taux d'Absence Justifiée --}}
                <div class="col-md-3">
                    <div class="card kpi-card border-0 shadow-sm text-center p-3">
                        <small class="text-muted fw-bold text-uppercase">Absences Justifiées</small>
                        <h3 class="text-primary fw-bold mb-0">{{ $analyses['taux_justifie'] }}%</h3>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-primary" style="width: {{ $analyses['taux_justifie'] }}%"></div>
                        </div>
                    </div>
                </div>
                {{-- Taux d'Absence Non-Justifiée --}}
                <div class="col-md-3">
                    <div class="card kpi-card border-0 shadow-sm text-center p-3">
                        <small class="text-muted fw-bold text-uppercase">Absences Injustifiées</small>
                        <h3 class="text-danger fw-bold mb-0">{{ $analyses['taux_injustifie'] }}%</h3>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-danger" style="width: {{ $analyses['taux_injustifie'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABLEAU --}}
            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-hover align-middle" id="attendanceTable">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Agent</th>
                            <th>Heures</th>
                            <th>Statut Présence</th>
                            <th>Autorisation</th>
                            <th class="text-center no-print">Actions</th>
                        </tr>
                        {{-- FILTRES JS --}}
                        <tr class="bg-light shadow-sm no-print">
                            <td><input type="date" id="filterDate" class="form-control form-control-sm"></td>
                            <td><input type="text" id="filterAgent" class="form-control form-control-sm" placeholder="Rechercher..."></td>
                            <td></td>
                            <td>
                                <select id="filterStatut" class="form-select form-select-sm">
                                    <option value="">Tous</option>
                                    <option value="Présent">Présent</option>
                                    <option value="En Retard">En Retard</option>
                                    <option value="Absent">Absent</option>
                                    <option value="Absence justifiée">Absence justifiée</option>
                                </select>
                            </td>
                            <td><input type="text" id="filterAutorisation" class="form-control form-control-sm" placeholder="Type..."></td>
                            <td class="text-center">
                                <button type="button" onclick="resetColumnFilters()" class="btn btn-outline-danger btn-sm"><i class="fas fa-undo"></i></button>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donnees as $ligne)
                        <tr class="presence-row">
                            <td class="date-cell" data-date="{{ $ligne->created_at->format('Y-m-d') }}">
                                <span class="fw-bold">{{ $ligne->created_at->format('d/m/Y') }}</span>
                            </td>
                            <td class="agent-name">
                                <div class="text-dark fw-semibold">{{ $ligne->agent->first_name }} {{ $ligne->agent->last_name }}</div>
                                <small class="text-muted">{{ $ligne->agent->matricule }}</small>
                            </td>
                            <td>
                                <div class="small"><i class="far fa-clock text-primary"></i> {{ $ligne->heure_arrivee->format('H:i') }}</div>
                                <div class="small text-muted"><i class="fas fa-sign-out-alt"></i> {{ $ligne->heure_depart ? $ligne->heure_depart->format('H:i') : '--:--' }}</div>
                            </td>
                            <td class="statut-cell">
                                @php
                                    $badgeClass = match($ligne->statut) {
                                        'Présent'           => 'bg-success text-white',
                                        'En Retard'         => 'bg-warning text-dark',
                                        'Absent'            => 'bg-danger text-white',
                                        'Absence justifiée' => 'bg-primary text-white',
                                        default             => 'bg-secondary text-white',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} px-2 py-2 w-100 shadow-sm">
                                    {{ $ligne->statut }}
                                </span>
                            </td>
                            <td class="autorisation-cell">
                                @if($ligne->autorisation)
                                    <span class="text-primary small fw-bold">{{ $ligne->autorisation->typeAbsence->nom_type }}</span>
                                @else
                                    <span class="text-muted small">Aucune</span>
                                @endif
                            </td>
                            <td class="text-center no-print">
                                @if($ligne->autorisation && $ligne->autorisation->document_justificatif)
                                    <a href="{{ asset('storage/' . $ligne->autorisation->document_justificatif) }}" class="btn btn-sm btn-info text-white">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Récupération des éléments de filtrage
    const filters = {
        date: document.getElementById('filterDate'),
        agent: document.getElementById('filterAgent'),
        statut: document.getElementById('filterStatut'),
        autorisation: document.getElementById('filterAutorisation')
    };

    // 2. Fonction de filtrage principale
    function filterTable() {
        const rows = document.querySelectorAll('.presence-row');

        // Récupération des valeurs des filtres
        const valDate = filters.date.value; // Format: YYYY-MM-DD
        const valAgent = filters.agent.value.toLowerCase().trim();
        const valStatut = filters.statut.value.trim();
        const valAutorisation = filters.autorisation.value.toLowerCase().trim();

        rows.forEach(row => {
            // Récupération du contenu des cellules
            const textDate = row.querySelector('.date-cell').getAttribute('data-date') || "";
            const textAgent = row.querySelector('.agent-name').textContent.toLowerCase();
            const textStatut = row.querySelector('.statut-cell').textContent.trim();
            const textAutorisation = row.querySelector('.autorisation-cell').textContent.toLowerCase();

            // Logique de correspondance (Match)
            const matchDate = valDate === "" || textDate === valDate;
            const matchAgent = valAgent === "" || textAgent.includes(valAgent);
            const matchStatut = valStatut === "" || textStatut === valStatut;
            const matchAutorisation = valAutorisation === "" || textAutorisation.includes(valAutorisation);

            // Affichage ou masquage de la ligne
            if (matchDate && matchAgent && matchStatut && matchAutorisation) {
                row.style.setProperty('display', '', 'important');
            } else {
                row.style.setProperty('display', 'none', 'important');
            }
        });
    }

    // 3. Écouteurs d'événements
    // 'input' pour le texte, 'change' pour la date et le select
    Object.values(filters).forEach(filterEl => {
        if(filterEl) {
            filterEl.addEventListener('input', filterTable);
            filterEl.addEventListener('change', filterTable);
        }
    });

    console.log("Système de filtrage initialisé pour 2026");
});

// Fonction globale pour réinitialiser
function resetColumnFilters() {
    document.getElementById('filterDate').value = "";
    document.getElementById('filterAgent').value = "";
    document.getElementById('filterStatut').value = "";
    document.getElementById('filterAutorisation').value = "";

    // Déclenche manuellement le recalcul
    document.getElementById('filterAgent').dispatchEvent(new Event('input'));
}
</script>

@endsection
