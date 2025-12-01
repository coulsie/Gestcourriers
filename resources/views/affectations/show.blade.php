@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Détails de l\'Affectation') }} #{{ $affectation->id }}
                    
                    <a href="{{ route('courriers.show', $affectation->courrier_id) }}" class="btn btn-secondary btn-sm float-end">
                        {{ __('Retour au Courrier') }}
                    </a>
                </div>

                <div class="card-body">
                    <p><strong>{{ __('Courrier Référence') }}:</strong> {{ $affectation->courrier->reference ?? 'N/A' }}</p>
                    <p><strong>{{ __('Assigné à') }}:</strong> {{ $affectation->user->name ?? 'Utilisateur Inconnu' }}</p>
                    
                    <p><strong>{{ __('Statut') }}:</strong> 
                        <span class="badge 
                            @if($affectation->statut == 'completed') bg-success 
                            @elseif($affectation->statut == 'pending') bg-warning text-dark 
                            @else bg-info @endif">
                            {{ ucfirst($affectation->statut) }}
                        </span>
                    </p>

                    <p><strong>{{ __('Date d\'Affectation') }}:</strong> {{ $affectation->date_affectation->format('d/m/Y H:i') }}</p>
                    <p><strong>{{ __('Date de Traitement') }}:</strong> 
                        @if ($affectation->date_traitement)
                            {{ $affectation->date_traitement->format('d/m/Y H:i') }}
                        @else
                            Pas encore traité
                        @endif
                    </p>

                    <div class="mt-4">
                        <h5>{{ __('Commentaires') }}</h5>
                        <p>{{ $affectation->commentaires ?? 'Aucun commentaire.' }}</p>
                    </div>

                    <div class="mt-4">
                        <!-- Bouton pour aller vers la page d'édition -->
                        <a href="{{ route('affectations.edit', $affectation->id) }}" class="btn btn-warning">
                            {{ __('Modifier l\'Affectation') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
