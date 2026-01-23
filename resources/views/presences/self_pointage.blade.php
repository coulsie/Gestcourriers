@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 position-relative">
            <!-- BOUTON FERMER DANS LE COIN DE LA CARTE -->
            <div class="text-end mb-3">
                <a href="{{ url('/home') }}" class="btn btn-sm btn-danger shadow-sm px-3 rounded-pill">
                    <i class="fas fa-times me-1"></i> Quitter l'espace de pointage
                </a>
            </div>

            <div class="card shadow border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Espace Pointage - {{ Auth::user()->name }}</h4>
                </div>
                <div class="card-body p-5">
                    <h2 class="mb-4 text-secondary">{{ now()->translatedFormat('d F 2026') }}</h2>
                    <h1 class="display-3 mb-4 fw-bold" id="clock">00:00:00</h1>

                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
                    @endif

                    <!-- ... (Logique des boutons Arrivée / Départ identique) ... -->
                    @if(!$presence)
                        <form action="{{ route('presences.enregistrerPointage') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg w-100 py-3 shadow">
                                <i class="fas fa-sign-in-alt me-2"></i> Pointer mon Arrivée
                            </button>
                        </form>
                    @elseif($presence && is_null($presence->heure_depart))
                        <div class="alert alert-info border-0 mb-4">
                            Arrivée enregistrée à : <strong>{{ \Carbon\Carbon::parse($presence->heure_arrivee)->format('H:i') }}</strong>
                        </div>
                        <form action="{{ route('presences.enregistrerPointage') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg w-100 py-3 shadow">
                                <i class="fas fa-sign-out-alt me-2"></i> Pointer mon Départ
                            </button>
                        </form>
                    @else
                         <div class="alert alert-secondary py-4 border-0">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5>Journée terminée !</h5>
                            <p class="mb-0">À demain.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer bg-light border-0">
                    <a href="{{ route('presences.monHistorique') }}" class="text-decoration-none">Voir mon historique complet</a>
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
