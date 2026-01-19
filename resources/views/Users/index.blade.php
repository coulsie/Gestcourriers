{{-- Fichier : resources/views/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="bg-white shadow-lg rounded-lg p-6 border-top border-4 border-primary">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-xl font-bold text-primary mb-0">
                <i class="fas fa-users me-2"></i>Gestion des Utilisateurs
            </h1>

            {{-- Bouton pour aller à la page de création --}}
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
                        @php
                            // Définition des couleurs de badge par rôle (Enum Laravel 12)
                            $roleColor = match($user->role->value ?? $user->role) {
                                'Directeur' => 'bg-danger',
                                'Superviseur' => 'bg-primary',
                                'Agent' => 'bg-info',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <tr class="opacity-transition">
                            <td class="fw-bold px-3">#{{ $user->id }}</td>
                            <td>
                                <div class="fw-bold text-dark">{{ $user->name }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $roleColor }} text-white px-3 py-2 shadow-sm">
                                    <i class="fas fa-shield-alt me-1 small"></i> {{ strtoupper($user->role->value ?? $user->role) }}
                                </span>
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
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info text-white" title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning text-white" title="Modifier l'utilisateur">
                                        <i class="fas fa-edit"></i>
                                    </a>
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
    </div>
</div>

<style>
    .opacity-transition { transition: all 0.2s; }
    .opacity-transition:hover { background-color: rgba(248, 249, 250, 1); }
    .badge { font-size: 0.75rem; letter-spacing: 0.5px; }
    .btn-group .btn { border-radius: 4px; margin: 0 2px; }
</style>
@endsection
