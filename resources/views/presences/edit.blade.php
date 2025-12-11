<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Éditer Présence #{{ $presence->id }}</title>
    <!-- Inclure Tailwind CSS CDN -->
    <link href="cdn.jsdelivr.net" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Éditer la Présence #{{ $presence->id }}</h1>
        <a href="{{ route('presences.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Retour à la liste
        </a>
    </div>

    <!-- Affichage des erreurs de validation -->
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            <p class="font-bold">Oups ! Quelque chose s'est mal passé :</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Le formulaire utilise la méthode PUT via @method('PUT') pour la mise à jour -->
    <form action="{{ route('presences.update', $presence->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- agent_id -->
            <div>
                <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent</label>
                <select name="agent_id" id="agent_id" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Sélectionnez un agent</option>
                    <!--
                        NOTE: Comme pour la vue 'create', vous devez passer la liste des agents depuis le contrôleur.
                    -->
                    {{--
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" {{ old('agent_id', $presence->agent_id) == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name }}
                        </option>
                    @endforeach
                    --}}
                     <!-- Un fallback simple si vous n'envoyez pas la liste des agents -->
                     <option value="{{ $presence->agent_id }}" selected>
                        Agent Actuel (ID: {{ $presence->agent_id }})
                    </option>
                </select>
            </div>

            <!-- Statut -->
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">statut</label>
                <select name="statut" id="statut" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <!-- Utilisation de old() pour conserver la valeur en cas d'erreur de validation, sinon utilise la valeur actuelle $presence->statut -->
                    <option value="Présent" {{ old('statut', $presence->statut) == 'Présent' ? 'selected' : '' }}>Présent</option>
                    <option value="Absent" {{ old('statut', $presence->statut) == 'Absent' ? 'selected' : '' }}>Absent</option>
                    <option value="En Retard" {{ old('statut', $presence->statut) == 'En Retard' ? 'selected' : '' }}>En Retard</option>
                    <option value="Congé" {{ old('statut', $presence->statut) == 'Congé' ? 'selected' : '' }}>Congé</option>
                </select>
            </div>

            <!-- heure_arrivee -->
            <div>
                <label for="heure_arrivee" class="block text-sm font-medium text-gray-700">Heure d'Arrivée</label>
                <!-- Formate la date/heure pour qu'elle soit compatible avec le champ input type="datetime-local" -->
                <input type="datetime-local" name="heure_arrivee" id="heure_arrivee"
                       value="{{ old('heure_arrivee', $presence->heure_arrivee ? $presence->heure_arrivee : '') }}"
                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>

            <!-- heure_depart -->
            <div>
                <label for="heure_depart" class="block text-sm font-medium text-gray-700">Heure de Départ (Optionnel)</label>
                 <!-- Formate la date/heure pour qu'elle soit compatible avec le champ input type="datetime-local" -->
                <input type="datetime-local" name="heure_depart" id="heure_depart"
                       value="{{ old('heure_depart', $presence->heure_depart ? $presence->heure_depart: '') }}"
                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
        </div>

        <!-- Notes -->
        <div class="mt-6">
            <label for="notes" class="block text-sm font-medium text-gray-700">Notes ou Justification (Optionnel)</label>
            <textarea name="notes" id="notes" rows="3" class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">{{ old('Notes', $presence->notes) }}</textarea>
        </div>

        <div class="mt-6">
            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Mettre à jour la Présence
            </button>
        </div>
    </form>
</div>

</body>
</html>
