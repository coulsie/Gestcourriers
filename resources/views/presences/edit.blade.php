@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Éditer la présence #{{ $presence->id }}
                </div>

                <div class="card-body">
                    <!-- Le formulaire utilise la méthode POST mais simule PUT/PATCH via @method('PUT') -->
                    <form action="{{ route('presences.update', $presence->id) }}" method="POST">
                        @csrf
                        @method('PUT') <!-- Indique à Laravel d'utiliser la méthode PUT pour la mise à jour -->

                        <!-- Champ Agent ID (ou une liste déroulante si vous avez les agents) -->
                        <div class="mb-3">
                            <label for="agent_id" class="form-label">Agent</label>
                            <!-- Idéalement, ceci devrait être une liste déroulante d'agents -->
                            <input type="text"
                                   class="form-control"
                                   id="agent_id"
                                   name="agent_id"
                                   value="{{ old('agent_id', $presence->agent_id) }}"
                                   required>
                            @error('agent_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Heure d'arrivée -->
                        <div class="mb-3">
                            <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                            <input type="datetime-local"
                                   class="form-control"
                                   id="heure_arrivee"
                                   name="heure_arrivee"
                                   value="{{ old('heure_arrivee', \Carbon\Carbon::parse($presence->heure_arrivee)->format('Y-m-d\TH:i')) }}"
                                   required>
                            @error('heure_arrivee')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Heure de départ (optionnel) -->
                        <div class="mb-3">
                            <label for="heure_depart" class="form-label">Heure de départ</label>
                            @php
                                // Formate la valeur seulement si elle existe, sinon vide la chaîne pour l'input
                                $heureDepartValue = $presence->heure_depart ? \Carbon\Carbon::parse($presence->heure_depart)->format('Y-m-d\TH:i') : '';
                            @endphp
                            <input type="datetime-local"
                                   class="form-control"
                                   id="heure_depart"
                                   name="heure_depart"
                                   value="{{ old('heure_depart', $heureDepartValue) }}">
                            @error('heure_depart')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Statut (ENUM) -->
                        <div class="mb-3">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut" required>
                                <!-- Pré-sélectionne l'option actuelle -->
                                <option value="Absent" {{ old('statut', $presence->statut) == 'Absent' ? 'selected' : '' }}>Absent</option>
                                <option value="Présent" {{ old('statut', $presence->statut) == 'Présent' ? 'selected' : '' }}>Présent</option>
                                <option value="En Retard" {{ old('statut', $presence->statut) == 'En Retard' ? 'selected' : '' }}>En Retard</option>
                            </select>
                            @error('statut')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ Notes (TEXT) -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3">{{ old('notes', $presence->notes) }}</textarea>
                            @error('notes')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success">Mettre à jour la présence</button>
                        <a href="{{ route('presences.index') }}" class="btn btn-secondary">Annuler</a>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
