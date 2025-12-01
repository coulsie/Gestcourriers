@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    {{ __('Historique des Affectations pour le Courrier') }} #{{ $courrier->reference }}
                    
                    <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-secondary btn-sm float-end">
                        {{ __('Retour au Courrier') }}
                    </a>
                </div>

                <div class="card-body">
                    <!-- Message de succès flash -->
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('courriers.affectations.create', $courrier->id) }}" class="btn btn-primary">
                            {{ __('Nouvelle Affectation / Mise à jour') }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID Affectation</th>
                                    <th>Assigné à</th>
                                    <th>Statut</th>
                                    <th>Date d'Affectation</th>
                                    <th>Date de Traitement</th>
                                    <th>Commentaires</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($affectations as $affectation)
                                <tr>
                                    <td>{{ $affectation->id }}</td>
                                    <td>
                                        <!-- Accède au nom de l'utilisateur via la relation Eloquent 'user' -->
                                        {{ $affectation->user->name ?? 'Utilisateur supprimé' }}
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($affectation->statut == 'completed') bg-success 
                                            @elseif($affectation->statut == 'pending') bg-warning text-dark 
                                            @else bg-info @endif">
                                            {{ ucfirst($affectation->statut) }}
                                        </span>
                                    </td>
                                    <td>{{ $affectation->date_affectation->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($affectation->date_traitement)
                                            {{ $affectation->date_traitement->format('d/m/Y H:i') }}
                                        @else
                                            *En cours*
                                        @endif
                                    </td>
                                    <td>{{ $affectation->commentaires ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
