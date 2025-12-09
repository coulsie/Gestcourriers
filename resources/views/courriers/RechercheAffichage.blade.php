@extends('layouts.app')

@section('title', "Recherche de Courriers")

@section('content')
    <h1>Recherche et Liste des Courriers</h1>

    <div class="card mb-4">
        <div class="card-header">
            Formulaire de Recherche Avancée
        </div>
        <div class="card-body">
            <form action="{{ route('courriers.RechercheAffichage') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search_term" class="form-label">Référence, Objet, Expéditeur ou Destinataire</label>
                    <input type="text" name="search_term" id="search_term" class="form-control" value="{{ request('search_term') }}" placeholder="Mot clé de recherche...">
                </div>

                <div class="col-md-3">
                    <label for="statut" class="form-label">Statut</label>
                    <select name="statut" id="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        {{-- Remplacez par vos statuts réels (ex: 'En cours', 'Traité', 'Archivé') --}}
                        @foreach(['En attente', 'Traité', 'Clos'] as $statut)
                           <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ $statut }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_debut" class="form-label">Date Début</label>
                    <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>

                <div class="col-md-2">
                    <label for="date_fin" class="form-label">Date Fin</label>
                    <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>

                <div class="col-md-1 align-self-end">
                    <button type="submit" class="btn btn-success w-100">Rechercher</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Résultats de la recherche ({{ $courriers->total() }} courriers trouvés)
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Type</th>
                        <th>Objet</th>
                        <th>Expéditeur</th>
                        <th>Statut</th>
                        <th>Date Courrier</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courriers as $courrier)
                        <tr>
                            <td>{{ $courrier->reference }}</td>
                            <td>{{ $courrier->type }}</td>
                            <td>{{ $courrier->objet }}</td>
                            <td>{{ $courrier->expediteur_nom }}</td>
                            <td><span class="badge bg-info">{{ $courrier->statut }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($courrier->date_courrier)->format('d/m/Y') }}</td>
                            <td>
                                {{-- Ajoutez ici un lien pour voir les détails ou télécharger le fichier --}}
                                <a href="{{ asset('storage/' . $courrier->chemin_fichier) }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                                    Voir Fichier
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Liens de pagination qui conservent les paramètres de recherche --}}
            {{ $courriers->appends(request()->except('page'))->links() }}
        </div>
    </div>
@endsection
