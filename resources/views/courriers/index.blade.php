@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Liste des Courriers') }}
                    <a href="{{ route('courriers.create') }}" class="btn btn-success float-end">
                        {{ __('Nouveau Courrier') }}
                    </a>
                </div>

                <div class="card-body">
                    <!-- Message de succès flash -->
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Référence</th>
                                    <th>Type</th>
                                    <th>Objet</th>
                                    <th>Statut</th>
                                    <th>Date Courrier</th>
                                    <th>Expéditeur</th>
                                    <th>Destinataire</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courriers as $courrier)
                                <tr>
                                    <td>{{ $courrier->id }}</td>
                                    <td>{{ $courrier->reference }}</td>
                                    <td>{{ $courrier->type }}</td>
                                    <td>{{ $courrier->objet }}</td>
                                    <td>
                                        <span class="badge {{ $courrier->statut == 'completed' ? 'bg-success' : ($courrier->statut == 'pending' ? 'bg-warning text-dark' : 'bg-danger') }}">
                                            {{ ucfirst($courrier->statut) }}
                                        </span>
                                    </td>
                                    <td>{{ $courrier->date_courrier->format('d/m/Y') }}</td>
                                    <td>{{ $courrier->expediteur_nom }}</td>
                                    <td>{{ $courrier->destinataire_nom }}</td>
                                    <td>
                                        <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-info btn-sm" title="Voir">
                                            <i class="fa fa-eye"></i> Voir
                                        </a>
                                        <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fa fa-edit"></i> Modifier
                                        </a>
                                        <a href="{{ route('courriers.affectation.create', $courrier->id) }}" class="btn btn-success btn-sm" title="Affecter">
                                            <i class="fa fa-edit"></i> Affecter
                                        </a>
                                        <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce courrier ?')">
                                                <i class="fa fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                        {{-- Supposons que vous ayez une variable $courrier disponible dans votre vue --}}

                                        @if($courrier->chemin_fichier)
                                            <div class="mt-3">
                                                <p>Document associé :</p>

                                                <!-- Bouton pour visualiser/ouvrir dans un nouvel onglet -->
                                                <a href="{{ asset($courrier->chemin_fichier) }}" target="_blank" class="btn btn-info">
                                                    <i class="fas fa-eye"></i> Visualiser le document
                                                </a>

                                                <!-- Bouton pour forcer le téléchargement -->
                                                <a href="{{ asset($courrier->chemin_fichier) }}" download class="btn btn-success ml-2">
                                                    <i class="fas fa-download"></i> Télécharger le fichier
                                                </a>
                                            </div>
                                        @else
                                            <p class="text-muted">Aucun fichier n'est associé à ce courrier.</p>
                                        @endif
                                                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Si vous utilisez la pagination dans le contrôleur, ajoutez ceci : --}}
                    {{-- <div class="mt-3">
                        {{ $courriers->links() }}
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
        {{-- Supposons que vous ayez une variable $courrier disponible dans votre vue --}}


</div>
@endsection
