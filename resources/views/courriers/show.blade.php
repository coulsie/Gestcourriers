


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
                                    <i class="fas fa-info-circle me-2"></i>{{ __('Détails de l\'enregistrement') }}
                                </h5>
                                <hr class="text-primary opacity-25">
                                <ul class="list-unstyled">
                                    <li class="mb-3"><strong>{{ __('N° Enregistrement') }}:</strong> <span class="text-primary fw-bold">{{ $courrier->num_enregistrement ?? 'N/A' }}</span></li>
                                    <li class="mb-3"><strong>{{ __('Référence') }}:</strong> <span class="badge bg-secondary px-2 fs-6">{{ $courrier->reference }}</span></li>
                                    <li class="mb-3"><strong>{{ __('Type') }}:</strong>
                                        <span class="fw-bold {{ $courrier->type == 'Incoming' ? 'text-primary' : 'text-warning' }}">
                                            {{ $courrier->type == 'Incoming' ? '📩 ENTRANT' : '📤 SORTANT' }}
                                        </span>
                                    </li>
                                    <li class="mb-3"><strong>{{ __('Objet') }}:</strong> <span class="fw-bold text-dark">{{ $courrier->objet }}</span></li>
                                    <li class="mb-3"><strong>{{ __('Date du Courrier') }}:</strong> {{ $courrier->date_courrier?->format('d/m/Y') ?? 'Date non définie' }}</li>

                                    <li class="mb-3">
                                        <strong>{{ __('Statut Actuel') }}:</strong>
                                        @php
                                            $badgeColor = match(strtolower($courrier->statut)) {
                                                'reçu', 'recu' => 'bg-danger',
                                                'affecté', 'affecte' => 'bg-success',
                                                'archivé', 'archive' => 'bg-secondary',
                                                default => 'bg-info',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeColor }} rounded-pill px-3">
                                            {{ strtoupper($courrier->statut) }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Colonne de droite (Expéditeur/Destinataire) -->
                        <div class="col-md-6">
                            <div class="p-3 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-indigo" style="border-color: #6366f1 !important;">
                                <h5 class="fw-bold mb-3" style="color: #6366f1;">
                                    <i class="fas fa-users me-2"></i>{{ __('Intervenants') }}
                                </h5>
                                <hr style="color: #6366f1;" class="opacity-25">
                                <div class="mb-4">
                                    <h6 class="fw-bold text-uppercase small text-muted">{{ __('De (Expéditeur)') }}</h6>
                                    <p class="mb-1 fw-bold"><i class="fas fa-user-edit me-2"></i>{{ $courrier->expediteur_nom }}</p>
                                    <p class="text-muted small"><i class="fas fa-phone me-2"></i>{{ $courrier->expediteur_contact ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-uppercase small text-muted">{{ __('À (Destinataire)') }}</h6>
                                    <p class="mb-1 fw-bold"><i class="fas fa-user-check me-2"></i>{{ $courrier->destinataire_nom }}</p>
                                    <p class="text-muted small"><i class="fas fa-phone me-2"></i>{{ $courrier->destinataire_contact ?? 'N/A' }}</p>
                                </div>
                                <div class="mt-3 pt-2 border-top">
                                    <h6 class="fw-bold text-uppercase small text-muted">{{ __('Traitement') }}</h6>
                                    <p class="mb-0"><i class="fas fa-user-tag me-2 text-primary"></i><strong>Assigné à :</strong> {{ $courrier->assigne_a ?? 'Non assigné' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                   <!-- Fichier Joint et Aperçu -->
<!-- Section Document Principal de l'Imputation -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="fas fa-file-pdf me-2"></i>{{ __('Document d\'Imputation Principal') }}</h5>
            </div>
            <div class="card-body bg-light">
                @if($imputation->chemin_fichier)
                    <div class="row">
                        <!-- Actions et Infos -->
                        <div class="col-md-4">
                            <div class="p-3 bg-white rounded border shadow-sm h-100 text-center">
                                <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                                <h6 class="fw-bold text-break">{{ $imputation->chemin_fichier }}</h6>
                                <hr>
                                <div class="d-grid gap-2">
                                    <a href="{{ asset('documents/imputations/' . $imputation->chemin_fichier) }}"
                                       target="_blank" class="btn btn-primary">
                                        <i class="fas fa-external-link-alt me-2"></i>{{ __('Consulter en plein écran') }}
                                    </a>
                                    <a href="{{ asset('documents/imputations/' . $imputation->chemin_fichier) }}"
                                       download class="btn btn-outline-success">
                                        <i class="fas fa-download me-2"></i>{{ __('Télécharger') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Zone d'Aperçu -->
                        <div class="col-md-8">
                            <div class="rounded shadow-sm bg-secondary" style="height: 600px; border: 2px solid #dee2e6;">
                                {{-- Utilisation de object pour une meilleure compatibilité des navigateurs en 2026 --}}
                                <object data="{{ asset('documents/imputations/' . $imputation->chemin_fichier) }}" type="application/pdf" width="100%" height="100%">
                                    <iframe src="{{ asset('documents/imputations/' . $imputation->chemin_fichier) }}#toolbar=0" width="100%" height="100%" style="border: none;">
                                        <p>Votre navigateur ne supporte pas l'aperçu PDF.
                                           <a href="{{ asset('documents/imputations/' . $imputation->chemin_fichier) }}">Téléchargez le fichier ici</a>.
                                        </p>
                                    </iframe>
                                </object>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5 bg-white border rounded">
                        <i class="fas fa-file-circle-xmark fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Aucun fichier n'est rattaché à la colonne <code>chemin_fichier</code></h5>
                        <p class="small text-secondary">Vérifiez l'enregistrement ID #{{ $imputation->id }} dans la table imputations.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="p-3 bg-white rounded-3 shadow-sm border border-info border-opacity-25" style="background-color: #f0faff;">
            <h5 class="text-info fw-bold mb-3"><i class="fas fa-paperclip me-2"></i>{{ __('Documents Annexes joints') }}</h5>

            @php
                $annexes = json_decode($imputation->documents_annexes, true);
            @endphp

            @if(is_array($annexes) && count($annexes) > 0)
                <div class="row g-3">
                    @foreach($annexes as $fichier)
                        <div class="col-md-3">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body text-center p-2">
                                    <i class="fas fa-file-alt fa-2x text-secondary mb-2"></i>
                                    <p class="small text-truncate mb-2" title="{{ $fichier }}">{{ $fichier }}</p>
                                    <div class="btn-group btn-group-sm w-100">
                                        <a href="{{ asset('documents/imputations/annexes/' . $fichier) }}" target="_blank" class="btn btn-outline-primary">Voir</a>
                                        <a href="{{ asset('documents/imputations/annexes/' . $fichier) }}" download class="btn btn-outline-success"><i class="fas fa-download"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted small">Aucune annexe disponible.</p>
            @endif
        </div>
    </div>
</div>

                    <!-- Description / Commentaires -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="p-3 bg-white rounded-3 shadow-sm border">
                                <h5 class="text-dark fw-bold mb-3"><i class="fas fa-align-left me-2 text-warning"></i>{{ __('Commentaires / Description') }}</h5>
                                <div class="p-3 rounded bg-light border-start border-3 border-warning text-dark italic">
                                    {{ $courrier->description ?? __('Aucune description disponible.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
