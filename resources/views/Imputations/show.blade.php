@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- En-tête Dynamique avec Dégradé -->
            <div class="card border-0 shadow-lg mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-8 p-4 bg-white">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-2">
                                    <li class="breadcrumb-item"><a href="{{ route('imputations.index') }}" class="text-decoration-none">Imputations</a></li>
                                    <li class="breadcrumb-item active">Détails #{{ $imputation->id }}</li>
                                </ol>
                            </nav>
                            <h2 class="fw-bold text-dark mb-1">
                                <i class="fas fa-file-signature text-primary me-2"></i>Dossier d'Imputation
                            </h2>
                            <p class="text-muted mb-0">Créé le {{ $imputation->date_imputation->format('d/m/Y') }} par <strong>{{ $imputation->auteur->name ?? 'Système' }}</strong></p>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-center p-4"
                             style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                            <div class="text-center text-white">
                                <div class="small text-uppercase opacity-75 mb-1">Niveau d'urgence</div>
                                <span class="badge {{ $imputation->niveau == 'primaire' ? 'bg-danger' : ($imputation->niveau == 'secondaire' ? 'bg-warning text-dark' : 'bg-info') }} px-4 py-2 fs-6 shadow-sm">
                                    {{ strtoupper($imputation->niveau) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- COLONNE GAUCHE : CŒUR DU DOSSIER -->
                <div class="col-lg-8">
                    <!-- INFOS COURRIER -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold text-primary mb-0"><i class="fas fa-envelope-open-text me-2"></i>Référence du Courrier</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="p-3 rounded-3 bg-light border-start border-4 border-primary">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center border-end">
                                        <span class="d-block small text-muted">Référence</span>
                                        <span class="h5 fw-bold text-dark">{{ $imputation->courrier->reference }}</span>
                                    </div>
                                    <div class="col-md-9 ps-4">
                                        <span class="d-block small text-muted">Objet du document</span>
                                        <p class="mb-0 fw-semibold text-dark">{{ $imputation->courrier->objet }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INSTRUCTIONS -->
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h5 class="fw-bold text-success mb-0"><i class="fas fa-clipboard-list me-2"></i>Instructions du Responsable</h5>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="bg-white p-4 rounded-4 border shadow-sm mb-3">
                                <p class="fs-5 text-dark lh-base italic mb-0">
                                    <i class="fas fa-quote-left text-muted opacity-25 me-2"></i>
                                    {!! nl2br(e($imputation->instructions)) !!}
                                </p>
                            </div>
                            @if($imputation->observations)
                                <div class="alert alert-warning border-0 shadow-sm rounded-3">
                                    <h6 class="fw-bold"><i class="fas fa-exclamation-circle me-2"></i>Observations complémentaires</h6>
                                    <p class="mb-0 small">{{ $imputation->observations }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- COLONNE DROITE : STATUT & ACTEURS -->
                <div class="col-lg-4">
                    <!-- STATUT & ECHEANCIER -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                        <div class="p-4 text-center {{ $imputation->echeancier && $imputation->echeancier->isPast() && $imputation->statut != 'termine' ? 'bg-danger text-white' : 'bg-dark text-white' }}">
                            <h6 class="text-uppercase small opacity-75 mb-2">Échéance de traitement</h6>
                            <h3 class="fw-bold mb-0">
                                <i class="far fa-clock me-2"></i>{{ $imputation->echeancier ? $imputation->echeancier->format('d/m/Y') : 'Aucune date' }}
                            </h3>
                            @if($imputation->echeancier && $imputation->echeancier->isPast() && $imputation->statut != 'termine')
                                <div class="badge bg-white text-danger mt-2">RETARD DÉTECTÉ</div>
                            @endif
                        </div>
                        <div class="card-body p-4 bg-white text-center">
                            <span class="d-block small text-muted mb-2 text-uppercase fw-bold">État actuel</span>
                            @switch($imputation->statut)
                                @case('en_attente') <span class="btn btn-danger disabled w-100 rounded-pill shadow-sm"><i class="fas fa-hourglass-start me-2"></i>EN ATTENTE</span> @break
                                @case('en_cours') <span class="btn btn-primary disabled w-100 rounded-pill shadow-sm"><i class="fas fa-spinner fa-spin me-2"></i>EN COURS</span> @break
                                @case('termine') <span class="btn btn-success disabled w-100 rounded-pill shadow-sm"><i class="fas fa-check-circle me-2"></i>TERMINÉ</span> @break
                            @endswitch
                        </div>
                    </div>

                    <!-- AGENTS ASSIGNÉS -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold text-dark mb-0">Agents Assignés</h6>
                            <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $imputation->agents->count() }}</span>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <div class="vstack gap-3">
                                @foreach($imputation->agents as $agent)
                                    <div class="d-flex align-items-center p-2 rounded-3 hover-bg-light border shadow-xs">
                                        <div class="flex-shrink-0">
                                            <div class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;">
                                                {{ substr($agent->first_name, 0, 1) }}{{ substr($agent->last_name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-0 fw-bold small text-dark">{{ strtoupper($agent->last_name) }} {{ $agent->first_name }}</h6>
                                            <small class="text-muted">{{ $agent->service->name }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- DOCUMENTS ANNEXES -->
                    @if($imputation->documents_annexes)
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-transparent border-0 pt-4 px-4">
                            <h6 class="fw-bold text-dark mb-0">Pièces Jointes</h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            @php
                                $annexes = is_string($imputation->documents_annexes) ? json_decode($imputation->documents_annexes, true) : $imputation->documents_annexes;
                            @endphp
                            <div class="list-group list-group-flush border rounded-3">
                                @if(is_array($annexes))
                                    @foreach($annexes as $file)
                                        <a href="{{ asset('storage/' . $file) }}" target="_blank" class="list-group-item list-group-item-action d-flex align-items-center py-3">
                                            <i class="fas fa-file-pdf text-danger fs-4 me-3"></i>
                                            <div class="overflow-hidden">
                                                <div class="text-dark fw-semibold small text-truncate">{{ basename($file) }}</div>
                                                <small class="text-primary">Cliquer pour ouvrir</small>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- BARRE D'ACTIONS -->
            <div class="card border-0 shadow-lg rounded-4 mt-4 mb-5">
                <div class="card-body d-flex justify-content-between align-items-center p-3">
                    <a href="{{ route('imputations.index') }}" class="btn btn-light px-4 border rounded-pill">
                        <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                    </a>
                    <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                        <a href="{{ route('imputations.edit', $imputation->id) }}" class="btn btn-warning px-4 fw-bold">
                            <i class="fas fa-edit me-2"></i>Modifier l'imputation
                        </a>
                        <button type="button" class="btn btn-danger px-4 fw-bold" onclick="alert('Confirmer la suppression ?')">
                            <i class="fas fa-trash-alt me-2"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rounded-4 { border-radius: 1rem !important; }
    .bg-primary-subtle { background-color: #e0e7ff; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .italic { font-style: italic; }
    .avatar { font-size: 0.8rem; letter-spacing: 1px; }
    .hover-bg-light:hover { background-color: #f8fafc; transition: 0.2s; }
</style>
@endsection
