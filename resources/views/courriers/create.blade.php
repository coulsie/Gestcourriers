@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Card avec bordure colorée (Primaire) -->
            <div class="card border-primary shadow">
                <!-- Header avec fond bleu et texte blanc -->
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>{{ __('Créer un nouveau courrier') }}</h4>
                </div>

                <div class="card-body bg-light">
                    <!-- Gestion des erreurs de validation -->
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

                    <form method="POST" action="{{ route('courriers.store') }}">
                        @csrf

                        <!-- Section 1: Détails Principaux -->
                        <div class="p-3 mb-4 bg-white rounded shadow-sm border">
                            <h5 class="text-primary border-bottom pb-2 mb-3">{{ __('Informations Générales') }}</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="reference" class="form-label fw-bold">{{ __('Référence') }} <span class="text-danger">*</span></label>
                                    <input id="reference" type="text" class="form-control border-primary @error('reference') is-invalid @enderror" name="reference" value="{{ old('reference') }}" required autofocus>
                                    @error('reference') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label fw-bold">{{ __('Type') }} <span class="text-danger">*</span></label>
                                    <select id="type" class="form-select border-primary @error('type') is-invalid @enderror" name="type" required>
                                        <option value="" class="text-muted">Sélectionner</option>
                                        <option value="Incoming" {{ old('type') == 'Incoming' ? 'selected' : '' }}>📥 Entrant</option>
                                        <option value="Outgoing" {{ old('type') == 'Outgoing' ? 'selected' : '' }}>📤 Sortant</option>
                                    </select>
                                    @error('type') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="date_courrier" class="form-label fw-bold">{{ __('Date du Courrier') }} <span class="text-danger">*</span></label>
                                    <input id="date_courrier" type="date" class="form-control border-primary @error('date_courrier') is-invalid @enderror" name="date_courrier" value="{{ old('date_courrier') }}" required>
                                    @error('date_courrier') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="objet" class="form-label fw-bold">{{ __('Objet') }} <span class="text-danger">*</span></label>
                                    <input id="objet" type="text" class="form-control @error('objet') is-invalid @enderror" name="objet" placeholder="Sujet du courrier" value="{{ old('objet') }}" required>
                                    @error('objet') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Expéditeur et Destinataire -->
                        <div class="row mt-3">
                            <!-- Bloc Expéditeur (Couleur Info/Bleu clair) -->
                            <div class="col-md-6 mb-3">
                                <div class="card border-info h-100 shadow-sm">
                                    <div class="card-header bg-info text-white fw-bold">{{ __('Expéditeur') }}</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="expediteur_nom" class="form-label">{{ __('Nom') }} <span class="text-danger">*</span></label>
                                            <input id="expediteur_nom" type="text" class="form-control @error('expediteur_nom') is-invalid @enderror" name="expediteur_nom" value="{{ old('expediteur_nom') }}" required>
                                        </div>
                                        <div class="mb-0">
                                            <label for="expediteur_contact" class="form-label">{{ __('Contact') }}</label>
                                            <input id="expediteur_contact" type="text" class="form-control @error('expediteur_contact') is-invalid @enderror" name="expediteur_contact" value="{{ old('expediteur_contact') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bloc Destinataire (Couleur Success/Vert) -->
                            <div class="col-md-6 mb-3">
                                <div class="card border-success h-100 shadow-sm">
                                    <div class="card-header bg-success text-white fw-bold">{{ __('Destinataire') }}</div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="destinataire_nom" class="form-label">{{ __('Nom') }} <span class="text-danger">*</span></label>
                                            <input id="destinataire_nom" type="text" class="form-control @error('destinataire_nom') is-invalid @enderror" name="destinataire_nom" value="{{ old('destinataire_nom') }}" required>
                                        </div>
                                        <div class="mb-0">
                                            <label for="destinataire_contact" class="form-label">{{ __('Contact') }}</label>
                                            <input id="destinataire_contact" type="text" class="form-control @error('destinataire_contact') is-invalid @enderror" name="destinataire_contact" value="{{ old('destinataire_contact') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-md-12 d-flex justify-content-end">
                                <a href="{{ route('courriers.index') }}" class="btn btn-secondary me-2 shadow-sm">
                                    {{ __('Annuler') }}
                                </a>
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="fas fa-save me-1"></i> {{ __('Enregistrer le Courrier') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
