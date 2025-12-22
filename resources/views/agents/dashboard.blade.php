@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- En-tête avec profil -->
        <div class="col-md-12 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3">
                        @if(auth()->user()->photo)
                            <img src="{{ asset('storage/' . auth()->user()->photo) }}" class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="mb-0">Bonjour, {{ auth()->user()->name }}</h2>
                        <p class="text-muted">Espace Agent - {{ date('d/m/2025') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Notifications -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">Mes Notifications Récentes</div>
                <div class="card-body">
                    @forelse($notifications as $notif)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-3">
                            <div>
                                <h6 class="mb-1">{{ $notif->titre }}</h6>
                                <small class="text-muted">{{ $notif->date_creation }}</small>
                            </div>
                            <a href="{{ route('notifications.visualiser', ['id' => $notif->id_notification]) }}" class="btn btn-sm btn-outline-primary">
                                Voir
                            </a>
                        </div>
                    @empty
                        <p class="text-center py-4">Aucune notification pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sidebar Agent -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">Statistiques</h5>
                    <hr>
                    <p>Missions en cours : <strong>{{ $missionsCompte ?? 0 }}</strong></p>
                    <p>Alertes urgentes : <span class="badge bg-danger">{{ $urgences ?? 0 }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
