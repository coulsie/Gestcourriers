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
                            <!-- SECTION 1 : IDENTITÉ -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-4 shadow-sm h-100 border-start border-6 border-blue-700">
                                    <h5 class="text-blue-800 mb-4 border-bottom border-2 pb-2 fw-black text-uppercase" style="color: #1e40af;">
                                        <i class="fas fa-id-card me-2"></i> 1. Identité & Coordonnées Personnelles
                                    </h5>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">MATRICULE *</label>
                                            <input type="text" name="matricule" class="form-control border-2 border-primary @error('matricule') is-invalid @enderror" style="background-color: #eff6ff;" value="{{ old('matricule') }}" placeholder="Ex: MAT-2026" required>
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
                                            <input type="text" name="last_name" class="form-control border-2 shadow-sm text-uppercase" style="text-transform: uppercase;" value="{{ old('last_name') }}" placeholder="NOM DE L'AGENT" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">PRÉNOMS *</label>
                                            <input type="text" name="first_name" class="form-control border-2 shadow-sm" style="text-transform: capitalize;" value="{{ old('first_name') }}" placeholder="Ex: Jean Paul" required>
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

                            <!-- SECTION 2 : CARRIÈRE -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-4 shadow-sm h-100 border-start border-6 border-emerald-600">
                                    <h5 class="text-emerald-800 mb-4 border-bottom border-2 pb-2 fw-black text-uppercase" style="color: #047857;">
                                        <i class="fas fa-briefcase me-2"></i> 2. Carrière & Affectation
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-black small text-success text-uppercase"><i class="fas fa-at me-1"></i> Email Pro (Login) *</label>
                                        <input type="email" name="email" class="form-control border-2 border-success fw-bold @error('email') is-invalid @enderror" style="background-color: #ecfdf5;" value="{{ old('email') }}" required>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <label class="form-label fw-bold small text-dark text-uppercase">Fonction / Status Administratif *</label>
                                            <select name="status" class="form-select border-2 border-success shadow-sm fw-bold" required>
                                                <option value="">-- Sélectionner le titre --</option>
                                                @php
                                                    $statuses = ['Agent','Chef de service','Sous-directeur','Directeur','Conseiller Technique','Conseiller Spécial'];
                                                @endphp
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status }}" {{ old('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold small text-dark">SERVICE D'AFFECTATION *</label>
                                        <select name="service_id" class="form-select border-2 border-success shadow-sm" required>
                                            <option value="">-- Sélectionner le service --</option>
                                            @foreach($services ?? [] as $service)
                                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="alert alert-success border-0 shadow-sm rounded-3 py-2">
                                        <i class="fas fa-info-circle me-2"></i> MDP par défaut : <strong>Matricule</strong> de l'agent.
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3 : SÉCURITÉ & RÔLES -->
                            <div class="col-md-12 mb-4">
                                <div class="p-4 rounded-4 shadow-lg border-start border-6 border-warning bg-white">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <h5 class="text-warning-emphasis mb-2 border-bottom border-warning border-2 pb-2 fw-black text-uppercase">
                                                <i class="fas fa-user-lock me-2"></i> 3. Privilèges Système
                                            </h5>
                                            <p class="small text-muted mb-0">Définissez le rôle de l'utilisateur pour les accès aux modules.</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-dark">RÔLE SYSTÈME *</label>
                                            <select name="role" class="form-select border-2 border-warning fw-bold" required>
                                                <option value="">-- Choisir un rôle --</option>
                                                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>{{ strtoupper($role->name) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-white py-3 px-4 text-end">
                        <button type="submit" class="btn btn-primary px-5 fw-bold shadow-lg" style="background: #1e40af;">
                            <i class="fas fa-save me-2"></i> VALIDER L'ENREGISTREMENT
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
