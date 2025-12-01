{{-- Étend un layout principal pour la structure de la page --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Détails de l\'utilisateur') }}</div>

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"><strong>ID :</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $user->id }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Nom :</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Email :</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Membre depuis :</strong></label>
                        <div class="col-md-6">
                            {{-- Utilisation de Carbon pour formater joliment la date --}}
                            <p class="form-control-static">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="form-group row mb-0 mt-4">
                        <div class="col-md-6 offset-md-4">
                            {{-- Bouton pour revenir à la liste --}}
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                {{ __('Retour à la liste') }}
                            </a>
                            {{-- Bouton pour éditer cet utilisateur spécifique --}}
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                                {{ __('Modifier') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
