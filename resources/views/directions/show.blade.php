@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Détails de la Direction</div>

                <div class="card-body">

                    <h1 class="card-title">{{ $direction->name }}</h1>
                    <p class="card-subtitle mb-4 text-muted">Code : {{ $direction->code }}</p>

                    <div class="mb-4">
                        <strong>Description :</strong>
                        <p>{{ $direction->description ?? 'Aucune description fournie.' }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Responsable (Head) :</strong>
                        @if($direction->head)
                            {{-- Suppose une relation 'head' définie dans le modèle Direction --}}
                            <p>{{ $direction->head->last_name }} {{ $direction->head->first_name }}</p>
                        @else
                            <p>Non affecté</p>
                        @endif
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('directions.index') }}" class="btn btn-secondary">
                            Retour à la liste
                        </a>
                        <a href="{{ route('directions.edit', $direction) }}" class="btn btn-primary">
                            Modifier la Direction
                        </a>
                    </div>
                </div>

                <div class="card-footer text-muted">
                    Créé le: {{ $direction->created_at->format('d/m/Y H:i') }} |
                    Mis à jour le: {{ $direction->updated_at->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
