{{-- resources/views/type_absences/edit.blade.php --}}

@extends('layouts.app') {{-- Assuming a main layout file --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Absence Type: {{ $typeAbsence->nom_type }}</div>

                <div class="card-body">
                    {{-- Form action points to the update method in TypeAbsenceController --}}
                    <form method="POST" action="{{ route('typeabsences.update', $typeAbsence->id) }}">
                        @csrf
                        @method('PUT') {{-- Simulates a PUT/PATCH request for updates --}}

                        {{-- Nom Type Field --}}
                        <div class="mb-3">
                            <label for="nom_type" class="form-label">Name of Type</label>
                            <input type="text" class="form-control @error('nom_type') is-invalid @enderror" id="nom_type" name="nom_type"
                                value="{{ old('nom_type', $typeAbsence->nom_type) }}" required>
                            @error('nom_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Code Field --}}
                        <div class="mb-3">
                            <label for="code" class="form-label">Code (e.g., SICK, VAC)</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                                value="{{ old('code', $typeAbsence->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description Field --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $typeAbsence->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Est Paye Checkbox --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="est_paye" name="est_paye" value="1"
                                {{ old('est_paye', $typeAbsence->est_paye) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_paye">Is this a paid absence?</label>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Update Type</button>
                            <a href="{{ route('typeabsences.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
