@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Mon Profil Utilisateur</h1>
    </div>

    <div class="row">
        <!-- Carte de Profil Gauche -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4 border-bottom-primary">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <!-- Génération d'un avatar automatique basé sur le nom -->
                        <img class="img-profile rounded-circle shadow" 
                             src="https://ui-avatars.com{{ urlencode($user->name) }}&background=4e73df&color=fff&size=128&bold=true"
                             style="width: 128px; height: 128px;">
                    </div>
                    <h4 class="font-weight-bold text-gray-800">{{ $user->name }}</h4>
                    <p class="text-primary small font-weight-bold mb-3">{{ $user->email }}</p>
                    
                    <hr>
                    
                    <div class="text-left px-3">
                        <h6 class="font-weight-bold text-dark">Ma Biographie :</h6>
                        <p class="text-gray-600 italic">
                            {{ $user->bio ?? "Aucune biographie renseignée." }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carte des Détails Droite -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Détails du compte (DSESF 2026)</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="small mb-1 text-gray-500 font-weight-bold">Identifiant système (#ID)</label>
                            <div class="h6 text-gray-900">{{ $user->id }}</div>
                        </div>
                        <div class="col-md-6">
                            <label class="small mb-1 text-gray-500 font-weight-bold">Date d'inscription</label>
                            <div class="h6 text-gray-900">{{ $user->created_at->format('d F Y') }}</div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <label class="small mb-1 text-gray-500 font-weight-bold">Adresse Email</label>
                            <div class="h6 text-gray-900">{{ $user->email }}</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h6 class="font-weight-bold text-danger mb-3">
                        <i class="fas fa-user-shield mr-2"></i>Paramètres de sécurité
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="small mb-1 text-gray-500 font-weight-bold">Dernière mise à jour du mot de passe</label>
                            <div class="text-gray-900">
                                @if($user->password_changed_at)
                                    <span class="badge badge-light p-2 border">
                                        {{ \Carbon\Carbon::parse($user->password_changed_at)->diffForHumans() }}
                                    </span>
                                @else
                                    <span class="badge badge-warning">Action requise</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="small mb-1 text-gray-500 font-weight-bold">État du compte</label>
                            <div>
                                @if($user->must_change_password)
                                    <span class="badge badge-danger px-3 py-2">Changement obligatoire</span>
                                @else
                                    <span class="badge badge-success px-3 py-2">Sécurisé</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button class="btn btn-primary shadow-sm" type="button">
                            <i class="fas fa-user-edit fa-sm text-white-50 mr-1"></i> Modifier mes infos
                        </button>
                        <button class="btn btn-dark shadow-sm ml-2" type="button">
                            <i class="fas fa-lock fa-sm text-white-50 mr-1"></i> Modifier le mot de passe
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
