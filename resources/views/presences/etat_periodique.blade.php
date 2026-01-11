@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">
        {{-- EN-TÊTE & FILTRE --}}
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0"><i class="fas fa-chart-line me-2"></i> État Périodique des Présences</h4>
            <form action="{{ route('rapports.presences.periodique') }}" method="GET" class="d-flex gap-2">
                <input type="date" name="debut" class="form-control form-control-sm" value="{{ $debut }}">
                <input type="date" name="fin" class="form-control form-control-sm" value="{{ $fin }}">
                <button type="submit" class="btn btn-warning btn-sm fw-bold">Analyser</button>
            </form>
        </div>

        <div class="card-body bg-light">
            {{-- BLOC ANALYTIQUE (KPIs) --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white text-center p-3">
                        <small class="text-muted text-uppercase fw-bold">Taux de Présence</small>
                        <h2 class="text-success mb-0">{{ $analyses['taux_presence'] }}%</h2>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-success" style="width: {{ $analyses['taux_presence'] }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white text-center p-3">
                        <small class="text-muted text-uppercase fw-bold">Retards Cumulés</small>
                        <h2 class="text-warning mb-0">{{ $analyses['total_retards'] }}</h2>
                        <p class="small text-muted mb-0">Sur la période</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white text-center p-3">
                        <small class="text-muted text-uppercase fw-bold">Absences Justifiées</small>
                        <h2 class="text-primary mb-0">{{ $analyses['absences_autorisees'] }}</h2>
                        <p class="small text-muted mb-0">Autorisations approuvées</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm bg-white text-center p-3">
                        <small class="text-muted text-uppercase fw-bold">Absences Non-Justifiées</small>
                        <h2 class="text-danger mb-0">{{ $analyses['absences_injustifiees'] }}</h2>
                        <p class="small text-muted mb-0">Alertes critiques</p>
                    </div>
                </div>
            </div>

            {{-- TABLEAU DÉTAILLÉ --}}
            <div class="table-responsive bg-white rounded shadow-sm p-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Agent</th>
                            <th>Date / Heures</th>
                            <th>Statut Présence</th>
                            <th>Autorisation d'Absence</th>
                            <th>Actions / Justificatifs</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donnees as $ligne)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $ligne->agent->first_name }} {{ $ligne->agent->last_name }}</div>
                                <small class="text-muted">{{ $ligne->agent->matricule }}</small>
                            </td>
                            <td>
                                <div><i class="far fa-clock me-1 text-primary"></i> {{ $ligne->heure_arrivee->format('H:i') }}</div>
                                <div class="small text-muted"><i class="fas fa-door-open me-1"></i> {{ $ligne->heure_depart ? $ligne->heure_depart->format('H:i') : '--:--' }}</div>
                            </td>
                                                        <td>
                                @php
                                    // Définition des couleurs selon le statut
                                    $badgeClass = match($ligne->statut) {
                                        'Présent'   => 'bg-success text-white',
                                        'En Retard' => 'bg-warning text-dark', // NOIR SUR JAUNE
                                        'Absent'    => 'bg-danger text-white',  // BLANC SUR ROUGE
                                        default     => 'bg-secondary text-white',
                                    };
                                @endphp
                                
                                <span class="badge {{ $badgeClass }} px-3 py-2 shadow-sm" style="min-width: 85px;">
                                    {{ $ligne->statut }}
                                </span>
                            </td>
                            <td>
                                {{-- Vérification si une autorisation existe pour cette période --}}
                                @if($ligne->autorisation)
                                    <span class="text-primary small fw-bold">
                                        <i class="fas fa-check-circle"></i> {{ $ligne->autorisation->typeAbsence->nom_type }}
                                    </span>
                                @else
                                    <span class="text-muted small italic">Aucune</span>
                                @endif
                            </td>
                            <td>
                                @if($ligne->autorisation && $ligne->autorisation->document_justificatif)
                                    <a href="{{ asset('storage/' . $ligne->autorisation->document_justificatif) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf"></i> Voir Doc
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <button onclick="window.print()" class="btn btn-secondary btn-sm"><i class="fas fa-print me-1"></i> Imprimer l'état</button>
        </div>
    </div>
</div>
@endsection
