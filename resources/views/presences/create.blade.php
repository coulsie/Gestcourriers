@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Card avec bordure supérieure colorée --}}
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-gradient-primary text-white py-3 border-bottom-0">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-fingerprint me-2"></i>Enregistrer une nouvelle présence</h5>
                </div>

                <div class="card-body p-4 bg-white">
                    {{-- Alertes de session --}}
                    @if (session('error'))
                        <div class="alert alert-danger border-left-danger shadow-sm alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('presences.store') }}">
                        @csrf

                        {{-- Section Agent : Encadré Bleu --}}
                        <div class="mb-4 p-3 rounded-3" style="background-color: #f0f7ff; border-left: 5px solid #0d6efd;">
                            <label for="agent_id" class="form-label fw-bold text-primary">
                                <i class="fas fa-user-tie me-1"></i> Sélection de l'Agent
                            </label>
                            <div class="input-group shadow-sm">
                                <span class="input-group-text bg-primary text-white border-primary"><i class="fas fa-id-card"></i></span>
                                <select id="agent_id" name="agent_id" class="form-select border-primary @error('agent_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choisir un agent...</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ strtoupper($agent->last_name) }} {{ $agent->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('agent_id')
                                <div class="text-danger small mt-1"><strong>{{ $message }}</strong></div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Section Arrivée : Encadré Vert --}}
                            <div class="col-md-6 mb-4">
                                <div class="p-3 rounded-3 h-100" style="background-color: #f1fdf4; border-left: 5px solid #198754;">
                                    <label for="heure_arrivee" class="form-label fw-bold text-success">
                                        <i class="fas fa-clock me-1"></i> Heure d'arrivée
                                    </label>
                                    <input type="datetime-local" name="heure_arrivee" id="heure_arrivee"
                                           class="form-control border-success shadow-sm"
                                           value="{{ old('heure_arrivee') }}">
                                    <div class="form-text text-success small italic"><i class="fas fa-info-circle"></i> Vide = Maintenant</div>
                                </div>
                            </div>

                            {{-- Section Départ : Encadré Rouge --}}
                            <div class="col-md-6 mb-4">
                                <div class="p-3 rounded-3 h-100" style="background-color: #fff5f5; border-left: 5px solid #dc3545;">
                                    <label for="heure_depart" class="form-label fw-bold text-danger">
                                        <i class="fas fa-history me-1"></i> Heure de départ
                                    </label>
                                    <input id="heure_depart" type="datetime-local"
                                           class="form-control border-danger shadow-sm @error('heure_depart') is-invalid @enderror"
                                           name="heure_depart" value="{{ old('heure_depart') }}">
                                    @error('heure_depart')
                                        <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section Statut : Encadré Jaune --}}
                        <div class="mb-4 p-3 rounded-3" style="background-color: #fff9e6; border-left: 5px solid #ffc107;">
                            <label for="statut" class="form-label fw-bold text-dark">
                                <i class="fas fa-traffic-light me-1"></i> Statut du Pointage
                            </label>
                            <div class="d-flex gap-3 mt-2">
                                <div class="flex-grow-1">
                                    <select name="statut" class="form-select border-warning shadow-sm fw-bold">
                                        <option value="Présent" {{ old('statut') == 'Présent' ? 'selected' : '' }}>🟢 PRÉSENT</option>
                                        <option value="En Retard" {{ old('statut') == 'En Retard' ? 'selected' : '' }}>🟡 EN RETARD</option>
                                        <option value="Absent" {{ old('statut') == 'Absent' ? 'selected' : '' }}>🔴 ABSENT</option>
                                        <option value="permission" {{ old('statut') == 'permission' ? 'selected' : '' }}>🔵 PERMISSION</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section Notes --}}
                        <div class="mb-4">
                            <label for="notes" class="form-label fw-bold text-secondary">
                                <i class="fas fa-comment-alt me-1"></i> Observations / Notes
                            </label>
                            <textarea id="notes" class="form-control border-2 @error('notes') is-invalid @enderror"
                                      name="notes" rows="2" placeholder="Ex: Panne de véhicule, rdv médical...">{{ old('notes') }}</textarea>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center border-top pt-4 mt-2">
                            <a href="{{ route('presences.index') }}" class="btn btn-light border px-4 fw-bold text-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow-lg fw-bold transition-up">
                                <i class="fas fa-save me-2"></i> VALIDER LE POINTAGE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary { background: linear-gradient(45deg, #4e73df 0%, #224abe 100%); }
    .transition-up { transition: transform 0.2s; }
    .transition-up:hover { transform: translateY(-3px); }
    .form-control:focus, .form-select:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15); border-color: #4e73df; }
</style>
@endsection
