@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Détails de la présence #{{ $presence->id }}
                </div>

                <div class="card-body">
                    <h5 class="card-title">Informations sur l'agent</h5>
                    <!-- Supposons que vous ayez accès au nom de l'agent via $presence->agent->nom -->
                    <p><strong>Agent :</strong> {{ $presence->agent->nom ?? 'N/A' }}</p>
                    <hr>

                    <h5 class="card-title">Détails de la présence</h5>

                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <strong>Heure d'arrivée :</strong>
                            <!-- Formatez l'heure d'arrivée pour un affichage lisible -->
                            {{ $presence->heure_arrivee ? \Carbon\Carbon::parse($presence->heure_arrivee)->format('d/m/Y H:i') : 'N/A' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Heure de départ :</strong>
                            <!-- Formatez l'heure de départ ou affichez 'En cours' si null -->
                            {{ $presence->heure_depart ? \Carbon\Carbon::parse($presence->heure_depart)->format('d/m/Y H:i') : 'En cours / Non renseigné' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Statut :</strong>
                            <!-- Utilisation de badges Bootstrap pour le statut -->
                            @php
                                $badgeClass = '';
                                if ($presence->statut == 'Présent') {
                                    $badgeClass = 'bg-success';
                                } elseif ($presence->statut == 'En Retard') {
                                    $badgeClass = 'bg-warning text-dark';
                                } else {
                                    $badgeClass = 'bg-danger';
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ $presence->statut }}
                            </span>
                        </li>
                    </ul>

                    <div class="mt-4">
                        <strong>Notes :</strong>
                        <p class="text-muted">
                            {{ $presence->notes ?? 'Aucune note.' }}
                        </p>
                    </div>

                    <a href="{{ route('presences.index') }}" class="btn btn-primary mt-3">Retour à la liste</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
