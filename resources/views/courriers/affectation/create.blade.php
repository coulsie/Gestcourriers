{{-- resources/views/affectations/create.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Créer une nouvelle affectation</h1>
        <p>Affectations disponibles : <code>id</code>, <code>courrier_id</code>, <code>user_id</code>, <code>statut</code>, <code>commentaires</code>, <code>date_affectation</code>, <code>date_traitement</code></p>

        {{-- Assurez-vous que la route 'affectations.store' est définie dans web.php --}}
        <form action="{{ route('courriers.affectation.store',['id' => $courrier->id]) }}" method="POST">
            @csrf

            {{-- 1. Sélection du Courrier (Obligatoire) --}}
            <div class="form-group">
                {{-- <label for="courrier_id">Courrier associé :</label> --}}
                {{-- $courriers doit être passé depuis le contrôleur create() --}}
                <fieldset class="border p-3">
                    <legend class="w-auto  text-info"> <b>Information générale du courrier associé</b> </legend>
                    <p class=""> 
                        <span>Référence : </span>
                        <span> <b>{{ $courrier->reference }}</b> </span>
                        <br>
                        <span>Objet : </span>
                        <span><b>{{ $courrier->objet }} </b></span>
                        <br>
                        <span>Type : </span>
                        <span><b>{{ $courrier->type }} </b></span>
                        <br>
                        <span>Description : </span>
                        <span><b>{{ $courrier->description }} </b></span>
                        <br>
                        <span>Date d'enregistrement : </span>
                        <span><b>{{ $courrier->date_courrier?$courrier->date_courrier->format('d/m/Y'):'' }} </b></span>
                    </p>
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

             <fieldset class="border p-3">   
                <legend class="w-auto text-success"> <b> Affectation </b>   </legend>
                {{-- 2. Sélection de l'Utilisateur destinataire (Obligatoire) --}}
                <div class="form-group">
                    <label for="user_id">Affecté à (Utilisateur) :</label>
                    {{-- $users doit être passé depuis le contrôleur create() --}}
                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Sélectionnez un utilisateur --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- 3. Statut Initial --}}
                <div class="form-group">
                    <label for="statut">Statut :</label>
                    <select name="statut" id="statut" class="form-control @error('statut') is-invalid @enderror" required>
                        <option value="en_attente" {{ old('statut', 'en_attente') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="en_cours" {{ old('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="traite" {{ old('statut') == 'traite' ? 'selected' : '' }}>Traité</option>
                    </select>
                    @error('statut')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- 4. Commentaires --}}
                <div class="form-group">
                    <label for="commentaires">Commentaires :</label>
                    <textarea name="commentaires" id="commentaires" rows="4" class="form-control @error('commentaires') is-invalid @enderror">{{ old('commentaires') }}</textarea>
                    @error('commentaires')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- 5. Date d'affectation (Pré-remplie avec la date/heure actuelle) --}}
                <div class="form-group">
                    <label for="date_affectation">Date d'affectation :</label>
                    {{-- Format datetime-local requis par HTML5, utilisez Carbon pour la valeur par défaut si nécessaire --}}
                    <input type="datetime-local" name="date_affectation" id="date_affectation" class="form-control @error('date_affectation') is-invalid @enderror" value="{{ old('date_affectation', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('date_affectation')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- 6. Date de traitement (Optionnel, généralement rempli lors de la mise à jour) --}}
                <div class="form-group">
                    <label for="date_traitement">Date de traitement (Optionnel) :</label>
                    <input type="datetime-local" name="date_traitement" id="date_traitement" class="form-control @error('date_traitement') is-invalid @enderror" value="{{ old('date_traitement') }}">
                    @error('date_traitement')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

            </fieldset>

            

            <hr>

            <button type="submit" class="btn btn-success">Créer l'Affectation</button>

            {{-- Ici, si vous avez un bouton retour, assurez-vous qu'il ne cause pas l'erreur d'URL --}}
            <a href="{{ url()->previous() }}" class="btn btn-danger">Annuler</a>

        </form>
    </div>
@endsection
