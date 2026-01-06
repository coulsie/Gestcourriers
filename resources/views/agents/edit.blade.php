@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg border-0 rounded-lg">
                <!-- En-tête avec dégradé ambre/orange -->
                <div class="card-header text-white py-3 shadow-sm" style="background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%);">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-edit me-2"></i> Modifier l'agent : {{ $agent->first_name }} {{ $agent->last_name }}
                    </h5>
                </div>

                <div class="card-body p-4 bg-light">
                    <form action="{{ route('agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- SECTION 1: IDENTITÉ & MATRICULE --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-warning">
                            <h6 class="text-warning font-weight-bold mb-4 text-uppercase"><i class="fas fa-id-card me-2"></i> État Civil & Identifiant</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control border-warning-soft @error('first_name') is-invalid @enderror" value="{{ old('first_name', $agent->first_name) }}" required>
                                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control border-warning-soft @error('last_name') is-invalid @enderror" value="{{ old('last_name', $agent->last_name) }}" required>
                                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small text-primary">N° Matricule <span class="text-danger">*</span></label>
                                    <input type="text" name="matricule" class="form-control border-left-primary fw-bold @error('matricule') is-invalid @enderror" value="{{ old('matricule', $agent->matricule) }}" required>
                                    @error('matricule') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small">Sexe</label>
                                    <select name="sexe" class="form-select @error('sexe') is-invalid @enderror">
                                        <option value="Male" {{ old('sexe', $agent->sexe) == 'Male' ? 'selected' : '' }}>♂ Masculin</option>
                                        <option value="Female" {{ old('sexe', $agent->sexe) == 'Female' ? 'selected' : '' }}>♀ Féminin</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small">Date de Naissance</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $agent->date_of_birth) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Lieu de Naissance</label>
                                    <input type="text" name="place_birth" class="form-control" value="{{ old('place_birth', $agent->place_birth) }}">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: CONTACTS & COMMUNICATION --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-info">
                            <h6 class="text-info font-weight-bold mb-4 text-uppercase"><i class="fas fa-envelope-open-text me-2"></i> Contacts & Coordonnées</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">E-mail Personnel</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-at"></i></span>
                                        <input type="email" name="email" class="form-control border-info-soft @error('email') is-invalid @enderror" value="{{ old('email', $agent->email) }}">
                                    </div>
                                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">E-mail Pro</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-soft-info border-0 text-info"><i class="fas fa-briefcase"></i></span>
                                        <input type="email" name="email_professionnel" class="form-control border-info-soft @error('email_professionnel') is-invalid @enderror" value="{{ old('email_professionnel', $agent->email_professionnel) }}">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Téléphone</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $agent->phone_number) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label fw-bold small">Adresse Domicile</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $agent->address) }}">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 3: AFFECTATION PROFESSIONNELLE --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-success">
                            <h6 class="text-success font-weight-bold mb-4 text-uppercase"><i class="fas fa-briefcase me-2"></i> Poste & Affectation</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Service Actuel <span class="text-danger">*</span></label>
                                    <select name="service_id" class="form-select border-success-soft @error('service_id') is-invalid @enderror" required>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id', $agent->service_id) == $service->id ? 'selected' : '' }}>
                                                🏢 {{ $service->name }} ({{ $service->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Niveau Hiérarchique <span class="text-danger">*</span></label>
                                    <select name="status" class="form-select border-success-soft" required>
                                        <option value="Agent" {{ $agent->status == 'Agent' ? 'selected' : '' }}>Agent de base</option>
                                        <option value="Chef de service" {{ $agent->status == 'Chef de service' ? 'selected' : '' }}>Chef de service</option>
                                        <option value="Sous-directeur" {{ $agent->status == 'Sous-directeur' ? 'selected' : '' }}>Sous-directeur</option>
                                        <option value="Directeur" {{ $agent->status == 'Directeur' ? 'selected' : '' }}>Directeur</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- BOUTONS D'ACTION -->
                        <div class="d-flex justify-content-between align-items-center mt-5 p-3 bg-white rounded shadow-sm border-top-warning">
                            <a href="{{ route('agents.index') }}" class="btn btn-outline-secondary px-4 rounded-pill">
                                <i class="fas fa-times me-2"></i> Abandonner les changements
                            </a>
                            <button type="submit" class="btn btn-warning px-5 text-white fw-bold rounded-pill shadow-sm hover-elevate">
                                <i class="fas fa-sync-alt me-2"></i> Appliquer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Design 2026 */
    .bg-light { background-color: #f8f9fc !important; }
    .border-left-warning { border-left: 5px solid #f6c23e !important; }
    .border-left-info { border-left: 5px solid #36b9cc !important; }
    .border-left-success { border-left: 5px solid #1cc88a !important; }
    .border-left-primary { border-left: 5px solid #4e73df !important; }

    .border-warning-soft { border-color: #ffeeba !important; }
    .border-info-soft { border-color: #bee5eb !important; }
    .border-success-soft { border-color: #c3e6cb !important; }

    .border-top-warning { border-top: 3px solid #f6c23e !important; }
    .bg-soft-info { background-color: rgba(54, 185, 204, 0.1); }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.6rem 1rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #f6c23e;
        box-shadow: 0 0 0 0.25rem rgba(246, 194, 62, 0.25);
    }

    .hover-elevate {
        transition: all 0.3s;
    }

    .hover-elevate:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(246, 194, 62, 0.4) !important;
        background-color: #dda20a !important;
    }

    .form-label { letter-spacing: 0.5px; }
</style>
@endsection
