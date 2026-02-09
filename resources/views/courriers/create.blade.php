@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            {{-- Card principale avec bordure renforcée --}}
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center py-3"
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a46a3 100%);">
                    <h4 class="mb-0 text-white fw-bold"><i class="fas fa-envelope-open-text me-2 text-warning"></i>{{ __('Créer un nouveau courrier') }}</h4>
                    <span class="badge bg-white text-primary fw-bold shadow-sm px-3 py-2">GESTION COURRIER 2026</span>
                </div>

                <div class="card-body bg-light p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger border-start border-5 border-danger shadow-sm bg-white small">
                            <h6 class="alert-heading fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Attention !</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courriers.store') }}" enctype="multipart/form-data">
                        @csrf

                       <!-- Section 1: Informations Générales -->
                        <div class="p-4 mb-4 bg-white rounded-3 shadow-sm border-top border-4 border-primary">
                            <!-- En-tête : Titre & Confidentialité -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="text-primary fw-bold mb-0 small text-uppercase">
                                    <i class="fas fa-info-circle me-2"></i>{{ __('Informations Générales') }}
                                </h5>
                                <div class="d-flex align-items-center bg-light p-2 rounded border shadow-sm">
                                    <div class="form-check form-switch me-3">
                                        <input class="form-check-input" type="checkbox" name="is_confidentiel" id="checkConfid" onclick="togglePassword(this)">
                                        <label class="form-check-label fw-bold small text-danger" for="checkConfid">
                                            <i class="fas fa-user-shield me-1"></i>Confidentiel
                                        </label>
                                    </div>
                                    <div id="passwordField" style="display:none;">
                                        <input type="password" name="code_acces" class="form-control form-control-sm border-danger"
                                            placeholder="Code" style="width: 100px;">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <!-- SECTION 1 : CLASSIFICATION (Direction & Type) -->
                                <div class="col-12">
                                    <div class="row g-3 p-3 rounded-3" style="background-color: #f8f9fa; border: 1px solid #e9ecef;">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-muted">Direction du Flux</label>
                                            <select id="directionSelect" class="form-select border-2 border-primary fw-bold">
                                                <option value="Incoming">📩 Entrant (Arrivée)</option>
                                                <option value="Outgoing">📤 Sortant (Départ)</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label fw-bold small text-muted">Type / Nature du Courrier <span class="text-danger">*</span></label>
                                            <select name="type" id="typeSelect" class="form-select border-2 border-primary fw-bold" required>
                                                @foreach($categories as $direction => $subTypes)
                                                    @foreach($subTypes as $value => $label)
                                                        <option value="{{ $value }}" data-direction="{{ $direction }}">{{ $label }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 2 : IDENTIFICATION (Référence & Objet) -->
                                <div class="col-md-12">
                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold small text-muted">Référence du Courrier <span class="text-danger">*</span></label>
                                            <input type="text" name="reference" class="form-control border-2 fw-bold"
                                                style="font-size: 1.1rem; background-color: #f0f7ff;"
                                                placeholder="Saisissez la référence complète..." required>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold small text-muted">Objet du Courrier <span class="text-danger">*</span></label>
                                            <textarea name="objet" class="form-control border-2 fw-bold" rows="2"
                                                    style="background-color: #fff9e6; border-color: #ffc107 !important;" required></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- SECTION 3 : TRAÇABILITÉ (Dates & Statut) -->
                                <div class="col-12">
                                    <div class="row g-3 border-top pt-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-info">Date du Document Original</label>
                                            <input type="date" name="date_document_original" class="form-control border-2 border-info fw-bold">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-muted">Date d'Enregistrement</label>
                                            <input type="date" name="date_courrier" class="form-control border-2 border-primary fw-bold" value="{{ date('Y-m-d') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold small text-muted">Statut Initial</label>
                                            <select name="statut" class="form-select border-2 border-primary fw-bold">
                                                <option value="reçu">🟢 Reçu</option>
                                                <option value="en_traitement">🟡 En traitement</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Section 2: Acteurs (Expéditeur / Destinataire) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                                    <div class="card-header py-2 bg-info text-dark fw-bold small"><i class="fas fa-paper-plane me-2"></i>EXPÉDITEUR</div>
                                    <div class="card-body p-3">
                                        <input type="text" name="expediteur_nom" class="form-control form-control-sm border-2 mb-2" placeholder="Nom de l'expéditeur" value="{{ old('expediteur_nom', '') }}" required>
                                        <input type="text" name="expediteur_contact" class="form-control form-control-sm border-2" placeholder="Contact / Email" value="{{ old('expediteur_contact') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                                    <div class="card-header py-2 bg-success text-white fw-bold small"><i class="fas fa-user-tie me-2"></i>DESTINATAIRE</div>
                                    <div class="card-body p-3">
                                        <input type="text" name="destinataire_nom" class="form-control form-control-sm border-2" placeholder="Nom ou Service destinataire" value="{{ old('destinataire_nom', '') }}" required>
                                        <div class="mt-2 small text-muted font-italic"><i class="fas fa-info-circle me-1 text-success"></i>Assignation automatique.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Pièce Jointe (CENTRÉE) -->
                        <div class="p-4 mb-4 bg-white rounded-3 shadow-sm border border-2 border-dark" style="border-style: dashed !important;">
                            <div class="row justify-content-center">
                                <div class="col-md-8 text-center d-flex flex-column align-items-center">
                                    <h6 class="text-dark fw-bold mb-3 text-uppercase">
                                        <i class="fas fa-file-import me-2 text-secondary"></i>{{ __('Documents & Pièces Jointes') }}
                                    </h6>

                                    {{-- Changement du name pour correspondre au contrôleur : chemin_fichier --}}
                                    <div class="w-100 mb-2" style="max-width: 450px;">
                                        <input type="file" name="chemin_fichier"
                                            class="form-control form-control-sm border-2 @error('chemin_fichier') is-invalid @enderror"
                                            style="border-color: #6c757d !important; background-color: #f8f9fa;">

                                        {{-- Affichage de l'erreur si le fichier est invalide --}}
                                        @error('chemin_fichier')
                                            <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                        @enderror
                                    </div>

                                        <p class="text-muted mb-0" style="font-size: 0.75rem;">
                                            <i class="fas fa-shield-alt me-1 text-primary"></i>
                                            Formats autorisés : <strong>PDF, JPG, PNG, DOC, DOCX, XLS, XLSX, PPT, PPTX</strong> (Max 800Mo)
                                        </p>
                                </div>
                            </div>
                        </div>

                        {{-- Boutons d'Action --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('courriers.index') }}" class="btn btn-outline-secondary px-4 fw-bold shadow-sm">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                                <i class="fas fa-save me-2 text-warning"></i> Enregistrer le Courrier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-2 { border-width: 2px !important; }
    .form-control:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); border-color: #0d6efd !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // === 1. Gestion du Filtrage des Types de Courriers ===
    const directionSelect = document.getElementById('directionSelect');
    const typeSelect = document.getElementById('typeSelect');

    function filterTypes() {
        if (!directionSelect || !typeSelect) return;

        const direction = directionSelect.value;
        const options = typeSelect.querySelectorAll('option');
        let firstMatch = null;

        options.forEach(opt => {
            if (opt.dataset.direction === direction) {
                opt.style.display = 'block';
                if (!firstMatch) firstMatch = opt.value;
            } else {
                opt.style.display = 'none';
            }
        });

        // Sélectionne automatiquement le premier type correspondant
        if (firstMatch) {
            typeSelect.value = firstMatch;
        }
    }

    // === 2. Initialisation des Événements ===
    if (directionSelect) {
        directionSelect.addEventListener('change', filterTypes);
        // Exécuter immédiatement pour initialiser l'affichage au chargement
        filterTypes();
    }
});

// === 3. Gestion de la Confidentialité (Mise en dehors du DOMContentLoaded pour onclick) ===
function togglePassword(checkbox) {
    const field = document.getElementById('passwordField');
    if (!field) return;

    if (checkbox.checked) {
        field.style.display = 'block';
        const input = field.querySelector('input');
        if (input) input.focus(); // Met le curseur directement dans le champ code
    } else {
        field.style.display = 'none';
        const input = field.querySelector('input');
        if (input) input.value = ''; // Efface le code si on décoche
    }
}
</script>
@endsection
