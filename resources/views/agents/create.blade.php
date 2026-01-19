@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 bg-soft-secondary">
    <div class="row justify-content-center">
        <div class="col-md-11 col-xl-10">
            <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
                {{-- Header --}}
                <div class="card-header bg-dark py-3 border-bottom border-primary border-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-white fw-bold text-uppercase tracking-wide">
                            <i class="fas fa-user-plus me-2 text-warning"></i> Fiche d'Enrôlement (Version 2026)
                        </h5>
                        <span class="badge bg-primary px-3 py-2">COMPLET</span>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="{{ route('agents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            {{-- SECTION 1 : IDENTITÉ --}}
                            <div class="col-12">
                                <div class="p-4 border-start border-primary border-5 bg-light rounded shadow-sm">
                                    <h6 class="text-primary fw-bold mb-4 text-uppercase border-bottom pb-2">
                                        <i class="fas fa-id-card me-2"></i> 1. Identité & État Civil
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-2">
                                            <label class="form-label fw-bold">Matricule *</label>
                                            <input type="text" name="matricule" class="form-control border-primary fw-bold" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold">Nom *</label>
                                            <input type="text" name="last_name" class="form-control border-primary" required>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label fw-bold">Prénom(s) *</label>
                                            <input type="text" name="first_name" class="form-control border-primary" required>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Sexe</label>
                                            <select name="sexe" class="form-select border-primary">
                                                <option value="Male">Masculin</option>
                                                <option value="Female">Féminin</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Date Naissance</label>
                                            <input type="date" name="date_of_birth" class="form-control border-primary">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Lieu de Naissance</label>
                                            <input type="text" name="place_birth" class="form-control border-primary">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 2 : CONTACTS --}}
                            <div class="col-lg-6">
                                <div class="p-4 border-start border-warning border-5 bg-light rounded shadow-sm h-100">
                                    <h6 class="text-dark fw-bold mb-4 text-uppercase border-bottom pb-2">
                                        <i class="fas fa-phone-alt me-2 text-warning"></i> 2. Contacts & Urgence
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Email Perso</label>
                                            <input type="email" name="email" class="form-control border-warning">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Téléphone</label>
                                            <input type="text" name="phone_number" class="form-control border-warning">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Adresse Domicile</label>
                                            <input type="text" name="address" class="form-control border-warning">
                                        </div>
                                        <div class="col-12 mt-3 p-3 bg-white rounded border border-danger shadow-sm">
                                            <p class="small fw-bold text-danger mb-2"><i class="fas fa-exclamation-circle"></i> CONTACT D'URGENCE</p>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <input type="text" name="Personne_a_prevenir" class="form-control form-control-sm border-danger" placeholder="Nom complet">
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" name="Contact_personne_a_prevenir" class="form-control form-control-sm border-danger" placeholder="Téléphone">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SECTION 3 : PROFESSIONNEL (CORRIGÉ POUR LE CHAMP SERVICE) --}}
                            <div class="col-lg-6">
                                <div class="p-4 border-start border-success border-5 bg-light rounded shadow-sm h-100">
                                    <h6 class="text-success fw-bold mb-4 text-uppercase border-bottom pb-2">
                                        <i class="fas fa-briefcase me-2"></i> 3. Carrière & Affectation
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Statut *</label>
                                            <select name="status" class="form-select border-success fw-bold" required>
                                                <option value="Agent">Agent</option>
                                                <option value="Chef de service">Chef de service</option>
                                                <option value="Sous-directeur">Sous-directeur</option>
                                                <option value="Directeur">Directeur</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Service *</label>
                                            {{-- Le style max-width et text-overflow empêche le débordement --}}
                                            <select name="service_id" class="form-select border-success" required style="max-width: 100%;">
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" title="{{ $service->name }}">
                                                        {{ Str::limit($service->name, 35) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label fw-bold text-success">Email Professionnel</label>
                                            <input type="email" name="email_professionnel" class="form-control border-success fw-bold">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Emploi / Fonction</label>
                                            <input type="text" name="Emploi" class="form-control border-success">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">Grade</label>
                                            <input type="text" name="Grade" class="form-control border-success">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">Prise de service</label>
                                            <input type="date" name="Date_Prise_de_service" class="form-control border-success">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small">Photo d'identité</label>
                                            <input type="file" name="photo" class="form-control border-success">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BOUTONS --}}
                        <div class="d-flex justify-content-between align-items-center mt-5 p-4 bg-dark rounded-lg">
                            <a href="{{ route('agents.index') }}" class="btn btn-outline-light px-4 fw-bold">ANNULER</a>
                            <button type="submit" class="btn btn-success btn-lg px-5 fw-bolder shadow-lg">
                                <i class="fas fa-check-circle me-2 text-warning"></i> VALIDER L'ENREGISTREMENT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-secondary { background-color: #f8fafc; }
    .rounded-xl { border-radius: 1rem !important; }
    .border-5 { border-width: 5px !important; }
    .form-control, .form-select {
        border-width: 2px;
        font-size: 0.95rem;
    }
    {{-- Force l'affichage propre des sélecteurs --}}
    .form-select {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .btn-lg { letter-spacing: 1px; }
</style>
@endsection
