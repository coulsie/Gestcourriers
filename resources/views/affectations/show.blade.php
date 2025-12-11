@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Détails de l'Affectation n°: <strong>{{ $affectation->id }}</strong>
                    <div class="float-right">
                        <a href="{{ route('affectations.index') }}" class="btn btn-success btn-sm">
                            Retour aux Affectations
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <h5 class="card-title">Courrier Associé : {{ $affectation->courrier->objet ?? 'N/A' }}</h5>
                    <hr>

                    <dl class="row">
                        <dt class="col-sm-4">Affecté à :</dt>
                        {{-- Supposant une relation 'user' dans le modèle Affectation --}}
                        <dd class="col-sm-8">{{ $affectation->user->name ?? 'Utilisateur supprimé' }}</dd>

                        <dt class="col-sm-4">Statut :</dt>
                        <dd class="col-sm-8">
                            <span class="badge
                                @if($affectation->statut == 'affecté') badge-info
                                @elseif($affectation->statut == 'traité') badge-success
                                @else badge-secondary
                                @endif">
                                {{ $affectation->statut }}
                            </span>
                        </dd>

                        <dt class="col-sm-4">Date d'Affectation :</dt>
                        <dd class="col-sm-8">{{ $affectation->date_affectation ? $affectation->date_affectation->format('d/m/Y H:i') : 'N/A' }}</dd>

                        <dt class="col-sm-4">Date de Traitement :</dt>
                        <dd class="col-sm-8">{{ $affectation->date_traitement ? $affectation->date_traitement->format('d/m/Y H:i') : 'Pas encore traité' }}</dd>
                    </dl>

                    <h6>Commentaires :</h6>
                    <p class="alert alert-light">
                        {{ $affectation->commentaires ?? 'Aucun commentaire.' }}
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
