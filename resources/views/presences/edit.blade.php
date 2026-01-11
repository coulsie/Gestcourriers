@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Card avec bordure colorée selon le statut actuel --}}
            @php
                $statusColor = match($presence->statut) {
                    'Présent' => 'success',
                    'En Retard' => 'warning',
                    'Absent' => 'danger',
                    default => 'primary',
                };
            @endphp

            <div class="card shadow border-{{ $statusColor }}">
                {{-- En-tête avec fond coloré --}}
                <div class="card-header bg-{{ $statusColor }} text-{{ $statusColor == 'warning' ? 'dark' : 'white' }}">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Modifier la présence : {{ $presence->agent->last_name }} {{ $presence->agent->first_name }}
                    </h5>
                    <small>Session du {{ \Carbon\Carbon::parse($presence->heure_arrivee)->translatedFormat('d F Y') }}</small>
                </div>

                <div class="card-body bg-light-subtle">
                    <form action="{{ route('presences.update', $presence->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Champ Agent (Lecture seule ou désactivé pour la modification) -->
                        <div class="mb-3">
                            <label for="agent_id" class="form-label fw-bold text-primary">Agent concerné</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
                                <select name="agent_id" id="agent_id" class="form-select @error('agent_id') is-invalid @enderror" required>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id', $presence->agent_id) == $agent->id ? 'selected' : '' }}>
                                            {{ $agent->last_name }} {{ $agent->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('agent_id') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <!-- Heure d'arrivée -->
                            <div class="col-md-6 mb-3">
                                <label for="heure_arrivee" class="form-label fw-bold text-success">Heure d'arrivée</label>
                                <input type="datetime-local" class="form-control border-success-subtle shadow-sm" id="heure_arrivee" name="heure_arrivee"
                                       value="{{ old('heure_arrivee', \Carbon\Carbon::parse($presence->heure_arrivee)->format('Y-m-d\TH:i')) }}" required>
                                @error('heure_arrivee') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- Heure de départ -->
                            <div class="col-md-6 mb-3">
                                <label for="heure_depart" class="form-label fw-bold text-danger">Heure de départ</label>
                                @php $heureDepartValue = $presence->heure_depart ? \Carbon\Carbon::parse($presence->heure_depart)->format('Y-m-d\TH:i') : ''; @endphp
                                <input type="datetime-local" class="form-control border-danger-subtle shadow-sm" id="heure_depart" name="heure_depart"
                                       value="{{ old('heure_depart', $heureDepartValue) }}">
                                @error('heure_depart') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Champ Statut avec indicateurs visuels -->
                        <div class="mb-3">
                            <label for="statut" class="form-label fw-bold">Statut de présence</label>
                            <select class="form-select border-2" id="statut" name="statut" required 
                                    style="border-left: 10px solid {{ $presence->statut == 'Présent' ? '#198754' : ($presence->statut == 'En Retard' ? '#ffc107' : '#dc3545') }}">
                                <option value="Présent" class="text-success" {{ old('statut', $presence->statut) == 'Présent' ? 'selected' : '' }}>🟢 Présent</option>
                                <option value="En Retard" class="text-warning" {{ old('statut', $presence->statut) == 'En Retard' ? 'selected' : '' }}>🟡 En Retard</option>
                                <option value="Absent" class="text-danger" {{ old('statut', $presence->statut) == 'Absent' ? 'selected' : '' }}>🔴 Absent</option>
                            </select>
                            @error('statut') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold text-secondary">Notes / Justification</label>
                            <textarea class="form-control bg-white" id="notes" name="notes" rows="3" placeholder="Ajouter un commentaire...">{{ old('notes', $presence->notes) }}</textarea>
                            @error('notes') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <a href="{{ route('presences.index') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success px-5 shadow">
                                <i class="fas fa-save me-1"></i> Mettre à jour la présence
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
