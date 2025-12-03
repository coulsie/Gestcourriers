@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer un nouveau Service</div>

                <div class="card-body">
                    {{-- Formulaire qui envoie les données à la méthode store du ServiceController --}}
                    <form method="POST" action="{{ route('services.store') }}">
                        @csrf

                        {{-- Champ Nom du Service --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom du service</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Code (Optionnel) --}}
                        <div class="mb-3">
                            <label for="code" class="form-label">Code (Optionnel)</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Direction (Liste déroulante obligatoire) --}}
                        <div class="mb-3">
                            <label for="direction_id" class="form-label">Direction parente</label>
                            <select class="form-select @error('direction_id') is-invalid @enderror" id="direction_id" name="direction_id" required>
                                <option value="">Choisir une direction...</option>
                                {{-- Boucle sur la liste des directions --}}
                                @foreach ($directions as $direction)
                                    <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                                        {{ $direction->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('direction_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Responsable (Liste déroulante optionnelle) --}}
                        <div class="mb-3">
                            <label for="head_id" class="form-label">Responsable du service (Optionnel)</label>
                            <select class="form-select @error('head_id') is-invalid @enderror" id="head_id" name="head_id">
                                <option value="">Choisir un agent...</option>
                                {{-- Boucle sur la liste des agents --}}
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('head_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->first_name }} {{ $agent->last_name }} (Mat: {{ $agent->matricule }})
                                    </option>
                                @endforeach
                            </select>
                            @error('head_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description (Optionnel)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Boutons d'action --}}
                        <button type="submit" class="btn btn-success">Enregistrer le service</button>
                        <a href="{{ route('services.index') }}" class="btn btn-danger">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
