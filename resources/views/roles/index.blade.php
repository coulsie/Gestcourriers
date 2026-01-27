@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="bg-white shadow-lg rounded-lg p-6 border-top border-4 border-primary">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-xl font-bold text-primary mb-0">
                <i class="fas fa-id-badge me-2"></i>Gestion des Rôles & Permissions
            </h1>

            {{-- Bouton Créer activé --}}
            <a href="{{ route('roles.create') }}" class="btn btn-success shadow-sm fw-bold">
                <i class="fas fa-plus-circle me-1"></i> Ajouter un rôle
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive rounded shadow-sm">
            <table class="table table-hover align-middle border mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="py-3 px-3">ID</th>
                        <th>Nom du Rôle</th>
                        <th>Permissions Associées</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($roles as $role)
                        <tr>
                            <td class="fw-bold px-3">#{{ $role->id }}</td>
                            <td class="fw-bold text-dark">{{ $role->name }}</td>
                            <td>
                                @forelse($role->permissions as $permission)
                                    <span class="badge bg-light text-primary border me-1 my-1 px-2 py-1 shadow-sm small">
                                        {{ $permission->name }}
                                    </span>
                                @empty
                                    <span class="badge bg-secondary text-white">Aucune permission</span>
                                @endforelse
                            </td>
                            <td class="text-center">
                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-warning text-white btn-sm shadow-sm" title="Modifier les permissions">
                                    <i class="fas fa-edit"></i> Modifier Permissions
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted bg-light">
                                <i class="fas fa-id-badge fa-3x mb-3 text-secondary"></i><br>
                                Aucun rôle enregistré dans le système.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .badge { border-radius: 4px; font-size: 0.75rem; }
    /* Optionnel : effet au survol du bouton */
    .btn-success:hover { transform: translateY(-1px); transition: 0.2s; }
</style>
@endsection
