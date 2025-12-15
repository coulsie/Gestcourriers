{{-- resources/views/absences/edit.blade.php --}}

@extends('layouts.app') {{-- Suppose que vous ayez un fichier de mise en page principal --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier l'absence #{{ $absence->id }} {{ $absence->agent->last_name }} {{ $absence->agent->first_name }}</div>

                <div class="card-body">
                    {{-- Le formulaire utilise la méthode POST mais simule PATCH/PUT avec @method --}}
                    <form method="POST" action="{{ route('absences.update', $absence->id) }}">
                        @csrf
                        @method('PUT') {{-- Indique à Laravel que c'est une requête de mise à jour --}}

                        {{-- Champ de sélection de l'agent --}}

                            <div class="mb-3">
                                <label for="agent_id" class="form-label">Agent</label>
                                <select name="agent_id" id="agent_id" class="form-control @error('agent_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un agent</option>
                                    {{-- Supposons que vous passez une variable $agents depuis le contrôleur --}}
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id', $absence->agent_id) == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->last_name }} {{ $agent->first_name}}
                                        </option>
                                    @endforeach
                                </select>
                                @error('agent_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                        {{-- Champ de sélection du type d'absence --}}
                        <div class="mb-3">
                            <label for="type_absence_id" class="form-label">Type d'absence</label>
                            <select class="form-select @error('type_absence_id') is-invalid @enderror" id="type_absence_id" name="type_absence_id" required>
                                <option value="">Sélectionnez un type</option>
                                {{-- Loop through absence types provided by the controller --}}
                                @foreach($type_absences as $type)
                                    <option value="{{ $type->id }}" {{ old('type_absence_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} {{ $type->nom_type}}
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
                                value="{{ old('date_debut', $absence->date_debut) }}"required>
                            @error('date_debut')<div class="invalid-feedback">{{ $message }}</div> @enderror

                        {{-- Champ Date de fin --}}
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin"
                                value="{{ old('date_fin', $absence->date_fin) }}" required>
                            @error('date_fin') <div class="invalid-feedback">{{ $message }}</div>
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
