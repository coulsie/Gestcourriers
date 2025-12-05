@extends('layouts.app') {{-- Assurez-vous que votre layout principal se nomme bien 'layouts.app' --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Édition de l'affectation du courrier</div>

                <div class="card-body">
                    {{--
                        Le formulaire pointe vers une route 'update' que vous devez définir dans routes/web.php
                        Assurez-vous de remplacer 'affectations.update' par le nom correct de votre route si nécessaire.
                    --}}
                    <form method="POST" action="{{ route('affectations.update', $courrier->id) }}">
                        @csrf
                        @method('PUT') {{-- Utilise la méthode HTTP PUT pour la mise à jour --}}

                        {{-- Section d'affichage des détails du courrier (lecture seule) --}}
                        <div class="form-group row">
                            <label for="objet" class="col-md-4 col-form-label text-md-right">Objet du courrier</label>
                            <div class="col-md-6">
                                <input id="objet" type="text" class="form-control" value="{{ $courrier->objet }}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="expediteur" class="col-md-4 col-form-label text-md-right">Expéditeur</label>
                            <div class="col-md-6">
                                <input id="expediteur" type="text" class="form-control" value="{{ $courrier->expediteur }}" readonly>
                            </div>
                        </div>

                        <hr>

                        {{-- Section d'affectation : Ligne 14 du fichier original --}}
                        <div class="form-group row">
                            <label for="agent_id" class="col-md-4 col-form-label text-md-right">Affecter à l'agent</label>

                            <div class="col-md-6">
                                {{--
                                    Ceci est la ligne critique où vous sélectionnez un agent.
                                    Le nom 'agent_id' sera envoyé au contrôleur.
                                --}}
                                <select id="agent_id" name="agent_id" class="form-control @error('agent_id') is-invalid @enderror" required>
                                    <option value="">-- Sélectionnez un agent --</option>

                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}"
                                            {{-- Cette condition vérifie si cet agent est déjà affecté au courrier actuel --}}
                                            @if($courrier->agent_id == $agent->id) selected @endif
                                        >
                                            {{ $agent->nom_complet }} {{-- Remplacez par le nom de colonne approprié (ex: nom, prenom) --}}
                                        </option>
                                    @endforeach
                                </select>

                                @error('agent_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Bouton de soumission --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Mettre à jour l'affectation
                                </button>
                                <a href="{{ route('affectations.index') }}" class="btn btn-secondary">
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
