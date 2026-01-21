{{-- Étend un layout principal pour la structure de la page --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">{{ __('Détails de l\'utilisateur') }}</div>

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
                            <p class="form-control-static text-uppercase">{{ $user->name }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Email (Login) :</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $user->email }}</p>
                        </div>
                    </div>

                    {{-- SECTION MODIFIÉE POUR AFFICHER LE RÔLE --}}
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Rôle(s) :</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">
                                @if($user->roles->isNotEmpty())
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge badge-info py-2 px-3 text-capitalize">
                                            <i class="fas fa-user-shield mr-1"></i> {{ $role }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted italic">Aucun rôle assigné</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right"><strong>Membre depuis :</strong></label>
                        <div class="col-md-6">
                            <p class="form-control-static">{{ $user->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>

                    <div class="form-group row mb-0 mt-4 border-top pt-3">
                        <div class="col-md-6 offset-md-4">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('Retour à la liste') }}
                            </a>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> {{ __('Modifier') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
