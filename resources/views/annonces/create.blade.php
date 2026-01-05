@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Créer une nouvelle annonce</h5>
                    <a href="{{ route('annonces.index') }}" class="btn btn-sm btn-light">Retour</a>
                </div>

                <div class="card-body">
                    <form action="{{ route('annonces.store') }}" method="POST">
                        @csrf

                        <!-- Titre -->
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre de l'annonce</label>
                            <input type="text" name="titre" id="titre" 
                                   class="form-control @error('titre') is-invalid @enderror" 
                                   value="{{ old('titre') }}" placeholder="Ex: Réunion de service" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type d'annonce (Important pour vos couleurs) -->
                        <div class="mb-3">
                            <label for="type" class="form-label">Type d'importance</label>
                            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="" selected disabled>Choisir un type...</option>
                                <option value="information" {{ old('type') == 'information' ? 'selected' : '' }}>Information (Bleu)</option>
                                <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent (Rouge)</option>
                                <option value="evenement" {{ old('type') == 'evenement' ? 'selected' : '' }}>Événement (Vert)</option>
                                <option value="avertissement" {{ old('type') == 'avertissement' ? 'selected' : '' }}>Avertissement (Jaune)</option>
                                <option value="general" {{ old('type') == 'general' ? 'selected' : '' }}>Général (Gris)</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Contenu -->
                        <div class="mb-3">
                            <label for="contenu" class="form-label">Message de l'annonce</label>
                            <textarea name="contenu" id="contenu" rows="4" 
                                      class="form-control @error('contenu') is-invalid @enderror" 
                                      placeholder="Détaillez votre annonce ici..." required>{{ old('contenu') }}</textarea>
                            @error('contenu')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Statut Actif -->
                        <div class="mb-3 form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">Afficher immédiatement sur le bandeau défilant</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Publier l'annonce
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
