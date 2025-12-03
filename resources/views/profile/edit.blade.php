@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Modifier le Profil</h4>
                </div>
                <div class="card-body">
                    {{-- Afficher les messages de succès ou d'erreur si nécessaire --}}
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Le formulaire pointe vers la route 'profile.update' que nous avons définie --}}
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        {{-- Laravel utilise la méthode POST pour tous les formulaires web, mais nous utilisons @method('PUT') pour signaler au routeur que c'est une action de mise à jour (RESTful style) --}}
                        @method('PUT')

                        {{-- Champ Nom --}}
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nom complet</label>
                            <input type="text" name="name" id="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Champ Email --}}
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Adresse E-mail</label>
                            <input type="email" name="email" id="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        {{-- Ajoutez ici d'autres champs personnalisés (ex: téléphone, adresse) --}}

                        {{-- Bouton de soumission --}}
                        <button type="submit" class="btn btn-primary mt-3">
                            Sauvegarder les modifications
                        </button>

                        {{-- Lien de retour --}}
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary mt-3 ms-2">
                            Annuler
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
