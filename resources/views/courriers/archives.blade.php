@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-lg-5" style="background-color: #f4f7f6;">
    <!-- En-tête avec Titre en Couleur -->
    <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm border-bottom border-primary border-3">
        <div>
            <h2 class="fw-extrabold mb-1" style="color: #2c3e50; letter-spacing: -1px;">
                <i class="bi bi-archive-fill p-2 bg-primary text-white rounded-3 shadow me-2"></i>
                <span class="text-primary">GESTION</span> DES ARCHIVES
            </h2>
            <p class="text-muted small mb-0 fw-bold">
                <i class="bi bi-calendar3 me-1"></i> Historique consolidé au {{ date('d F 2026') }}
            </p>
        </div>
        <div class="text-end">
            <!-- MODIFICATION : FOND ROUGE TEXTE BLANC -->
            <span class="badge bg-danger text-white p-2 px-4 shadow-sm rounded-pill" style="font-size: 0.9rem; border: 2px solid #ffffff;">
                {{ $courriers->total() }} Courriers Archivés
            </span>
        </div>
    </div>

    <!-- Section Filtres avec fond contrasté -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px; border-left: 6px solid #4e73df !important;">
        <div class="card-body bg-white p-4">
            <form action="{{ route('courriers.archives') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary">Période d'archivage</label>
                    <div class="input-group input-group-sm shadow-sm">
                        <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                        <span class="input-group-text bg-primary text-white border-0">à</span>
                        <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-danger">Expéditeur</label>
                    <div class="form-floating form-floating-sm shadow-sm">
                        <input type="text" name="expediteur" class="form-control border-danger border-opacity-25" id="exp" placeholder="Nom" value="{{ request('expediteur') }}">
                        <label for="exp" class="text-muted small">Nom ou Entité</label>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-primary">Destinataire</label>
                    <div class="form-floating shadow-sm">
                        <input type="text" name="destinataire" class="form-control border-primary border-opacity-25" id="dest" placeholder="Nom" value="{{ request('destinataire') }}">
                        <label for="dest" class="text-muted small">Service ou Nom</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-dark">Objet du Courrier</label>
                    <div class="form-floating shadow-sm">
                        <input type="text" name="objet" class="form-control border-secondary border-opacity-25" id="obj" placeholder="Objet" value="{{ request('objet') }}">
                        <label for="obj" class="text-muted small">Mots-clés...</label>
                    </div>
                </div>
                <div class="col-md-2 d-flex flex-column justify-content-end gap-1">
                    <button type="submit" class="btn btn-primary shadow fw-bold py-2">
                        <i class="bi bi-search me-1"></i> Rechercher
                    </button>
                    <a href="{{ route('courriers.archives') }}" class="btn btn-sm btn-link text-muted py-0">Réinitialiser</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau avec En-tête de couleur -->
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
                    <tr>
                        <th class="ps-4 py-3 border-0">DATE</th>
                        <th class="border-0">RÉFÉRENCE</th>
                        <th class="border-0">FLUX (EXPÉDITEUR / DESTINATAIRE)</th>
                        <th class="border-0">OBJET DU DOSSIER</th>
                        <th class="text-center border-0">DOCUMENT</th>
                        <th class="text-center border-0 ps-4">ACTION</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($courriers as $courrier)
                    <tr class="border-bottom border-light">
                        <td class="ps-4">
                            <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($courrier->date_courrier)->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <span class="badge bg-light text-primary border border-primary border-opacity-25 px-2 py-1 shadow-sm">
                                {{ $courrier->reference }}
                            </span>
                        </td>
                        <td>
                            <div class="mb-1">
                                <span class="text-danger fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                                    <i class="bi bi-send-fill me-1"></i>{{ $courrier->expediteur_nom }}
                                </span>
                            </div>
                            <div>
                                <span class="text-primary fw-bold text-uppercase small" style="letter-spacing: 0.5px;">
                                    <i class="bi bi-envelope-check-fill me-1"></i>{{ $courrier->destinataire_nom }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark small" style="max-width: 300px;">{{ $courrier->objet }}</div>
                        </td>
                        <td class="text-center">
                            @if($courrier->chemin_fichier)
                                <a href="{{ asset('documents/courriers/'.$courrier->chemin_fichier) }}" target="_blank" 
                                   class="btn btn-danger btn-sm shadow-sm fw-bold px-3">
                                    <i class="bi bi-file-pdf-fill"></i> PDF
                                </a>
                            @else
                                <span class="text-muted small italic">Aucun</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                <i class="bi bi-eye-fill"></i> Consulter
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted fw-bold italic">
                            <i class="bi bi-inbox display-4 d-block mb-2 opacity-50"></i>
                            Aucun document trouvé dans les archives
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-light border-0 py-3">
            <div class="d-flex justify-content-center">
                {{ $courriers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .fw-extrabold { font-weight: 800; }
    .table-hover tbody tr:hover { background-color: #f0f7ff !important; transition: 0.3s; }
</style>
@endsection
