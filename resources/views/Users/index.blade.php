{{-- Fichier : resources/views/users/index.blade.php --}}

@extends('layouts.app') {{-- Assume un layout de base --}}

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Liste des Utilisateurs</h1>

            {{-- Bouton pour aller à la page de création d'utilisateur --}}
            <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">
                Ajouter un nouvel utilisateur
            </a>

            @if ($users->count())
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Boucle sur la collection $users passée par le contrôleur --}}
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    {{-- Liens vers d'autres actions (show, edit, delete) --}}
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">Voir</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>Aucun utilisateur trouvé.</p>
            @endif
        </div>
    </div>
</div>
@endsection
