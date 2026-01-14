@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête Dynamique -->
    <div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
            <div class="card-body p-4 text-white">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px;">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1">
                            <i class="fas fa-folder-open me-2"></i>Dossier Virtuel de :
                            {{ auth()->user()->agent->first_name ?? '' }} {{ strtoupper(auth()->user()->agent->last_name ?? auth()->user()->name) }}
                        </h3>
                        <p class="mb-0 opacity-75">
                            <i class="fas fa-calendar-day me-1"></i> Suivi en temps réel de vos dossiers au {{ date('d/m/Y à H:i') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="card shadow-lg border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Provenance / Auteur</th>
                            <th>Courrier (Référence & Objet)</th>
                            <th class="text-center">Niveau</th>
                            <th>Échéance</th>
                            <th style="width: 15%">Progression</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($imputations as $imputation)
                        <tr>
                            <!-- Auteur de l'imputation -->
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $imputation->auteur->name }}</div>
                                <span class="badge bg-primary text-white shadow-sm px-2 py-1" style="font-size: 0.7rem;">
                                    <i class="fas fa-id-badge me-1"></i>{{ strtoupper($imputation->auteur->role->value ?? $imputation->auteur->role) }}
                                </span>
                            </td>

                            <!-- Courrier -->
                            <td>
                                <div class="text-primary fw-bold">{{ $imputation->courrier->reference }}</div>
                                <div class="small fw-semibold text-dark text-truncate" style="max-width: 250px;" title="{{ $imputation->courrier->objet }}">
                                    {{ $imputation->courrier->objet }}
                                </div>
                            </td>

                            <!-- Niveau (Blanc sur fond vif) -->
                            <td class="text-center">
                                @if($imputation->niveau == 'primaire')
                                    <span class="badge bg-danger text-white px-3 py-2 shadow-sm w-100 border-0" style="color: #ffffff !important; letter-spacing: 1px;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>PRIMAIRE
                                    </span>
                                @elseif($imputation->niveau == 'secondaire')
                                    <span class="badge bg-warning text-dark px-3 py-2 shadow-sm w-100 border-0" style="letter-spacing: 1px;">
                                        <i class="fas fa-layer-group me-1"></i>SECONDAIRE
                                    </span>
                                @else
                                    <span class="badge bg-info text-white px-3 py-2 shadow-sm w-100 border-0" style="color: #ffffff !important; letter-spacing: 1px;">
                                        <i class="fas fa-stream me-1"></i>TERTIAIRE
                                    </span>
                                @endif
                            </td>

                            <!-- Échéance -->
                            <td>
                                @if($imputation->echeancier)
                                    @php $isPast = $imputation->echeancier->isPast() && $imputation->statut != 'termine'; @endphp
                                    <div class="fw-bold {{ $isPast ? 'text-danger' : 'text-primary' }}">
                                        <i class="far fa-calendar-alt me-1"></i>{{ $imputation->echeancier->format('d/m/Y') }}
                                    </div>
                                    @if($isPast)
                                        <span class="badge bg-danger text-white shadow-sm mt-1 animate-pulse">RETARD</span>
                                    @endif
                                @else
                                    <span class="text-muted italic small">Aucune</span>
                                @endif
                            </td>

                            <!-- Progression (Janvier 2026) -->
                            <td>
                                @if($imputation->statut == 'termine')
                                    <div class="progress shadow-sm" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                    </div>
                                    <small class="text-success fw-bold">Clôturé à 100%</small>
                                @elseif($imputation->echeancier)
                                    @php
                                        $total = $imputation->created_at->diffInDays($imputation->echeancier) ?: 1;
                                        $restant = now()->diffInDays($imputation->echeancier, false);
                                        $percent = max(0, min(100, ($restant / $total) * 100));
                                        $pColor = $percent < 30 ? 'bg-danger' : ($percent < 60 ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <div class="progress shadow-sm" style="height: 10px;">
                                        <div class="progress-bar {{ $pColor }} progress-bar-striped progress-bar-animated" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <small class="fw-bold {{ $percent < 30 ? 'text-danger' : 'text-dark' }}" style="font-size: 0.75rem;">
                                        {{ round($percent) }}% temps restant
                                    </small>
                                @endif
                            </td>

                            <!-- Statut (Blanc sur fond vif) -->
                            <td class="text-center">
                                @switch($imputation->statut)
                                    @case('en_attente')
                                        <span class="badge bg-danger px-3 py-2 text-white shadow-sm w-100 border-0" style="color: #ffffff !important;">À COMMENCER</span>
                                        @break
                                    @case('en_cours')
                                        <span class="badge bg-primary px-3 py-2 text-white shadow-sm w-100 border-0" style="color: #ffffff !important;">EN TRAITEMENT</span>
                                        @break
                                    @case('termine')
                                        <span class="badge bg-success px-3 py-2 text-white shadow-sm w-100 border-0" style="color: #ffffff !important;">TERMINÉ</span>
                                        @break
                                @endswitch
                            </td>

                            <!-- Action -->
                            <td class="text-center pe-4">
                                <a href="{{ route('imputations.show', $imputation->id) }}" class="btn btn-dark btn-sm px-3 shadow-sm">
                                    <i class="fas fa-paper-plane me-1"></i> Ouvrir & Répondre
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3 d-block"></i>
                                <span class="fw-bold text-muted h5">Aucun dossier à traiter pour le moment.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top">
                {{ $imputations->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .table thead th { font-size: 0.85rem; letter-spacing: 0.5px; border: none; }
    .badge { font-weight: 700; border-radius: 4px; }
    .progress { border-radius: 10px; background-color: #e9ecef; }
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endsection
