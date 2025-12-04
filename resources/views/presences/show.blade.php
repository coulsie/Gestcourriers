<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails Présence #{{ $presence->PresenceID }}</title>
    <!-- Inclure Tailwind CSS CDN -->
    <link href="cdn.jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Détails de la Présence #{{ $presence->PresenceID }}</h1>
        <a href="{{ route('presences.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Retour à la liste
        </a>
    </div>

    <div class="border-t border-gray-200">
        <dl>
            <!-- Agent -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Agent</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <!-- Affiche le nom de l'agent si la relation agent() est définie -->
                    {{ $presence->agent->name ?? 'ID Agent: ' . $presence->AgentID }}
                </dd>
            </div>

            <!-- Statut -->
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Statut</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($presence->Statut === 'Présent') bg-green-100 text-green-800
                        @elseif($presence->Statut === 'Absent') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800
                        @endif">
                        {{ $presence->Statut }}
                    </span>
                </dd>
            </div>

            <!-- Heure Arrivée -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Heure d'Arrivée</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $presence->HeureArrivee ? $presence->HeureArrivee->format('d/m/Y H:i:s') : 'Non spécifiée' }}
                </dd>
            </div>

            <!-- Heure Départ -->
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Heure de Départ</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $presence->HeureDepart ? $presence->HeureDepart->format('d/m/Y H:i:s') : 'Non spécifiée' }}
                </dd>
            </div>

            <!-- Notes -->
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Notes / Justification</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $presence->Notes ?? 'Aucune note.' }}
                </dd>
            </div>

             <!-- Dates de création/modification -->
             <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $presence->created_at->format('d/m/Y H:i') }}
                </dd>
            </div>
             <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Dernière mise à jour</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    {{ $presence->updated_at->format('d/m/Y H:i') }}
                </dd>
            </div>
        </dl>
    </div>

    <div class="mt-6 flex justify-end">
        <a href="{{ route('presences.edit', $presence->PresenceID) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Modifier la présence
        </a>
    </div>
</div>

</body>
</html>
