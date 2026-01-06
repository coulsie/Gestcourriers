@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-primary">
                <!-- En-tête avec fond bleu primaire et texte blanc -->
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Modifier le Courrier') }}</h5>
                    <span class="badge bg-light text-primary">#{{ $courrier->reference }}</span>
                </div>

                <div class="card-body bg-light-subtle">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('courriers.update', $courrier->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Section 1: Détails Principaux - Titre coloré -->
                        <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-info-circle me-2"></i>Informations Générales</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="reference" class="form-label fw-bold">{{ __('Référence') }} <span class="text-danger">*</span></label>
                                <input id="reference" type="text" class="form-control border-primary-subtle @error('reference') is-invalid @enderror" name="reference" value="{{ old('reference', $courrier->reference) }}" required autofocus>
                                @error('reference') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label fw-bold">{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select id="type" class="form-select border-primary-subtle @error('type') is-invalid @enderror" name="type" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Incoming" {{ old('type', $courrier->type) == 'Incoming' ? 'selected' : '' }}>📩 Entrant</option>
                                    <option value="Outgoing" {{ old('type', $courrier->type) == 'Outgoing' ? 'selected' : '' }}>📤 Sortant</option>
                                </select>
                                @error('type') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="date_courrier" class="form-label fw-bold">{{ __('Date du Courrier') }} <span class="text-danger">*</span></label>
                                <input id="date_courrier" type="date" class="form-control border-primary-subtle @error('date_courrier') is-invalid @enderror" name="date_courrier" value="{{ old('date_courrier', optional($courrier->date_courrier)->format('Y-m-d')) }}" required>
                                @error('date_courrier') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="objet" class="form-label fw-bold">{{ __('Objet') }} <span class="text-danger">*</span></label>
                                <input id="objet" type="text" class="form-control border-primary-subtle @error('objet') is-invalid @enderror" name="objet" value="{{ old('objet', $courrier->objet) }}" required>
                                @error('objet') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label fw-bold">{{ __('Description') }}</label>
                                <textarea id="description" class="form-control border-primary-subtle @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description', $courrier->description) }}</textarea>
                                @error('description') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <!-- Section 2: Expéditeur et Destinataire avec couleurs distinctes -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm h-100">
                                    <h5 class="text-info border-bottom pb-2 mb-3">{{ __('Expéditeur') }}</h5>
                                    <div class="mb-3">
                                        <label for="expediteur_nom" class="form-label">{{ __('Nom') }} <span class="text-danger">*</span></label>
                                        <input id="expediteur_nom" type="text" class="form-control @error('expediteur_nom') is-invalid @enderror" name="expediteur_nom" value="{{ old('expediteur_nom', $courrier->expediteur_nom) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="expediteur_contact" class="form-label">{{ __('Contact') }}</label>
                                        <input id="expediteur_contact" type="text" class="form-control @error('expediteur_contact') is-invalid @enderror" name="expediteur_contact" value="{{ old('expediteur_contact', $courrier->expediteur_contact) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 border rounded bg-white shadow-sm h-100">
                                    <h5 class="text-success border-bottom pb-2 mb-3">{{ __('Destinataire') }}</h5>
                                    <div class="mb-3">
                                        <label for="destinataire_nom" class="form-label">{{ __('Nom') }} <span class="text-danger">*</span></label>
                                        <input id="destinataire_nom" type="text" class="form-control @error('destinataire_nom') is-invalid @enderror" name="destinataire_nom" value="{{ old('destinataire_nom', $courrier->destinataire_nom) }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="destinataire_contact" class="form-label">{{ __('Contact') }}</label>
                                        <input id="destinataire_contact" type="text" class="form-control @error('destinataire_contact') is-invalid @enderror" name="destinataire_contact" value="{{ old('destinataire_contact', $courrier->destinataire_contact) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Boutons d'action -->
                        <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                            <a href="{{ route('courriers.index') }}" class="btn btn-outline-secondary me-2">
                                {{ __('Annuler') }}
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow">
                                <i class="bi bi-save me-1"></i> {{ __('Mettre à jour le courrier') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
