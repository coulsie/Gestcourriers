@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Liste de mes notifications et tâches
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($notifications->isEmpty())
                        <p class="text-center">Vous n'avez aucune notification ou tâche en cours.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="fw-bold">Priorité</th>
                                        <th class="fw-bold">Titre</th>
                                        <th class="fw-bold">Description</th>
                                        <th class="fw-bold">Statut</th>
                                        <th class="fw-bold">Échéance</th>
                                        <th class="fw-bold">Progression</th>
                                        <th class="fw-bold">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $notification)
                                        <!-- Appliquer une classe de ligne différente si non lu -->
                                        <tr class="{{ $notification->statut == 'Non lu' ? 'table-info fw-semibold' : '' }}">
                                            <td>
                                                @php
                                                    $prioriteClass = '';
                                                    switch ($notification->priorite) {
                                                        case 'Urgent': $prioriteClass = 'bg-danger'; break;
                                                        case 'Élevée': $prioriteClass = 'bg-warning text-dark'; break;
                                                        case 'Moyenne': $prioriteClass = 'bg-info'; break;
                                                        case 'Faible': $prioriteClass = 'bg-secondary'; break;
                                                    }
                                                @endphp
                                                <span class="badge {{ $prioriteClass }}">
                                                    {{ $notification->priorite }}
                                                </span>
                                            </td>
                                            <td>{{ $notification->titre }}</td>
                                            <td>{{ Str::limit($notification->description, 50) }}</td>
                                            <td>
                                                @php
                                                    $statutClass = '';
                                                    switch ($notification->statut) {
                                                        case 'Non lu': $statutClass = 'bg-primary'; break;
                                                        case 'En cours': $statutClass = 'bg-info'; break;
                                                        case 'Complétée': $statutClass = 'bg-success'; break;
                                                        case 'Annulée': $statutClass = 'bg-danger'; break;
                                                    }
                                                @endphp
                                                <span class="badge {{ $statutClass }}">
                                                    {{ $notification->statut }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($notification->date_echeance)
                                                    {{ $notification->date_echeance->format('d/m/Y') }}
                                                    @if($notification->date_echeance->isPast() && $notification->statut != 'Complétée' && $notification->statut != 'Annulée')
                                                        <span class="badge bg-danger ms-1">Expirée</span>
                                                    @endif
                                                @else
                                                    N/A
                                                @endif
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
                                                <!-- Bouton Voir les détails (et marquer comme lu si nécessaire) -->
                                                <a href="{{ route('notifications.show', $notification->id_notification) }}" class="btn btn-info btn-sm" title="Voir les détails">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <!-- Bouton Accès direct si un lien d'action existe -->
                                                @if($notification->lien_action)
                                                    <a href="{{ $notification->lien_action }}" class="btn btn-primary btn-sm" title="Accès rapide" target="_blank">
                                                        <i class="fa fa-arrow-right"></i>
                                                    </a>
                                                @endif

                                                <a href="{{ route('reponses.create', ['id_notification' => $notification->id_notification, 'agent_id' => $notification->agent_id]) }}"
                                                    class="btn btn-info btn-sm"
                                                    title="Repondre"> <i class="fas fa-save"></i>
                                                </a>
                                                                                                                                                                                            <!-- Bouton pour marquer comme complétée (Formulaire) -->
                                                @if($notification->statut != 'Complétée')
                                                <form action="{{ route('notifications.markAsRead', $notification->id_notification) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Marquer comme lue/traitée" onclick="return confirm('Confirmez-vous le traitement de cette notification ?')">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                </form>
                                                @endif

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Links -->
                        <div class="d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
