@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            {{-- Card avec bordure dynamique selon l'approbation --}}
            <div class="card shadow border-0">
                <div class="card-header {{ $absence->approuvee ? 'bg-success' : 'bg-primary' }} text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Modifier l'absence #{{ $absence->id }}
                    </h5>
                    <span class="badge bg-light text-dark">
                        {{ $absence->agent->last_name }} {{ $absence->agent->first_name }}
                    </span>
                </div>

                <div class="card-body p-4 bg-light-subtle">
                    {{-- Note : enctype="multipart/form-data" est CRUCIAL pour le fichier scanné --}}
                    <form method="POST" action="{{ route('absences.update', $absence->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Section Agent (Bleu) --}}
                            <div class="col-md-6">
                                <label for="agent_id" class="form-label fw-bold text-primary">Agent</label>
                                <select name="agent_id" id="agent_id" class="form-select border-primary @error('agent_id') is-invalid @enderror" required>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id', $absence->agent_id) == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->last_name }} {{ $agent->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('agent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Section Motif (Violet/Info) --}}
                            <div class="col-md-6">
                                <label for="type_absence_id" class="form-label fw-bold text-info">Motif de l'absence</label>
                                <select class="form-select border-info @error('type_absence_id') is-invalid @enderror" id="type_absence_id" name="type_absence_id" required>
                                    @foreach($type_absences as $type)
                                        <option value="{{ $type->id }}" {{ old('type_absence_id', $absence->type_absence_id) == $type->id ? 'selected' : '' }}>
                                            {{ $type->libelle }} {{ $type->nom_type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_absence_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Section Dates (Vert) --}}
                            <div class="col-md-6">
                                <label for="date_debut" class="form-label fw-bold text-success">Date de début</label>
                                <input type="date" class="form-control border-success @error('date_debut') is-invalid @enderror" 
                                       id="date_debut" name="date_debut" value="{{ old('date_debut', $absence->date_debut) }}" required>
                                @error('date_debut') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_fin" class="form-label fw-bold text-success">Date de fin</label>
                                <input type="date" class="form-control border-success @error('date_fin') is-invalid @enderror" 
                                       id="date_fin" name="date_fin" value="{{ old('date_fin', $absence->date_fin) }}" required>
                                @error('date_fin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Section Justificatif (Orange) --}}
                            <div class="col-12">
                                <label for="document_justificatif" class="form-label fw-bold text-warning">
                                    Document Justificatif (Scan PDF/Image)
                                </label>
                                <input type="file" name="document_justificatif" class="form-control border-warning @error('document_justificatif') is-invalid @enderror">
                                
                                @if($absence->document_justificatif)
                                    <div class="mt-2 p-2 border rounded bg-white d-flex justify-content-between align-items-center">
                                        <small class="text-muted"><i class="fas fa-file-pdf me-2"></i>Document actuel présent</small>
                                        <a href="{{ asset('storage/' . $absence->document_justificatif) }}" target="_blank" class="btn btn-xs btn-outline-info py-0">Visualiser</a>
                                    </div>
                                @endif
                                @error('document_justificatif') <div class="text-danger small">{{ $message }}</div> @enderror
                            </div>

                            {{-- Section Approbation (Switch Coloré) --}}
                            <div class="col-12 mt-3">
                                <div class="form-check form-switch p-3 border rounded shadow-sm {{ $absence->approuvee ? 'bg-success-subtle' : 'bg-white' }}">
                                    <input type="checkbox" class="form-check-input ms-0 me-3" id="approuvee" name="approuvee" value="1"
                                        {{ old('approuvee', $absence->approuvee) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="approuvee">
                                        Absence Approuvée par la Direction
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5 border-top pt-3">
                            <a href="{{ route('absences.index') }}" class="btn btn-outline-secondary px-4">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 shadow">
                                <i class="fas fa-save me-2"></i>Mettre à jour l'absence
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-select, .form-control { border-width: 2px; }
    .bg-success-subtle { background-color: #d1e7dd !important; }
</style>
@endsection
