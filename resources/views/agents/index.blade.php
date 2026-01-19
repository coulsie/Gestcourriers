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
                    <!-- BARRE DE RECHERCHE ET FILTRES -->
                    <div class="card mb-4 border-0 bg-light shadow-sm">
                        <div class="card-body">
                            <form action="{{ route('agents.index') }}" method="GET" class="row g-3">
                                <!-- Recherche textuelle -->
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-primary small">RECHERCHER</label>
                                    <div class="input-group border border-primary rounded">
                                        <span class="input-group-text bg-primary text-white border-0"><i class="fas fa-search"></i></span>
                                        <input type="text" name="search" class="form-control" placeholder="Nom, prénom ou matricule..." value="{{ request('search') }}">
                                    </div>
                                </div>
                                <!-- Filtre Service -->
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-primary small">SERVICE</label>
                                    <select name="service" class="form-select border-primary fw-bold">
                                        <option value="">Tous les services</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ request('service') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Filtre Accès -->
                                <div class="col-md-3">
                                    <label class="form-label fw-bold text-primary small">ÉTAT COMPTE</label>
                                    <select name="account" class="form-select border-primary fw-bold">
                                        <option value="">Tous les états</option>
                                        <option value="active" {{ request('account') == 'active' ? 'selected' : '' }}>Avec compte actif</option>
                                        <option value="none" {{ request('account') == 'none' ? 'selected' : '' }}>Sans compte</option>
                                    </select>
                                </div>
                                <!-- Boutons -->
                                <div class="col-md-2 d-flex align-items-end gap-2">
                                    <button type="submit" class="btn btn-primary w-100 fw-bold">FILTRER</button>
                                    <a href="{{ route('agents.index') }}" class="btn btn-outline-dark w-100 fw-bold">RESET</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success border-start border-success border-5 shadow-sm alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- TABLEAU RENFORCÉ -->
                    <div class="table-responsive rounded shadow-sm border">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white text-uppercase small">
                                <tr>
                                    <th class="py-3 px-4">Matricule</th>
                                    <th>Nom & Prénoms</th>
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
                                                <a href="{{ route('agents.show', $agent->id) }}" class="btn btn-sm btn-info text-white mx-1 rounded" title="Consulter">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-sm btn-warning text-white mx-1 rounded" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('agents.destroy', $agent->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger text-white mx-1 rounded" onclick="return confirm('Supprimer cet agent ?')" title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 bg-light text-muted fw-bold">
                                            <i class="fas fa-search fa-3x mb-3"></i><br>AUCUN AGENT TROUVÉ
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-4">
                        {{ $agents->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Couleurs Renforcées */
    .bg-primary { background-color: #0d6efd !important; }
    .text-primary { color: #0d6efd !important; }
    .avatar-circle { width: 45px; height: 45px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }

    /* Hover effects */
    .table-hover tbody tr:hover { background-color: rgba(13, 110, 253, 0.05) !important; cursor: pointer; transition: 0.3s; }
    .btn-group .btn:hover { transform: scale(1.1); transition: 0.2s; }

    /* Inputs */
    .form-control:focus, .form-select:focus { border-color: #0d6efd; box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1); }
</style>
@endsection
