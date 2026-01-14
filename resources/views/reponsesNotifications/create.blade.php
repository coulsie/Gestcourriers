@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Répondre à la notification</div>

                <div class="card-body">
                    {{--
                        L'action du formulaire utilise la route 'reponses.store'.
                        Nous devons lui passer les IDs nécessaires pour l'enregistrement.
                        Assurez-vous d'avoir une route POST nommée 'reponses.store' définie.
                    --}}
                    <form action="{{ route('reponses.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

       <div class="form-group">
                {{-- <label for="courrier_id">Courrier associé :</label> --}}
                {{-- $courriers doit être passé depuis le contrôleur create() --}}
               <fieldset class="border border-info p-3 rounded shadow-sm">
                    <legend class="w-auto px-2 text-info bg-white">
                        <i class="fas fa-info-circle"></i> <b>Information générale de la notification</b>
                    </legend>

                    <div class="row">
                        <div class="col-12 mb-2">
                            <span class="text-muted small">Titre :</span><br>
                            <span class="text-dark"><b>{{ $notification->titre }}</b></span>
                        </div>

                        <div class="col-12 mb-2">
                            <span class="text-muted small">Description :</span><br>
                            <span class="text-dark">{{ $notification->description }}</span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <span class="text-muted small">Suivi par :</span><br>
                            <span class="badge bg-light text-dark border">{{ $notification->suivi_par }}</span>
                        </div>

                        <div class="col-md-6 mb-2">
                            <span class="text-muted small">Agent :</span><br>
                            <span class="text-primary font-weight-bold">
                                {{ $notification->agent->nom }} {{ $notification->agent->last_name }} {{ $notification->agent->first_name }}
                            </span>
                        </div>

                        <div class="col-12 mt-2 pt-2 border-top">
                            <span class="text-muted small">Date d'échéance :</span>
                            <span class="badge {{ $notification->date_echeance > now() ? 'bg-success text-white' : 'bg-danger' }}"
                                style="{{ $notification->date_echeance <= now() ? 'color: #00008B !important;' : '' }}">
                                {{ $notification->date_echeance ? $notification->date_echeance->format('d/m/Y') : 'N/A' }}
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






                        {{-- Champs cachés pour id_notification et agent_id --}}
                        {{-- Ces IDs ont été passés à la vue depuis le contrôleur --}}
                        <input type="hidden" name="id_notification" value="{{ $id_notification }}">
                        <input type="hidden" name="agent_id" value="{{ $agent_id }}">

                        {{-- Champ Message (text) --}}
                        <div class="form-group row">
                            <label for="message" class="col-md-4 col-form-label text-md-right">Message</label>

                            <div class="col-md-6">
                                <textarea id="message"
                                          class="form-control @error('message') is-invalid @enderror"
                                          name="message"
                                          rows="5"
                                          required>{{ old('message') }}</textarea>

                                @error('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ Pièce jointe (Reponse_Piece_jointe) --}}
                        <div class="form-group row">
                            <label for="Reponse_Piece_jointe" class="col-md-4 col-form-label text-md-right">
                                Pièce jointe (Optionnel)
                            </label>

                            <div class="col-md-6">
                                <input id="Reponse_Piece_jointe"
                                type="file" class="form-control-file @error('Reponse_Piece_jointe') is-invalid @enderror" name="Reponse_Piece_jointe">

                                @error('Reponse_Piece_jointe')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Bouton de soumission --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    Envoyer la réponse
                                </button>
                                <a href="{{ url()->previous() }}" class="btn btn-danger">Annuler</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
