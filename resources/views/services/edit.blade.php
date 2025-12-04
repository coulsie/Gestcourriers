@extends('layouts.app') {{-- Assurez-vous que 'layouts.app' existe --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Modifier le Service : {{ $service->name }}</div>

                <div class="card-body">
                    {{-- Formulaire de mise à jour --}}
                    <form method="POST" action="{{ route('services.update', $service) }}">
                        @csrf
                        @method('PUT')

                        {{-- Champ Nom (name) --}}
                        <div class="form-group mb-3">
                            <label for="name">Nom du Service</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $service->name) }}"
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
                                   value="{{ old('code', $service->code) }}"
                                   required>

                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Direction parente (direction_id) --}}
                        <div class="form-group mb-3">
                            <label for="direction_id">Direction de rattachement</label>
                            <select name="direction_id" id="direction_id" class="form-control @error('direction_id') is-invalid @enderror" required>
                                <option value="">-- Choisir une direction --</option>
                                {{-- Boucle sur la liste des directions passée par le contrôleur --}}
                                @foreach($directions as $direction)
                                    <option value="{{ $direction->id }}"
                                        {{ old('direction_id', $service->direction_id) == $direction->id ? 'selected' : '' }}>
                                        {{ $direction->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('direction_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Responsable (head_id) --}}
                        <div class="form-group mb-3">
                            <label for="head_id">Responsable du Service</label>
                            <select name="head_id" id="head_id" class="form-control @error('head_id') is-invalid @enderror">
                                <option value="">-- Non affecté --</option>
                                {{-- Boucle sur la liste des utilisateurs passée par le contrôleur --}}
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}"
                                        {{ old('head_id', $service->head_id) == $agent->id ? 'selected' : '' }}>
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
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $service->description) }}</textarea>

                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                Mettre à jour le service
                            </button>
                            <a href="{{ route('services.index') }}" class="btn btn-secondary">
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
