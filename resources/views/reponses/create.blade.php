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
