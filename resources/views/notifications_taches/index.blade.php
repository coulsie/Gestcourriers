@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Tableau de Bord des Tâches et Notifications</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{ route('notifications_taches.create') }}" class="btn btn-primary mb-3">Nouvelle Tâche</a>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Titre</th>
                                    <th>Priorité</th>
                                    <th>Statut</th>
                                    <th>Assigné à (ID Agent)</th>
                                    <th>Date Échéance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taches as $tache)
                                    <tr>
                                        <td>{{ $tache->id_notification }}</td>
                                        <td>
                                            @if ($tache->statut === 'Non lu')
                                                <strong>{{ $tache->titre }}</strong>
                                            @else
                                                {{ $tache->titre }}
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($tache->priorite === 'Urgent') bg-danger
                                                @elseif($tache->priorite === 'Élevée') bg-warning
                                                @else bg-info
                                                @endif">
                                                {{ $tache->priorite }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge
                                                @if($tache->statut === 'Complétée') bg-success
                                                @elseif($tache->statut === 'Annulée') bg-secondary
                                                @elseif($tache->statut === 'En cours') bg-primary
                                                @else bg-secondary
                                                @endif">
                                                {{ $tache->statut }}
                                            </span>
                                        </td>
                                        <td>{{ $tache->id_agent }}</td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($tache->date_echeance)->format('d/m/Y') ?? 'N/A' }}
                                        </td>
                                        <td>
                                            @if ($tache->lien_action)
                                                <a href="{{ url($tache->lien_action) }}" class="btn btn-sm btn-info">
                                                    Agir
                                                </a>
                                            @endif
                                            {{-- Lien pour voir les détails complets --}}
                                            <a href="{{ route('notifications_taches.show', $tache->id_notification) }}" class="btn btn-sm btn-secondary">
                                                Voir
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Affichage de la pagination si vous l'utilisez dans le contrôleur (e.g., $taches->links()) --}}
                    {{-- {{ $taches->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
