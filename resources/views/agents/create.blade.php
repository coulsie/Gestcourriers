@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Créer un nouvel Agent</div>

                <div class="card-body">
                    {{-- Le formulaire gère l'upload de photo avec enctype="multipart/form-data" --}}
                    <form method="POST" action="{{ route('agents.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            {{-- COLONNE GAUCHE (Informations personnelles) --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="matricule" class="form-label">Matricule</label>
                                    <input type="text" class="form-control @error('matricule') is-invalid @enderror" id="matricule" name="matricule" value="{{ old('matricule') }}" required>
                                    @error('matricule') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="first_name" class="form-label">Prénom</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="last_name" class="form-label">Nom</label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="sexe" class="form-label">Sexe</label>
                                    <select class="form-select @error('sexe') is-invalid @enderror" id="sexe" name="sexe">
                                        <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                                        <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                    @error('sexe') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_of_birth" class="form-label">Date de naissance</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    @error('date_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="place_of_birth" class="form-label">Lieu de naissance</label>
                                    <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror" id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}">
                                    @error('place_of_birth') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Statut (Ex: Contractuel, Titulaire)</label>
                                    <input type="text" class="form-control @error('status') is-invalid @enderror" id="status" name="status" value="{{ old('status') }}">
                                    @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                            </div>

                            {{-- COLONNE DROITE (Contact et Affectation) --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Professionnel</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
                                    @error('phone_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="service_id" class="form-label">Service d'affectation</label>
                                    <select class="form-select @error('service_id') is-invalid @enderror" id="service_id" name="service_id" required>
                                        <option value="">Choisir un service...</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                {{ $service->name }} ({{ $service->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="date_prise_de_service" class="form-label">Date prise de service</label>
                                    <input type="date" class="form-control @error('date_prise_de_service') is-invalid @enderror" id="date_prise_de_service" name="date_prise_de_service" value="{{ old('date_prise_de_service') }}">
                                    @error('date_prise_de_service') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Champ Photo (Input type file) --}}
                                <div class="mb-3">
                                    <label for="photo" class="form-label">Photo de profil</label>
                                    <input class="form-control @error('photo') is-invalid @enderror" type="file" id="photo" name="photo">
                                    @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section Emploi et Contact d'Urgence --}}
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="emploi" class="form-label">Emploi / Poste</label>
                                    <input type="text" class="form-control @error('emploi') is-invalid @enderror" id="emploi" name="emploi" value="{{ old('emploi') }}">
                                    @error('emploi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="grade" class="form-label">Grade</label>
                                    <input type="text" class="form-control @error('grade') is-invalid @enderror" id="grade" name="grade" value="{{ old('grade') }}">
                                    @error('grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Lier à un compte utilisateur</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                        <option value="">Ne pas lier (Admin seulement)</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="personne_a_prevenir" class="form-label">Personne à prévenir (Nom)</label>
                                    <input type="text" class="form-control @error('personne_a_prevenir') is-invalid @enderror" id="personne_a_prevenir" name="personne_a_prevenir" value="{{ old('personne_a_prevenir') }}">
                                    @error('personne_a_prevenir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_personne_a_prevenir" class="form-label">Contact personne à prévenir</label>
                                    <input type="text" class="form-control @error('contact_personne_a_prevenir') is-invalid @enderror" id="contact_personne_a_prevenir" name="contact_personne_a_prevenir" value="{{ old('contact_personne_a_prevenir') }}">
                                    @error('contact_personne_a_prevenir') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <button type="submit" class="btn btn-primary mt-3">Enregistrer l'agent</button>
                        <a href="{{ route('agents.index') }}" class="btn btn-secondary mt-3">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
