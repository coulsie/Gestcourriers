@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header d-flex justify-content-between align-items-center py-3"
                     style="background: linear-gradient(135deg, #0d6efd 0%, #0a46a3 100%);">
                    <h4 class="mb-0 text-white fw-bold">
                        <i class="fas fa-edit me-2 text-warning"></i>{{ __('Modifier le Courrier') }}
                    </h4>
                    <span class="badge bg-white text-primary fw-bold shadow-sm px-3 py-2">
                        REF: {{ $courrier->reference }}
                    </span>
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

                    <form method="POST" action="{{ route('courriers.update', $courrier->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Informations Générales -->
                        <div class="p-3 mb-4 bg-white rounded-3 shadow-sm border-top border-4 border-primary">
                            <h6 class="text-primary fw-bold mb-3 text-uppercase small">
                                <i class="fas fa-info-circle me-2"></i>1. Informations Générales
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-dark">{{ __('N° Enregistrement') }}</label>
                                    <input type="text" name="num_enregistrement" class="form-control border-2 bg-light fw-bold" value="{{ old('num_enregistrement', $courrier->num_enregistrement) }}" readonly>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-dark">{{ __('Référence') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="reference" class="form-control border-2 border-primary fw-bold" value="{{ old('reference', $courrier->reference) }}" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-dark">{{ __('Type de Courrier') }} <span class="text-danger">*</span></label>
                                    <select name="type" class="form-select border-2 border-primary fw-bold" required>
                                        <option value="Incoming" {{ old('type', $courrier->type) == 'Incoming' ? 'selected' : '' }}>📩 Entrant</option>
                                        <option value="Outgoing" {{ old('type', $courrier->type) == 'Outgoing' ? 'selected' : '' }}>📤 Sortant</option>
                                        <option value="Information" {{ old('type', $courrier->type) == 'Information' ? 'selected' : '' }}>ℹ️ Information</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-dark">{{ __('Date du Courrier') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="date_courrier" class="form-control border-2 border-primary" value="{{ old('date_courrier', $courrier->date_courrier ? $courrier->date_courrier->format('Y-m-d') : '') }}" required>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-dark">{{ __('Objet') }} <span class="text-danger">*</span></label>
                                    <input type="text" name="objet" class="form-control border-2 fw-bold" value="{{ old('objet', $courrier->objet) }}" required style="background-color: #fff9e6; border-color: #ffc107 !important;">
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Acteurs -->
                        <div class="row g-3 mt-2 mb-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-4 border-info">
                                    <div class="card-header py-2 bg-info text-dark fw-bold small"><i class="fas fa-paper-plane me-2"></i>EXPÉDITEUR</div>
                                    <div class="card-body p-3">
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold mb-0">Nom complet</label>
                                            <input type="text" name="expediteur_nom" class="form-control form-control-sm border-2" value="{{ old('expediteur_nom', $courrier->expediteur_nom) }}" required>
                                        </div>
                                        <div>
                                            <label class="form-label small fw-bold mb-0">Contact</label>
                                            <input type="text" name="expediteur_contact" class="form-control form-control-sm border-2" value="{{ old('expediteur_contact', $courrier->expediteur_contact) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100 border-start border-4 border-success">
                                    <div class="card-header py-2 bg-success text-white fw-bold small"><i class="fas fa-user-tie me-2"></i>DESTINATAIRE</div>
                                    <div class="card-body p-3">
                                        <div class="mb-2">
                                            <label class="form-label small fw-bold mb-0">Nom / Service</label>
                                            <input type="text" name="destinataire_nom" class="form-control form-control-sm border-2" value="{{ old('destinataire_nom', $courrier->destinataire_nom) }}" required>
                                        </div>
                                        <div>
                                            <label class="form-label small fw-bold mb-0">Contact</label>
                                            <input type="text" name="destinataire_contact" class="form-control form-control-sm border-2" value="{{ old('destinataire_contact', $courrier->destinataire_contact) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Traitement & Document -->
                        <div class="p-3 mb-4 bg-white rounded-3 shadow-sm border-start border-4 border-warning">
                            <h6 class="text-warning fw-bold mb-3 text-uppercase small">
                                <i class="fas fa-tasks me-2"></i>3. Traitement & Document
                            </h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Assigné à</label>
                                    <input type="text" name="assigne_a" class="form-control border-2" value="{{ old('assigne_a', $courrier->assigne_a) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Statut</label>
                                    <select name="statut" class="form-select border-2">
                                        <option value="reçu" {{ old('statut', $courrier->statut) == 'reçu' ? 'selected' : '' }}>Reçu</option>
                                        <option value="affecté" {{ old('statut', $courrier->statut) == 'affecté' ? 'selected' : '' }}>Affecté</option>
                                        <option value="Archivé" {{ old('statut', $courrier->statut) == 'Archivé' ? 'selected' : '' }}>Archivé</option>
                                    </select>
                                </div>

                                {{-- Champ affecter masqué pour éviter l'erreur SQL 1048 --}}
                                <input type="hidden" name="affecter" value="{{ $courrier->affecter }}">

                                <div class="col-md-12">
                                    <div class="p-3 border rounded bg-light">
                                        <label class="form-label fw-bold small"><i class="fas fa-paperclip me-1"></i> Document (Laisser vide pour conserver l'ancien)</label>
                                        <input type="file" name="chemin_fichier" class="form-control border-2">
                                        @if($courrier->chemin_fichier)
                                            <div class="mt-2 small">
                                                <span class="text-muted">Fichier actuel :</span>
                                                <a href="{{ asset('Documents/courriers/' . $courrier->chemin_fichier) }}" target="_blank" class="fw-bold text-primary">
                                                    <i class="fas fa-file-pdf"></i> {{ $courrier->chemin_fichier }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('courriers.index') }}" class="btn btn-secondary px-4 fw-bold">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                                <i class="fas fa-save me-1"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
