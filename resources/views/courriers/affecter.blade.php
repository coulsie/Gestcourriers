@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Affecter le Courrier N° {{ $courrier->numero_de_suivi }}</div>

                <div class="card-body">
                    <p>Objet du courrier : **{{ $courrier->objet }}**</p>

                    {{-- Le formulaire d'affectation --}}
                    {{-- On utilise la directive @csrf pour la sécurité --}}
                    <form method="POST" action="{{ route('courriers.affecter.store', $courrier) }}">
                        @csrf

                        <div class="form-group">
                            <label for="user_id">Sélectionner un destinataire :</label>
                            <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                <option value="">-- Choisir un utilisateur --</option>
                                {{-- Boucle sur la liste des utilisateurs passée par le contrôleur --}}
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->service_name ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>

                            @error('user_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">
                                Affecter le courrier
                            </button>
                            <a href="{{ route('courriers.show', $courrier) }}" class="btn btn-secondary">
                                Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
