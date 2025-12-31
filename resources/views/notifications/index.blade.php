@extends('layouts.app')

<style>
@media print {
    .btn, .navbar, .sidebar, footer {
        display: none !important;
    }
}
</style>



@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Gestion des Notifications</h1>
        <a href="{{ route('notifications.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Nouvelle Notification
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                     <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Titre & Description</th>
                            <th>Attribué à</th>
                            <th>Priorité</th>
                            <th>Statut</th>
                            <th>Échéance</th>
                            <th>Suivi par</th>
                            <th>Reponses</th>
                            <th>Progression</th>
                            <th>Actions</th>

                        </tr>
                        </thead>
                    </thead>
                    <tbody>
                        @forelse($notifications as $notification)
                            <tr>
                                <td>#{{ $notification->id_notification }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $notification->titre }}</div>
                                    <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                        {{ $notification->description }}
                                    </small>
                                    @if($notification->document)
                                        <div class="mt-1">
                                            <a href="{{ asset('documents/' . $notification->document) }}" class="badge bg-info text-decoration-none text-white small" target="_blank">
                                                📄 Document
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td>

                                        @if($notification) {{-- Vérifie que l'objet notification existe --}}

                                            {{ $notification->id }}

                                            {{-- Utilisation de l'opérateur nullsafe pour plus de sécurité --}}
                                           {{ $notification->agent?->last_name }} {{ $notification->agent?->first_name }}

                                        @else
                                                <span class="text-muted">Agent non assigné</span>
                                        @endif
                                    {{-- Affichage des liens de pagination --}}
                                    {{ $notifications->links() }}

                                </td>
                                <td>
                                    @php
                                        $prioriteColor = match($notification->priorite) {
                                            'Urgent' => 'danger',
                                            'Élevée' => 'warning',
                                            'Moyenne' => 'primary',
                                            default => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge success-{{ $prioriteColor }}">
                                        {{ $notification->priorite }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill border {{ $notification->statut == 'Non lu' ? 'bg-danger text-white' : 'bg-light text-dark' }}">
                                        {{ $notification->statut }}
                                    </span>
                                </td>
                                <td>
                                    @if($notification->date_echeance)
                                        <div class="small {{ \Carbon\Carbon::parse($notification->date_echeance)->isPast() ? 'text-danger fw-bold' : '' }}">
                                            {{ \Carbon\Carbon::parse($notification->date_echeance)->format('d/m/Y') }}
                                        </div>
                                    @else
                                        <span class="text-muted small">Aucune</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="small"><i class="bi bi-person"></i> {{ $notification->suivi_par }}</span>
                                </td>
                                <td>
                                    <span class="small"><i class="bi bi-chat-dots"></i> {{ optional($notification->reponseNotification)->Response_Piece_jointe }}</span>
                                </td>

                                <td>
                                       @php $percent = $notification->progression ?? 0; @endphp

                                        <div class="mb-2">
                                            <strong>{{ $notification->titre }}</strong> ({{ $percent }}%)
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $percent > 80 ? 'bg-danger' : ($percent > 50 ? 'bg-warning' : 'bg-success') }}"
                                                    role="progressbar"
                                                    style="width: {{ $percent }}%;"
                                                    aria-valuenow="{{ $percent }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                Échéance le : {{ $notification->date_echeance ? \Carbon\Carbon::parse($notification->date_echeance)->format('d/m/Y') : 'N/A' }}
                                            </small>
                                        </div>
                                </td>


                                <td>
                                    <div class="btn-group btn-group-sm">
                                        @if($notification->lien_action)
                                            <a href="{{ $notification->lien_action }}" class="btn btn-outline-primary" title="Lien action">
                                                <i class="bi bi-link-45deg"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('notifications.show', $notification->id_notification) }}" class="btn btn-info btn-sm">
                                            <i class="fa fa-eye"></i>Voir
                                        </a>
                                        <a href="{{ route('notifications.edit', $notification->id_notification) }}" class="btn btn-primary btn-sm">
                                            <i class="fa fa-edit"></i> Modifier
                                        </a>

                                        <form action="{{ route('notifications.destroy', $notification->id_notification) }}" method="POST" onsubmit="return confirm('Supprimer cette notification ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fa fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    Aucune notification trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($notifications->hasPages())
            <div class="card-footer bg-white">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
    <nav class="navbar no-print"> ... </nav>
         <button onclick="window.print()" class="btn btn-success">
    Imprimer la page
    </button>
</div>
@endsection
