@extends('layouts.app')

@section('title', "Créer un Type d'Absence")

@section('content')
    <h1>Créer un nouveau Type d'Absence</h1>

    <div class="card">
        <div class="card-header">
            Formulaire de création
        </div>
        <div class="card-body">

            {{-- Assurez-vous que la route 'type_absences.store' est définie dans web.php --}}
            <form action="{{ route('typeabsences.store') }}" method="POST">
                @csrf

                {{-- Champ nom_type (ENUM) --}}
                <div class="mb-3">
                    <label for="nom_type" class="form-label">Nom du Type d'Absence</label>
                    <select name="nom_type" id="nom_type" class="form-select @error('nom_type') is-invalid @enderror" required>
                        <option value="Congé" {{ old('nom_type') == 'Congé' ? 'selected' : '' }}>Congé</option>
                        <option value="Repos Maladie" {{ old('nom_type') == 'Repos Maladie' ? 'selected' : '' }}>Repos Maladie</option>
                        <option value="Mission" {{ old('nom_type') == 'Mission' ? 'selected' : '' }}>Mission</option>
                        <option value="Permission" {{ old('nom_type') == 'Permission' ? 'selected' : '' }}>Permission</option>
                        <option value="Autres" {{ old('nom_type') == 'Autres' ? 'selected' : '' }}>Autres</option>
                    </select>
                    @error('nom_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Champ code (VARCHAR) --}}
                <div class="mb-3">
                    <label for="code" class="form-label">Code (Optionnel)</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code') }}" maxlength="10">
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Champ description (TEXT) --}}
                <div class="mb-3">
                    <label for="description" class="form-label">Description (Optionnel)</label>
                    <textarea name="description" id="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Champ est_paye (TINYINT / Checkbox) --}}
                <div class="mb-3 form-check">
                    <input type="checkbox" name="est_paye" id="est_paye" class="form-check-input @error('est_paye') is-invalid @enderror" value="1" {{ old('est_paye', 0) ? 'checked' : '' }}>
                    <label class="form-check-label" for="est_paye">Absence payée ?</label>
                    @error('est_paye')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Enregistrer le type d'absence</button>
                <a href="{{ route('typeabsences.index') }}" class="btn btn-danger">Annuler</a>

            </form>
        </div>
    </div>
@endsection
