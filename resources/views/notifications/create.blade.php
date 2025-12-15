@extends('layouts.app') {{-- Assurez-vous d'avoir un layout approprié --}}

@section('content')
    <div class="container">
        <h1>Créer une notification</h1>

        {{-- Gestion des erreurs de validation --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulaire de création --}}
        <form method="POST" action="{{ route('notifications.store') }}" enctype="multipart/form-data">
            @csrf {{-- Protection CSRF --}}

            {{-- agent_id (champ de sélection pour l'agent) --}}

            <div class="form-group">
                <label for="agent_id">Agent assigné</label>
                <div class="col-md-6">
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
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                </div>
            </div>

            {{-- Titre (varchar) --}}
            <div class="form-group">
                <label for="titre">Titre</label>
                <input type="text" name="titre" id="titre" class="form-control" value="{{ old('titre') }}" required maxlength="255">
            </div>

            {{-- Description (text) --}}
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
            </div>

            {{-- Date d'échéance (timestamp nullable) --}}
            <div class="form-group">
                <label for="date_echeance">Date d'échéance</label>
                <input type="datetime-local" name="date_echeance" id="date_echeance" class="form-control" value="{{ old('date_echeance') }}">
                {{-- Laisser vide si non requis, le champ est nullable dans la BD --}}
            </div>

            {{-- Suivi par (varchar) --}}
            <div class="form-group">
                <label for="suivi_par">Suivi par</label>
                <input type="text" name="suivi_par" id="suivi_par" class="form-control" value="{{ old('suivi_par') }}" required maxlength="100">
            </div>

            {{-- Priorité (enum) --}}
            <div class="form-group">
                <label for="priorite">Priorité</label>
                <select name="priorite" id="priorite" class="form-control" required>
                    {{-- Si vous utilisez des Enums PHP, vous pouvez itérer sur les cas --}}
                    @foreach(['Faible', 'Moyenne', 'Élevée', 'Urgent'] as $priorite)
                        <option value="{{ $priorite }}" {{ old('priorite', 'Moyenne') == $priorite ? 'selected' : '' }}>
                            {{ $priorite }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Statut (enum) --}}
            <div class="form-group">
                <label for="statut">Statut</label>
                <select name="statut" id="statut" class="form-control" required>
                    {{-- Si vous utilisez des Enums PHP, vous pouvez itérer sur les cas --}}
                    @foreach(['Non lu', 'En cours', 'Complétée', 'Annulée'] as $statut)
                        <option value="{{ $statut }}" {{ old('statut', 'Non lu') == $statut ? 'selected' : '' }}>
                            {{ $statut }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lien d'action (varchar nullable) --}}
            <div class="form-group">
                <label for="lien_action">Lien d'action</label>
                <input type="url" name="lien_action" id="lien_action" class="form-control" value="{{ old('lien_action') }}" maxlength="512">
            </div>

            {{-- Document (varchar nullable - gestion de fichier) --}}
            <div class="form-group">
                <label for="document">Document (fichier)</label>
                <input type="file" name="document" id="document" class="form-control-file">
            </div>

            <button type="submit" class="btn btn-primary">Créer</button>
        </form>
    </div>
@endsection
