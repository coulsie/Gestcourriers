@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user-plus"></i> Créer un nouvel agent</h5>
                </div>

                <div class="card-body bg-light">
                    <form action="{{ route('agents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- SECTION 1 : Identité et Photo --}}
                        <div class="p-3 mb-4 bg-white border-left border-primary rounded shadow-sm">
                            <h6 class="text-primary font-weight-bold mb-3"><i class="fas fa-id-card"></i> Identité & Photo</h6>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="matricule" class="font-weight-bold">Matricule <span class="text-danger">*</span></label>
                                    <input type="text" name="matricule" id="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}" required>
                                    @error('matricule') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="first_name" class="font-weight-bold">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="last_name" class="font-weight-bold">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="photo">Photo de profil</label>
                                    <input type="file" name="photo" id="photo" class="form-control-file border p-1 rounded @error('photo') is-invalid @enderror">
                                    @error('photo') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label for="sexe">Sexe</label>
                                    <select name="sexe" id="sexe" class="form-control @error('sexe') is-invalid @enderror">
                                        <option value="">Sélectionner...</option>
                                        <option value="Male" {{ old('sexe') == 'Male' ? 'selected' : '' }}>Masculin</option>
                                        <option value="Female" {{ old('sexe') == 'Female' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="date_of_birth">Date de Naissance</label>
                                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="place_birth">Lieu de Naissance</label>
                                    <input type="text" name="place_birth" id="place_birth" class="form-control @error('place_birth') is-invalid @enderror" value="{{ old('place_birth') }}">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2 : Professionnel --}}
                        <div class="p-3 mb-4 bg-white border-left border-success rounded shadow-sm">
                            <h6 class="text-success font-weight-bold mb-3"><i class="fas fa-briefcase"></i> Informations Professionnelles</h6>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="status" class="font-weight-bold">Statut <span class="text-danger">*</span></label>
                                    <select name="status" id="status" class="form-control border-success @error('status') is-invalid @enderror" required>
                                        <option value="Agent" {{ old('status') == 'Agent' ? 'selected' : '' }}>Agent</option>
                                        <option value="Chef de service" {{ old('status') == 'Chef de service' ? 'selected' : '' }}>Chef de service</option>
                                        <option value="Sous-directeur" {{ old('status') == 'Sous-directeur' ? 'selected' : '' }}>Sous-directeur</option>
                                        <option value="Directeur" {{ old('status') == 'Directeur' ? 'selected' : '' }}>Directeur</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="service_id">Service d'affectation <span class="text-danger">*</span></label>
                                    <select name="service_id" id="service_id" class="form-control border-success @error('service_id') is-invalid @enderror" required>
                                        <option value="">Choisir un service...</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Date_Prise_de_service">Date Prise de service</label>
                                    <input type="date" name="Date_Prise_de_service" id="Date_Prise_de_service" class="form-control @error('Date_Prise_de_service') is-invalid @enderror" value="{{ old('Date_Prise_de_service') }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="Emploi">Emploi / Fonction</label>
                                    <input type="text" name="Emploi" id="Emploi" class="form-control @error('Emploi') is-invalid @enderror" value="{{ old('Emploi') }}" placeholder="Ex: Informaticien">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="Grade">Grade</label>
                                    <input type="text" name="Grade" id="Grade" class="form-control @error('Grade') is-invalid @enderror" value="{{ old('Grade') }}" placeholder="Ex: A4">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email_professionnel">E-mail Professionnel</label>
                                    <input type="email" name="email_professionnel" id="email_professionnel" class="form-control @error('email_professionnel') is-invalid @enderror" value="{{ old('email_professionnel') }}">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 3 : Contact & Urgence --}}
                        <div class="p-3 mb-4 bg-white border-left border-warning rounded shadow-sm">
                            <h6 class="text-warning font-weight-bold mb-3"><i class="fas fa-phone"></i> Contacts & Urgence</h6>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="email">E-mail Personnel</label>
                                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="phone_number">Téléphone</label>
                                    <input type="text" name="phone_number" id="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number') }}">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="address">Adresse géographique</label>
                                    <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}">
                                </div>
                            </div>
                            <div class="form-row border-top pt-3 mt-2">
                                <div class="form-group col-md-6">
                                    <label for="Personne_a_prevenir" class="text-danger font-weight-bold">Personne à prévenir (Urgence)</label>
                                    <input type="text" name="Personne_a_prevenir" id="Personne_a_prevenir" class="form-control border-danger @error('Personne_a_prevenir') is-invalid @enderror" value="{{ old('Personne_a_prevenir') }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="Contact_personne_a_prevenir" class="text-danger font-weight-bold">Contact d'urgence</label>
                                    <input type="text" name="Contact_personne_a_prevenir" id="Contact_personne_a_prevenir" class="form-control border-danger @error('Contact_personne_a_prevenir') is-invalid @enderror" value="{{ old('Contact_personne_a_prevenir') }}">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 4 : Système (Optionnel) --}}
                        @if(isset($users))
                        <div class="p-3 mb-4 bg-white border-left border-secondary rounded shadow-sm">
                            <h6 class="text-secondary font-weight-bold mb-3"><i class="fas fa-user-lock"></i> Liaison Compte Utilisateur</h6>
                            <div class="form-group col-md-6 px-0">
                                <label for="user_id">Associer à un compte utilisateur</label>
                                <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                    <option value="">Aucun compte lié</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        <div class="form-group mb-0 d-flex justify-content-between">
                            <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary shadow-sm px-4">
                                <i class="fas fa-arrow-left"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-primary shadow-sm px-5">
                                <i class="fas fa-save"></i> Enregistrer les données de l'agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
