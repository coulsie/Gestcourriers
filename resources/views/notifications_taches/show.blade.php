@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Détails de la Tâche : {{ $tache->titre }}
                </div>

                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>ID Notification:</strong>
                            <p>{{ $tache->id_notification }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Agent Assigné (ID):</strong>
                            <p>{{ $tache->id_agent_assigne }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Description:</strong>
                            <p>{{ $tache->description }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Statut:</strong>
                            <span class="badge
                                @if($tache->statut === 'Complétée') bg-success
                                @elseif($tache->statut === 'Annulée') bg-secondary
                                @elseif($tache->statut === 'En cours') bg-primary
                                @else bg-info
                                @endif">
                                {{ $tache->statut }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <strong>Priorité:</strong>
                            <span class="badge
                                @if($tache->priorite === 'Urgent') bg-danger
                                @elseif($tache->priorite === 'Élevée') bg-warning
                                @else bg-info
                                @endif">
                                {{ $tache->priorite }}
                            </span>
                        </div>
                        <div class="col-md-4">
                            <strong>Suivi Par:</strong>
                            <p>{{ $tache->suivi_par }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <strong>Date de Création:</strong>
                            <p>{{ \Carbon\Carbon::parse($tache->date_creation)->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Date d'Échéance:</strong>
                            <p>{{ $tache->date_echeance ? \Carbon\Carbon::parse($tache->date_echeance)->format('d M Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <strong>Date de Lecture:</strong>
                            <p>{{ $tache->date_lecture ? \Carbon\Carbon::parse($tache->date_lecture)->format('d M Y H:i') : 'Non lu' }}</p>
                        </div>
                    </div>

                    @if ($tache->date_completion)
                    <div class="alert alert-success mt-3">
                        Cette tâche a été complétée le {{ \Carbon\Carbon::parse($tache->date_completion)->format('d M Y H:i') }}.
                    </div>
                    @endif

                    @if ($tache->lien_action)
                        <a href="{{ url($tache->lien_action) }}" class="btn btn-primary btn-lg mt-3">
                            <i class="fa fa-external-link-alt"></i> Accéder à l'action requise
                        </a>
                    @endif

                    <hr>
                    <a href="{{ route('notifications_taches.index') }}" class="btn btn-secondary">
                        Retour à la liste
                    </a>

                    {{-- Formulaire pour marquer comme complété (exemple) --}}
                    @if ($tache->statut != 'Complétée')
                        <form action="{{ route('notifications_taches.complete', $tache->id_notification) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Marquer comme Complétée</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
