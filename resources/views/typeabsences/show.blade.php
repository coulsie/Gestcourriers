{{-- resources/views/type_absences/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- Ajout d'une ombre 'shadow' pour le relief --}}
            <div class="card shadow border-0">
                
                {{-- En-tête avec fond bleu primaire et texte blanc --}}
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détails du Type d'Absence : {{ $typeAbsence->nom_type }}</h5>
                    <span class="badge bg-light text-primary">ID: {{ $typeAbsence->id }}</span>
                </div>                

                <div class="card-body bg-light">
                    <div class="row mb-3 p-2 bg-white rounded shadow-sm mx-1">
                        <label class="col-sm-4 col-form-label text-muted"><strong>Nom :</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static fw-bold text-dark">{{ $typeAbsence->nom_type }}</p>
                        </div>
                    </div>

                    <div class="row mb-3 p-2 bg-white rounded shadow-sm mx-1">
                        <label class="col-sm-4 col-form-label text-muted"><strong>Code :</strong></label>
                        <div class="col-sm-8">
                            <code class="px-2 py-1 bg-light border rounded text-danger">{{ $typeAbsence->code }}</code>
                        </div>
                    </div>

                    <div class="row mb-3 p-2 bg-white rounded shadow-sm mx-1">
                        <label class="col-sm-4 col-form-label text-muted"><strong>Description :</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static italic">{{ $typeAbsence->description ?? 'Aucune description' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3 p-2 bg-white rounded shadow-sm mx-1">
                        <label class="col-sm-4 col-form-label text-muted"><strong>Statut de rémunération :</strong></label>
                        <div class="col-sm-8">
                            @if ($typeAbsence->est_paye)
                                <span class="badge rounded-pill bg-success">
                                    <i class="fas fa-check-circle me-1"></i> Payé
                                </span>
                            @else
                                <span class="badge rounded-pill bg-danger">
                                    <i class="fas fa-times-circle me-1"></i> Non Payé
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr class="text-muted">

                    <div class="row mb-2">
                        <div class="col-sm-6 text-muted small">
                            <i class="far fa-calendar-plus me-1"></i> Créé le : 
                            <span class="text-dark">{{ $typeAbsence->date_champ?->format('d/m/Y') ?? 'N/A' }}</span>
                        </div>
                        <div class="col-sm-6 text-muted small text-end">
                            <i class="far fa-edit me-1"></i> Mise à jour : 
                            <span class="text-dark">{{ $typeAbsence->updated_at->format('d M Y à H:i') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Footer avec boutons colorés --}}
                <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                    <a href="{{ route('typeabsences.index') }}" class="btn btn-outline-secondary shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Retour à la liste
                    </a>
                    <a href="{{ route('typeabsences.edit', $typeAbsence->id) }}" class="btn btn-warning px-4 shadow-sm">
                        <i class="fas fa-edit me-1"></i> Modifier ce type
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
