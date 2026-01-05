@extends('layouts.app') {{-- Assurez-vous que 'layouts.app' est votre template de mise en page principal --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Enregistrer une nouvelle présence</div>

                <div class="card-body">
                    {{-- L'action du formulaire doit pointer vers la route 'store' de votre ressource 'presences' --}}
                    <form method="POST" action="{{ route('presences.store') }}">
                        @csrf {{-- Protection CSRF obligatoire dans Laravel --}}

                        {{-- Champ agent_id (Index) --}}
                        {{-- Supposons que vous passez une variable $agents depuis le contrôleur --}}
                        <div class="form-group row mb-3">
                            <label for="agent_id" class="col-md-4 col-form-label text-md-right">Agent</label>
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

                        {{-- Champ heure_arrivee (Timestamp) --}}
                        <div class="mb-3">
                            <label for="heure_arrivee" class="form-label">Heure d'arrivée (laisser vide pour l'heure actuelle)</label>
                            <input type="datetime-local"
                                name="heure_arrivee"
                                id="heure_arrivee"
                                class="form-control">
                        </div>
                        <div class="form-group row mb-3">
                            <label for="heure_depart" class="col-md-4 col-form-label text-md-right">Heure depart</label>
                            <div class="col-md-6">
                                {{-- Utilisez 'datetime-local' pour une interface simple de sélection date/heure --}}
                                <input id="heure_depart" type="datetime-local" class="form-control @error('heure_depart') is-invalid @enderror" name="heure_depart" value="{{ old('heure_depart') ?? now()->format('Y-m-d\TH:i') }}" required autocomplete="heure_depart" autofocus>

                                @error('heure_depart')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>


                        {{-- Champ statut (Enum: Absent, Présent, En Retard) --}}


                        {{-- Champ notes (Text) --}}
                        <div class="form-group row mb-3">
                            <label for="notes" class="col-md-4 col-form-label text-md-right">Notes</label>
                            <div class="col-md-6">
                                <textarea id="notes" class="form-control @error('notes') is-invalid @enderror" name="notes" rows="4">{{ old('notes') }}</textarea>

                                @error('notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Note sur heure_depart : Ce champ est NULLABLE et souvent rempli lors d'une action de "pointage de sortie",
                           donc il est généralement omis dans le formulaire de création initiale. --}}


                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                              <a href="{{ route('presences.index') }}" class="btn btn-danger px-4">
                                <i class="fas fa-times me-1"></i> Annuler
                                </a>
                                <button type="submit" class="btn btn-success">
                                    Enregistrer
                                </button>


                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
