@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Liste des Annonces</h2>
{{-- Bouton pour créer un nouvel agent --}}
                    <a href="{{ route('annonces.create') }}" class="btn btn-success btn-sm float-end">
                        Créer une annonce
                    </a>
    <div class="row">
        @forelse($recentAnnonces as $annonce)
            @php
                // Logique de couleur identique à votre ticker
                $color = match($annonce->type) {
                    'urgent' => 'danger',
                    'information' => 'primary',
                    'evenement' => 'success',
                    'avertissement' => 'warning text-dark',
                    default => 'secondary',
                };
            @endphp

            <div class="col-md-12 mb-3">
                <div class="card border-{{ explode(' ', $color)[0] }}">
                    <div class="card-header bg-{{ $color }} @if($annonce->type !== 'avertissement') text-white @endif">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $annonce->titre }}</strong>
                            <span class="badge bg-light text-dark text-uppercase" style="font-size: 0.7rem;">
                                {{ $annonce->type }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $annonce->contenu }}</p>
                        <small class="text-muted">Publié le {{ $annonce->created_at->format('d/m/Y à H:i') }}</small>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Aucune annonce active pour le moment.</div>
            </div>
        @endforelse
    </div>
</div>
@endsection
