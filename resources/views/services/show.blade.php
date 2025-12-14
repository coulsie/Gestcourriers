@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Détails du Service</div>

                <div class="card-body">

                    <h1 class="card-title">{{ $service->name }}</h1>
                    <p class="card-subtitle mb-4 text-muted">Code : {{ $service->code }}</p>

                    <div class="mb-4">
                        <strong>Description :</strong>
                        <p>{{ $service->description ?? 'Aucune description fournie.' }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Direction de rattachement :</strong>
                        @if($service->direction)
                            {{-- Suppose une relation 'direction' définie dans le modèle Service --}}
                            <p>
                                <a href="{{ route('directions.show', $service->direction) }}">
                                    {{ $service->direction->name }} ({{ $service->direction->code }})
                                </a>
                            </p>
                        @else
                            <p>Aucune direction parente.</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <strong>Responsable du Service :</strong>
                        @if($service->head)
                            {{-- Suppose une relation 'head' (vers User) définie dans le modèle Service --}}
                            <p>{{ $service->head->last_name }} {{ $service->head->first_name }} </p>
                        @else
                            <p>Non affecté</p>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('services.index') }}" class="btn btn-secondary">
                            Retour à la liste
                        </a>
                        <a href="{{ route('services.edit', $service) }}" class="btn btn-primary">
                            Modifier le Service
                        </a>
                    </div>
                </div>

                <div class="card-footer text-muted">
                    Créé le: {{ $service->created_at->format('d/m/Y H:i') }} |
                    Mis à jour le: {{ $service->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
