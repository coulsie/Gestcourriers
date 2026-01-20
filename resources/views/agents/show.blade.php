@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-11 mx-auto">
            <!-- Carte principale avec bordure supérieure marquée -->
            <div class="card shadow-lg border-0 rounded-xl overflow-hidden">

                {{-- Header Sombre & Contrasté --}}
                <div class="card-header bg-dark py-3 d-flex align-items-center justify-content-between border-bottom border-primary border-5">
                    <h5 class="mb-0 text-white fw-bold text-uppercase tracking-wider">
                        <i class="fas fa-id-badge me-2 text-warning"></i> Profil de l'Agent : {{ $agent->first_name }} {{ $agent->last_name }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('agents.index') }}" class="btn btn-outline-light btn-sm fw-bold">
                            <i class="fas fa-arrow-left me-1"></i> RETOUR
                        </a>
                        <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-warning btn-sm fw-bold text-dark shadow">
                            <i class="fas fa-edit me-1"></i> MODIFIER
                        </a>
                    </div>
                </div>

                <div class="card-body p-4 bg-white">
                    {{-- Section En-tête : Photo et Identité --}}
                    <div class="row align-items-center mb-5 p-4 bg-light rounded-4 border border-2 border-primary-subtle shadow-sm">
                        <div class="col-md-3 text-center">
                            @if($agent->photo && file_exists(public_path('agents_photos/' . $agent->photo)))
                               <img src="{{ asset('agents_photos/' . $agent->photo) }}?v={{ time() }}"
                                    alt="Photo de {{ $agent->last_name }}"
                                    class="img-fluid rounded-4 border border-4 border-primary shadow-lg mb-3 mb-md-0"
                                    style="width: 180px; height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-primary text-white p-5 rounded-4 mb-3 mb-md-0 mx-auto d-flex align-items-center justify-content-center shadow" style="width: 180px; height: 180px;">
                                    <i class="fas fa-user fa-5x"></i>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-9 px-md-4">
                            <h2 class="fw-bolder text-primary text-uppercase mb-1">{{ $agent->first_name }} {{ $agent->last_name }}</h2>
                            <h5 class="text-secondary fw-bold mb-4">Matricule : <span class="badge bg-dark text-white px-3">{{ $agent->matricule }}</span></h5>

                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="p-2 bg-white border-start border-4 border-primary rounded shadow-sm">
                                        <small class="text-muted fw-bold d-block text-uppercase">Statut Actuel</small>
                                        <span class="fw-bolder text-dark">{{ $agent->status }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="p-2 bg-white border-start border-4 border-info rounded shadow-sm">
                                        <small class="text-muted fw-bold d-block text-uppercase">Genre / Sexe</small>
                                        <span class="fw-bolder text-dark">{{ $agent->sexe }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Grille d'informations détaillées --}}
                    <div class="row g-4">
                        {{-- Informations de Contact (VERT) --}}
                        <div class="col-md-6">
                            <div class="h-100 p-4 border-start border-5 border-success bg-light rounded shadow-sm">
                                <h5 class="text-success fw-bold mb-4 border-bottom border-2 pb-2 text-uppercase">
                                    <i class="fas fa-address-book me-2"></i> Coordonnées
                                </h5>
                                <p class="mb-3"><strong><i class="fas fa-envelope me-2 text-success"></i> Email :</strong> <span class="text-dark">{{ $agent->email }}</span></p>
                                <p class="mb-3"><strong><i class="fas fa-phone me-2 text-success"></i> Téléphone :</strong> <span class="text-dark fw-bold">{{ $agent->phone_number }}</span></p>
                                <p class="mb-0"><strong><i class="fas fa-map-marker-alt me-2 text-success"></i> Adresse :</strong> <span class="text-dark">{{ $agent->address }}</span></p>
                            </div>
                        </div>

                        {{-- Détails d'Affectation (BLEU) --}}
                      
                        <div class="col-md-6">
                            <div class="h-100 p-4 border-start border-5 border-primary bg-light rounded shadow-sm">
                                <h5 class="text-primary fw-bold mb-4 border-bottom border-2 pb-2 text-uppercase">
                                    <i class="fas fa-briefcase me-2"></i> Poste & Service
                                </h5>
                                <p class="mb-2"><strong>Service :</strong>
                                    @if($agent->service)
                                        <span class="badge bg-primary text-white px-2 py-1">{{ $agent->service->name }}</span>
                                        <small class="d-block text-muted ms-4">Direction : {{ $agent->service->direction->name ?? 'N/A' }}</small>
                                    @else
                                        <span class="text-danger fw-bold">Non affecté</span>
                                    @endif
                                </p>
                                <p class="mb-2"><strong>Emploi/Poste :</strong> <span class="text-dark fw-bold">{{ $agent->Emploi }}</span></p>

                                {{-- GRADE MODIFIÉ : Écriture blanche sur couleur (Emerald/Success) --}}
                                <p class="mb-2">
                                    <strong>Grade :</strong>
                                    <span class="badge px-3 py-2 text-white shadow-sm" style="background-color: #059669; font-size: 0.9rem; border-radius: 8px;">
                                        <i class="fas fa-medal me-1"></i> {{ $agent->Grade ?? 'N/A' }}
                                    </span>
                                </p>

                                <p class="mb-0"><strong>Prise de service :</strong> <span class="text-dark fw-bold">{{ \Carbon\Carbon::parse($agent->Date_Prise_de_service)->format('d/m/Y') }}</span></p>
                            </div>
                        </div>

                        {{-- État Civil (VIOLET) --}}
                        <div class="col-md-6">
                            <div class="h-100 p-4 border-start border-5 border-info bg-light rounded shadow-sm">
                                <h5 class="text-info fw-bold mb-4 border-bottom border-2 pb-2 text-uppercase text-dark">
                                    <i class="fas fa-birthday-cake me-2"></i> État Civil
                                </h5>
                                <p class="mb-2"><strong>Date de naissance :</strong> {{ $agent->date_of_birth }}</p>
                                <p class="mb-0"><strong>Lieu de naissance :</strong> {{ $agent->place_birth }}</p>
                            </div>
                        </div>

                        {{-- Urgence (ROUGE RENFORCÉ) --}}
                        <div class="col-md-6">
                            <div class="h-100 p-4 border-start border-5 border-danger bg-light rounded shadow-sm">
                                <h5 class="text-danger fw-bold mb-4 border-bottom border-2 pb-2 text-uppercase text-dark">
                                    <i class="fas fa-exclamation-triangle me-2"></i> Contact d'Urgence
                                </h5>
                                <p class="mb-3 text-dark">
                                    <strong class="small text-uppercase d-block mb-1">À prévenir :</strong>
                                    <span class="fs-4 fw-bolder text-uppercase">{{ $agent->Personne_a_prevenir }}</span>
                                </p>

                                {{-- BLOC TÉLÉPHONE XL --}}
                                <div class="mt-2">
                                    <div class="bg-danger text-white p-3 rounded-3 d-inline-flex align-items-center shadow-lg w-100">
                                        <i class="fas fa-phone-alt fs-1 me-3 animate-pulse"></i>
                                        <div>
                                            <small class="d-block fw-bold text-uppercase" style="opacity: 0.8; font-size: 0.75rem;">Numéro Prioritaire :</small>
                                            <span class="fs-1 fw-black tracking-tighter">
                                                {{ $agent->Contact_personne_a_prevenir }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section Compte Système --}}
                    <div class="mt-5 p-4 rounded-4 shadow-sm border border-2 {{ $agent->user ? 'border-success bg-success-subtle' : 'border-danger bg-danger-subtle' }}">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="fw-bold mb-1">
                                    <i class="fas fa-laptop-code me-2"></i> Compte Utilisateur Système
                                </h5>
                                <p class="mb-0 small text-dark">Accès requis pour les fonctionnalités de pointage numérique.</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                @if($agent->user)
                                    <div class="bg-success text-white px-4 py-2 rounded-pill shadow-sm d-inline-block fw-bold">
                                        <i class="fas fa-check-circle me-1"></i> ACTIF : {{ $agent->user->email }}
                                    </div>
                                @else
                                    <div class="bg-danger text-white px-4 py-2 rounded-pill shadow-sm d-inline-block fw-bold">
                                        <i class="fas fa-times-circle me-1"></i> AUCUN COMPTE LIÉ
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles 2026 */
    .bg-light { background-color: #f8fafc !important; }
    .text-primary { color: #2563eb !important; }
    .bg-primary { background-color: #2563eb !important; }
    .border-primary { border-color: #2563eb !important; }
    .rounded-xl { border-radius: 1.5rem !important; }
    .rounded-4 { border-radius: 1rem !important; }
    .fw-black { font-weight: 900; }

    /* Animation Pulse Urgence */
    .animate-pulse {
        animation: pulse-red 2s infinite;
    }
    @keyframes pulse-red {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.15); opacity: 0.8; }
        100% { transform: scale(1); opacity: 1; }
    }

    .bg-success-subtle { background-color: #d1fae5 !important; }
    .bg-danger-subtle { background-color: #fee2e2 !important; }
</style>
@endsection
