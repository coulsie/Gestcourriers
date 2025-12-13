@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Liste des Agents
                    {{-- Bouton pour créer un nouvel agent --}}
                    <a href="{{ route('agents.create') }}" class="btn btn-success btn-sm float-end">
                        Ajouter un agent
                    </a>
                </div>

                <div class="card-body">
                    {{-- Affichage d'un message de session s'il existe --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Matricule</th>
                                <th>Nom Complet</th>
                                <th>Date d'Embauche</th>
                                <th>Service</th>
                                <th>Compte Utilisateur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Boucle sur la collection d'agents passée depuis le contrôleur --}}
                            @foreach ($agents as $agent)
                                <tr>
                                    <td>{{ $agent->matricule }}</td>
                                    <td>{{ $agent->first_name }} {{ $agent->last_name }}</td>
                                    <td>{{ $agent->Date_Prise_de_service }}</td>
                                    {{-- Accès à la relation 'service' --}}
                                    <td>
                                        @if($agent->service)
                                            <a href="{{ route('services.show', $agent->service->id) }}">
                                                {{ $agent->service->name }} ({{ $agent->service->code }})
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    {{-- Accès à la relation 'user' --}}
                                    <td>
                                        @if($agent->user)
                                            {{ $agent->user->email }}
                                        @else
                                            Pas de compte
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Liens d'actions CRUD --}}
                                        <a href="{{ route('agents.show', $agent->id) }}" class="btn btn-info btn-sm">
                                            Voir
                                        </a>
                                        <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-warning btn-sm">
                                            Modifier
                                        </a>
                                        <form action="{{ route('agents.destroy', $agent->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')">
                                                Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
