@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger" style="background: #fee2e2; color: #991b1b; padding: 1rem; margin-bottom: 1rem; border: 1px solid #f87171;">
        <strong>Erreurs détectées :</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="container-fluid py-4">
    <form action="{{ route('agents.enregistrer') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="card shadow-lg border-0 rounded-lg">
                    <!-- En-tête avec dégradé Royal -->
                    <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                        <h4 class="mb-0 font-weight-bold">
                            <i class="fas fa-user-shield me-2"></i> Enregistrement Complet Agent & Compte Système
                        </h4>
                    </div>

                    <div class="card-body p-4" style="background-color: #f8fafc;">
                        <div class="row">
                            <!-- SECTION 1 : IDENTITÉ & CONTACTS PERSO (Bleu) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-primary">
                                    <h5 class="text-primary mb-4 border-bottom pb-2 font-weight-bold">
                                        <i class="fas fa-id-card me-2"></i> 1. Identité & Coordonnées Personnelles
                                    </h5>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">MATRICULE *</label>
                                            <input type="text" name="matricule" class="form-control border-primary-subtle @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}" placeholder="Ex: MAT-2026" required>
                                            @error('matricule') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">SEXE</label>
                                            <select name="sexe" class="form-select border-primary-subtle">
                                                <option value="Male" {{ old('sexe') == 'Male' ? 'selected' : '' }}>♂ Masculin</option>
                                                <option value="Female" {{ old('sexe') == 'Female' ? 'selected' : '' }}>♀ Féminin</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">NOM *</label>
                                            <input type="text" name="last_name" class="form-control border-primary-subtle" value="{{ old('last_name') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">PRÉNOMS *</label>
                                            <input type="text" name="first_name" class="form-control border-primary-subtle" value="{{ old('first_name') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted"><i class="fas fa-envelope me-1"></i> EMAIL PERSONNEL (Privé)</label>
                                        <input type="email" name="email_personnel" class="form-control border-primary-subtle" value="{{ old('email_personnel') }}" placeholder="exemple@gmail.com">
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">TÉLÉPHONE</label>
                                            <input type="text" name="phone_number" class="form-control border-primary-subtle" value="{{ old('phone_number') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">DATE DE NAISSANCE</label>
                                            <input type="date" name="date_of_birth" class="form-control border-primary-subtle" value="{{ old('date_of_birth') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">LIEU DE NAISSANCE</label>
                                            <input type="text" name="place_birth" class="form-control border-primary-subtle" value="{{ old('place_birth') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">ADRESSE</label>
                                            <input type="text" name="address" class="form-control border-primary-subtle" value="{{ old('address') }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <<label for="photo">Photo de profil</label>
                                    <input type="file" name="photo" id="photo" class="form-control-file border p-1 rounded @error('photo') is-invalid @enderror">
                                    @error('photo') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2 : CARRIÈRE & CONTACT PRO (Vert) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-start border-4 border-success">
                                    <h5 class="text-success mb-4 border-bottom pb-2 font-weight-bold">
                                        <i class="fas fa-briefcase me-2"></i> 2. Carrière & Affectation Professionnelle
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted text-success"><i class="fas fa-at me-1"></i> EMAIL PROFESSIONNEL</label>
                                        <input type="email" name="email_professionnel" class="form-control border-success-subtle @error('email_professionnel') is-invalid @enderror" value="{{ old('email_professionnel') }}" placeholder="nom.p@ministere.gouv">
                                        @error('email_professionnel') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted">SERVICE D'AFFECTATION *</label>
                                        <select name="service_id" class="form-select border-success-subtle shadow-sm" required>
                                            <option value="">Sélectionner un service...</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                    🏢 {{ $service->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">EMPLOI / POSTE</label>
                                            <input type="text" name="Emploi" class="form-control border-success-subtle" value="{{ old('Emploi') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">GRADE</label>
                                            <input type="text" name="Grade" class="form-control border-success-subtle" value="{{ old('Grade') }}">
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">STATUT HIÉRARCHIQUE</label>
                                            <select name="status" class="form-select border-success-subtle">
                                                <option value="Agent">Agent</option>
                                                <option value="Chef de service">Chef de service</option>
                                                <option value="Sous-directeur">Sous-directeur</option>
                                                <option value="Directeur">Directeur</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted">DATE PRISE SERVICE</label>
                                            <input type="date" name="Date_Prise_de_service" class="form-control border-success-subtle" value="{{ old('Date_Prise_de_service') }}">
                                        </div>
                                    </div>

                                    <div class="p-3 mt-2 rounded-3 border border-danger-subtle bg-danger-subtle bg-opacity-10">
                                        <h6 class="fw-bold text-danger mb-2 small text-uppercase"><i class="fas fa-ambulance"></i> Urgence</h6>
                                        <div class="row">
                                            <div class="col-md-6"><input type="text" name="Personne_a_prevenir" class="form-control form-control-sm mb-1" placeholder="Contact d'urgence"></div>
                                            <div class="col-md-6"><input type="text" name="Contact_personne_a_prevenir" class="form-control form-control-sm" placeholder="Téléphone"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3 : SÉCURITÉ & CONNEXION (Orange) -->
                            <div class="col-md-12 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm border-start border-4 border-warning">
                                    <h5 class="text-warning mb-4 border-bottom pb-2 font-weight-bold">
                                        <i class="fas fa-user-lock me-2"></i> 3. Paramètres du Compte Utilisateur (Accès Système)
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold small text-muted">NOM D'AFFICHAGE *</label>
                                            <input type="text" name="name" class="form-control border-warning-subtle" value="{{ old('name') }}" placeholder="Nom pour le profil" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <!-- CHAMP AJOUTÉ : MAIL DE CONNEXION -->
                                            <label class="form-label fw-bold small text-dark"><i class="fas fa-key me-1 text-warning"></i> MAIL DE CONNEXION *</label>
                                            <input type="email" name="email" class="form-control border-warning border-2 @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="identifiant@connexion.com" required style="background-color: #fffdf5;">
                                            <small class="text-danger fw-bold" style="font-size: 0.7rem;">Cet email sera l'identifiant de connexion unique.</small>
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold small text-muted">RÔLE D'ACCÈS *</label>
                                            <select name="role" class="form-select border-warning-subtle fw-bold text-dark" required>
                                                <option value="directeur">🎓 Directeur</option>
                                                <option value="sous_directeur">🏢 Sous-directeur</option>
                                                <option value="chef_de_service">👨‍💼 Chef de service</option>
                                                <option value="agent">👤 Agent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold small text-muted">MOT DE PASSE *</label>
                                            <input type="password" name="password" class="form-control border-warning-subtle shadow-sm" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold small text-muted">CONFIRMER MOT DE PASSE *</label>
                                            <input type="password" name="password_confirmation" class="form-control border-warning-subtle shadow-sm" required>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center">
                                            <div class="form-check form-switch p-3 bg-light rounded border border-warning-subtle">
                                                <input class="form-check-input ms-0 me-2" type="checkbox" name="must_change_password" id="mustChange" checked>
                                                <label class="form-check-label fw-bold text-dark small" for="mustChange">Forcer changement password</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOUTONS D'ACTION FINAUX -->
                        <div class="d-flex justify-content-end gap-3 mt-3">
                            <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary px-4 fw-bold">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-lg fw-bold rounded-pill text-uppercase">
                                <i class="fas fa-save me-2"></i> Créer Agent & Compte
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .border-primary-subtle { border-color: #cfe2ff !important; }
    .border-success-subtle { border-color: #d1e7dd !important; }
    .border-warning-subtle { border-color: #fff3cd !important; }
    .form-control:focus { box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1); border-color: #3b82f6; }
    .card-header h4 { letter-spacing: 0.8px; }
</style>
@endsection
