{{-- resources/views/absences/create.blade.php --}}

@extends('layouts.app') {{-- Assuming a main layout file --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Enrégistrer une nouvelle autorisation d'absence</div>

                <div class="card-body">
                    {{-- Form action points to the store method in AbsenceController --}}
                    <form method="POST" action="{{ route('absences.store') }}">
                        @csrf

                        {{-- Agent Selection Field --}}
                        <div class="mb-3">
                            <label for="agent_id" class="form-label">Nom de l'agent</label>
                            <select id="agent_id" name="agent_id" class="form-control @error('agent_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un agent</option>
                                    {{-- Boucle sur les agents disponibles pour créer les options --}}
                                    {{-- Remplacez $agents par le nom exact de votre variable --}}
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->nom_complet }} {{ $agent->last_name }} {{ $agent->first_name }}{{-- Remplacez par le nom de la colonne appropriée --}}
                                        </option>
                                    @endforeach
                                </select>
                            @error('agent_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Absence Type Selection Field --}}
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

                        {{-- Start Date Field --}}
                        <div class="mb-3">
                            <label for="date_debut" class="form-label">Date de début</label>
                            <input type="date" class="form-control @error('date_debut') is-invalid @enderror" id="date_debut" name="date_debut" value="{{ old('date_debut') }}" required>
                            @error('date_debut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- End Date Field --}}
                        <div class="mb-3">
                            <label for="date_fin" class="form-label">Date de fin</label>
                            <input type="date" class="form-control @error('date_fin') is-invalid @enderror" id="date_fin" name="date_fin" value="{{ old('date_fin') }}" required>
                            @error('date_fin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Approved Status Checkbox (Optional for creation form, typically handled by HR) --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="approuvee" name="approuvee" value="1" {{ old('approuvee') ? 'checked' : '' }}>
                            <label class="form-check-label" for="approuvee">Approuvé (Cochez uniquement si approuvé automatiquement)</label>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                            <a href="{{ route('absences.index') }}" class="btn btn-danger">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
