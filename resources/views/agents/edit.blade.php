@extends('layouts.app') {{-- Assurez-vous que ce layout existe --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Modifier l'agent : {{ $agent->first_name }} {{ $agent->last_name }}</div>

                <div class="card-body">
                    {{-- Formulaire de mise à jour --}}
                    <form method="POST" action="{{ route('agents.update', $agent->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Section Informations Personnelles --}}
                        <fieldset class="mb-4">
                            <legend>Informations Personnelles</legend>
                            <div class="row">
                                <!-- Matricule -->
                                <div class="col-md-4 mb-3">
                                    <label for="matricule" class="form-label">Matricule</label>
                                    <input type="text" class="form-control" id="matricule" name="matricule" value="{{ old('matricule', $agent->matricule) }}" required>
                                    @error('matricule')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Nom -->
                                <div class="col-md-4 mb-3">
                                    <label for="last_name" class="form-label">Nom de famille</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $agent->last_name) }}" required>
                                    @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Prénom -->
                                <div class="col-md-4 mb-3">
                                    <label for="first_name" class="form-label">Prénom(s)</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $agent->first_name) }}" required>
                                    @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Sexe -->
                                <div class="col-md-3 mb-3">
                                    <label for="sexe" class="form-label">Sexe</label>
                                    <select class="form-select" id="sexe" name="sexe" required>
                                        <option value="M" {{ old('sexe', $agent->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('sexe', $agent->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                    @error('sexe')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Date de naissance -->
                                <div class="col-md-4 mb-3">
                                    <label for="date_of_birth" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $agent->date_of_birth) }}">
                                    @error('date_of_birth')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Lieu de naissance (Place of birth) -->
                                <div class="col-md-5 mb-3">
                                    <label for="Place of birth" class="form-label">Lieu de naissance</label>
                                    <input type="text" class="form-control" id="Place of birth" name="Place of birth" value="{{ old('Place of birth', $agent->Place_of_birth) }}">
                                    @error('Place of birth')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- Section Contact et Adresse --}}
                        <fieldset class="mb-4">
                            <legend>Contact et Adresse</legend>
                            <div class="row">
                                <!-- Email -->
                                <div class="col-md-4 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $agent->email) }}">
                                    @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Téléphone -->
                                <div class="col-md-4 mb-3">
                                    <label for="phone_number" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $agent->phone_number) }}">
                                    @error('phone_number')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Adresse -->
                                <div class="col-md-4 mb-3">
                                    <label for="address" class="form-label">Adresse Physique</label>
                                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $agent->address) }}">
                                    @error('address')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- Section Professionnelle --}}
                        <fieldset class="mb-4">
                            <legend>Informations Professionnelles</legend>
                            <div class="row">
                                <!-- Emploi -->
                                <div class="col-md-4 mb-3">
                                    <label for="Emploi" class="form-label">Emploi</label>
                                    <input type="text" class="form-control" id="Emploi" name="Emploi" value="{{ old('Emploi', $agent->Emploi) }}">
                                    @error('Emploi')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Grade -->
                                <div class="col-md-4 mb-3">
                                    <label for="Grade" class="form-label">Grade</label>
                                    <input type="text" class="form-control" id="Grade" name="Grade" value="{{ old('Grade', $agent->Grade) }}">
                                    @error('Grade')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Date Prise de Service -->
                                <div class="col-md-4 mb-3">
                                    <label for="Date_Prise_de_service" class="form-label">Date Prise de Service</label>
                                    <input type="date" class="form-control" id="Date_Prise_de_service" name="Date_Prise_de_service" value="{{ old('Date_Prise_de_service', $agent->Date_Prise_de_service) }}">
                                    @error('Date_Prise_de_service')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Status -->
                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Statut</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="Actif" {{ old('status', $agent->status) == 'Actif' ? 'selected' : '' }}>Actif</option>
                                        <option value="Inactif" {{ old('status', $agent->status) == 'Inactif' ? 'selected' : '' }}>Inactif</option>
                                        {{-- Ajoutez d'autres statuts si nécessaire --}}
                                    </select>
                                    @error('status')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Service ID -->
                                <div class="col-md-4 mb-3">
                                    <label for="service_id" class="form-label">Service (ID)</label>
                                    {{-- Idéalement, utilisez un select dynamique pour lister les services existants --}}
                                    <input type="number" class="form-control" id="service_id" name="service_id" value="{{ old('service_id', $agent->service_id) }}">
                                    @error('service_id')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- Section Contact d'Urgence --}}
                        <fieldset class="mb-4">
                            <legend>Contact d'Urgence</legend>
                            <div class="row">
                                <!-- Personne a prévenir -->
                                <div class="col-md-6 mb-3">
                                    <label for="Personne_a_prevenir" class="form-label">Personne à prévenir</label>
                                    <input type="text" class="form-control" id="Personne_a_prevenir" name="Personne_a_prevenir" value="{{ old('Personne_a_prevenir', $agent->Personne_a_prevenir) }}">
                                    @error('Personne_a_prevenir')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                                <!-- Contact personne a prévenir -->
                                <div class="col-md-6 mb-3">
                                    <label for="Contact_personne_a_prevenir" class="form-label">Contact d'urgence</label>
                                    <input type="text" class="form-control" id="Contact_personne_a_prevenir" name="Contact_personne_a_prevenir" value="{{ old('Contact_personne_a_prevenir', $agent->Contact_personne_a_prevenir) }}">
                                    @error('Contact_personne_a_prevenir')<div class="text-danger">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </fieldset>

                        {{-- Section Photo et User ID (si applicable) --}}
                        <fieldset class="mb-4">
                            <legend>Autres</legend>
                            <div class="row">
                                <!-- Photo -->
                                <div class="col-md-6 mb-3">
                                    <label for="photo" class="form-label">Photo de profil</label>
                                    <input type="file" class="form-control" id="photo" name="photo">
                                    @error('photo')<div class="text-danger">{{ $message }}</div>@enderror

                                    @if($agent->photo)
                                        <p class="mt-2">Photo actuelle: <a href="{{ asset('storage/' . $agent->photo) }}" target="_blank">Voir l'image</a></p>
                                    @endif
                                </div>
                                <!-- User ID (souvent caché ou géré automatiquement) -->
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label">ID Utilisateur (lié)</label>
                                    <input type="number" class="form-control" id="user_id" name="user_id" value="{{ old('user_id', $agent->user_id) }}" readonly>
                                </div>
                            </div>
                        </fieldset>

                        {{-- Boutons d'action --}}
                        <div class="d-flex justify-content-between mt-4">
                            <button type="submit" class="btn btn-success">
                                Mettre à jour l'agent
                            </button>
                            <a href="{{ route('agents.index') }}" class="btn btn-danger">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
