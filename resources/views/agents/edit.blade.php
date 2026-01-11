@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-lg border-0 rounded-lg">
                <!-- En-tête -->
                <div class="card-header text-white py-3 shadow-sm" style="background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%);">
                    <h5 class="mb-0 font-weight-bold">
                        <i class="fas fa-user-edit me-2"></i> Modifier l'agent : {{ $agent->first_name }} {{ $agent->last_name }}
                    </h5>
                </div>

                <div class="card-body p-4 bg-light">
                    <form action="{{ route('agents.update', $agent->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- SECTION PHOTO --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-warning text-center">
                            <h6 class="text-warning font-weight-bold mb-3 text-uppercase text-start"><i class="fas fa-camera me-2"></i> Photo de profil</h6>
                            <div class="mb-3">
                                @if($agent->photo && file_exists(public_path('agents_photos/' . $agent->photo)))
                                    <img src="{{ asset('agents_photos/' . $agent->photo) }}?v={{ time() }}" class="img-thumbnail rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f6c23e;">
                                @else
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; border: 3px solid #dee2e6;">
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 mx-auto">
                                <input type="file" name="photo" class="form-control form-control-sm @error('photo') is-invalid @enderror">
                                <small class="text-muted">Laisser vide pour conserver la photo actuelle</small>
                            </div>
                        </div>

                        {{-- SECTION 1: IDENTITÉ & STATUT --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-warning border-start border-4">
                            <h6 class="text-warning font-weight-bold mb-4 text-uppercase"><i class="fas fa-id-card me-2"></i> État Civil & Identifiant</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $agent->first_name) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Nom <span class="text-danger">*</span></label>
                                    <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $agent->last_name) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small text-primary">N° Matricule <span class="text-danger">*</span></label>
                                    <input type="text" name="matricule" class="form-control fw-bold border-primary @error('matricule') is-invalid @enderror" value="{{ old('matricule', $agent->matricule) }}" required>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small">Sexe</label>
                                    <select name="sexe" class="form-select">
                                        <option value="Male" {{ old('sexe', $agent->sexe) == 'Male' ? 'selected' : '' }}>Masculin</option>
                                        <option value="Female" {{ old('sexe', $agent->sexe) == 'Female' ? 'selected' : '' }}>Féminin</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small">Date de Naissance</label>
                                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth', $agent->date_of_birth) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small">Lieu de Naissance</label>
                                    <input type="text" name="place_birth" class="form-control" value="{{ old('place_birth', $agent->place_birth) }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label fw-bold small">Statut (Rang)</label>
                                    <select name="status" class="form-select">
                                        @foreach(['Agent', 'Chef de service', 'Sous-directeur', 'Directeur'] as $st)
                                            <option value="{{ $st }}" {{ old('status', $agent->status) == $st ? 'selected' : '' }}>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 2: CONTACTS --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-info border-start border-4 border-info">
                            <h6 class="text-info font-weight-bold mb-4 text-uppercase"><i class="fas fa-envelope-open-text me-2"></i> Contacts & Coordonnées</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">E-mail Personnel</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $agent->email) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small text-info">E-mail Professionnel</label>
                                    <input type="email" name="email_professionnel" class="form-control border-info" value="{{ old('email_professionnel', $agent->email_professionnel) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Téléphone</label>
                                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $agent->phone_number) }}">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold small">Adresse Résidence</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $agent->address) }}">
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 3: CARRIÈRE --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-success border-start border-4 border-success">
                            <h6 class="text-success font-weight-bold mb-4 text-uppercase"><i class="fas fa-briefcase me-2"></i> Poste & Affectation</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Service <span class="text-danger">*</span></label>
                                    <select name="service_id" class="form-select shadow-sm border-success" required>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id', $agent->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Emploi / Fonction</label>
                                    <input type="text" name="Emploi" class="form-control" value="{{ old('Emploi', $agent->Emploi) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Grade</label>
                                    <input type="text" name="Grade" class="form-control" value="{{ old('Grade', $agent->Grade) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small text-success">Date Prise de Service</label>
                                    <input type="date" name="Date_Prise_de_service" class="form-control border-success" value="{{ old('Date_Prise_de_service', $agent->Date_Prise_de_service) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label fw-bold small">Compte Utilisateur (ID)</label>
                                    <input type="number" name="user_id" class="form-control bg-light" value="{{ old('user_id', $agent->user_id) }}" readonly>
                                    <small class="text-muted italic text-xs">Liaison système uniquement</small>
                                </div>
                            </div>
                        </div>

                        {{-- SECTION 4: URGENCE --}}
                        <div class="p-4 bg-white rounded shadow-sm mb-4 border-left-danger border-start border-4 border-danger">
                            <h6 class="text-danger font-weight-bold mb-4 text-uppercase"><i class="fas fa-exclamation-triangle me-2"></i> En cas d'urgence</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Personne à prévenir</label>
                                    <input type="text" name="Personne_a_prevenir" class="form-control border-danger-soft" value="{{ old('Personne_a_prevenir', $agent->Personne_a_prevenir) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold small">Contact Urgence</label>
                                    <input type="text" name="Contact_personne_a_prevenir" class="form-control border-danger-soft" value="{{ old('Contact_personne_a_prevenir', $agent->Contact_personne_a_prevenir) }}">
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <a href="{{ route('agents.index') }}" class="btn btn-secondary px-4 me-2 shadow-sm">Annuler</a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold shadow-sm text-dark">
                                <i class="fas fa-save me-2"></i> Mettre à jour l'agent
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
