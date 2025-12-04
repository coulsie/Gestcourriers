@extends('layouts.app') {{-- Assurez-vous que 'layouts.app' existe --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modification : {{ $direction->name }}</div>

                <div class="card-body">
                    {{--
                        Le formulaire pointe vers la route 'directions.update' en passant
                        l'objet $direction pour générer l'URL correcte (/directions/{id}).
                    --}}
                    <form method="POST" action="{{ route('directions.update', $direction) }}">
                        @csrf
                        @method('PUT') {{-- Utilise la méthode HTTP PUT pour la mise à jour --}}

                        {{-- Champ Nom (name) --}}
                        <div class="form-group mb-3">
                            <label for="name">Nom de la Direction</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $direction->name) }}" {{-- old() pour retenir la saisie si erreur --}}
                                   required
                                   autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Code (code) --}}
                        <div class="form-group mb-3">
                            <label for="code">Code Unique</label>
                            <input type="text"
                                   name="code"
                                   id="code"
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $direction->code) }}"
                                   required>

                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Responsable (head_id) --}}
                        {{-- NOTE: Assurez-vous de passer une variable $users ou $heads depuis votre contrôleur --}}
                        <div class="form-group mb-3">
                            <label for="head_id">Responsable de la Direction</label>
                            <select name="head_id" id="head_id" class="form-control @error('head_id') is-invalid @enderror">
                                <option value="">-- Non affecté --</option>
                                {{-- Boucle sur la liste des utilisateurs passée par le contrôleur --}}
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ old('head_id', $direction->head_id) == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->first_name }} {{ $agent->last_name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('head_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Description (description) --}}
                         <div class="form-group mb-3">
                            <label for="description">Description</label>
                            <textarea name="description"
                                      id="description"
                                      rows="3"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $direction->description) }}</textarea>

                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Mettre à jour la direction
                            </button>
                            <a href="{{ route('directions.index') }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
