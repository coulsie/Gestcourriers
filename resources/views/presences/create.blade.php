@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer une Nouvelle Présence</div>

                <div class="card-body">
                    {{-- Le formulaire pointe vers la route 'presences.store' pour traitement --}}
                    <form method="POST" action="{{ route('presences.store') }}">
                        @csrf

                        {{-- Champ Agent (Utilisateur) --}}
                        <div class="mb-3">
                            <label for="agent_id" class="form-label">Agent</label>
                            {{-- Le nom du champ POST doit être 'agent_id' --}}
                            <select name="agent_id" id="agent_id" class="form-control @error('agent_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un agent</option>
                                {{-- Supposons que vous passez une variable $agents depuis le contrôleur --}}
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                        {{ $agent->name }} {{ $agent->first_name}}

                                    </option>
                                @endforeach

                            </select>
                            @error('agent_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        {{-- Champ Date de Présence --}}
                        <div class="mb-3">
                            <label for="date_presence" class="form-label">Date</label>
                            <input type="date" name="date_presence" id="date_presence" class="form-control @error('date_presence') is-invalid @enderror" value="{{ old('date_presence', now()->format('Y-m-d')) }}" required>
                            @error('date_presence')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Heure d'Arrivée --}}
                        <div class="mb-3">
                            <label for="heure_arrivee" class="form-label">Heure d'Arrivée</label>
                            <input type="time" name="heure_arrivee" id="heure_arrivee" class="form-control @error('heure_arrivee') is-invalid @enderror" value="{{ old('heure_arrivee') }}">
                            @error('heure_arrivee')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Heure de Départ --}}
                        <div class="mb-3">
                            <label for="heure_depart" class="form-label">Heure de Départ</label>
                            <input type="time" name="heure_depart" id="heure_depart" class="form-control @error('heure_depart') is-invalid @enderror" value="{{ old('heure_depart') }}">
                            @error('heure_depart')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Statut --}}
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select name="statut" id="statut" class="form-control @error('statut') is-invalid @enderror">
                                <option value="Présent" {{ old('statut') == 'Présent' ? 'selected' : '' }}>Présent</option>
                                <option value="Absent" {{ old('statut') == 'Absent' ? 'selected' : '' }}>Absent</option>
                                <option value="Retard" {{ old('statut') == 'Retard' ? 'selected' : '' }}>Retard</option>
                            </select>
                            @error('statut')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">Enregistrer la Présence</button>
                            <a href="{{ route('presences.index') }}" class="btn btn-danger">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
