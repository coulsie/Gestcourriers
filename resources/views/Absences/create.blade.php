@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow-lg border-0">
                {{-- En-tête coloré --}}
                <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2 text-warning"></i>Nouvelle Demande d'Absence</h5>
                    <a href="{{ route('absences.index') }}" class="btn btn-sm btn-outline-light">Retour</a>
                </div>

                <div class="card-body p-4 bg-light-subtle">
                    {{-- Note : enctype obligatoire pour le champ document_justificatif --}}
                    <form action="{{ route('absences.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            {{-- Sélection de l'Agent (Bleu) --}}
                            <div class="col-md-6">
                                <label for="agent_id" class="form-label fw-bold text-primary">Agent concerné</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
                                    <select name="agent_id" id="agent_id" class="form-select border-primary @error('agent_id') is-invalid @enderror" required>
                                        <option value="" selected disabled>Choisir un agent...</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                                {{ $agent->last_name }} {{ $agent->first_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('agent_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- Type d'absence (Violet) --}}
                            <div class="col-md-6">
                                <label for="type_absence_id" class="form-label fw-bold text-secondary">Motif de l'absence</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-secondary text-white"><i class="fas fa-tag"></i></span>
                                    <select name="type_absence_id" id="type_absence_id" class="form-select border-secondary @error('type_absence_id') is-invalid @enderror" required>
                                        <option value="" selected disabled>Type d'absence...</option>
                                        @foreach($typeAbsences as $type)
                                            <option value="{{ $type->id }}" {{ old('type_absence_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->nom_type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('type_absence_id') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- Dates (Vert) --}}
                            <div class="col-md-6">
                                <label for="date_debut" class="form-label fw-bold text-success">Date de début</label>
                                <input type="date" name="date_debut" id="date_debut" 
                                       class="form-control border-success @error('date_debut') is-invalid @enderror" 
                                       value="{{ old('date_debut') }}" required>
                                @error('date_debut') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_fin" class="form-label fw-bold text-success">Date de fin</label>
                                <input type="date" name="date_fin" id="date_fin" 
                                       class="form-control border-success @error('date_fin') is-invalid @enderror" 
                                       value="{{ old('date_fin') }}" required>
                                @error('date_fin') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- Document Justificatif (Orange) --}}
                            <div class="col-12">
                                <label for="document_justificatif" class="form-label fw-bold text-warning">Document Justificatif (Scan PDF/JPG)</label>
                                <div class="input-group">
                                    <input type="file" name="document_justificatif" id="document_justificatif" 
                                           class="form-control border-warning @error('document_justificatif') is-invalid @enderror">
                                    <label class="input-group-text bg-warning-subtle text-dark border-warning" for="document_justificatif">
                                        <i class="fas fa-upload me-1"></i> Upload
                                    </label>
                                </div>
                                <small class="text-muted italic">Format accepté : PDF, JPG, PNG (Max 2Mo)</small>
                                @error('document_justificatif') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- Case à cocher Approbation (Switch) --}}
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch p-3 bg-white border rounded shadow-sm">
                                    <input class="form-check-input ms-0 me-3" type="checkbox" name="approuvee" id="approuvee" value="1" {{ old('approuvee') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-dark" for="approuvee">
                                        Approuver l'absence immédiatement
                                    </label>
                                    <small class="d-block text-muted ps-5">Si coché, le statut sera marqué comme approuvé par l'administration.</small>
                                </div>
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="d-flex justify-content-between align-items-center mt-5 border-top pt-4">
                            <button type="reset" class="btn btn-outline-danger">
                                <i class="fas fa-undo me-1"></i> Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                                <i class="fas fa-save me-2"></i> Enregistrer l'absence
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-select, .form-control { border-width: 2px; transition: all 0.3s ease; }
    .form-select:focus, .form-control:focus { box-shadow: 0 0 10px rgba(13, 110, 253, 0.15); border-color: inherit; }
    .bg-light-subtle { background-color: #f8f9fa; }
</style>
@endsection
