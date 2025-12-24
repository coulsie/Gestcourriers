@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer une reponse</div>

                    <div class="card-body">

                        <form action="{{ route('reponses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Champs cachés récupérés depuis la fonction create -->
                            <input type="hidden" name="id_notification" value="{{ $id_notification }}">
                            <input type="hidden" name="agent_id" value="{{ $agent_id }}">

                            <div class="form-group">
                                <label>Message</label>
                                <textarea name="message" class="form-control" required></textarea>
                            </div>

                            <div class="form-group">
                                <label>Pièce jointe</label>
                                <input type="file" name="Reponse_Piece_jointe" class="form-control">
                            </div>

                            <button type="submit" class="btn btn-success">Envoyer la réponse</button>
                        </form>
                    </div>
             </div>
            </div>
        </div>
    </div>
 </div>
 @endsection
