{{-- Fichier : resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="bg-white shadow-lg rounded-lg p-6 border-top border-4 border-primary">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-xl font-bold text-primary mb-0">
                <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
            </h1>

            <a href="{{ route('users.create') }}" class="btn btn-success shadow-sm fw-bold">
                <i class="fas fa-user-plus me-1"></i> Ajouter un utilisateur
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
                        <th>Utilisateur</th>
                        <th>Rôle</th>
                        <th>Email</th>
                        <th>Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="opacity-transition">
                            <td class="fw-bold px-3">#{{ $user->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                            </td>

                            <td>
                                @forelse($user->getRoleNames() as $role)
                                    @php
                                        $roleColor = match($role) {
                                            'admin' => 'bg-danger',
                                            'directeur' => 'bg-danger',
                                            'superviseur' => 'bg-primary',
                                            'utilisateur' => 'bg-info',
                                            'agent' => 'bg-info',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    {{-- Ajout de text-white ici pour l'écriture en blanc --}}
                                    <span class="badge {{ $roleColor }} text-white px-2 py-1 shadow-sm text-uppercase">
                                        <i class="fas fa-user-tag me-1" style="font-size: 0.65rem;"></i>
                                        {{ $role }}
                                    </span>
                                @empty
                                    <span class="badge bg-light text-dark border italic">Aucun rôle</span>
                                @endforelse
                            </td>

                            <td class="text-secondary">{{ $user->email }}</td>
                            <td>
                                <span class="text-dark fw-medium">
                                    <i class="far fa-calendar-alt me-1 text-primary"></i>
                                    {{ $user->created_at->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm">
                                    <!-- Voir -->
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info text-white" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Modifier -->
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning text-white" title="Modifier l'utilisateur">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Réinitialiser Password (NOUVEAU) -->
                                    <form action="{{ route('users.reset_password', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Générer un nouveau mot de passe provisoire pour cet utilisateur ?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary text-white" title="Réinitialiser le mot de passe">
                                            <i class="fas fa-key"></i>
                                        </button>
                                    </form>

                                    <!-- Supprimer -->
                                    @can('manage-users')
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted bg-light">
                                <i class="fas fa-user-slash fa-3x mb-3 text-secondary"></i><br>
                                Aucun utilisateur enregistré dans le système.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>

<style>
    .opacity-transition { transition: all 0.2s; }
    .opacity-transition:hover { background-color: rgba(248, 249, 250, 1); }
    .badge { font-size: 0.7rem; letter-spacing: 0.5px; border-radius: 50px; border: none; }
    .btn-group .btn { border-radius: 4px; margin: 0 2px; }
    .table thead th { border-bottom: none; font-size: 0.85rem; text-transform: uppercase; }
</style>
@endsection
