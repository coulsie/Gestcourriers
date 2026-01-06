@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <!-- En-tête avec dégradé bleu -->
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>{{ __('Liste des Courriers') }}</h5>
                    <a href="{{ route('courriers.create') }}" class="btn btn-light btn-sm fw-bold shadow-sm text-success">
                        <i class="fas fa-plus-circle"></i> {{ __('Nouveau Courrier') }}
                    </a>
                </div>

                <div class="card-body bg-light">
                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive rounded shadow-sm bg-white p-2">
                        <table class="table table-hover align-middle">
                            <thead class="table-primary text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Référence</th>
                                    <th>Type</th>
                                    <th>Objet</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Expéditeur</th>
                                    <th>Destinataire</th>
                                    <th class="text-center">Actions / Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courriers as $courrier)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $courrier->id }}</td>
                                    <td><span class="badge bg-success text-white">{{ $courrier->reference }}</span></td>
                                    <td>
                                        @if($courrier->type == 'Incoming')
                                            <span class="text-primary fw-bold"><i class="fas fa-arrow-alt-circle-down"></i> Entrant</span>
                                        @else
                                            <span class="text-orange fw-bold" style="color: #fd7e14;"><i class="fas fa-arrow-alt-circle-up"></i> Sortant</span>
                                        @endif
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $courrier->objet }}</td>
                                  <td>
                                        @php
                                            // On utilise match pour définir la couleur selon le statut
                                            $color = match($courrier->statut) {
                                                'Affecté', 'affecté'   => 'success',
                                                'Réçu', 'reçu', 'Reçu' => 'danger',
                                                'En_Traitement'        => 'warning',
                                                'Traité', 'traité'     => 'info',
                                                'Archivé', 'archivé'   => 'secondary',
                                                default                => 'dark',
                                            };
                                        @endphp {{-- <--- Correction ici : utilisez @endphp et non @php --}}

                                        <span class="badge bg-{{ $color }} text-white rounded-pill">
                                            {{ $courrier->statut }}
                                        </span>
                                    </td>


                                    <td class="text-nowrap">{{ $courrier->date_courrier->format('d/m/Y') }}</td>
                                    <td><small class="fw-bold">{{ $courrier->expediteur_nom }}</small></td>
                                    <td><small class="fw-bold text-info">{{ $courrier->destinataire_nom }}</small></td>
                                    <td>
                                        <!-- Groupe de boutons Actions -->
                                        <div class="d-flex gap-1 justify-content-center mb-2">
                                            <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-outline-info btn-sm" title="Voir">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{ route('courriers.affectation.create', $courrier->id) }}" class="btn btn-outline-success btn-sm" title="Affecter">
                                                <i class="fa fa-user-tag"></i>
                                            </a>
                                            <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="Supprimer" onclick="return confirm('Supprimer ce courrier ?')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Section Document avec couleurs -->
                                        <div class="text-center pt-2 border-top">
                                            @if($courrier->chemin_fichier)
                                                <div class="btn-group btn-group-sm w-100">
                                                    <a href="{{ asset($courrier->chemin_fichier) }}" target="_blank" class="btn btn-primary shadow-sm">
                                                        <i class="fas fa-file-pdf"></i> Voir
                                                    </a>
                                                    <a href="{{ asset($courrier->chemin_fichier) }}" download class="btn btn-success shadow-sm">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted fw-normal" style="font-size: 0.7rem;">Pas de fichier</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($courriers, 'links'))
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $courriers->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
