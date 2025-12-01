{{-- Étend un layout principal pour la structure de la page --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{-- Affiche le nom de l'utilisateur que nous éditons --}}
                <div class="card-header">{{ __('Modifier l\'utilisateur') }}: {{ $user->name }}</div>

                <div class="card-body">
                    {{--
                        Le formulaire pointe vers la route 'users.update', en lui passant l'ID de l'utilisateur.
                        La méthode utilisée est POST, mais @method('PUT') indique à Laravel de traiter cela comme une requête PUT/PATCH.
                    --}}
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT') {{-- Simule la méthode HTTP PUT pour la mise à jour --}}

                        {{-- Champ Nom (Name) --}}
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nom') }}</label>

                            <div class="col-md-6">
                                {{-- 'old("name", $user->name)' : utilise l'ancienne valeur si la validation échoue, sinon utilise la valeur actuelle de l'utilisateur --}}
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ Email (Email Address) --}}
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Adresse E-mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- NOTE: Nous ne mettons généralement pas le champ mot de passe ici par défaut. --}}
                        {{-- La gestion des mots de passe se fait souvent dans un formulaire séparé pour des raisons de sécurité. --}}

                        {{-- Bouton de soumission --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Mettre à jour l\'utilisateur') }}
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    {{ __('Annuler') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
