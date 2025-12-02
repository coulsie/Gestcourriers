@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Affecter le Courrier n°: <strong>{{ $courrier->id }}</strong>
                </div>

                <div class="card-body">
                    {{-- L'action du formulaire utilise la route nommée 'courriers.affectations.store'
                         et passe l'objet $courrier pour le Route Model Binding --}}
                    <form action="{{ route('courriers.affectations.store', $courrier) }}" method="POST">
                        @csrf

                        {{-- Section d'information sur le courrier --}}
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">Objet du Courrier</label>
                            <div class="col-md-6">
                                <p class="form-control-static">{{ $courrier->objet ?? 'N/A' }}</p>
                            </div>
                        </div>

                        {{-- Champ de sélection de l'utilisateur --}}
                        <div class="form-group row">
                            <label for="user_id" class="col-md-4 col-form-label text-md-right">Affecter à</label>
                            <div class="col-md-6">
                                <select id="user_id" name="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                    <option value="">Sélectionnez un utilisateur...</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @endforeach
                                </select>

                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ commentaires --}}
                        <div class="form-group row">
                            <label for="commentaires" class="col-md-4 col-form-label text-md-right">Commentaires (Optionnel)</label>
                            <div class="col-md-6">
                                <textarea id="commentaires" name="commentaires" rows="4" class="form-control @error('commentaires') is-invalid @enderror">{{ old('commentaires') }}</textarea>

                                @error('commentaires')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Confirmer l'Affectation
                                </button>
                                <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-secondary">
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
