@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer une Nouvelle Tâche / Notification</div>

                <div class="card-body">
                    {{-- Formulaire de création utilisant la méthode POST --}}
                    {{-- Assurez-vous d'avoir une route nommée 'notifications_taches.store' --}}
                    <form action="{{ route('notifications_taches.store') }}" method="POST">
                        @csrf

                        {{-- Champ Titre --}}
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre de la tâche</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Agent Assigné (ID) --}}
                        {{-- Idéalement, vous passeriez une liste d'agents depuis le contrôleur ici --}}
                        <div class="mb-3">
                            <label for="agent_id" class="form-label">Agent</label>
                            {{-- Le nom du champ POST doit être 'agent_id' --}}
                            <select name="agent_id" id="agent_id" class="form-control @error('agent_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un agent</option>
                                {{-- Supposons que vous passez une variable $agents depuis le contrôleur --}}
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }} {{ $agent->first_name}} {{ $agent->last_name}}

                                    </option>
                                @endforeach

                            </select>
                            @error('agent_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Suivi Par (Qui a créé la tâche, peut être automatiquement l'utilisateur connecté) --}}
                        <div class="mb-3">
                            <label for="suivi_par" class="form-label">Créée/Suivie par</label>
                            {{-- On peut pré-remplir avec le nom de l'utilisateur connecté --}}
                            <input type="text" class="form-control @error('suivi_par') is-invalid @enderror" id="suivi_par" name="suivi_par" value="{{ old('suivi_par', Auth::user()->name ?? 'Admin') }}" required>
                            @error('suivi_par')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Champ Priorité (ENUM) --}}
                            <div class="col-md-6 mb-3">
                                <label for="priorite" class="form-label">Priorité</label>
                                <select class="form-select @error('priorite') is-invalid @enderror" id="priorite" name="priorite" required>
                                    @foreach(['Faible', 'Moyenne', 'Élevée', 'Urgent'] as $priorite)
                                        <option value="{{ $priorite }}" {{ old('priorite', 'Moyenne') == $priorite ? 'selected' : '' }}>
                                            {{ $priorite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priorite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Champ Date d'Échéance (Datetime local pour faciliter la sélection) --}}
                            <div class="col-md-6 mb-3">
                                <label for="date_echeance" class="form-label">Date d'Échéance (Optionnel)</label>
                                <input type="datetime-local" class="form-control @error('date_echeance') is-invalid @enderror" id="date_echeance" name="date_echeance" value="{{ old('date_echeance') }}">
                                @error('date_echeance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ Lien d'action (Optionnel) --}}
                        <div class="mb-3">
                            <label for="lien_action" class="form-label">Lien d'action (URL)</label>
                            <input type="url" class="form-control @error('lien_action') is-invalid @enderror" id="lien_action" name="lien_action" value="{{ old('lien_action') }}">
                            @error('lien_action')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Créer la Tâche</button>
                            <a href="{{ route('notifications_taches.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
