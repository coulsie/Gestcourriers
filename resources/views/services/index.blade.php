@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Liste des Services
                    {{-- Bouton pour créer un nouveau service --}}
                    <a href="{{ route('services.create') }}" class="btn btn-success btn-sm float-end">
                        Ajouter un service
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
                                <th>ID</th>
                                <th>Nom du Service</th>
                                <th>Direction</th>
                                <th>Responsable</th>
                                <th>Nb Agents</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Boucle sur la collection de services passée depuis le contrôleur --}}
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>{{ $service->name }}</td>
                                    {{-- Accès à la relation 'direction' --}}
                                    <td>
                                        @if($service->direction)
                                            <a href="{{ route('directions.show', $service->direction->id) }}">
                                                {{ $service->direction->name }}
                                            </a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    {{-- Accès à la relation 'head' (Agent responsable) --}}
                                    <td>
                                        @if($service->head)
                                            {{ $service->head->first_name }} {{ $service->head->last_name }}
                                        @else
                                            Non assigné
                                        @endif
                                    </td>
                                    {{-- Accès à la relation 'agents' (compte le nombre d'agents) --}}
                                    <td>{{ $service->agents->count() }}</td>
                                    <td>
                                        {{-- Liens d'actions CRUD --}}
                                        <a href="{{ route('services.show', $service->id) }}" class="btn btn-info btn-sm">
                                            Voir
                                        </a>
                                        <a href="{{ route('services.edit', $service->id) }}" class="btn btn-warning btn-sm">
                                            Modifier
                                        </a>
                                        <form action="{{ route('services.destroy', $service->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce service ?')">
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
