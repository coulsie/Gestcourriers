@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Liste des Notifications</h2>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Date Création</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notificationTache as $notificationTache)
            <tr>
                {{-- Affichage de la clé primaire personnalisée --}}
                <td>{{ $notificationTache->id_notification }}</td>
                <td>{{ $notificationTache->titre }}</td>
                <td>
                    <span class="badge {{ $notificationTache->priorite == 'Urgent' ? 'bg-danger' : 'bg-info' }}">
                        {{ $notificationTache->priorite }}
                    </span>
                </td>
                <td>{{ $notificationTache->statut }}</td>
                <td>{{ $notificationTache->date_creation }}</td>
                <td>
                    {{-- CORRECTION : On passe explicitement id_notification --}}
                    <a href="{{ route('notifications.visualiser', ['id' => $notificationTache->id_notification]) }}"
                       class="btn btn-sm btn-primary">
                       Visualiser
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
