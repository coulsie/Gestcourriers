@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger shadow-lg border-0" style="background: #7f1d1d; color: #ffffff; padding: 1.2rem; margin-bottom: 1.5rem; border-radius: 12px;">
        <strong class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i> Erreurs détectées :</strong>
        <ul class="mt-2 mb-0">
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
                <div class="card shadow-2xl border-0 rounded-4 overflow-hidden">
                    <!-- En-tête Royal -->
                    <div class="card-header text-white py-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #1e40af 100%); border-bottom: 4px solid #f59e0b;">
                        <h4 class="mb-0 fw-bold text-uppercase" style="letter-spacing: 1px;">
                            <i class="fas fa-user-shield me-2 text-warning"></i> Enregistrement Complet Agent & Compte Système
                        </h4>
                    </div>

                    <div class="card-body p-4" style="background-color: #f1f5f9;">
                        <div class="row">
                            <!-- SECTION 1 : IDENTITÉ (Bleu Intense) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-4 shadow-sm h-100 border-start border-6 border-blue-700">
                                    <h5 class="text-blue-800 mb-4 border-bottom border-2 pb-2 fw-black text-uppercase" style="color: #1e40af;">
                                        <i class="fas fa-id-card me-2"></i> 1. Identité & Coordonnées Personnelles
                                    </h5>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">MATRICULE *</label>
                                            <input type="text" name="matricule" class="form-control border-2 border-primary @error('matricule') is-invalid @enderror" style="background-color: #eff6ff;" value="{{ old('matricule') }}" placeholder="Ex: MAT-2026" required shadow-sm>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">SEXE</label>
                                            <select name="sexe" class="form-select border-2 border-primary shadow-sm">
                                                <option value="Male" {{ old('sexe') == 'Male' ? 'selected' : '' }}>♂ Masculin</option>
                                                <option value="Female" {{ old('sexe') == 'Female' ? 'selected' : '' }}>♀ Féminin</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">NOM *</label>
                                            <input type="text" name="last_name" class="form-control border-2 shadow-sm" value="{{ old('last_name') }}" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">PRÉNOMS *</label>
                                            <input type="text" name="first_name" class="form-control border-2 shadow-sm" value="{{ old('first_name') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-dark text-uppercase"><i class="fas fa-envelope me-1 text-primary"></i> Email Personnel</label>
                                        <input type="email" name="email_personnel" class="form-control border-2 shadow-sm" value="{{ old('email_personnel') }}" placeholder="exemple@gmail.com">
                                    </div>

                                    <div class="p-3 rounded-3 border-2 border-dashed border-primary bg-light">
                                        <label class="fw-bold text-primary mb-2" for="photo"><i class="fas fa-camera me-2"></i>PHOTO DE PROFIL</label>
                                        <input type="file" name="photo" id="photo" class="form-control border-0">
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2 : CARRIÈRE (Vert Émeraude) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-4 shadow-sm h-100 border-start border-6 border-emerald-600">
                                    <h5 class="text-emerald-800 mb-4 border-bottom border-2 pb-2 fw-black text-uppercase" style="color: #047857;">
                                        <i class="fas fa-briefcase me-2"></i> 2. Carrière & Affectation
                                    </h5>

                                    <div class="mb-4">
                                        <label class="form-label fw-black small text-success text-uppercase"><i class="fas fa-at me-1"></i> Email Professionnel (Login Système) *</label>
                                        <input type="email" name="email" class="form-control border-2 border-success fw-bold @error('email') is-invalid @enderror" style="background-color: #ecfdf5;" value="{{ old('email') }}" placeholder="nom.p@ministere.gouv" required>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-dark">SERVICE D'AFFECTATION *</label>
                                        <select name="service_id" class="form-select border-2 border-success shadow-sm" required>
                                            <option value="">-- Sélectionner le service --</option>
                                            @foreach($services ?? [] as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="alert alert-success border-0 shadow-sm rounded-3">
                                        <i class="fas fa-info-circle me-2"></i> L'email professionnel servira d'identifiant unique.
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3 : SÉCURITÉ & RÔLES (Ambre / Orange) -->
                                <div class="col-md-12 mb-4">
                                    <div class="p-4 rounded-4 shadow-lg border-start border-6 border-warning bg-white">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h5 class="text-warning-emphasis mb-3 border-bottom border-warning border-2 pb-2 fw-black text-uppercase">
                                                    <i class="fas fa-user-lock me-2"></i> 3. Sécurité & Privilèges Système
                                                </h5>
                                                <label class="form-label fw-black text-dark small text-uppercase">Rôle d'accès (Permissions) *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-warning border-warning text-white"><i class="fas fa-user-tag"></i></span>
                                                    <select name="old_role" class="form-select border-2 border-warning fw-bold @error('old_role') is-invalid @enderror" required>
                                                        <option value="" disabled selected>Choisir un rôle...</option>
                                                        <option value="superviseur">SUPERVISEUR</option>
                                                        <option value="directeur">DIRECTEUR</option>
                                                        <option value="sous_directeur">SOUS-DIRECTEUR</option>
                                                        <option value="chef_de_service">CHEF DE SERVICE</option>
                                                        <option value="agent">AGENT</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="p-3 bg-light-warning rounded-3 border border-warning text-center">
                                                    <i class="fas fa-magic fa-2x text-warning mb-2"></i>
                                                    <p class="mb-0 fw-bold text-dark italic">Les accès système seront générés automatiquement.</p>
                                                    <small class="text-muted text-uppercase fw-black">Mot de passe : Matricule</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>

                        <!-- BOUTONS ACTIONS -->
                        <div class="d-flex justify-content-between align-items-center mt-2 p-3 bg-dark rounded-4 shadow-lg">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-light border-2 px-4 fw-bold">
                                <i class="fas fa-arrow-left me-2"></i> RETOUR
                            </a>

                            <button type="submit" class="btn btn-success btn-lg px-5 fw-black text-uppercase shadow-lg border-2 border-white" style="background-color: #059669; min-width: 350px;">
                                <i class="fas fa-check-circle me-2"></i> Créer le profil & les accès
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .fw-black { font-weight: 900; }
    .border-6 { border-width: 6px !important; }
    .border-blue-700 { border-color: #1e40af !important; }
    .border-emerald-600 { border-color: #059669 !important; }
    .border-warning { border-color: #f59e0b !important; }
    .bg-light-warning { background-color: #fffbeb; }
    .text-warning-emphasis { color: #92400e !important; }
    .shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }
    .form-control:focus, .form-select:focus {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.2);
    }
</style>

@endsection
