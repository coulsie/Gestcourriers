@extends('layouts.app') {{-- Assurez-vous d'utiliser votre layout principal --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer une nouvelle affectation</div>

                <div class="card-body">
                    {{-- Formulaire qui envoie les données à la route 'affectations.store' --}}
                    <form method="POST" action="{{ route('affectations.store') }}">
                        @csrf

                        {{-- Champ Courrier ID --}}
                        <div class="form-group row mb-3">
                            <label for="courrier_id" class="col-md-4 col-form-label text-md-right">Courrier</label>
                            <div class="col-md-6">
                                <select id="courrier_id" name="courrier_id" class="form-control @error('courrier_id') is-invalid @enderror" required autofocus>
                                    <option value="">Sélectionnez un courrier</option>
                                    @foreach($courriers as $courrier)
                                        <option value="{{ $courrier->id }}" {{ old('courrier_id') == $courrier->id ? 'selected' : '' }}>
                                            {{ $courrier->id }} - {{ $courrier->objet }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('courrier_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ Agent ID --}}
                        <div class="form-group row mb-3">
                            <label for="agent_id" class="col-md-4 col-form-label text-md-right">Agent affecté</label>
                            <div class="col-md-6">
                                <select id="agent_id" name="agent_id" class="form-control @error('agent_id') is-invalid @enderror" required>
                                    <option value="">Sélectionnez un agent</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->nom}} {{ $agent->first_name}} {{ $agent->last_name}} {{-- Affichez le nom ou l'email de l'agent --}}
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

                        {{-- Champ Statut (Généralement 'Affecté' ou 'En Cours' par défaut) --}}
                        <div class="form-group row mb-3">
                            <label for="statut" class="col-md-4 col-form-label text-md-right">Statut</label>
                            <div class="col-md-6">
                                <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                    <option value="affecte" {{ old('statut') == 'affecte' ? 'selected' : '' }}>Affecté</option>
                                    <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                    <option value="traite" {{ old('statut') == 'traite' ? 'selected' : '' }}>Traité</option>
                                </select>
                                @error('statut')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ Commentaires (Optionnel) --}}
                        <div class="form-group row mb-3">
                            <label for="commentaires" class="col-md-4 col-form-label text-md-right">Commentaires</label>
                            <div class="col-md-6">
                                <textarea id="commentaires" name="commentaires" rows="3" class="form-control @error('commentaires') is-invalid @enderror">{{ old('commentaires') }}</textarea>
                                @error('commentaires')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Les champs date_affectation, date_traitement, created_at, updated_at seront gérés automatiquement par Laravel Eloquent et la base de données. --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Affecter le courrier
                                </button>
                                <a href="{{ route('affectations.index') }}" class="btn btn-secondary">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
