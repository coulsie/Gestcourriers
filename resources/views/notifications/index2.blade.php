@extends('layouts.app')

<style>
@media print {
    .btn, .navbar, .sidebar, footer {
        display: none !important;
    }
}
</style>



@section('content')



<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Liste des Notifications des Agents</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Priorité</th>
                    <th>Titre & Description</th>
                    <th>Agent (Assigné à)</th>
                    <th>Échéance</th>
                    <th>Statut</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notifications as $notif)
                  <thead class="table-dark">
                   <tr>
                        <td>#{{ $notif->id_notification }}</td>
                        <td>
                            @php
                                $prioriteClass = match($notif->priorite) {
                                    'Urgent' => 'bg-danger',
                                    'Élevée' => 'bg-warning text-dark',
                                    'Moyenne' => 'bg-info',
                                    'Faible' => 'bg-secondary',
                                    default => 'bg-light text-dark'
                                };
                            @endphp
                            <span class="badge {{ $prioriteClass }}">{{ $notif->priorite }}</span>
                        </td>
                        <td>
                            <div class="fw-bold">{{ $notif->titre }}</div>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 250px;">
                                {{ $notif->description }}
                            </small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-2 bg-light rounded-circle text-center" style="width: 30px; height: 30px; line-height: 30px;">
                                    <i class="fas fa-user text-primary small"></i>
                                </div>
                                <div>
                                    {{-- Utilisation de l'opérateur Nullsafe pour éviter l'erreur "property on null" --}}
                                    <span class="fw-bold text-uppercase">{{ $notif->agent?->last_name ?? 'Inconnu' }}</span>
                                    <span class="d-block small text-muted">{{ $notif->agent?->first_name ?? '-' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($notif->date_echeance)
                                <span class="{{ \Carbon\Carbon::parse($notif->date_echeance)->isPast() ? 'text-danger fw-bold' : '' }}">
                                    {{ \Carbon\Carbon::parse($notif->date_echeance)->format('d/m/Y') }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge border {{ $notif->statut == 'Non lu' ? 'text-danger border-danger' : 'text-success border-success' }}">
                                {{ $notif->statut }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('notifications.show', $notif->id_notification) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($notif->document)
                                    <a href="{{ asset('storage/' . $notif->document) }}" class="btn btn-sm btn-outline-secondary" target="_blank" title="Document">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    </thead>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-bell-slash fa-2x mb-3 d-block"></i>
                            Aucune notification trouvée.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notifications->hasPages())
        <div class="card-footer">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
