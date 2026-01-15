@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><i class="fas fa-chart-line me-2 text-primary"></i>Tableau de Bord des Imputations</h2>
        <span class="badge bg-white text-dark shadow-sm p-2">Situation au {{ date('d/m/Y') }}</span>
    </div>

    <div class="row g-3">
        <!-- TOTAL DES DOSSIERS -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-primary text-white">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Dossiers Imputés</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total_imputations'] }}</h2>
                    <i class="fas fa-folder-tree fa-3x position-absolute end-0 bottom-0 mb-3 me-3 opacity-25"></i>
                </div>
            </div>
        </div>

        <!-- EN COURS -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-warning text-dark">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">En cours de traitement</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['en_cours'] }}</h2>
                    <i class="fas fa-spinner fa-3x fa-spin position-absolute end-0 bottom-0 mb-3 me-3 opacity-25"></i>
                </div>
            </div>
        </div>

        <!-- TERMINÉS -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-success text-white">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Dossiers Clôturés</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['termine'] }}</h2>
                    <i class="fas fa-check-double fa-3x position-absolute end-0 bottom-0 mb-3 me-3 opacity-25"></i>
                </div>
            </div>
        </div>

        <!-- EN RETARD -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 bg-danger text-white">
                <div class="card-body p-4">
                    <h6 class="text-uppercase small fw-bold opacity-75">Alertes Retard</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['en_retard'] }}</h2>
                    <i class="fas fa-exclamation-triangle fa-3x position-absolute end-0 bottom-0 mb-3 me-3 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- GRAPHIQUE D'AVANCEMENT GLOBAL -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-4">Performance Globale du Traitement</h5>

                <label class="fw-bold mb-2">Taux de dossiers clôturés : {{ $stats['taux_reussite'] }}%</label>
                <div class="progress mb-4" style="height: 25px; border-radius: 50px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $stats['taux_reussite'] }}%"></div>
                </div>

                <label class="fw-bold mb-2">Avancement moyen des réponses : {{ round($stats['avancement_moyen']) }}%</label>
                <div class="progress" style="height: 25px; border-radius: 50px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $stats['avancement_moyen'] }}%"></div>
                </div>
            </div>
        </div>

        <!-- RÉPARTITION PAR NIVEAU -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
                <h5 class="fw-bold mb-3">Priorités (Urgence)</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                        <span class="text-danger fw-bold"><i class="fas fa-circle me-2"></i>Primaire</span>
                        <span class="badge bg-danger rounded-pill">{{ $stats['primaire'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                        <span class="text-warning fw-bold"><i class="fas fa-circle me-2"></i>Secondaire</span>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $stats['secondaire'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center border-0">
                        <span class="text-info fw-bold"><i class="fas fa-circle me-2"></i>Réponses totales</span>
                        <span class="badge bg-info rounded-pill">{{ $stats['total_reponses'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
