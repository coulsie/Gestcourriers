@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">

                    Affectations pour le courrier n°: <strong>{{ $courrier->id }}</strong>

                    <div class="float-right">
                        {{-- Bouton pour retourner à la vue détaillée du courrier --}}
                         <a href="{{ route('courriers.show', ['courrier' => $courrier->id]) }}">Voir le courrier</a>

                        {{-- Bouton pour affecter à nouveau --}}
                        <a href="{{ route('courriers.affectations.create', $courrier->id) }}" class="btn btn-primary btn-sm">
                            Nouvelle Affectation
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($affectations->isEmpty())
                        <p>Aucune affectation trouvée pour ce courrier.</p>
                    @else
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID Affectation</th>
                                    <th>Affecté à</th>
                                    <th>Statut</th>
                                    <th>Date Affectation</th>
                                    <th>Date Traitement</th>
                                    <th>Commentaires</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($affectations as $affectation)
                                    <tr>
                                        <td>{{ $affectation->id }}</td>
                                        {{-- Supposant une relation 'user' dans le modèle Affectation --}}
                                        <td>
                                            @if($affectation->user)
                                                {{ $affectation->user->name }}
                                            @else
                                                Utilisateur inconnu
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($affectation->statut == 'affecté') badge-info
                                                @elseif($affectation->statut == 'traité') badge-success
                                                @else badge-secondary
                                                @endif">
                                                {{ $affectation->statut }}
                                            </span>
                                        </td>
                                        <td>{{ $affectation->date_affectation ? $affectation->date_affectation->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>{{ $affectation->date_traitement ? $affectation->date_traitement->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td>
                                            <p>{{ Str::limit($affectation->commentaires, 50) }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
