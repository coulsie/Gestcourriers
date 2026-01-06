@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Carte principale avec bordure supérieure élégante -->
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between" style="border-top: 5px solid #4e73df;">
                    <h5 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users-cog me-2"></i> Annuaire du Personnel (2026)
                    </h5>
                    {{-- Bouton Créer avec animation --}}
                    <a href="{{ route('agents.create') }}" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm hover-elevate">
                        <i class="fas fa-plus-circle me-1"></i> Ajouter un nouvel agent
                    </a>
                </div>

                <div class="card-body">
                    {{-- Alerte de succès modernisée --}}
                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-light" id="agentsTable">
                            <thead class="bg-light text-dark shadow-sm">
                                <tr>
                                    <th class="border-0">Matricule</th>
                                    <th class="border-0">Nom & Prénoms</th>
                                    <th class="border-0">Service</th>
                                    <th class="border-0 text-center">Accès Système</th>
                                    <th class="border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($agents as $agent)
                                    <tr class="transition-all">
                                        <td class="fw-bold text-primary">{{ $agent->matricule }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3 bg-soft-primary text-primary">
                                                    {{ strtoupper(substr($agent->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold text-dark">{{ $agent->last_name }}</span>
                                                    <small class="text-muted">{{ $agent->first_name }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($agent->service)
                                                <span class="badge rounded-pill bg-soft-info text-info border border-info px-3">
                                                    <i class="fas fa-building me-1"></i> {{ $agent->service->name }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted">Aucun service</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($agent->user)
                                                <span class="badge bg-success-soft text-success small" title="{{ $agent->user->email }}">
                                                    <i class="fas fa-user-check me-1"></i> Actif
                                                </span>
                                            @else
                                                <span class="badge bg-danger-soft text-danger small">
                                                    <i class="fas fa-user-slash me-1"></i> Pas de compte
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group shadow-sm rounded">
                                                <a href="{{ route('agents.show', $agent->id) }}" class="btn btn-outline-info btn-sm" title="Consulter">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('agents.destroy', $agent->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm"
                                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet agent ?')" title="Supprimer">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Couleurs Douces (Soft Colors) */
    .bg-soft-primary { background-color: #e8edff; }
    .bg-soft-info { background-color: #e0f7fa; }
    .bg-success-soft { background-color: #d1fae5; color: #065f46; }
    .bg-danger-soft { background-color: #fee2e2; color: #991b1b; }

    /* Avatar style */
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    /* Transitions & Effets */
    .transition-all { transition: all 0.2s ease-in-out; }
    .table-hover tbody tr:hover {
        background-color: #f8f9fc !important;
        transform: scale(1.005);
    }

    .hover-elevate:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3) !important;
    }

    .btn-group .btn {
        border-width: 1px;
        padding: 0.4rem 0.7rem;
    }

    /* Style de la table 2026 */
    #agentsTable thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 700;
    }
</style>
@endsection
