@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white py-3">
            <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Création de l'Agent et du Compte</h4>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('agents.enregistrer') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- SECTION A : INFORMATIONS PERSONNELLES -->
                    <div class="col-md-6 border-end">
                        <h5 class="text-primary mb-4 border-bottom pb-2">Identité de l'Agent</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Matricule <span class="text-danger">*</span></label>
                                <input type="text" name="matricule" class="form-control @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}" required>
                                @error('matricule') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sexe</label>
                                <select name="sexe" class="form-select @error('sexe') is-invalid @enderror">
                                    <option value="">Choisir...</option>
                                    <option value="Male" {{ old('sexe') == 'Male' ? 'selected' : '' }}>Masculin</option>
                                    <option value="Female" {{ old('sexe') == 'Female' ? 'selected' : '' }}>Féminin</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nom <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Prénoms <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date de Naissance</label>
                                <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Lieu de Naissance</label>
                                <input type="text" name="place_birth" class="form-control" value="{{ old('place_birth') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Photo de profil</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                            <small class="text-muted">Format: jpg, png (Max 2Mo)</small>
                        </div>
                    </div>

                    <!-- SECTION B : EMPLOI & SERVICE -->
                    <div class="col-md-6">
                        <h5 class="text-primary mb-4 border-bottom pb-2">Affectation & Grade</h5>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Service <span class="text-danger">*</span></label>
                            <select name="service_id" class="form-select @error('service_id') is-invalid @enderror" required>
                                <option value="">Sélectionner un service...</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} ({{ $service->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Statut / Fonction <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="Agent">Agent</option>
                                <option value="Chef de service">Chef de service</option>
                                <option value="Sous-directeur">Sous-directeur</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Emploi</label>
                                <input type="text" name="Emploi" class="form-control" value="{{ old('Emploi') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Grade</label>
                                <input type="text" name="Grade" class="form-control" value="{{ old('Grade') }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Date de prise de service</label>
                            <input type="date" name="Date_Prise_de_service" class="form-control" value="{{ old('Date_Prise_de_service') }}">
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <!-- SECTION C : CONTACTS -->
                    <div class="col-md-6 border-end">
                        <h5 class="text-primary mb-4 border-bottom pb-2">Contacts & Urgence</h5>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Téléphone Personnel</label>
                            <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Adresse Domicile</label>
                            <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Professionnel (@entreprise.com)</label>
                            <input type="email" name="email_professionnel" class="form-control @error('email_professionnel') is-invalid @enderror" value="{{ old('email_professionnel') }}">
                            @error('email_professionnel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Personnel (Récupération)</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Personne à prévenir</label>
                                <input type="text" name="Personne_a_prevenir" class="form-control" value="{{ old('Personne_a_prevenir') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Contact Urgence</label>
                                <input type="text" name="Contact_personne_a_prevenir" class="form-control" value="{{ old('Contact_personne_a_prevenir') }}">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION D : COMPTE UTILISATEUR -->
                    <div class="col-md-6">
                        <h5 class="text-danger mb-4 border-bottom pb-2">Identifiants de Connexion</h5>
                        

                        <div class="mb-3">
                            <label class="form-label">Email de connexion</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Type d'accès (Rôle)</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Agent (Utilisateur standard)</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            </select>
                            @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="alert alert-info py-2">
                            <small><i class="fas fa-info-circle"></i> Le mot de passe par défaut sera généré automatiquement (ex: Matricule2026).</small>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="reset" class="btn btn-light border me-2">Annuler</button>
                    <button type="submit" class="btn btn-success">Enregistrer l'Agent</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
