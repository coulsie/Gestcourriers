@extends('layouts.app') {{-- Assurez-vous que le nom du layout correspond au vôtre --}}

@section('content')
<div class="container-fluid">

    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Annonces</h1>
        <a href="{{ route('annonces.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Créer une annonce
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Tableau des annonces -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary">
            <h6 class="m-0 font-weight-bold text-white">Liste des annonces publiées</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Statut</th>
                            <th>Expiration</th>
                            <th>Créé le</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($annonces as $annonce)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $annonce->titre }}</td>
                            <td>
                                {{-- Couleurs dynamiques selon le type --}}
                                @php
                                    $badgeColor = [
                                        'urgent' => 'danger',
                                        'information' => 'info',
                                        'evenement' => 'success',
                                        'avertissement' => 'warning'
                                    ][$annonce->type] ?? 'secondary';
                                @endphp
                                <span class="badge badge-{{ $badgeColor }} text-uppercase p-2">
                                    {{ $annonce->type }}
                                </span>
                            </td>
                            <td>
                                @if($annonce->is_active)
                                    <span class="text-success"><i class="fas fa-check-circle"></i> Actif</span>
                                @else
                                    <span class="text-muted"><i class="fas fa-times-circle"></i> Inactif</span>
                                @endif
                            </td>
                            <td>{{ $annonce->expires_at ? \Carbon\Carbon::parse($annonce->expires_at)->format('d/m/Y') : 'Permanent' }}</td>
                            <td>{{ $annonce->created_at->format('d/m/Y') }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <!-- Bouton Modifier -->
                                    <a href="{{ route('annonces.edit', $annonce->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Bouton Supprimer -->
                                    <form action="{{ route('annonces.destroy', $annonce->id) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?');" style="display:inline-block; margin-left: 5px;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
{{-- Activation du plugin DataTable pour la recherche et pagination --}}
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
            }
        });
    });
</script>
@endsection
