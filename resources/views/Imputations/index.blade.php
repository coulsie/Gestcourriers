@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- BARRE DE FILTRES - TAILLE DE POLICE AUGMENTÉE -->
    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-4" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
            <form action="{{ route('imputations.index') }}" method="GET" class="row g-3 align-items-end">
                <!-- Recherche -->
                <div class="col-md-3">
                    <label class="form-label fw-bold text-dark mb-2 fs-5"><i class="fas fa-search me-1 text-primary"></i> RECHERCHE</label>
                    <input type="text" name="search" class="form-control border-2 shadow-sm fs-5 py-2" value="{{ request('search') }}" placeholder="Réf. ou objet...">
                </div>

                <!-- Niveau -->
                <div class="col-md-2">
                    <label class="form-label fw-bold text-dark mb-2 fs-5">NIVEAU</label>
                    <select name="niveau" class="form-select border-2 shadow-sm fs-5 py-2">
                        <option value="">Tous</option>
                        <option value="primaire" {{ request('niveau') == 'primaire' ? 'selected' : '' }}>Primaire</option>
                        <option value="secondaire" {{ request('niveau') == 'secondaire' ? 'selected' : '' }}>Secondaire</option>
                        <option value="tertiaire" {{ request('niveau') == 'tertiaire' ? 'selected' : '' }}>Tertiaire</option>
                    </select>
                </div>

                <!-- Statut -->
                <div class="col-md-2">
                    <label class="form-label fw-bold text-dark mb-2 fs-5">STATUT</label>
                    <select name="statut" class="form-select border-2 shadow-sm fs-5 py-2">
                        <option value="">Tous les statuts</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="termine" {{ request('statut') == 'termine' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>

                <!-- Agent -->
                <div class="col-md-3">
                    <label class="form-label fw-bold text-dark mb-2 fs-5">AGENT ASSIGNÉ</label>
                    <select name="agent_id" class="form-select border-2 shadow-sm fs-5 py-2">
                        <option value="">Tous les agents</option>
                        @foreach($allAgents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                {{ strtoupper($agent->last_name) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Boutons -->
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold w-100 shadow-sm border-2 py-2 fs-5">
                        <i class="fas fa-filter"></i>
                    </button>
                    <a href="{{ route('imputations.index') }}" class="btn btn-light fw-bold w-100 shadow-sm border-2 py-2 fs-5">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLEAU DES IMPUTATIONS - TEXTES AGRANDIS -->
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark py-4 d-flex justify-content-between align-items-center border-bottom">
            <h4 class="mb-0 text-white fw-bold">
                <i class="fas fa-tasks me-2 text-warning"></i> Suivi des Imputations (Janvier 2026)
            </h4>
            <a href="{{ route('imputations.create') }}" class="btn btn-warning btn-lg fw-bold shadow-sm px-4">
                <i class="fas fa-plus-circle me-1"></i> NOUVELLE IMPUTATION
            </a>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 fs-5">
                    <thead class="table-secondary">
                        <tr class="text-uppercase fw-black">
                            <th class="py-4 ps-4" style="width: 160px;">Niveau</th>
                            <th>Courrier (Réf & Objet)</th>
                            <th>Agents Assignés</th>
                            <th>Échéancier</th>
                            <th style="width: 18%;">Progression</th>
                            <th>Statut</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($imputations as $imputation)
                        <tr>
                            <!-- NIVEAU STYLE RENFORCÉ -->
                            <td class="ps-4 text-center">
                                @if($imputation->niveau == 'primaire')
                                    <div class="bg-danger text-white fw-black rounded-3 px-3 py-2 shadow-sm fs-6">
                                        PRIMAIRE
                                    </div>
                                @elseif($imputation->niveau == 'secondaire')
                                    <div class="bg-warning text-dark fw-black rounded-3 px-3 py-2 shadow-sm fs-6">
                                        SECONDAIRE
                                    </div>
                                @else
                                    <div class="bg-info text-white fw-black rounded-3 px-3 py-2 shadow-sm fs-6">
                                        TERTIAIRE
                                    </div>
                                @endif
                            </td>

                            <!-- Détails Courrier -->
                            <td>
                                <div class="fw-bold text-primary mb-1 fs-5">{{ $imputation->courrier->reference }}</div>
                                <div class="text-dark fw-medium text-truncate" style="max-width: 300px; font-size: 1rem;">
                                    {{ $imputation->courrier->objet }}
                                </div>
                            </td>

                            <!-- Agents -->
                            <td>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($imputation->agents as $agent)
                                        <span class="badge bg-white text-indigo border border-indigo-subtle fw-bold p-2 fs-6 shadow-sm" style="color: #4338ca;">
                                            <i class="fas fa-user-circle me-1"></i>{{ strtoupper($agent->last_name) }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>

                            <!-- Échéancier -->
                            <td class="fw-bold fs-5">
                                @if($imputation->echeancier)
                                    @php
                                        $echeance = \Carbon\Carbon::parse($imputation->echeancier);
                                        $isPast = $echeance->isPast() && $imputation->statut != 'termine';
                                    @endphp
                                    <div class="{{ $isPast ? 'text-danger' : 'text-dark' }}">
                                        <i class="far fa-clock me-1"></i>{{ $echeance->format('d/m/Y') }}
                                        @if($isPast) <br><span class="badge bg-danger text-uppercase fs-small mt-1">Hors Délai</span> @endif
                                    </div>
                                @else
                                    <span class="text-muted italic fs-6">Non défini</span>
                                @endif
                            </td>

                            <!-- Progression Dynamique -->
                            <td>
                                @if($imputation->statut == 'termine')
                                    <div class="progress shadow-sm mb-1" style="height: 12px; border-radius: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
                                    </div>
                                    <small class="fw-bold text-success fs-6">100% Terminé</small>
                                @elseif($imputation->echeancier && $imputation->date_imputation)
                                    @php
                                        $start = \Carbon\Carbon::parse($imputation->date_imputation);
                                        $end = \Carbon\Carbon::parse($imputation->echeancier);
                                        $now = \Carbon\Carbon::now();
                                        $totalDays = max(1, $start->diffInDays($end));
                                        $daysRemaining = $now->diffInDays($end, false);
                                        $percentRemaining = max(0, min(100, ($daysRemaining / $totalDays) * 100));
                                        $color = ($percentRemaining <= 30) ? 'bg-danger' : (($percentRemaining <= 60) ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <div class="progress shadow-sm mb-2" style="height: 12px; border-radius: 10px; background-color: #e2e8f0;">
                                        <div class="progress-bar {{ $color }} progress-bar-striped progress-bar-animated" role="progressbar" style="width: {{ $percentRemaining }}%"></div>
                                    </div>
                                    <small class="fw-bold fs-6">{{ round($percentRemaining) }}% restant</small>
                                @else
                                    <small class="text-muted italic fs-6">---</small>
                                @endif
                            </td>

                            <!-- Statut -->
                            <td>
                                @switch($imputation->statut)
                                    @case('en_attente')
                                        <span class="badge bg-soft-danger text-danger border border-danger fw-bold w-100 py-3 fs-6">EN ATTENTE</span> @break
                                    @case('en_cours')
                                        <span class="badge bg-soft-primary text-primary border border-primary fw-bold w-100 py-3 fs-6">EN COURS</span> @break
                                    @case('termine')
                                        <span class="badge bg-soft-success text-success border border-success fw-bold w-100 py-3 fs-6">TERMINÉ</span> @break
                                @endswitch
                            </td>

                            <!-- Actions -->
                            
                            <td class="text-center pe-4">
                                <div class="btn-group shadow-sm">
                                    <!-- Bouton Voir/Traiter -->
                                    <a href="{{ route('imputations.show', $imputation->id) }}" class="btn btn-sm btn-info text-white" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Bouton Modifier -->
                                    <a href="{{ route('imputations.edit', $imputation->id) }}" class="btn btn-sm btn-warning text-white" title="Modifier l'imputation">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Bouton Supprimer avec Confirmation -->
                                    <form action="{{ route('imputations.destroy', $imputation->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette imputation ? Cette action est irréversible.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer l'imputation">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted fw-bold fs-4">
                                <i class="fas fa-search-minus fa-3x mb-3 opacity-25"></i><br>
                                Aucun dossier trouvé.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center py-4 bg-light border-top">
                {{ $imputations->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    /* Tailles de polices et styles personnalisés 2026 */
    .fs-small { font-size: 0.75rem; }
    .fs-6 { font-size: 0.95rem !important; }
    .fs-5 { font-size: 1.15rem !important; }
    .fw-black { font-weight: 900 !important; }
    .rounded-4 { border-radius: 1.25rem !important; }

    .bg-soft-danger { background-color: #fff1f2; }
    .bg-soft-primary { background-color: #eef2ff; }
    .bg-soft-success { background-color: #ecfdf5; }

    .table thead th {
        font-weight: 800;
        color: #334155;
        font-size: 1rem;
    }

    .form-control, .form-select {
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        transform: translateY(-1px);
    }
</style>
@endsection

