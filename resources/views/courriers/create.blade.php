@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-primary shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>{{ __('Créer un nouveau courrier') }}</h4>
                    <span class="badge bg-light text-primary">Nouveau</span>
                </div>

                <div class="card-body bg-light">
                    @if ($errors->any())
                        <div class="alert alert-danger border-start border-4 shadow-sm">
                            <h6 class="alert-heading fw-bold">Attention !</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courriers.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Section 1: Détails Principaux -->
                        <div class="p-3 mb-4 bg-white rounded shadow-sm border">
                            <h5 class="text-primary border-bottom pb-2 mb-3">{{ __('Informations Générales') }}</h5>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="reference" class="form-label fw-bold">{{ __('Référence') }}</label>
                                    <input id="reference" type="text" class="form-control border-primary @error('reference') is-invalid @enderror" name="reference" value="{{ old('reference') }}" placeholder="Ex: REF-2026-001">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="type" class="form-label fw-bold">{{ __('Type') }} <span class="text-danger">*</span></label>
                                    <select id="type" class="form-select border-primary @error('type') is-invalid @enderror" name="type" required>
                                        <option value="">Sélectionner</option>
                                        <option value="Incoming" {{ old('type') == 'Incoming' ? 'selected' : '' }}>📥 Entrant (Incoming)</option>
                                        <option value="Outgoing" {{ old('type') == 'Outgoing' ? 'selected' : '' }}>📤 Sortant (Outgoing)</option>
                                        <option value="Information" {{ old('type') == 'Information' ? 'selected' : '' }}>ℹ️ Information</option>
                                        <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>❓ Autre</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="date_courrier" class="form-label fw-bold">{{ __('Date du Courrier') }}</label>
                                    <input id="date_courrier" type="date" class="form-control border-primary @error('date_courrier') is-invalid @enderror" name="date_courrier" value="{{ old('date_courrier', date('Y-m-d')) }}">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="statut" class="form-label fw-bold">{{ __('Statut Initial') }} <span class="text-danger">*</span></label>
                                    <select id="statut" class="form-select border-primary @error('statut') is-invalid @enderror" name="statut" required>
                                        <option value="reçu" {{ old('statut') == 'reçu' ? 'selected' : '' }}>Reçu</option>
                                        <option value="en_traitement" {{ old('statut') == 'en_traitement' ? 'selected' : '' }}>En traitement</option>
                                        <option value="traité" {{ old('statut') == 'traité' ? 'selected' : '' }}>Traité</option>
                                        <option value="archivé" {{ old('statut') == 'archivé' ? 'selected' : '' }}>Archivé</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-9 mb-3">
                                    <label for="objet" class="form-label fw-bold">{{ __('Objet') }} <span class="text-danger">*</span></label>
                                    <input id="objet" type="text" class="form-control @error('objet') is-invalid @enderror" name="objet" placeholder="Sujet du courrier" value="{{ old('objet') }}" required>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label fw-bold">{{ __('Description / Notes supplémentaires') }}</label>
                                    <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3" placeholder="Détails du contenu...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Expéditeur et Destinataire -->
                        <div class="row mt-3">
                            <div class="col-md-6 mb-3">
                                <div class="card border-info h-100 shadow-sm">
                                    <div class="card-header bg-info text-white fw-bold"><i class="fas fa-paper-plane me-2"></i>{{ __('Expéditeur') }}</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="expediteur_nom" class="form-label fw-bold">{{ __('Nom') }} <span class="text-danger">*</span></label>
                                            <input id="expediteur_nom" type="text" class="form-control @error('expediteur_nom') is-invalid @enderror" name="expediteur_nom" value="{{ old('expediteur_nom', 'non spécifié') }}" required>
                                        </div>
                                        <div class="mb-0">
                                            <label for="expediteur_contact" class="form-label fw-bold">{{ __('Contact (Email/Tel)') }}</label>
                                            <input id="expediteur_contact" type="text" class="form-control @error('expediteur_contact') is-invalid @enderror" name="expediteur_contact" value="{{ old('expediteur_contact') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="card border-success h-100 shadow-sm">
                                    <div class="card-header bg-success text-white fw-bold"><i class="fas fa-user-tie me-2"></i>{{ __('Destinataire / Assignation') }}</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="destinataire_nom" class="form-label fw-bold">{{ __('Destinataire (Nom/Service)') }} <span class="text-danger">*</span></label>
                                            <input id="destinataire_nom" type="text" class="form-control @error('destinataire_nom') is-invalid @enderror" name="destinataire_nom" value="{{ old('destinataire_nom', 'Direction Générale') }}" required>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Pièce Jointe -->
                        <
                        <div class="mb-3">
                                <label for="chemin_fichier">Document (PDF, Image)</label>
                                <input type="file" name="chemin_fichier" id="chemin_fichier" class="form-control">
                        </div>

                            <!-- Champ Affecter (S'assurer qu'il a une valeur) -->
                        <div class="mb-3">
                            <label for="affecter" class="form-label">Est-ce que ce courrier doit être affecté ?</label>
                            <select name="affecter" id="affecter" class="form-control @error('affecter') is-invalid @enderror">
                                <option value="0" {{ old('affecter') == '0' ? 'selected' : '' }}>Non (Pas encore affecté)</option>
                                <option value="1" {{ old('affecter') == '1' ? 'selected' : '' }}>Oui (À affecter)</option>
                            </select>
                            @error('affecter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <a href="{{ route('courriers.index') }}" class="btn btn-secondary me-md-2 shadow-sm">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary px-5 shadow">
                                <i class="fas fa-save me-1"></i> {{ __('Enregistrer le Courrier') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
