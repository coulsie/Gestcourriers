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
                        <div class="p-3 mb-4 bg-white rounded-3 shadow-sm border-top border-4 border-primary">
                            <h5 class="text-primary fw-bold mb-3 small text-uppercase">
                                <i class="fas fa-info-circle me-2"></i>{{ __('Informations Générales') }}
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small">{{ __('Référence') }}</label>
                                    <input type="text" name="reference" class="form-control form-control-sm border-2 border-primary fw-bold bg-light" value="{{ old('reference') }}" placeholder="REF-2026-001">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold small">{{ __('Type') }} <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select form-select-sm border-2 border-primary fw-bold" required>
                                        <option value="Incoming">📥 Entrant</option>
                                        <option value="Outgoing">📤 Sortant</option>
                                        <option value="Information">ℹ️ Information</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold small">{{ __('Date du Courrier') }}</label>
                                    <input type="date" name="date_courrier" class="form-control form-control-sm border-2 border-primary" value="{{ old('date_courrier', date('Y-m-d')) }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold small">{{ __('Statut Initial') }}</label>
                                    <select name="statut" class="form-select form-select-sm border-2 border-primary fw-bold">
                                        <option value="reçu">🟢 Reçu</option>
                                        <option value="en_traitement">🟡 En traitement</option>
                                    </select>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-bold small">{{ __('Objet du Courrier') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="objet" class="form-control border-2 fw-bold" placeholder="Sujet du courrier" value="{{ old('objet') }}" required style="background-color: #fff9e6; border-color: #ffc107 !important;">
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Acteurs (Expéditeur / Destinataire) -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                                    <div class="card-header py-2 bg-info text-dark fw-bold small"><i class="fas fa-paper-plane me-2"></i>EXPÉDITEUR</div>
                                    <div class="card-body p-3">
                                        <input type="text" name="expediteur_nom" class="form-control form-control-sm border-2 mb-2" placeholder="Nom complet" value="{{ old('expediteur_nom', 'non spécifié') }}" required>
                                        <input type="text" name="expediteur_contact" class="form-control form-control-sm border-2" placeholder="Contact / Email" value="{{ old('expediteur_contact') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                                    <div class="card-header py-2 bg-success text-white fw-bold small"><i class="fas fa-user-tie me-2"></i>DESTINATAIRE</div>
                                    <div class="card-body p-3">
                                        <input type="text" name="destinataire_nom" class="form-control form-control-sm border-2" placeholder="Nom ou Service" value="{{ old('destinataire_nom', 'Direction Générale') }}" required>
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

                                    {{-- Champ de fichier centré avec largeur max --}}
                                    <div class="w-100 mb-2" style="max-width: 450px;">
                                        <input type="file" name="pj" class="form-control form-control-sm border-2 @error('pj') is-invalid @enderror"
                                               style="border-color: #6c757d !important; background-color: #f8f9fa;">
                                    </div>

                                    <p class="text-muted mb-0" style="font-size: 0.75rem;">
                                        <i class="fas fa-shield-alt me-1 text-primary"></i>
                                        Formats autorisés : <strong>PDF, JPG, PNG</strong> (Max 5Mo)
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
@endsection
