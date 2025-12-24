@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer un nouveau Service</div>

                    <div class="card-body">

                        <form action="{{ route('reponses.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- ID de la notification (souvent caché ou via un select) -->
                            <input type="hidden" name="id_notification" value="{{ $notificationId }}">

                            <textarea name="message" required></textarea>
                            
                            <input type="file" name="Reponse_Piece_jointe">

                            <button type="submit">Envoyer la réponse</button>
                        </form>
                    </div>
             </div>
            </div>
        </div>
    </div>
 </div>
 @endsection