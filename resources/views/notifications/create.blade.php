@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Créer une nouvelle notification / tâche
                </div>

                <div class="card-body">
                    <form action="{{ route('notifications.store') }}" method="POST">
                        @csrf

                        <!-- Champ id_agent (Liste déroulante) -->
                        <div class="mb-3">
                            <label for="id_agent" class="form-label">Agent Bénéficiaire</label>
                            <select class="form-select @error('id_agent') is-invalid @enderror" id="id_agent" name="id_agent" required>
                                <option value="">Sélectionner un agent</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('id_agent') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }} {{ $agent->last_name }} {{ $agent->first_name }} <!-- Affichez le nom ou une combinaison nom/prénom de l'agent -->
                                    </option>
                                @endforeach
                            </select>
                            @error('id_agent')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Titre -->
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre') }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Description (Textarea) -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Suivi Par -->
                        <div class="mb-3">
                            <label for="suivi_par" class="form-label">Suivi par</label>
                            <input type="text" class="form-control @error('suivi_par') is-invalid @enderror" id="suivi_par" name="suivi_par" value="{{ old('suivi_par') }}" required>
                            @error('suivi_par')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Priorité (ENUM) -->
                        <div class="mb-3">
                            <label for="priorite" class="form-label">Priorité</label>
                            <select class="form-select @error('priorite') is-invalid @enderror" id="priorite" name="priorite" required>
                                <option value="Faible" {{ old('priorite') == 'Faible' ? 'selected' : '' }}>Faible</option>
                                <option value="Moyenne" {{ old('priorite') == 'Moyenne' ? 'selected' : '' }}>Moyenne</option>
                                <option value="Élevée" {{ old('priorite') == 'Élevée' ? 'selected' : '' }}>Élevée</option>
                                <option value="Urgent" {{ old('priorite') == 'Urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                            @error('priorite')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ date_echeance (Optionnel) -->
                        <div class="mb-3">
                            <label for="date_echeance" class="form-label">Date d'échéance (optionnel)</label>
                            <input type="datetime-local" class="form-control @error('date_echeance') is-invalid @enderror" id="date_echeance" name="date_echeance" value="{{ old('date_echeance') }}">
                            @error('date_echeance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ lien_action (Optionnel) -->
                        <div class="mb-3">
                            <label for="lien_action" class="form-label">Lien d'action (URL optionnelle)</label>
                            <input type="url" class="form-control @error('lien_action') is-invalid @enderror" id="lien_action" name="lien_action" value="{{ old('lien_action') }}">
                            @error('lien_action')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="document" class="form-label">Document</label>
                            <input type="file" class="form-control @error('document') is-invalid @enderror" id="document" name="document" value="{{ old('document') }}">
                            @error('document')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Les champs date_creation, statut, date_lecture, date_completion sont gérés par défaut dans le contrôleur/BDD -->

                        <button type="submit" class="btn btn-success">Créer la notification</button>
                        <a href="{{ route('notifications.index') }}" class="btn btn-danger">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
