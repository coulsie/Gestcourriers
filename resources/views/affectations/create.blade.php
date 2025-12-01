@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    {{ __('Nouvelle Affectation pour le Courrier') }} #{{ $courrier->reference }}
                </div>

                <div class="card-body">
                    <!-- Gestion des erreurs de validation -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- L'action pointe vers la route 'courriers.affectations.store' -->
                    <form method="POST" action="{{ route('courriers.affectations.store', $courrier->id) }}">
                        @csrf 

                        <div class="row">
                            <!-- Champ Utilisateur Affecté -->
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">{{ __('Assigner à l\'utilisateur') }} <span class="text-danger">*</span></label>
                                <select id="user_id" class="form-control @error('user_id') is-invalid @enderror" name="user_id" required>
                                    <option value="">Sélectionner un utilisateur</option>
                                    @foreach ($users as $id => $name)
                                        <!-- Pré-sélectionne l'ancien choix ou l'utilisateur actuellement assigné si vous le souhaitez -->
                                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <!-- Champ Statut -->
                            <div class="col-md-6 mb-3">
                                <label for="statut" class="form-label">{{ __('Statut') }} <span class="text-danger">*</span></label>
                                <select id="statut" class="form-control @error('statut') is-invalid @enderror" name="statut" required>
                                    <option value="pending" {{ old('statut') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="in_progress" {{ old('statut') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="completed" {{ old('statut') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                    <option value="rejected" {{ old('statut') == 'rejected' ? 'selected' : '' }}>Rejeté</option>
                                </select>
                                @error('statut') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <!-- Champ Commentaires -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="commentaires" class="form-label">{{ __('Commentaires') }}</label>
                                <textarea id="commentaires" class="form-control @error('commentaires') is-invalid @enderror" name="commentaires" rows="3">{{ old('commentaires') }}</textarea>
                                @error('commentaires') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>
                        
                        <!-- Note: date_affectation et date_traitement sont gérés par le contrôleur -->

                        <div class="form-group row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enregistrer l\'Affectation') }}
                                </button>
                                <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-secondary">
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
