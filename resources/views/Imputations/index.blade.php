@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="fas fa-tasks me-2"></i> Suivi & Progression des Imputations
            </h5>
            <a href="{{ route('imputations.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Nouvelle Imputation
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle border">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3">Niveau</th>
                            <th>Courrier (Réf & Objet)</th>
                            <th>Agents Assignés</th>
                            <th>Échéancier</th>
                            <th style="width: 15%;">Progression (Temps)</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($imputations as $imputation)
                        <tr>
                            <!-- Niveau -->
                            <td>
                                @if($imputation->niveau == 'primaire')
                                    <span class="badge bg-danger px-3 py-2 shadow-sm text-white">PRIMAIRE</span>
                                @elseif($imputation->niveau == 'secondaire')
                                    <span class="badge bg-warning px-3 py-2 shadow-sm text-dark">SECONDAIRE</span>
                                @else
                                    <span class="badge bg-info px-3 py-2 shadow-sm text-white">TERTIAIRE</span>
                                @endif
                            </td>

                            <!-- Détails Courrier -->
                            <td>
                                <div class="fw-bold text-primary">{{ $imputation->courrier->reference }}</div>
                                <div class="text-dark small fw-semibold text-truncate" style="max-width: 200px;" title="{{ $imputation->courrier->objet }}">
                                    {{ $imputation->courrier->objet }}
                                </div>
                            </td>

                            <!-- Liste des Agents -->
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    @foreach($imputation->agents as $agent)
                                        <span class="small fw-bold text-dark" style="font-size: 0.75rem;">
                                            <i class="fas fa-user-circle text-primary me-1"></i>{{ strtoupper($agent->last_name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Échéancier -->
                            <td>
                                @if($imputation->echeancier)
                                    @php
                                        $echeance = \Carbon\Carbon::parse($imputation->echeancier);
                                        $isPast = $echeance->isPast() && $imputation->statut != 'termine';
                                    @endphp
                                    <span class="badge {{ $isPast ? 'bg-danger' : 'bg-light text-dark border' }} p-2">
                                        <i class="far fa-calendar-times me-1"></i>{{ $echeance->format('d/m/Y') }}
                                    </span>
                                @else
                                    <span class="text-muted small">Aucun</span>
                                @endif
                            </td>

                            <!-- COLONNE PROGRESSION (Calculée au 13 Janvier 2026) -->
                            <td>
                                @if($imputation->statut == 'termine')
                                    <div class="small fw-bold text-success mb-1">100% - Terminé</div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                    </div>
                                @elseif($imputation->echeancier && $imputation->date_imputation)
                                    @php
                                        $start = \Carbon\Carbon::parse($imputation->date_imputation);
                                        $end = \Carbon\Carbon::parse($imputation->echeancier);
                                        $now = \Carbon\Carbon::now();

                                        $totalDays = $start->diffInDays($end) ?: 1;
                                        $daysRemaining = $now->diffInDays($end, false);

                                        // Calcul du pourcentage de temps restant
                                        $percentRemaining = ($daysRemaining / $totalDays) * 100;
                                        $percentRemaining = max(0, min(100, $percentRemaining));

                                        // Détermination de la couleur
                                        $color = 'bg-success'; // +60% restant
                                        if($percentRemaining <= 30) $color = 'bg-danger'; // -30% restant (Urgence)
                                        elseif($percentRemaining <= 60) $color = 'bg-warning'; // Entre 30% et 60%
                                    @endphp

                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-bold" style="font-size: 0.7rem; color: {{ $percentRemaining <= 30 ? 'red' : 'black' }}">
                                            {{ round($percentRemaining) }}% restant
                                        </span>
                                    </div>
                                    <div class="progress" style="height: 10px; background-color: #e9ecef;">
                                        <div class="progress-bar {{ $color }} progress-bar-striped progress-bar-animated"
                                             role="progressbar"
                                             style="width: {{ $percentRemaining }}%">
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>

                            <!-- Statut -->
                            <td>
                                @switch($imputation->statut)
                                    @case('en_attente')
                                        <span class="badge bg-danger text-white w-100 py-2 shadow-sm">EN ATTENTE</span> @break
                                    @case('en_cours')
                                        <span class="badge bg-primary text-white w-100 py-2 shadow-sm">EN COURS</span> @break
                                    @case('termine')
                                        <span class="badge bg-success text-white w-100 py-2 shadow-sm">TERMINÉ</span> @break
                                @endswitch
                            </td>

                            <!-- Actions -->
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <a href="{{ route('imputations.show', $imputation->id) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('imputations.edit', $imputation->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('imputations.destroy', $imputation->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-4">Aucune donnée.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $imputations->links() }}</div>
        </div>
    </div>
</div>
@endsection
