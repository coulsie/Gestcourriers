@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-5">
    <!-- Header avec Fil d'Ariane -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-envelope-paper shadow-sm p-2 rounded bg-white me-2"></i>Détails du Courrier</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('courriers.index') }}">Liste</a></li>
                    <li class="breadcrumb-item active">{{ $courrier->reference }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('courriers.index') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
            <button onclick="window.print()" class="btn btn-info text-white shadow-sm">
                <i class="bi bi-printer"></i> Imprimer la fiche
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- COLONNE GAUCHE : INFOS -->
        <div class="col-lg-4">
            <!-- Statut Card -->
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-3 {{ $courrier->statut == 'pending' ? 'bg-warning text-dark' : 'bg-success text-white' }} text-center fw-bold">
                        STATUT : {{ mb_convert_case($courrier->statut, MB_CASE_TITLE, "UTF-8") }}
                    </div>
                </div>
            </div>

            <!-- Main Info Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3 text-primary border-bottom pb-2">Identifiants</h5>
                    <div class="mb-3">
                        <label class="text-muted small d-block">Référence interne</label>
                        <span class="fw-bold h5 text-dark">{{ $courrier->reference }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small d-block">N° Enregistrement</label>
                        <span class="badge bg-light text-dark border p-2 w-100 text-start">{{ $courrier->num_enregistrement ?? 'Non attribué' }}</span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="text-muted small d-block">Type</label>
                            <span class="badge bg-primary-subtle text-primary p-2">{{ $courrier->type }}</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small d-block">Date</label>
                            <span class="fw-semibold">{{ \Carbon\Carbon::parse($courrier->date_courrier)->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contacts Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-0">
                        <div class="col-12 mb-4">
                            <h6 class="text-uppercase text-danger fw-bold small"><i class="bi bi-person-up me-2"></i>Expéditeur</h6>
                            <div class="p-3 rounded bg-danger-subtle border-start border-danger border-4">
                                <strong class="d-block">{{ $courrier->expediteur_nom }}</strong>
                                <span class="text-muted small">{{ $courrier->expediteur_contact }}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            <h6 class="text-uppercase text-primary fw-bold small"><i class="bi bi-person-down me-2"></i>Destinataire</h6>
                            <div class="p-3 rounded bg-primary-subtle border-start border-primary border-4">
                                <strong class="d-block">{{ $courrier->destinataire_nom }}</strong>
                                <span class="text-muted small">{{ $courrier->destinataire_contact }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE : CONTENU ET FICHIER -->
        <div class="col-lg-8">
            <!-- Objet & Description -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold text-dark">Objet : {{ $courrier->objet }}</h5>
                    <hr>
                    <p class="text-muted" style="white-space: pre-line;">
                        {{ $courrier->description ?: 'Aucune description fournie.' }}
                    </p>
                </div>
            </div>

            <!-- Zone Aperçu Document -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-earmark-pdf me-2"></i>Aperçu du document</h5>
                    @if($courrier->chemin_fichier)
                        <a href="{{ asset('documents/courriers/' . $courrier->chemin_fichier) }}" download class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-download"></i> Télécharger
                        </a>
                    @endif
                </div>
                <div class="card-body bg-light p-2">
                    @if($courrier->chemin_fichier)
                        @php
                            $extension = pathinfo($courrier->chemin_fichier, PATHINFO_EXTENSION);
                            $fileUrl = asset('documents/courriers/' . $courrier->chemin_fichier);
                        @endphp

                        <div class="rounded overflow-hidden shadow-inner bg-white" style="min-height: 600px;">
                            @if(strtolower($extension) == 'pdf')
                                <iframe src="{{ $fileUrl }}#toolbar=0" width="100%" height="700px" style="border: none;"></iframe>
                            @elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                <div class="text-center p-4">
                                    <img src="{{ $fileUrl }}" class="img-fluid rounded" alt="Document">
                                </div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center" style="height: 400px;">
                                    <i class="bi bi-file-earmark-zip h1 text-secondary"></i>
                                    <p class="mt-3">Ce type de fichier ({{ $extension }}) ne peut pas être visualisé directement.</p>
                                    <a href="{{ $fileUrl }}" class="btn btn-primary">Ouvrir le fichier</a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-file-earmark-x h1 text-muted"></i>
                            <p class="text-muted mt-2">Aucune pièce jointe associée.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-white text-end small text-muted">
                    Dernière modification : {{ $courrier->updated_at->diffForHumans() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-primary-subtle { background-color: #e7f1ff; }
    .bg-danger-subtle { background-color: #f8d7da; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
    @media print {
        .btn, .breadcrumb, .card-header button { display: none !important; }
        .card { border: 1px solid #ddd !important; shadow: none !important; }
    }
</style>
@endsection
