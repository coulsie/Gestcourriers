{{-- Fichier : resources/views/agents/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <!-- CARTE PRINCIPALE -->
            <div class="card shadow-2xl border-0 rounded-lg overflow-hidden">
                <!-- Header avec dégradé renforcé -->
                <div class="card-header bg-dark py-3 d-flex align-items-center justify-content-between border-bottom border-primary border-4">
                    <h5 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-users-cog me-2 text-warning"></i> ANNUAIRE DU PERSONNEL (2026)
                    </h5>
                    <a href="{{ route('agents.create') }}" class="btn btn-warning btn-sm fw-bold px-4 shadow hover-elevate text-dark">
                        <i class="fas fa-plus-circle me-1"></i> NOUVEL AGENT
                    </a>
                </div>

                <div class="card-body bg-white">
                    <!-- RÉSUMÉ DES EFFECTIFS 2026 -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm bg-primary text-white rounded-lg">
                                <div class="card-body d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="text-uppercase mb-1 small fw-bold">Effectif Total</h6>
                                        <h2 class="mb-0 fw-black">{{ $agents->total() }}</h2>
                                    </div>
                                    <div class="icon-shape bg-white text-primary rounded-circle shadow-sm p-3">
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm bg-success text-white rounded-lg">
                                <div class="card-body d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="text-uppercase mb-1 small fw-bold">Comptes Actifs</h6>
                                        <h2 class="mb-0 fw-black">{{ $agents->where('user_id', '!=', null)->count() }}</h2>
                                    </div>
                                    <div class="icon-shape bg-white text-success rounded-circle shadow-sm p-3">
                                        <i class="fas fa-user-check fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm bg-danger text-white rounded-lg">
                                <div class="card-body d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h6 class="text-uppercase mb-1 small fw-bold">Sans Accès Système</h6>
                                        <h2 class="mb-0 fw-black">{{ $agents->where('user_id', '==', null)->count() }}</h2>
                                    </div>
                                    <div class="icon-shape bg-white text-danger rounded-circle shadow-sm p-3">
                                        <i class="fas fa-user-slash fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

<!-- BARRE DE RECHERCHE ET FILTRES RÉDUITE -->
<div class="card mb-3 border-0 bg-light shadow-sm">
    <div class="card-body py-2 px-3">
        <form action="{{ route('agents.index') }}" method="GET" class="row gx-0 align-items-end">

            <!-- Recherche textuelle (Élargie pour équilibrer) -->
            <div class="col-md-4 pe-2">
                <label class="form-label fw-bold text-primary mb-0 small" style="font-size: 0.7rem;">RECHERCHER</label>
                <div class="input-group input-group-sm border border-primary rounded" style="height: 31px;">
                    <span class="input-group-text bg-primary text-white border-0"><i class="fas fa-search fa-xs"></i></span>
                    <input type="text" name="search" class="form-control border-0" placeholder="Nom, matricule..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- Filtre Service -->
            <div class="col-md-2 pe-3">
                <label class="form-label fw-bold text-primary mb-0 small" style="font-size: 0.7rem;">SERVICE</label>
                <select name="service" class="form-select form-select-sm border-primary fw-bold"
                        style="white-space: normal; height: 31px; line-height: 1.1; font-size: 0.75rem;">
                    <option value="">Tous les services</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>
                            {{ $service->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Séparateur + Filtre ÉTAT COMPTE -->
            <div class="col-md-3 ps-3 border-start border-2 border-primary-subtle">
                <label class="form-label fw-bold text-primary mb-0 small" style="font-size: 0.7rem;">ÉTAT COMPTE</label>
                <select name="account" class="form-select form-select-sm border-primary fw-bold" style="height: 31px; font-size: 0.75rem;">
                    <option value="">Tous les états</option>
                    <option value="active" {{ request('account') == 'active' ? 'selected' : '' }}>🟢 Actif</option>
                    <option value="none" {{ request('account') == 'none' ? 'selected' : '' }}>🔴 Sans compte</option>
                </select>
            </div>

            <!-- Boutons (Ajusté à col-md-3 pour remplir la ligne) -->
            <div class="col-md-3 ps-3">
                <div class="d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1 fw-bold shadow-sm" style="height: 31px;">
                        <i class="fas fa-filter fa-xs me-1"></i> FILTRER
                    </button>
                    <a href="{{ route('agents.index') }}" class="btn btn-outline-dark btn-sm fw-bold shadow-sm d-flex align-items-center justify-content-center" style="height: 31px; width: 40px;" title="Reset">
                        <i class="fas fa-sync-alt fa-xs"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>


                    <!-- TABLEAU RENFORCÉ -->
                    <div class="table-responsive rounded shadow-sm border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white text-uppercase small">
                                <tr>
                                    <th class="py-3 px-4">Matricule</th>
                                    <th>Nom & Prénoms</th>
                                    <th>Statut / Titre</th> {{-- Nouvelle Colonne Status --}}
                                    <th>Service</th>
                                    <th class="text-center">Accès Système</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($agents as $agent)
                                    <tr class="border-bottom">
                                        <td class="px-4"><span class="badge bg-dark text-white px-3 py-2 fw-bold shadow-sm">{{ $agent->matricule }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3 bg-primary text-white shadow-sm fw-bold">
                                                    {{ strtoupper(substr($agent->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bolder text-dark text-uppercase">{{ $agent->last_name }}</span>
                                                    <span class="text-primary fw-bold">{{ $agent->first_name }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- Colonne Status avec coloration dynamique --}}
                                        {{-- Colonne Status avec coloration dynamique et texte en blanc --}}
                                        <td>
                                            @php
                                                // Définition des couleurs de fond selon le titre
                                                $statusColor = match($agent->status) {
                                                    'Directeur', 'Conseiller Spécial' => 'bg-danger',
                                                    'Sous-directeur', 'Conseiller Technique' => 'bg-warning',
                                                    'Chef de service' => 'bg-primary',
                                                    'Agent' => 'bg-secondary',
                                                    default => 'bg-dark',
                                                };
                                            @endphp

                                            {{-- On force text-white pour l'écriture en blanc et shadow-sm pour le relief --}}
                                            <span class="badge {{ $statusColor }} text-white px-3 py-2 shadow-sm fw-bold text-uppercase w-100" style="font-size: 0.75rem; border: none;">
                                                <i class="fas fa-user-tag me-1"></i> {{ $agent->status ?? 'Non défini' }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($agent->service)
                                                <span class="badge bg-info text-white px-3 py-2 border-0 shadow-sm w-100">
                                                    <i class="fas fa-building me-1"></i> {{ $agent->service->name }}
                                                </span>
                                            @else
                                                <span class="text-muted italic small">Non affecté</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($agent->user)
                                                <span class="badge bg-success text-white px-3 py-2 shadow-sm border-0 w-75">
                                                    <i class="fas fa-check-circle me-1"></i> ACTIF
                                                </span>
                                            @else
                                                <span class="badge bg-danger text-white px-3 py-2 shadow-sm border-0 w-75">
                                                    <i class="fas fa-user-slash me-1"></i> AUCUN
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group shadow-sm border rounded bg-white p-1">
                                                <a href="{{ route('agents.show', $agent->id) }}" class="btn btn-sm btn-outline-primary border-0 px-2" title="Voir détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-outline-warning border-0 px-2" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @can('manage-users')
                                                <form action="{{ route('agents.destroy', $agent->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger border-0 px-2" onclick="return confirm('Confirmer la suppression ?')" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <img src="{{ asset('img/no-data.svg') }}" style="width: 100px; opacity: 0.5;" alt=""><br>
                                            <span class="text-muted mt-3 d-block fw-bold">Aucun agent trouvé.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $agents->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    .badge { font-size: 0.8rem; letter-spacing: 0.5px; }
    .table thead th { border: none; }
    .hover-elevate:hover { transform: translateY(-2px); transition: 0.3s; }
</style>
@endsection
