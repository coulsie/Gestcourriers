@extends('layouts.app')

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container-fluid py-5 bg-gradient-light">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-2xl border-0 rounded-4 overflow-hidden">
                <!-- En-tête Premium -->
                <div class="card-header py-4" style="background: linear-gradient(135deg, #0f172a 0%, #312e81 100%); border-bottom: 4px solid #f59e0b;">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-white fw-900 text-uppercase">
                            <i class="fas fa-user-edit me-2 text-warning"></i> Mise à jour Profil : {{ $agent->first_name }} {{ $agent->last_name }}
                        </h3>
                        <span class="badge bg-warning text-dark px-4 py-2 fs-5 fw-bold shadow-sm">MATRICULE: {{ $agent->matricule }}</span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="{{ route('agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                       
                            <input type="hidden" name="matricule" value="{{ $agent->matricule }}">

                        <div class="row g-4">
                            {{-- SECTION PHOTO --}}
                            <div class="col-md-12 mb-2 text-center">
                                <div class="p-4 rounded-4 shadow-sm border border-2 border-indigo-200 bg-light">
                                    <div class="mb-3 position-relative d-inline-block">
                                        @if($agent->photo && file_exists(public_path('agents_photos/' . $agent->photo)))
                                            <img src="{{ asset('agents_photos/' . $agent->photo) }}?v={{ time() }}" class="img-thumbnail rounded-circle shadow-lg border-indigo" style="width: 150px; height: 150px; object-fit: cover; border-width: 4px;">
                                        @else
                                            <div class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-lg border border-4 border-indigo" style="width: 150px; height: 150px;">
                                                <i class="fas fa-user fa-5x text-indigo opacity-25"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-5 mx-auto text-start">
                                        <label class="form-label fw-bold text-indigo fs-5 d-block text-center mb-2">Modifier la photo</label>
                                        <input type="file" name="photo" class="form-control border-2 border-indigo shadow-sm">
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 1: IDENTITÉ & ÉTAT CIVIL (INDIGO) --}}
                            <div class="col-md-12">
                                <div class="p-4 bg-white border-start border-5 border-indigo rounded shadow-sm">
                                    <h5 class="text-white fw-bold mb-4 text-uppercase p-2 rounded bg-indigo shadow-sm fs-5">
                                        <i class="fas fa-id-card me-2 text-warning"></i> 1. Identité & État Civil
                                    </h5>
                                    <div class="row gy-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">NOM *</label>
                                            <input type="text" name="last_name" class="form-control border-2 fs-5 fw-bold shadow-sm" value="{{ old('last_name', $agent->last_name) }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">PRÉNOMS *</label>
                                            <input type="text" name="first_name" class="form-control border-2 fs-5 fw-bold shadow-sm" value="{{ old('first_name', $agent->first_name) }}" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">SEXE *</label>
                                            <select name="sexe" class="form-select border-2 fs-5 fw-bold shadow-sm">
                                                <option value="Male" {{ $agent->sexe == 'Male' ? 'selected' : '' }}>Masculin</option>
                                                <option value="Female" {{ $agent->sexe == 'Female' ? 'selected' : '' }}>Féminin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">DATE DE NAISSANCE</label>
                                            <input type="date" name="date_of_birth" class="form-control border-2 fs-5 shadow-sm" value="{{ old('date_of_birth', $agent->date_of_birth) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">LIEU DE NAISSANCE</label>
                                            <input type="text" name="place_birth" class="form-control border-2 fs-5 shadow-sm" value="{{ old('place_birth', $agent->place_birth) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-indigo fs-5 text-uppercase mb-1"><i class="fas fa-user-tie me-1"></i> Emploi / Fonction *</label>
                                            <input type="text" name="Emploi" class="form-control border-2 border-primary fs-5 fw-bold shadow-sm" value="{{ old('Emploi', $agent->Emploi) }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">ADRESSE DOMICILE</label>
                                            <input type="text" name="address" class="form-control border-2 fs-5 shadow-sm" value="{{ old('address', $agent->address) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 2: CARRIÈRE & AFFECTATION (ÉMÉRAUDE) - COMPACTE --}}
                           {{-- SECTION 2: CARRIÈRE & AFFECTATION (ÉMÉRAUDE) - COMPACTE & RÉDUITE --}}
                            <{{-- SECTION 2: CARRIÈRE & AFFECTATION (ÉMÉRAUDE) --}}
<div class="col-md-12">
    <div class="p-3 bg-white border-start border-5 border-emerald rounded shadow-sm" style="border-left-color: #059669 !important;">
        <h6 class="text-white fw-bold mb-3 text-uppercase p-2 rounded shadow-sm" style="background-color: #059669;">
            <i class="fas fa-briefcase me-2 text-warning"></i> 2. Affectation & Carrière Professionnelle
        </h6>

        <div class="row g-3">
            {{-- Champ Status (Obligatoire) --}}
            <div class="col-md-4">
                <label class="form-label fw-bold text-dark fs-6 mb-1">STATUS / TITRE *</label>
                <select name="status" class="form-select border-2 shadow-sm fw-bold @error('status') is-invalid @enderror" required>
                    <option value="">-- Choisir --</option>
                    @php
                        $statuses = ['Agent','Chef de service','Sous-directeur','Directeur','Conseiller Technique','Conseiller Spécial'];
                    @endphp
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" {{ old('status', $agent->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Champ Service (Obligatoire) --}}
            <div class="col-md-4">
                <label class="form-label fw-bold text-dark fs-6 mb-1">SERVICE D'AFFECTATION *</label>
                <select name="service_id" class="form-select border-2 shadow-sm fw-bold @error('service_id') is-invalid @enderror" required>
                    <option value="">-- Choisir le service --</option>
                    @foreach($services ?? [] as $service)
                        <option value="{{ $service->id }}" {{ old('service_id', $agent->service_id) == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Grade --}}
            <div class="col-md-4">
                <label class="form-label fw-bold text-dark fs-6 mb-1">GRADE</label>
                <input type="text" name="Grade" class="form-control border-2 shadow-sm" value="{{ old('Grade', $agent->Grade) }}">
            </div>

            {{-- Email Professionnel (Login) --}}
            <div class="col-md-6">
                <label class="form-label fw-bold text-dark fs-6 mb-1">EMAIL PROFESSIONNEL *</label>
                <input type="email" name="email" class="form-control border-2 shadow-sm bg-light" value="{{ old('email', $agent->user->email ?? '') }}" required>
            </div>

            {{-- Date de Prise de Service --}}
            <div class="col-md-6">
                <label class="form-label fw-bold text-dark fs-6 mb-1">DATE DE PRISE DE SERVICE</label>
                <input type="date" name="Date_Prise_de_service" class="form-control border-2 shadow-sm" value="{{ old('Date_Prise_de_service', $agent->Date_Prise_de_service) }}">
            </div>
        </div>
    </div>
</div>
                            {{-- SECTION 3: CONTACTS & URGENCE (AMBRE) --}}
                            <div class="col-md-12">
                                <div class="p-4 bg-white border-start border-5 border-amber rounded shadow-sm">
                                    <h5 class="text-white fw-bold mb-4 text-uppercase p-2 rounded bg-amber shadow-sm fs-5">
                                        <i class="fas fa-phone-alt me-2 text-dark"></i> 3. Contacts d'Urgence
                                    </h5>
                                    <div class="row gy-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">TÉLÉPHONE AGENT</label>
                                            <input type="text" name="phone_number" class="form-control border-2 border-warning fs-5 fw-bold shadow-sm" value="{{ old('phone_number', $agent->phone_number) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">PERSONNE À PRÉVENIR</label>
                                            <input type="text" name="Personne_a_prevenir" class="form-control border-2 border-warning fs-5 shadow-sm" value="{{ old('Personne_a_prevenir', $agent->Personne_a_prevenir) }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">CONTACT URGENCE</label>
                                            <input type="text" name="Contact_personne_a_prevenir" class="form-control border-2 border-warning fs-5 fw-bold shadow-sm" value="{{ old('Contact_personne_a_prevenir', $agent->Contact_personne_a_prevenir) }}">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold text-dark fs-5 mb-1">ADRESSE DU CONTACT</label>
                                            <input type="text" name="adresse" class="form-control border-2 border-warning fs-5 shadow-sm" value="{{ old('adresse', $agent->adresse) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="d-flex justify-content-between align-items-center mt-5 p-4 bg-dark rounded-4 shadow-lg border-top border-warning border-4">
                            <a href="{{ route('agents.index') }}" class="btn btn-outline-light px-4 fs-5 fw-bold"><i class="fas fa-arrow-left me-2"></i> RETOUR</a>
                            <button type="submit" class="btn btn-warning btn-lg px-5 fs-4 fw-900 shadow-lg text-dark"><i class="fas fa-save me-2"></i> ENREGISTRER</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root { --indigo: #312e81; --emerald: #059669; --amber: #d97706; }
    .bg-emerald { background-color: var(--emerald) !important; }
    .bg-indigo { background-color: var(--indigo) !important; }
    .bg-amber { background-color: var(--amber) !important; }
    .border-success { border-color: var(--emerald) !important; }
    .border-warning { border-color: var(--amber) !important; }
    .fw-900 { font-weight: 900; }
    .border-5 { border-left-width: 8px !important; }
    .form-control, .form-select { border-radius: 8px; padding: 12px; transition: 0.3s; }
    .form-control:focus { border-color: #f59e0b !important; box-shadow: 0 0 12px rgba(245, 158, 11, 0.25); }
</style>
@endsection
