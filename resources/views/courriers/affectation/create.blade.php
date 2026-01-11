{{-- resources/views/affectations/create.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer une nouvelle affectation</h1>
       

        {{-- Assurez-vous que la route 'affectations.store' est définie dans web.php --}}
        <form action="{{ route('courriers.affectation.store',['id' => $courrier->id]) }}" method="POST">
            @csrf

            {{-- 1. Sélection du Courrier (Obligatoire) --}}
            <div class="form-group">
                {{-- <label for="courrier_id">Courrier associé :</label> --}}
                {{-- $courriers doit être passé depuis le contrôleur create() --}}
                <fieldset class="border border-primary p-3 rounded shadow-sm" style="border-width: 2px !important;">
                    <legend class="w-auto px-3 text-white bg-primary rounded">
                        <i class="fas fa-envelope"></i> <b>Information générale du courrier associé</b>
                    </legend>

                    <div class="row pt-2">
                        <div class="col-md-6 mb-2">
                            <span class="text-muted small d-block">Référence :</span>
                            <span class="text-primary"><b>{{ $courrier->reference }}</b></span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <span class="text-muted small d-block">Type :</span>
                            <span class="badge {{ $courrier->type > now() ? 'bg-success text-white' : 'bg-danger' }}"
                                style="{{ $courrier->type <= now() ? 'color: #00008B !important;' : '' }}">
                                {{ __( $courrier->type) }}
                            </span>
                        </div>



                        <div class="col-12 mb-2">
                            <span class="text-muted small d-block">Objet :</span>
                            <span class="text-dark"><b>{{ $courrier->objet }}</b></span>
                        </div>

                        <div class="col-12 mb-2">
                            <span class="text-muted small d-block">Description :</span>
                            <p class="text-dark mb-0" style="font-size: 0.95rem;">{{ $courrier->description }}</p>
                        </div>

                        <div class="col-12 mt-2 pt-2 border-top">
                            <span class="text-muted small">Date d'enregistrement : </span>
                            <span class="text-info">
                                <i class="far fa-calendar-alt"></i>
                                <b>{{ $courrier->date_courrier ? $courrier->date_courrier->format('d/m/Y') : 'Non précisée' }}</b>
                            </span>
                        </div>
                    </div>
                </fieldset>



                {{-- <select name="courrier_id" id="courrier_id" class="form-control @error('courrier_id') is-invalid @enderror" required>
                    <option value="">-- Sélectionnez un courrier --</option>


               @if(isset($courriers) && count($courriers) > 0)
                    @foreach($courriers as $courrier)
                        <option value="{{ $courrier->id }}" {{ old('courrier_id') == $courrier->id ? 'selected' : '' }}>
                            ID {{ $courrier->id }} - Sujet: {{ $courrier->sujet }}
                        </option>
                    @endforeach
                    @else
                     <p>Aucun courrier trouvé.</p>
                @endif
                </select> --}}
                @error('courrier_id')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

             <fieldset class="border border-primary p-4 rounded shadow-sm bg-light">
    <legend class="w-auto px-3 text-primary fw-bold">
        <i class="fas fa-user-plus"></i> Affectation
    </legend>

    {{-- 2. Sélection de l'Utilisateur destinataire --}}
    <div class="mb-3">
        <label for="agent_id" class="form-label fw-semibold text-secondary">
            <span class="text-danger">*</span> Agent
        </label>
        <div class="input-group">
            <span class="input-group-text bg-primary text-white"><i class="fas fa-user"></i></span>
            <select name="agent_id" id="agent_id" class="form-select border-primary @error('agent_id') is-invalid @enderror" required>
                <option value="" disabled selected>Sélectionnez un agent...</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                        {{ $agent->name }} {{ $agent->first_name }} {{ $agent->last_name }}
                    </option>
                @endforeach
            </select>
        </div>
        @error('agent_id')
            <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- 3. Statut Initial avec couleurs distinctes --}}
    <div class="mb-3">
        <label for="statut" class="form-label fw-semibold text-secondary">Statut de la tâche :</label>
        <select name="statut" id="statut" class="form-select border-info fw-bold @error('statut') is-invalid @enderror" required>
            <option value="en_attente" class="text-warning" {{ old('statut', 'en_attente') == 'en_attente' ? 'selected' : '' }}>
                🟡 En attente
            </option>
            <option value="en_cours" class="text-primary" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>
                🔵 En cours
            </option>
            <option value="traite" class="text-success" {{ old('statut') == 'traite' ? 'selected' : '' }}>
                🟢 Traité
            </option>
        </select>
        @error('statut')
            <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    {{-- 4. Commentaires --}}
    <div class="mb-3">
        <label for="commentaires" class="form-label fw-semibold text-secondary">Commentaires / Instructions :</label>
        <textarea name="commentaires" id="commentaires" rows="3"
                  class="form-control border-secondary @error('commentaires') is-invalid @enderror"
                  placeholder="Ajoutez des détails ici...">{{ old('commentaires') }}</textarea>
        @error('commentaires')
            <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
        @enderror
    </div>

    <div class="row">
        {{-- 5. Date d'affectation --}}
        <div class="col-md-6 mb-3">
            <label for="date_affectation" class="form-label fw-semibold text-secondary">Date d'affectation :</label>
            <input type="datetime-local" name="date_affectation" id="date_affectation"
                   class="form-control border-success @error('date_affectation') is-invalid @enderror"
                   value="{{ old('date_affectation', now()->format('Y-m-d\TH:i')) }}" required>
            @error('date_affectation')
                <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
            @enderror
        </div>

        {{-- 6. Date de traitement --}}
        <div class="col-md-6 mb-3">
            <label for="date_traitement" class="form-label fw-semibold text-secondary">Date de traitement (Optionnel) :</label>
            <input type="datetime-local" name="date_traitement" id="date_traitement"
                   class="form-control border-muted @error('date_traitement') is-invalid @enderror"
                   value="{{ old('date_traitement') }}">
            @error('date_traitement')
                <div class="invalid-feedback d-block"><strong>{{ $message }}</strong></div>
            @enderror
        </div>
    </div>
</fieldset>

<hr class="my-4 border-2 opacity-50">

            <button type="submit" class="btn btn-success">Créer l'Affectation</button>

            {{-- Ici, si vous avez un bouton retour, assurez-vous qu'il ne cause pas l'erreur d'URL --}}
            <a href="{{ url()->previous() }}" class="btn btn-danger">Annuler</a>

        </form>
    </div>
@endsection
