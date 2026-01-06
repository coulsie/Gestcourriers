@extends('layouts.app')

@section('title', "Créer un Type d'Absence")

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Titre avec icône et couleur --}}
            <h1 class="mb-4 text-dark font-weight-bold">
                <i class="fas fa-calendar-plus text-success mr-2"></i> Créer un nouveau Type d'Absence
            </h1>

            {{-- Carte avec bordure colorée sur le dessus --}}
            <div class="card shadow border-0">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit mr-1"></i> Formulaire de configuration</h5>
                </div>

                <div class="card-body p-4 bg-light">
                    <form action="{{ route('typeabsences.store') }}" method="POST">
                        @csrf

                        {{-- Section Nom du Type (ENUM) - Fond blanc pour contraste --}}
                        <div class="bg-white p-3 rounded shadow-sm mb-4 border-left border-success">
                            <label for="nom_type" class="form-label font-weight-bold text-success">
                                <i class="fas fa-tag"></i> Nom du Type d'Absence <span class="text-danger">*</span>
                            </label>
                            <select name="nom_type" id="nom_type" class="form-select form-control-lg @error('nom_type') is-invalid @enderror" required>
                                <option value="" disabled selected>Choisir une catégorie...</option>
                                <option value="Congé" {{ old('nom_type') == 'Congé' ? 'selected' : '' }}>🌴 Congé</option>
                                <option value="Repos Maladie" {{ old('nom_type') == 'Repos Maladie' ? 'selected' : '' }}>🏥 Repos Maladie</option>
                                <option value="Mission" {{ old('nom_type') == 'Mission' ? 'selected' : '' }}>💼 Mission</option>
                                <option value="Permission" {{ old('nom_type') == 'Permission' ? 'selected' : '' }}>📝 Permission</option>
                                <option value="Autres" {{ old('nom_type') == 'Autres' ? 'selected' : '' }}>✨ Autres</option>
                            </select>
                            @error('nom_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Champ Code --}}
                            <div class="col-md-4 mb-3">
                                <label for="code" class="form-label font-weight-bold">Code Identifiant</label>
                                <input type="text" name="code" id="code"
                                       placeholder="Ex: CONG-01"
                                       class="form-control border-info @error('code') is-invalid @enderror"
                                       value="{{ old('code') }}" maxlength="10">
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Champ Est Payé (Style Switch ou Card) --}}
                            <div class="col-md-8 mb-3">
                                <label class="form-label d-block font-weight-bold">Rémunération</label>
                                <div class="custom-control custom-switch custom-switch-md">
                                    <input type="checkbox" name="est_paye" class="custom-control-input @error('est_paye') is-invalid @enderror" id="est_paye" value="1" {{ old('est_paye', 0) ? 'checked' : '' }}>
                                    <label class="custom-control-label text-primary" for="est_paye">
                                        Cocher si cette absence est maintenue avec salaire
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Champ Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label font-weight-bold">Description des conditions</label>
                            <textarea name="description" id="description" rows="3"
                                      placeholder="Précisez les détails ou motifs ici..."
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-4">
                            <a href="{{ route('typeabsences.index') }}" class="btn btn-outline-danger px-4 shadow-sm">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                <i class="fas fa-check-circle"></i> Enregistrer le type
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Note informative --}}
            <p class="text-center mt-3 text-muted small">
                <i class="fas fa-info-circle text-info"></i> Les types d'absence définis ici seront disponibles pour tous les agents.
            </p>
        </div>
    </div>
</div>
@endsection
