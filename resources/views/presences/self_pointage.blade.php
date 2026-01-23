@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Bouton Retour discret en haut à gauche -->
            <div class="text-start mb-3">
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white">
                    <h4>Espace Pointage - {{ Auth::user()->name }}</h4>
                </div>
                <div class="card-body p-5">
                    <h2 class="mb-4">{{ now()->translatedFormat('d F 2026') }}</h2>
                    <h1 class="display-4 mb-4" id="clock">00:00:00</h1>

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                            <br>
                            <!-- Lien de retour rapide après pointage -->
                            <a href="{{ url()->previous() }}" class="alert-link">Cliquez ici pour revenir à la page précédente</a>
                        </div>
                    @endif

                    @if(!$presence)
                        <!-- Bouton Arrivée -->
                        <form action="{{ route('presences.enregistrerPointage') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow-sm">
                                <i class="fas fa-sign-in-alt"></i> Pointer mon Arrivée
                            </button>
                        </form>
                    @elseif($presence && is_null($presence->heure_depart))
                        <!-- Bouton Départ -->
                        <div class="alert alert-info mb-4">
                            Arrivée enregistrée à : <strong>{{ \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') }}</strong>
                        </div>
                        <form action="{{ route('presences.enregistrerPointage') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg w-100 py-3 shadow-sm">
                                <i class="fas fa-sign-out-alt"></i> Pointer mon Départ
                            </button>
                        </form>
                    @else
                        <!-- Journée terminée -->
                        <div class="alert alert-secondary py-4">
                            <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                            <h5>Travail terminé pour aujourd'hui</h5>
                            <p>Arrivée : {{ \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') }} |
                               Départ : {{ \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') }}</p>
                        </div>
                    @endif
                </div>

                <!-- Footer de carte pour le bouton retour principal -->
                <div class="card-footer bg-light">
                    <a href="{{ route('presences.monHistorique') }}" class="btn btn-link text-decoration-none">
                        Aller à la liste des présences
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setInterval(() => {
        document.getElementById('clock').innerText = new Date().toLocaleTimeString();
    }, 1000);
</script>
@endsection
