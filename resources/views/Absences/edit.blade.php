{{-- resources/views/absences/edit.blade.php --}}

@extends('layouts.app') {{-- Suppose que vous ayez un fichier de mise en page principal --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier l'absence #{{ $absence->id }}</div>

                <div class="card-body">
                    {{-- Le formulaire utilise la méthode POST mais simule PATCH/PUT avec @method --}}
                    <form method="POST" action="{{ route('absences.update', $absence->id) }}">
                        @csrf
                        @method('PUT') {{-- Indique à Laravel que c'est une requête de mise à jour --}}

                        {{-- Champ de sélection de l'agent --}}
                        <div class="mb-3">
                            <label for="agent_id" class="form-label">Nom de l'agent</label>
                            <select class="form-select @error('agent_id') is-invalid @enderror" id="agent_id" name="agent_id" required>
                                <option value="">Sélectionnez un agent</option>
                                {{-- Boucle à travers les agents fournis par le contrôleur --}}
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ old('agent_id', $absence->agent_id) == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('agent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ de sélection du type d'absence --}}
                        <div class="mb-3">
                            <label for="type_absence_id" class="form-label">Type d'absence</label>
                            <select class="form-select @error('type_absence_id') is-invalid @enderror" id="type_absence_id" name="type_absence_id" required>
                                <option value="">Sélectionnez un type</option>
                                {{-- Boucle à travers les types d'absence fournis par le contrôleur --}}
                                @foreach($types as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('type_absence_id', $absence->type_absence_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('type_absence_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Date de début --}}
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut"
                                value="{{ old('date_debut', $absence->date_debut) }}" required>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Date de fin --}}
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin"
                                value="{{ old('date_fin', $absence->date_fin) }}" required>
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Case à cocher Statut approuvé --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="approuvee" name="approuvee" value="1"
                                {{ old('approuvee', $absence->approuvee) ? 'checked' : '' }}>
                            <label class="form-check-label" for="approuvee">Approuvée</label>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Mettre à jour l'absence</button>
                            <a href="{{ route('absences.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
