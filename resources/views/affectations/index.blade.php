@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Liste des Affectations
                    {{-- Assuming you have a route to create a new affectation --}}
                    <a href="{{ route('courriers.index') }}" class="btn btn-success btn-sm float-right">Nouvelle Affectation</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Courrier ID</th>
                                <th>Affecté à (Agent)</th>
                                <th>Statut</th>
                                <th>Commentaires</th>
                                <th>Date Affectation</th>
                                <th>Date Traitement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop through the collection of affectations passed from the controller --}}
                            @foreach ($affectations as $affectation)
                                <tr>
                                    <td>{{ $affectation->id }}</td>
                                    {{-- Displaying related data assumes you have relationships defined in your Affectation model --}}
                                    <td>{{ $affectation->courrier_id }}</td>
                                    <td>{{ $affectation->agent_id}} </td> {{-- Assumes a 'agent' relationship --}}
                                    <td>
                                        <span class="badge
                                            @if($affectation->statut == 'Pending') badge-warning
                                            @elseif($affectation->statut == 'Completed') badge-success
                                            @else badge-info
                                            @endif">
                                            {{ $affectation->statut }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($affectation->commentaires, 50) }}</td>
                                    <td>{{ $affectation->date_affectation ? Carbon\Carbon::parse($affectation->date_affectation)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $affectation->date_traitement ? Carbon\Carbon::parse($affectation->date_traitement)->format('d/m/Y') : 'N/A' }}</td>
                                    <td>
                                        {{-- Actions Buttons --}}
                                        <a href="{{ route('affectations.show', $affectation->id) }}" class="btn btn-info btn-sm">Voir</a>
                                        <a href="{{ route('affectations.edit', $affectation->id) }}" class="btn btn-warning btn-sm">Modifier</a>

                                        {{-- Delete Form (use a form for POST/DELETE request) --}}
                                        <form action="{{ route('affectations.destroy', $affectation->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation ?')">Supprimer</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Add pagination links if you are paginating records in the controller --}}
                    {{-- {{ $affectations->links() }} --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
