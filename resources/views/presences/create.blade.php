@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Card avec bordure subtile --}}
            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Enregistrer une nouvelle présence</h5>
                </div>

                <div class="card-body p-4 bg-light-subtle">
                    <form method="POST" action="{{ route('presences.store') }}">
                        @csrf

                        {{-- Section Agent : Couleur Bleu --}}
                        <div class="mb-4">
                            <label for="agent_id" class="form-label fw-bold text-primary">Agent</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
                                <select id="agent_id" name="agent_id" class="form-select @error('agent_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choisir un agent...</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->last_name }} {{ $agent->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('agent_id')
                                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Section Arrivée : Couleur Vert --}}
                            <div class="col-md-6 mb-4">
                                <label for="heure_arrivee" class="form-label fw-bold text-success">
                                    <i class="fas fa-sign-in-alt me-1"></i> Heure d'arrivée
                                </label>
                                <input type="datetime-local" 
                                       name="heure_arrivee" 
                                       id="heure_arrivee" 
                                       class="form-control border-success-subtle shadow-sm"
                                       value="{{ old('heure_arrivee') }}">
                                <div class="form-text">Laissez vide pour l'heure actuelle</div>
                            </div>

                            {{-- Section Départ : Couleur Rouge --}}
                            <div class="col-md-6 mb-4">
                                <label for="heure_depart" class="form-label fw-bold text-danger">
                                    <i class="fas fa-sign-out-alt me-1"></i> Heure de départ
                                </label>
                                <input id="heure_depart" 
                                       type="datetime-local" 
                                       class="form-control border-danger-subtle shadow-sm @error('heure_depart') is-invalid @enderror" 
                                       name="heure_depart" 
                                       value="{{ old('heure_depart') }}">
                                @error('heure_depart')
                                    <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                @enderror
                            </div>
                        </div>

                        {{-- Section Statut (Optionnel mais recommandé pour les couleurs) --}}
                        <div class="mb-4">
                            <label for="statut" class="form-label fw-bold">Statut initial</label>
                            <select name="statut" class="form-select border-info shadow-sm">
                                <option value="Présent" {{ old('statut') == 'Présent' ? 'selected' : '' }}>🟢 Présent</option>
                                <option value="En Retard" {{ old('statut') == 'En Retard' ? 'selected' : '' }}>🟡 En Retard</option>
                                <option value="Absent" {{ old('statut') == 'Absent' ? 'selected' : '' }}>🔴 Absent</option>
                            </select>
                        </div>

                        {{-- Section Notes : Couleur Gris --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold text-secondary">Notes / Commentaires</label>
                            <textarea id="notes" 
                                      class="form-control border-secondary-subtle @error('notes') is-invalid @enderror" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Raison du retard ou observation particulière...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        {{-- Actions : Boutons Colorés --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <a href="{{ route('presences.index') }}" class="btn btn-outline-danger px-4">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success btn-lg px-5 shadow">
                                <i class="fas fa-save me-1"></i> Enregistrer la présence
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
