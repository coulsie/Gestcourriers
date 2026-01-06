@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card shadow-lg border-0 rounded-lg">
                <!-- En-tête avec dégradé moderne -->
                <div class="card-header bg-gradient-primary text-white py-3 shadow-sm" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                    <h4 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-plus me-2"></i> Création de l'Agent et du Compte
                    </h4>
                </div>

                <div class="card-body p-4 bg-light">
                    <form action="{{ route('agents.enregistrer') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- SECTION A : INFORMATIONS PERSONNELLES -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded shadow-sm h-100 border-top-primary">
                                    <h5 class="text-primary mb-4 d-flex align-items-center">
                                        <span class="badge bg-primary-soft text-primary me-2">1</span>
                                        Identité de l'Agent
                                    </h5>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Matricule <span class="text-danger">*</span></label>
                                            <input type="text" name="matricule" class="form-control border-left-primary @error('matricule') is-invalid @enderror" value="{{ old('matricule') }}" required placeholder="Ex: MAT-2026">
                                            @error('matricule') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Sexe</label>
                                            <select name="sexe" class="form-select border-left-info @error('sexe') is-invalid @enderror">
                                                <option value="">Choisir...</option>
                                                <option value="Male" {{ old('sexe') == 'Male' ? 'selected' : '' }}>♂ Masculin</option>
                                                <option value="Female" {{ old('sexe') == 'Female' ? 'selected' : '' }}>♀ Féminin</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Nom <span class="text-danger">*</span></label>
                                            <input type="text" name="last_name" class="form-control border-left-primary @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Prénoms <span class="text-danger">*</span></label>
                                            <input type="text" name="first_name" class="form-control border-left-primary @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Date de Naissance</label>
                                            <input type="date" name="date_of_birth" class="form-control border-left-secondary" value="{{ old('date_of_birth') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Lieu de Naissance</label>
                                            <input type="text" name="place_birth" class="form-control border-left-secondary" value="{{ old('place_birth') }}">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small text-uppercase">Photo de profil</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fas fa-camera"></i></span>
                                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror">
                                        </div>
                                        <small class="text-muted italic">Format: jpg, png (Max 2Mo)</small>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION B : EMPLOI & SERVICE -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded shadow-sm h-100 border-top-success">
                                    <h5 class="text-success mb-4 d-flex align-items-center">
                                        <span class="badge bg-success-soft text-success me-2">2</span>
                                        Affectation & Grade
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small text-uppercase">Service <span class="text-danger">*</span></label>
                                        <select name="service_id" class="form-select border-left-success @error('service_id') is-invalid @enderror" required>
                                            <option value="">Sélectionner un service...</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                                    🏢 {{ $service->name }} ({{ $service->code }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small text-uppercase">Statut / Fonction <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select border-left-success" required>
                                            <option value="Agent">👤 Agent</option>
                                            <option value="Chef de service">👨‍💼 Chef de service</option>
                                            <option value="Sous-directeur">🏢 Sous-directeur</option>
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Emploi</label>
                                            <input type="text" name="Emploi" class="form-control border-left-success" value="{{ old('Emploi') }}" placeholder="Ex: Comptable">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Grade</label>
                                            <input type="text" name="Grade" class="form-control border-left-success" value="{{ old('Grade') }}" placeholder="Ex: A3">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold text-dark small text-uppercase">Date de prise de service</label>
                                        <input type="date" name="Date_Prise_de_service" class="form-control border-left-warning" value="{{ old('Date_Prise_de_service') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION C : CONTACTS (Largeur complète) -->
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="p-4 bg-white rounded shadow-sm border-top-info">
                                    <h5 class="text-info mb-4 d-flex align-items-center">
                                        <span class="badge bg-info-soft text-info me-2">3</span>
                                        Contacts & Urgence
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Téléphone Personnel</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="fas fa-phone"></i></span>
                                                <input type="text" name="phone_number" class="form-control border-left-info" value="{{ old('phone_number') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Email Professionnel</label>
                                            <input type="email" name="email_professionnel" class="form-control border-left-info @error('email_professionnel') is-invalid @enderror" value="{{ old('email_professionnel') }}" placeholder="nom@entreprise.com">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold text-dark small text-uppercase">Email Personnel</label>
                                            <input type="email" name="email" class="form-control border-left-info" value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="mb-0">
                                        <label class="form-label fw-bold text-dark small text-uppercase">Adresse Domicile</label>
                                        <textarea name="address" class="form-control border-left-info" rows="2" placeholder="Quartier, Rue, Porte...">{{ old('address') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOUTONS D'ACTION -->
                        <div class="d-flex justify-content-end mt-2 pt-4 border-top">
                            <button type="reset" class="btn btn-light border me-3 px-4 shadow-sm">
                                <i class="fas fa-undo me-2"></i>Réinitialiser
                            </button>
                            <button type="submit" class="btn btn-primary px-5 py-2 shadow font-weight-bold rounded-pill">
                                <i class="fas fa-save me-2"></i> Enregistrer l'Agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles 2026 */
    .border-top-primary { border-top: 4px solid #4e73df !important; }
    .border-top-success { border-top: 4px solid #1cc88a !important; }
    .border-top-info { border-top: 4px solid #36b9cc !important; }

    .border-left-primary { border-left: 3px solid #4e73df !important; }
    .border-left-success { border-left: 3px solid #1cc88a !important; }
    .border-left-info { border-left: 3px solid #36b9cc !important; }
    .border-left-warning { border-left: 3px solid #f6c23e !important; }
    .border-left-secondary { border-left: 3px solid #858796 !important; }

    .bg-primary-soft { background-color: rgba(78, 115, 223, 0.1) !important; }
    .bg-success-soft { background-color: rgba(28, 200, 138, 0.1) !important; }
    .bg-info-soft { background-color: rgba(54, 185, 204, 0.1) !important; }

    .form-control:focus, .form-select:focus {
        border-color: #4e73df;
        box-shadow: 0 0 8px rgba(78, 115, 223, 0.25);
    }

    .form-label { margin-bottom: 0.3rem; letter-spacing: 0.5px; }

    .btn-primary {
        background: #4e73df;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4) !important;
        background: #224abe;
    }
</style>
@endsection
