<table class="table">
    <thead>
        <tr>
            <th>Statut</th>
            <th>Appréciation</th>
            <th>Message</th>
            <th>Pièce Jointe</th>
        </tr>
    </thead>
    <tbody>
        @isset($notification)
            <tr>
                <td>
                    @php
                        $badgeClass = match($notification?->approuvee) {
                            'acceptee' => 'bg-success',
                            'rejetee' => 'bg-danger',
                            default => 'bg-warning',
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ ucfirst($notification?->approuvee ?? 'en attente') }}
                    </span>
                </td>

                <td>{{ $notification?->appreciation_du_superieur ?? 'Aucune appréciation' }}</td>

                <td>{{ $notification?->message ?? 'Pas de message' }}</td>

                <td>
                    @if(!empty($notification?->Reponse_Piece_jointe))
                        <a href="{{ asset('storage/' . $notification->Reponse_Piece_jointe) }}"
                           class="btn btn-sm btn-primary"
                           target="_blank">
                            <i class="fas fa-download"></i> Télécharger
                        </a>
                    @else
                        <span class="text-muted text-italic">Aucun fichier</span>
                    @endif
                </td>
            </tr>
        @else
            <tr>
                <td colspan="4" class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle"></i> Erreur : Aucune donnée de notification trouvée.
                </td>
            </tr>
        @endisset
    </tbody>
</table>
