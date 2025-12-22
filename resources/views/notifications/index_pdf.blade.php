<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport des Notifications</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 20px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 9px; }

        /* Badges de priorité/statut */
        .badge { padding: 3px 6px; border-radius: 4px; color: #fff; font-size: 9px; }
        .bg-urgent { background-color: #dc3545; }
        .bg-elevee { background-color: #fd7e14; }
        .bg-moyenne { background-color: #ffc107; color: #000; }
        .bg-faible { background-color: #28a745; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Liste des Notifications</h1>
        <p>Généré le {{ date('d/m/2025 à H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Agent</th>
                <th>Titre</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Échéance</th>
                <th>Suivi par</th>
            </tr>
        </thead>
        <tbody>
            @foreach($notifications as $notification)
            <tr>
                <td>{{ $notification->id_notification }}</td>
                <td>{{ $notification->agent->first_name ?? 'N/A' }}</td>
                <td>
                    <strong>{{ $notification->titre }}</strong><br>
                    <small>{{ Str::limit($notification->description, 50) }}</small>
                </td>
                <td>
                    <span class="badge {{ $notification->priorite == 'Urgent' ? 'bg-urgent' : ($notification->priorite == 'Élevée' ? 'bg-elevee' : ($notification->priorite == 'Moyenne' ? 'bg-moyenne' : 'bg-faible')) }}">
                        {{ $notification->priorite }}
                    </span>
                </td>
                <td>{{ $notification->statut }}</td>
                <td>{{ $notification->date_echeance ? date('d/m/2025', strtotime($notification->date_echeance)) : '-' }}</td>
                <td>{{ $notification->suivi_par }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Page <span class="pagenum"></span> - Système de Gestion des Agents
    </div>

</body>
</html>
