@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Barre latérale simple -->
        <nav class="col-md-2 d-none d-md-block bg-light sidebar py-4">
            <div class="position-sticky">
                <h6 class="sidebar-heading px-3 mt-4 mb-1 text-muted text-uppercase">Menu Admin</h6>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('profile.create') }}">Ajouter un Agent</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('notifications.index') }}">Notifications</a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenu Principal -->
        <main class="col-md-10 ms-sm-auto px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard Administrateur</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="{{ route('profile.create') }}" class="btn btn-sm btn-outline-primary">Nouveau Profil</a>
                </div>
            </div>

            <!-- Cartes de Statistiques -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Agents</h5>
                            <p class="card-text">Total des profils créés : <strong>{{ $totalAgents ?? 0 }}</strong></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-white bg-warning mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Notifications</h5>
                            <p class="card-text">En attente de lecture : <strong>{{ $notifsNonLues ?? 0 }}</strong></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table des dernières actions -->
            <div class="mt-4">
                <h4>Dernières Notifications</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Exemple de boucle avec votre clé primaire id_notification --}}
                            @forelse($notifications as $notif)
                            <tr>
                                <td>{{ $notif->id_notification }}</td>
                                <td>{{ $notif->titre }}</td>
                                <td><span class="badge bg-danger">{{ $notif->statut }}</span></td>
                                <td>
                                    <a href="{{ route('notifications.visualiser', ['id' => $notif->id_notification]) }}" class="btn btn-xs btn-info">Voir</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4">Aucune notification récente.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
