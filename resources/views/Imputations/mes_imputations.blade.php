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
                            <th>Courrier (Réf. & Fichier)</th>
                            <th>Instructions</th>
                            <th class="text-center">Niveau</th>
                            <th>Échéance</th>
                            <th style="width: 12%">Progression</th>
                            <th class="text-center">Statut</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($imputations as $imputation)
                        <tr>
                            <!-- Auteur de l'imputation -->
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $imputation->auteur->name ?? 'Système' }}</div>
                                <span class="badge bg-primary text-white shadow-sm px-2 py-1" style="font-size: 0.7rem;">
                                    <i class="fas fa-id-badge me-1"></i>{{ strtoupper($imputation->auteur->role->value ?? $imputation->auteur->role ?? 'AGENT') }}
                                </span>
                            </td>

                            <!-- Courrier & Fichier -->
                            <td>
                                <div class="text-primary fw-bold mb-1">{{ $imputation->courrier->reference }}</div>
                                @if($imputation->courrier->chemin_fichier)
                                    <a href="{{ asset('Documents/courriers/' . $imputation->courrier->chemin_fichier) }}"
                                       target="_blank" class="btn btn-xs btn-danger fw-bold shadow-sm" style="font-size: 0.65rem; padding: 2px 8px;">
                                       <i class="fas fa-file-pdf me-1"></i> VOIR LE DOCUMENT
                                    </a>
                                @else
                                    <span class="text-muted small italic">Aucun fichier</span>
                                @endif
                            </td>

                            <!-- Instructions -->
                            <td>
                                <div class="small fw-semibold text-dark" style="max-width: 200px;">
                                    {{ Str::limit($imputation->instructions, 50) }}
                                </div>
                            </td>

                            <!-- Niveau -->
                            <td class="text-center">
                                @if($imputation->niveau == 'primaire')
                                    <span class="badge bg-danger text-white px-3 py-2 shadow-sm w-100 border-0" style="letter-spacing: 1px;">
                                        <i class="fas fa-exclamation-triangle me-1"></i>PRIMAIRE
                                    </span>
                                @elseif($imputation->niveau == 'secondaire')
                                    <span class="badge bg-warning text-dark px-3 py-2 shadow-sm w-100 border-0" style="letter-spacing: 1px;">
                                        <i class="fas fa-layer-group me-1"></i>SECONDAIRE
                                    </span>
                                @else
                                    <span class="badge bg-info text-white px-3 py-2 shadow-sm w-100 border-0" style="letter-spacing: 1px;">
                                        <i class="fas fa-stream me-1"></i>TERTIAIRE
                                    </span>
                                @endif
                            </td>

                            <!-- Échéance -->
                            <td>
                                @if($imputation->echeancier)
                                    @php
                                        $echeance = \Carbon\Carbon::parse($imputation->echeancier);
                                        $isPast = $echeance->isPast() && $imputation->statut != 'termine';
                                    @endphp
                                    <div class="fw-bold {{ $isPast ? 'text-danger' : 'text-primary' }}">
                                        <i class="far fa-calendar-alt me-1"></i>{{ $echeance->format('d/m/Y') }}
                                    </div>
                                    @if($isPast)
                                        <span class="badge bg-danger text-white shadow-sm mt-1 animate-pulse">RETARD</span>
                                    @endif
                                @else
                                    <span class="text-muted italic small">Aucune</span>
                                @endif
                            </td>

                            <!-- Progression -->
                            <td>
                                @if($imputation->statut == 'termine')
                                    <div class="progress shadow-sm" style="height: 8px;">
                                        <div class="progress-bar bg-success" style="width: 100%"></div>
                                    </div>
                                    <small class="text-success fw-bold">100%</small>
                                @elseif($imputation->echeancier)
                                    @php
                                        $total = $imputation->created_at->diffInDays($imputation->echeancier) ?: 1;
                                        $restant = now()->diffInDays($imputation->echeancier, false);
                                        $percent = max(0, min(100, ($restant / $total) * 100));
                                        $pColor = $percent < 30 ? 'bg-danger' : ($percent < 60 ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <div class="progress shadow-sm" style="height: 8px;">
                                        <div class="progress-bar {{ $pColor }} progress-bar-striped progress-bar-animated" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <small class="fw-bold" style="font-size: 0.7rem;">{{ round($percent) }}% restant</small>
                                @endif
                            </td>

                            <!-- Statut -->
                            <td class="text-center">
                                @switch($imputation->statut)
                                    @case('en_attente')
                                        <span class="badge bg-secondary text-white px-3 py-2 shadow-sm">
                                            <i class="fas fa-clock me-1"></i> EN ATTENTE
                                        </span>
                                        @break
                                    @case('en_cours')
                                        <span class="badge bg-warning text-white px-3 py-2 shadow-sm">
                                            <i class="fas fa-spinner fa-spin me-1"></i> EN COURS
                                        </span>
                                        @break
                                    @case('termine')
                                        <span class="badge bg-success text-white px-3 py-2 shadow-sm">
                                            <i class="fas fa-check-double me-1"></i> TERMINÉ
                                        </span>
                                        @break
                                    @default
                                        <span class="badge bg-dark text-white px-3 py-2 shadow-sm">
                                            {{ strtoupper($imputation->statut) }}
                                        </span>
                                @endswitch
                            </td>

                            <!-- Action -->

                                    <td class="text-center pe-4">
                                        <!-- Reimputation -->
       <span title="{{ $imputation->statut === 'termine' ? 'Réimputation impossible : imputation déjà traitée' : 'Réimputer ce courrier' }}" data-bs-toggle="tooltip">
    <a href="{{ $imputation->statut === 'termine' ? 'javascript:void(0)' : route('imputations.create', ['parent_id' => $imputation->id, 'courrier_id' => $imputation->courrier_id]) }}" 
       class="btn btn-sm {{ $imputation->statut === 'termine' ? 'btn-outline-secondary disabled' : 'btn-outline-primary' }}"
       @if($imputation->statut === 'termine') 
           tabindex="-1" 
           aria-disabled="true" 
           style="pointer-events: none; opacity: 0.5;" 
       @endif>
        <i class="fas fa-redo me-1"></i> Réimputer
    </a>
</span>

                                       <!-- Traiter / Résultat -->
<a href="{{ route('imputations.show', $imputation->id) }}" 
   class="btn btn-sm {{ $imputation->statut === 'termine' ? 'btn-success' : 'btn-primary' }} shadow-sm">
    @if($imputation->statut === 'termine')
        <i class="fas fa-file-alt me-1"></i> RÉSULTAT
    @else
        <i class="fas fa-eye me-1"></i> TRAITER
    @endif
</a>
                                    </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted bg-light">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><br>
                                Aucun dossier ne vous est imputé pour le moment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
