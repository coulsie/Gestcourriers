@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Liste des Directions
                    {{-- Bouton pour créer une nouvelle direction --}}
                    <a href="{{ route('directions.create') }}" class="btn btn-success btn-sm float-end">
                        Ajouter une direction
                    </a>
                </div>

                <div class="card-body">
                    {{-- Affichage d'un message de session s'il existe (ex: après suppression/ajout) --}}
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Code</th>
                                <th>Responsable</th>
                                <th>Services Associés</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Boucle sur la collection de directions passée depuis le contrôleur --}}
                            @foreach ($directions as $direction)
                                <tr>
                                    <td>{{ $direction->id }}</td>
                                    <td>{{ $direction->name }}</td>
                                    <td>{{ $direction->code ?? 'N/A' }}</td>
                                    {{-- Supposons que vous ayez une relation 'head' sur le modèle Direction --}}
                                    <td>{{ $direction->head->first_name ?? 'Non assigné' }}</td>
                                    <td>{{ $direction->services->count() }}</td>
                                    <td>
                                        {{-- Lien pour voir les détails --}}
                                        <a href="{{ route('directions.show', $direction->id) }}" class="btn btn-info btn-sm">
                                            Voir
                                        </a>
                                        {{-- Lien pour modifier --}}
                                        <a href="{{ route('directions.edit', $direction->id) }}" class="btn btn-warning btn-sm">
                                            Modifier
                                        </a>
                                        {{-- Formulaire pour supprimer (méthode DELETE) --}}
                                        <form action="{{ route('directions.destroy', $direction->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette direction ?')">
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
