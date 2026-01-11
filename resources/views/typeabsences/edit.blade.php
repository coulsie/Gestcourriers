{{-- resources/views/type_absences/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Carte avec bordure bleue --}}
            <div class="card shadow-sm border-primary">
                {{-- Header avec fond bleu et texte blanc --}}
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Modifier le Type : {{ $typeAbsence->nom_type }}
                    </h5>
                    <span class="badge bg-light text-primary">ID: {{ $typeAbsence->id }}</span>
                </div>

                <div class="card-body bg-light">
                    {{-- Correction de la ligne form (suppression du double method) --}}
                    <form action="{{ route('typeabsences.update', $typeAbsence) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nom Type Field --}}
                        <div class="mb-3">
                            <label for="nom_type" class="form-label fw-bold text-dark">Nom du Type</label>
                            <input type="text" class="form-control border-info @error('nom_type') is-invalid @enderror" 
                                id="nom_type" name="nom_type"
                                value="{{ old('nom_type', $typeAbsence->nom_type) }}" required>
                            @error('nom_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Code Field --}}
                        <div class="mb-3">
                            <label for="code" class="form-label fw-bold text-dark">Code (ex: MALADIE, CP)</label>
                            <input type="text" class="form-control border-info @error('code') is-invalid @enderror" 
                                id="code" name="code"
                                value="{{ old('code', $typeAbsence->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description Field --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold text-dark">Description</label>
                            <textarea class="form-control border-info @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="3">{{ old('description', $typeAbsence->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Est Paye Checkbox avec fond coloré si coché --}}
                        <div class="mb-4 p-3 border rounded bg-white">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="est_paye" name="est_paye" value="1"
                                    {{ old('est_paye', $typeAbsence->est_paye) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold {{ $typeAbsence->est_paye ? 'text-success' : 'text-danger' }}" for="est_paye">
                                    Est-ce une absence rémunérée ?
                                </label>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <a href="{{ route('typeabsences.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success px-4 shadow">
                                <i class="fas fa-save me-1"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
