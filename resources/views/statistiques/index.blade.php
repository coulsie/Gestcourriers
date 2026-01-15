@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- EN-TÊTE STYLE DASHBOARD -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded-4 shadow-sm border-start border-5 border-primary">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="fas fa-chart-line me-2 text-primary"></i>Analyse des Flux Courriers
            </h3>
            <p class="text-muted mb-0 small">Tableau de bord décisionnel mis à jour en temps réel</p>
        </div>
        <div class="text-end">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                <i class="far fa-calendar-alt me-1"></i> Situation au {{ date('d/m/Y') }}
            </span>
        </div>
    </div>

    <!-- LIGNE 1 : VOLUMES GÉNÉRAUX AVEC DÉGRADÉS -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden"
                 style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%);">
                <div class="card-body p-4 position-relative">
                    <div class="text-white">
                        <h6 class="text-uppercase small fw-bold opacity-75">Base Documentaire</h6>
                        <h1 class="fw-bold mb-0 display-5">{{ $stats['total'] }}</h1>
                        <p class="mb-0 small opacity-50">Total des courriers enregistrés</p>
                    </div>
                    <i class="fas fa-database fa-4x position-absolute end-0 bottom-0 mb-3 me-3 text-white opacity-10"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden"
                 style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);">
                <div class="card-body p-4 position-relative text-white">
                    <h6 class="text-uppercase small fw-bold opacity-75">Flux Entrants</h6>
                    <h1 class="fw-bold mb-0 display-5">{{ $stats['entrants'] }}</h1>
                    <p class="mb-0 small opacity-75"><i class="fas fa-level-down-alt me-1"></i>Courriers en réception</p>
                    <i class="fas fa-file-download fa-4x position-absolute end-0 bottom-0 mb-3 me-3 opacity-20"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden"
                 style="background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);">
                <div class="card-body p-4 position-relative text-white">
                    <h6 class="text-uppercase small fw-bold opacity-75">Flux Sortants</h6>
                    <h1 class="fw-bold mb-0 display-5">{{ $stats['sortants'] }}</h1>
                    <p class="mb-0 small opacity-75"><i class="fas fa-level-up-alt me-1"></i>Réponses et envois</p>
                    <i class="fas fa-paper-plane fa-4x position-absolute end-0 bottom-0 mb-3 me-3 opacity-20"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- LIGNE 2 : STATUTS DÉTAILLÉS (Cartes Blanches à accents) -->
    <div class="row g-4">
        <!-- REÇU -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white border-bottom border-4 border-danger rounded-4 h-100 transition-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="bg-danger-subtle p-3 rounded-4">
                            <i class="fas fa-envelope-open text-danger fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="fw-bold mb-0 text-dark">{{ $stats['recus'] }}</h2>
                            <span class="text-muted fw-bold small text-uppercase">Dossiers Reçus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AFFECTÉ -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white border-bottom border-4 border-warning rounded-4 h-100 transition-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="bg-warning-subtle p-3 rounded-4">
                            <i class="fas fa-user-tag text-warning fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="fw-bold mb-0 text-dark">{{ $stats['affectes'] }}</h2>
                            <span class="text-muted fw-bold small text-uppercase">Dossiers Affectés</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ARCHIVÉ -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white border-bottom border-4 border-success rounded-4 h-100 transition-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="bg-success-subtle p-3 rounded-4">
                            <i class="fas fa-archive text-success fa-2x"></i>
                        </div>
                        <div class="text-end">
                            <h2 class="fw-bold mb-0 text-dark">{{ $stats['archives'] }}</h2>
                            <span class="text-muted fw-bold small text-uppercase">Dossiers Archivés</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LIGNE 3 : PERFORMANCE -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h6 class="fw-bold text-dark mb-1"><i class="fas fa-tasks me-2 text-success"></i>Performance de Traitement</h6>
                        <p class="text-muted small mb-0">Progression globale vers l'archivage définitif</p>
                    </div>
                    <div class="text-end">
                        <span class="h4 fw-bold text-success mb-0">{{ $stats['taux_archivage'] }}%</span>
                        <div class="text-muted small">Taux de complétion</div>
                    </div>
                </div>
                <div class="progress shadow-sm" style="height: 12px; border-radius: 10px; background-color: #f1f5f9;">
                    <div class="progress-bar bg-success progress-bar-striped progress-bar-animated"
                         role="progressbar"
                         style="width: {{ $stats['taux_archivage'] }}%"
                         aria-valuenow="{{ $stats['taux_archivage'] }}"
                         aria-valuemin="0"
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.2s ease-in-out;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
