@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('Détails du Courrier') }} #{{ $courrier->reference }}

                    <a href="{{ route('courriers.index') }}" class="btn btn-secondary btn-sm float-end">
                        {{ __('Retour à la liste') }}
                    </a>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Colonne de gauche (Détails principaux) -->
                        <div class="col-md-6">
                            <h5 class="text-primary">{{ __('Informations Générales') }}</h5>
                            <hr>
                            <p><strong>{{ __('Référence') }}:</strong> {{ $courrier->reference }}</p>
                            <p><strong>{{ __('Type') }}:</strong> {{ $courrier->type }}</p>
                            <p><strong>{{ __('Objet') }}:</strong> {{ $courrier->objet }}</p>
                            <p><strong>{{ $courrier->date_attribut?->format('d/m/Y') ?? 'Date non définie' }}


                            <p><strong>{{ __('Statut') }}:</strong>
                                <span class="badge {{ $courrier->statut == 'completed' ? 'bg-success' : ($courrier->statut == 'pending' ? 'bg-warning text-dark' : 'bg-info') }}">
                                    {{ ucfirst($courrier->statut) }}
                                </span>
                            </p>
                            <p><strong>{{ __('Assigné à') }}:</strong> {{ $courrier->assigne_a ?  $courrier->currentAffectation->user->name :'Non assigné' }}</p>
                        </div>

                        <!-- Colonne de droite (Expéditeur/Destinataire) -->
                        <div class="col-md-6">
                            <h5 class="text-primary">{{ __('Parties Impliquées') }}</h5>
                            <hr>
                            <p><strong>{{ __('Expéditeur Nom') }}:</strong> {{ $courrier->expediteur_nom }}</p>
                            <p><strong>{{ __('Expéditeur Contact') }}:</strong> {{ $courrier->expediteur_contact ?? 'N/A' }}</p>
                            <p><strong>{{ __('Destinataire Nom') }}:</strong> {{ $courrier->destinataire_nom }}</p>
                            <p><strong>{{ __('Destinataire Contact') }}:</strong> {{ $courrier->destinataire_contact ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="text-primary">{{ __('Description') }}</h5>
                            <hr>
                            <p>{{ $courrier->description ?? 'Aucune description fournie.' }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5 class="text-primary">{{ __('Fichier Joint') }}</h5>
                            <hr>
                    
                            {{-- Supposons que vous ayez une variable $courrier disponible dans votre vue --}}

                                @if($courrier->chemin_fichier)
                                    <div class="mt-3">
                                        <p>Document associé :</p>

                                        <!-- Bouton pour visualiser/ouvrir dans un nouvel onglet -->
                                        <a href="{{ asset($courrier->chemin_fichier) }}" target="_blank" class="btn btn-info">
                                            <i class="fas fa-eye"></i> Visualiser le document
                                        </a>

                                        <!-- Bouton pour forcer le téléchargement -->
                                        <a href="{{ asset($courrier->chemin_fichier) }}" download class="btn btn-success ml-2">
                                            <i class="fas fa-download"></i> Télécharger le fichier
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted">Aucun fichier n'est associé à ce courrier.</p>
                                @endif
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <a href="@route('courriers.edit', ['courrier' => $courrier->id])">
                                {{ __('Modifier le Courrier') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    {{ $courrier->date_envoi?->format('d/m/Y') ?? '' }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
