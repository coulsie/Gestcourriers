@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer une nouvelle Direction</div>

                <div class="card-body">
                    {{-- Formulaire qui envoie les données à la méthode store du contrôleur --}}
                    <form method="POST" action="{{ route('directions.store') }}">
                        @csrf

                        {{-- Champ Nom --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la direction</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Code --}}
                        <div class="mb-3">
                            <label for="code" class="form-label">Code (Optionnel)</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Responsable (Liste déroulante) --}}
                        <div class="mb-3">
                            <label for="head_id" class="form-label">Responsable (Optionnel)</label>
                            <select class="form-select @error('head_id') is-invalid @enderror" id="head_id" name="head_id">
                                <option value="">Choisir un agent...</option>
                                {{-- Boucle sur la liste des agents passée par le contrôleur --}}
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('head_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->first_name }} {{ $agent->last_name }}
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
                        <button type="submit" class="btn btn-success">Enregistrer la direction</button>
                        <a href="{{ route('directions.index') }}" class="btn btn-danger">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
