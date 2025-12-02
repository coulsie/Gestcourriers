@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Modification de l'Affectation n°: <strong>{{ $affectation->id }}</strong>
                </div>

                <div class="card-body">
                    {{-- Le formulaire utilise PATCH/PUT pour la mise à jour --}}
                    <form action="{{ route('courriers.affectations.update', [$courrier, $affectation]) }}" method="POST">
                        @csrf
                        @method('PATCH') {{-- Utilise la méthode HTTP PATCH pour la mise à jour --}}

                        {{-- Champ de sélection de l'utilisateur --}}
                        <div class="form-group row">
                            <label for="user_id" class="col-md-4 col-form-label text-md-right">Affecté à</label>
                            <div class="col-md-6">
                                <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('user_id', $affectation->user_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ de sélection du statut --}}
                        <div class="form-group row">
                            <label for="statut" class="col-md-4 col-form-label text-md-right">Statut</label>
                            <div class="col-md-6">
                                <select id="statut" name="statut" class="form-control @error('statut') is-invalid @enderror" required>
                                    @foreach(['affecté', 'traité', 'en_attente', 'archivé'] as $statutOption)
                                        <option value="{{ $statutOption }}"
                                            {{ old('statut', $affectation->statut) == $statutOption ? 'selected' : '' }}>
                                            {{ ucfirst($statutOption) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('statut')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ commentaires --}}
                        <div class="form-group row">
                            <label for="commentaires" class="col-md-4 col-form-label text-md-right">Commentaires</label>
                            <div class="col-md-6">
                                <textarea id="commentaires" name="commentaires" rows="4" class="form-control @error('commentaires') is-invalid @enderror">{{ old('commentaires', $affectation->commentaires) }}</textarea>
                                @error('commentaires')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Mettre à Jour l'Affectation
                                </button>
                                <a href="{{ route('courriers.affectations.show', [$courrier, $affectation]) }}" class="btn btn-secondary">
                                    Annuler
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
