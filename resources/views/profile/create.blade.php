@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer un Nouveau Profil Agent</div>

                <div class="card-body">
                    {{-- Important : enctype="multipart/form-data" est obligatoire pour les photos --}}
                    <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nom / Titre -->
                        <div class="mb-3">
                            <label for="titre" class="form-label">Nom complet / Titre</label>
                            <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" required>
                            @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description / Bio</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Sélection de la Photo -->
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo de l'agent</label>
                            <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/*">
                            <small class="text-muted">Format accepté : JPG, PNG. Taille max : 2Mo</small>
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <!-- Rôle (Si Admin crée le profil) -->
                        <div class="mb-3">
                            <label for="role" class="form-label">Type d'utilisateur</label>
                            <select name="role" class="form-select">
                                <option value="user" selected>Agent standard</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enregistrer le profil</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
