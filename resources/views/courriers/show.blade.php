@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                <!-- En-tête avec dégradé -->
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%);">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-file-alt me-2"></i>{{ __('Fiche Courrier') }} : <span class="text-warning">{{ $courrier->reference }}</span>
                    </h4>
                    <a href="{{ route('courriers.index') }}" class="btn btn-light btn-sm fw-bold shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('Retour à la liste') }}
                    </a>
                </div>

                <div class="card-body bg-light-subtle p-4">
                    <div class="row g-4">
                        <!-- Colonne de gauche (Informations Générales) -->
                        <div class="col-md-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-primary">
                                <h5 class="text-primary fw-bold mb-3">
                                    <i class="fas fa-info-circle me-2"></i>{{ __('Informations Générales') }}
                                </h5>
                                <hr class="text-primary opacity-25">
                                <ul class="list-unstyled">
                                    <li class="mb-3"><strong>{{ __('Référence') }}:</strong> <span class="badge bg-secondary px-2 fs-6">{{ $courrier->reference }}</span></li>
                                    <li class="mb-3"><strong>{{ __('Type') }}:</strong> <span class="text-dark">{{ $courrier->type }}</span></li>
                                    <li class="mb-3"><strong>{{ __('Objet') }}:</strong> <span class="fw-bold text-primary">{{ $courrier->objet }}</span></li>
                                    <li class="mb-3"><strong>{{ __('Date Attribution') }}:</strong> {{ $courrier->date_courrier?->format('d/m/Y') ?? 'Date non définie' }}</li>

                                    <li class="mb-3">
                                        <strong>{{ __('Statut') }}:</strong>
                                        @php
                                            $badgeColor = match(strtolower($courrier->statut)) {
                                                'reçu', 'recu' => 'bg-danger',
                                                'affecté', 'affecte' => 'bg-success',
                                                'en_traitement' => 'bg-warning text-dark',
                                                'traité', 'traite' => 'bg-info',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }} rounded-pill px-3">
                                            {{ ucfirst($courrier->statut) }}
                                        </span>
                                    </li>
                                    <li><strong>{{ __('Assigné à') }}:</strong>
                                        <span class="text-muted fw-bold">
                                            <i class="fas fa-user-tag me-1"></i>
                                            {{ ($courrier->assigne_a && isset($courrier->currentAffectation) && isset($courrier->currentAffectation->user))?  $courrier->currentAffectation->user->name :'Non assigné' }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Colonne de droite (Expéditeur/Destinataire) -->
                        <div class="col-md-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-indigo" style="border-color: #6366f1 !important;">
                                <h5 class="fw-bold mb-3" style="color: #6366f1;">
                                    <i class="fas fa-users me-2"></i>{{ __('Parties Impliquées') }}
                                </h5>
                                <hr style="color: #6366f1;" class="opacity-25">
                                <div class="mb-4">
                                    <h6 class="fw-bold text-uppercase small text-muted">{{ __('Expéditeur') }}</h6>
                                    <p class="mb-1"><i class="fas fa-user me-2"></i>{{ $courrier->expediteur_nom }}</p>
                                    <p class="text-muted"><i class="fas fa-phone me-2 text-primary"></i>{{ $courrier->expediteur_contact ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-uppercase small text-muted">{{ __('Destinataire') }}</h6>
                                    <p class="mb-1"><i class="fas fa-user-tie me-2"></i>{{ $courrier->destinataire_nom }}</p>
                                    <p class="text-muted"><i class="fas fa-phone me-2 text-success"></i>{{ $courrier->destinataire_contact ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="p-3 bg-white rounded-3 shadow-sm border">
                                <h5 class="text-dark fw-bold mb-3"><i class="fas fa-align-left me-2 text-warning"></i>{{ __('Description') }}</h5>
                                <div class="p-3 rounded bg-light border-start border-3 border-warning">
                                    {{ $courrier->description ?? 'Aucune description fournie.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fichier Joint -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="p-3 bg-white rounded-3 shadow-sm border border-info border-opacity-25" style="background-color: #f0f9ff;">
                                <h5 class="text-info fw-bold mb-3"><i class="fas fa-paperclip me-2"></i>{{ __('Document Associé') }}</h5>
                                @if($courrier->chemin_fichier)
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="p-3 bg-white rounded border">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                        <div>
                                            <p class="mb-2 fw-bold text-dark">{{ $courrier->chemin_fichier }}</p>
                                            <a href="{{ asset('Documents/' . $courrier->chemin_fichier) }}" target="_blank" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye me-1"></i> Visualiser
                                            </a>
                                            <a href="{{ asset('Documents/' . $courrier->chemin_fichier) }}" download class="btn btn-success btn-sm ms-2">
                                                <i class="fas fa-download me-1"></i> Télécharger
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-secondary mb-0">
                                        <i class="fas fa-info-circle me-2"></i> Aucun fichier associé à ce courrier.
                                    </div>
                                @endif
                        </div>
                    </div>

                    <!-- Bouton Modifier -->
                    <div class="row mt-5">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-warning px-5 fw-bold shadow">
                                <i class="fas fa-edit me-2"></i>{{ __('Modifier ce Courrier') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Pied de carte -->
                <div class="card-footer bg-white text-muted small py-3">
                    <div class="d-flex justify-content-between">
                        <span><i class="fas fa-calendar-plus me-1"></i> Ajouté le : {{ $courrier->created_at?->format('d/m/Y H:i') }}</span>
                        <span><i class="fas fa-calendar-check me-1"></i> Envoyé le : {{ $courrier->date_envoi?->format('d/m/Y') ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
