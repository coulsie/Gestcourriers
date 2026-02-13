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

    <!-- Bouton d'impression global (Placé au-dessus du tableau) -->
<div class="d-flex justify-content-end mb-3">
    <button type="button" onclick="imprimerTableau()" class="btn btn-warning d-print-none shadow-sm fw-bold border-dark">
        <i class="fas fa-print me-2"></i> IMPRIMER TOUTE LA LISTE
    </button>
</div>


<!-- Tableau avec En-tête de couleur -->
<div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 15px;">
    <div class="table-responsive shadow-sm rounded-3" id="sectionAImprimer">
        <table class="table table-hover align-middle mb-0 bg-white">
            <thead style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%); color: white;">
                <tr>
                    <th class="ps-4 py-3 border-0 text-uppercase small" style="width: 10%">Date</th>
                    <th class="border-0 text-uppercase small" style="width: 15%">Référence</th>
                    <th class="border-0 text-uppercase small" style="width: 25%">Flux (Exp. / Dest.)</th>
                    <th class="border-0 text-uppercase small" style="width: 30%">Objet du Dossier</th>
                    <th class="text-center border-0 text-uppercase small" style="width: 10%">Document</th>
                    <th class="text-center border-0 text-uppercase small no-print" style="width: 10%">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courriers as $courrier)
                <tr class="border-bottom border-light">
                    <td class="ps-4">
                        <span class="fw-bold text-dark small">{{ \Carbon\Carbon::parse($courrier->date_courrier)->format('d/m/Y') }}</span>
                    </td>
                    <td>
                        <span class="badge bg-light text-primary border border-primary border-opacity-25 px-2 py-1">
                            {{ $courrier->reference }}
                        </span>
                    </td>
                    <td>
                        <div class="small fw-bold text-danger mb-1"><i class="fas fa-arrow-right me-1"></i>{{ $courrier->expediteur_nom }}</div>
                        <div class="small fw-bold text-primary"><i class="fas fa-arrow-left me-1"></i>{{ $courrier->destinataire_nom }}</div>
                    </td>
                    <td>
                        <div class="small fw-semibold text-dark" style="max-width: 350px;">{{ $courrier->objet }}</div>
                    </td>
                    <td class="text-center">
                        @if($courrier->chemin_fichier)
                            <span class="badge bg-danger text-white fw-bold shadow-sm px-3 py-2" style="background-color: #dc3545 !important; color: white !important; border: none;">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </span>
                        @else
                            <span class="text-muted small"><i>N/A</i></span>
                        @endif
                    </td>
                    <td class="text-center no-print">
                        <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                            <i class="fas fa-eye"></i> Consulter
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted fw-bold">Aucune archive disponible.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div> <!-- Fin table-responsive -->
    
    <div class="card-footer bg-light border-0 py-3">
        <div class="d-flex justify-content-center">
                    {{ $courriers->appends(request()->query())->links() }}
        </div>
    </div>
</div> <!-- Fin card -->

</div> <!-- Fin container-fluid -->


@push('scripts')
<script>
function imprimerTableau() {
    const zone = document.getElementById('sectionAImprimer');
    if (!zone) {
        alert("Erreur : Le tableau est introuvable.");
        return;
    }

    const win = window.open('', '_blank', 'height=850,width=1200');
    
    // Construction du document d'impression
    const html = `
        <html>
        <head>
            <title>Impression Archives</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net">
            <style>
                body { padding: 40px; font-family: sans-serif; background: #fff !important; }
                .no-print, .btn, .pagination { display: none !important; }
                table { width: 100% !important; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #333 !important; padding: 10px !important; font-size: 11px; }
                thead { background: #1e3a8a !important; color: #fff !important; -webkit-print-color-adjust: exact; }
                .bg-danger { 
                    background-color: #dc3545 !important; 
                    color: white !important; 
                    padding: 4px 8px; 
                    border-radius: 4px; 
                    -webkit-print-color-adjust: exact; 
                }
                .text-danger { color: #dc3545 !important; }
                .text-primary { color: #0d6efd !important; }
            </style>
        </head>
        <body>
            <div style="text-align:center; margin-bottom:30px;">
                <h2 style="text-decoration:underline;">RÉPERTOIRE DES ARCHIVES NUMÉRIQUES</h2>
                <p>Extrait généré le : <strong>{{ date('d/m/Y à H:i') }}</strong></p>
            </div>
            ${zone.innerHTML}
        </body>
        </html>`;

    win.document.write(html);
    win.document.close();

    win.onload = function() {
        setTimeout(() => {
            win.print();
            win.close();
        }, 600);
    };
}
</script>
@endpush

@push('styles')
<style>
    .fw-extrabold { font-weight: 800; }
    .table-hover tbody tr:hover { 
        background-color: #f0f7ff !important; 
        transition: 0.3s; 
        cursor: pointer; 
    }
    @media print {
        .no-print, .btn, .card-footer, form, .navbar { display: none !important; }
    }
</style>
@endpush
@endsection
