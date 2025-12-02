@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Créer un nouveau courrier') }}</div>

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

                    <form method="POST" action="{{ route('courriers.store') }}">
                        @csrf

                        <!-- Section 1: Détails Principaux -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="reference" class="form-label">{{ __('Référence') }} <span class="text-danger">*</span></label>
                                <input id="reference" type="text" class="form-control @error('reference') is-invalid @enderror" name="reference" value="{{ old('reference') }}" required autofocus>
                                @error('reference') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="type" class="form-label">{{ __('Type') }} <span class="text-danger">*</span></label>
                                <select id="type" class="form-control @error('type') is-invalid @enderror" name="type" required>
                                    <option value="">Sélectionner</option>
                                    <option value="Incoming" {{ old('type') == 'Incoming' ? 'selected' : '' }}>Entrant</option>
                                    <option value="Outgoing" {{ old('type') == 'Outgoing' ? 'selected' : '' }}>Sortant</option>
                                </select>
                                @error('type') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="date_courrier" class="form-label">{{ __('Date du Courrier') }} <span class="text-danger">*</span></label>
                                <input id="date_courrier" type="date" class="form-control @error('date_courrier') is-invalid @enderror" name="date_courrier" value="{{ old('date_courrier') }}" required>
                                @error('date_courrier') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="objet" class="form-label">{{ __('Objet') }} <span class="text-danger">*</span></label>
                                <input id="objet" type="text" class="form-control @error('objet') is-invalid @enderror" name="objet" value="{{ old('objet') }}" required>
                                @error('objet') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea id="description" class="form-control @error('description') is-invalid @enderror" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <!-- Section 2: Expéditeur et Destinataire -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <h5>{{ __('Expéditeur') }}</h5>
                                <div class="mb-3">
                                    <label for="expediteur_nom" class="form-label">{{ __('Nom') }} <span class="text-danger">*</span></label>
                                    <input id="expediteur_nom" type="text" class="form-control @error('expediteur_nom') is-invalid @enderror" name="expediteur_nom" value="{{ old('expediteur_nom') }}" required>
                                    @error('expediteur_nom') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="expediteur_contact" class="form-label">{{ __('Contact') }}</label>
                                    <input id="expediteur_contact" type="text" class="form-control @error('expediteur_contact') is-invalid @enderror" name="expediteur_contact" value="{{ old('expediteur_contact') }}">
                                    @error('expediteur_contact') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h5>{{ __('Destinataire') }}</h5>
                                <div class="mb-3">
                                    <label for="destinataire_nom" class="form-label">{{ __('Nom') }} <span class="text-danger">*</span></label>
                                    <input id="destinataire_nom" type="text" class="form-control @error('destinataire_nom') is-invalid @enderror" name="destinataire_nom" value="{{ old('destinataire_nom') }}" required>
                                    @error('destinataire_nom') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="destinataire_contact" class="form-label">{{ __('Contact') }}</label>
                                    <input id="destinataire_contact" type="text" class="form-control @error('destinataire_contact') is-invalid @enderror" name="destinataire_contact" value="{{ old('destinataire_contact') }}">
                                    @error('destinataire_contact') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Section 3: Statut et Assignation -->
                        <div class="row mt-3">
                            <div class="col-md-4 mb-3">
                                <label for="statut" class="form-label">{{ __('Statut') }}</label>
                                <select id="statut" class="form-control @error('statut') is-invalid @enderror" name="statut">
                                    <option value="pending" {{ old('statut') == 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="in_progress" {{ old('statut') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                    <option value="completed" {{ old('statut') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                    <option value="archived" {{ old('statut') == 'archived' ? 'selected' : '' }}>Archivé</option>
                                </select>
                                @error('statut') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="assigne_a" class="form-label">{{ __('Assigné à') }}</label>
                                <input id="assigne_a" type="text" class="form-control @error('assigne_a') is-invalid @enderror" name="assigne_a" value="{{ old('assigne_a') }}">
                                @error('assigne_a') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>

                             <div class="col-md-4 mb-3">
                                <label for="chemin_fichier" class="form-label">{{ __('Chemin du fichier (URL/Path)') }}</label>
                                <input id="chemin_fichier" type="text" class="form-control @error('chemin_fichier') is-invalid @enderror" name="chemin_fichier" value="{{ old('chemin_fichier') }}">
                                @error('chemin_fichier') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
                            </div>
                        </div>

                        <!-- Note: created_at et updated_at sont gérés automatiquement par Laravel -->

                        <div class="form-group row mt-4">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Enregistrer le Courrier') }}
                                </button>
                                <a href="{{ route('courriers.index') }}" class="btn btn-secondary">
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
