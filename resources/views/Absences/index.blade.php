@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Liste des Absences</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <a href="{{ route('absences.create') }}" class="btn btn-primary mb-3">
                        Ajouter une Absence
                    </a>

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Agent</th>
                                <th>Type d'Absence</th>
                                <th>Date Début</th>
                                <th>Date Fin</th>
                                <th>Approuvée</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($absences as $absence)
                                <tr>
                                    <td>{{ $absence->id }}</td>
                                    {{-- Assurez-vous que la relation 'agent' est définie dans le modèle Absence --}}
                                    <td>{{ $absence->agent->nom}} {{ $absence->agent->last_name}} {{ $absence->agent->first_name}}</td>
                                    {{-- Assurez-vous que la relation 'typeAbsence' est définie dans le modèle Absence --}}
                                    <td>{{ $absence->typeAbsence->nom}} {{ $absence->typeAbsence->nom_type}}</td>
                                    <td>{{ $absence->date_debut }}</td>
                                    <td>{{ $absence->date_fin }}</td>
                                    <td>
                                        @if ($absence->approuvee)
                                            <span class="badge badge-success">Oui</span>
                                        @else
                                            <span class="badge badge-danger">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- Ajoutez des liens vers les actions modifier/supprimer --}}
                                        <a href="{{ route('absences.show', $absence->id) }}" class="btn btn-sm btn-info">Voir</a>
                                        <a href="{{ route('absences.edit', $absence->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                                        {{-- Formulaire pour la suppression (méthode DELETE) --}}
                                        <form action="{{ route('absences.destroy', $absence->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette absence ?');">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Affichage des liens de pagination (résout l'erreur initiale) --}}
                    <div class="d-flex justify-content-center">
                        {{ $absences->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
