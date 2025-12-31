@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0">Nouvel Agent & Création de Compte</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('agents.enregistrer') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Section Informations Personnelles (Table Agents) -->
                    <div class="col-md-6 border-end">
                        <h5 class="text-secondary mb-3 border-bottom pb-2">Informations Agent</h5>

                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}" required>
                            @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Prénoms</label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}" required>
                            @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="text" name="telephone" class="form-control" value="{{ old('telephone') }}">
                        </div>
                    </div>

                    <!-- Section Compte Utilisateur (Table Users) -->
                    <div class="col-md-6">
                        <h5 class="text-secondary mb-3 border-bottom pb-2">Identifiants de Connexion</h5>

                        <div class="mb-3">
                            <label class="form-label">Adresse Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

                <div class="text-end mt-4">
                    <button type="reset" class="btn btn-outline-secondary">Annuler</button>
                    <button type="submit" class="btn btn-primary px-5">Enregistrer l'Agent</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
