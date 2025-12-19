@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">

        <!-- En-tête avec Statut et Priorité -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">
                Détails de la Notification #{{ $NotificationTache->id_notification }}
            </h1>
            <div class="flex space-x-2">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $NotificationTache->priorite == 'Urgent' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $NotificationTache->priorite }}
                </span>
                <span class="px-3 py-1 rounded-full text-sm font-semibold bg-gray-200 text-gray-700">
                    {{ $NotificationTache->statut }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <!-- Titre et Description -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-700 mb-2">{{ $NotificationTache->titre }}</h2>
                <p class="text-gray-600 leading-relaxed">
                    {{ $NotificationTache->description }}
                </p>
            </div>

            <!-- Informations Détaillées -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded-md">
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold">Agent concerné</p>
                    <p class="text-gray-800">ID Agent: {{ $NotificationTache->agent_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold">Suivi par</p>
                    <p class="text-gray-800">{{ $NotificationTache->suivi_par }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold">Date de création</p>
                    <p class="text-gray-800">{{ $NotificationTache->date_creation->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 uppercase font-bold">Échéance</p>
                    <p class="text-gray-800 {{ $NotificationTache->date_echeance < now() ? 'text-red-600 font-bold' : '' }}">
                        {{ $NotificationTache->date_echeance ? $NotificationTache->date_echeance->format('d/m/Y') : 'Aucune' }}
                    </p>
                </div>
            </div>

            <!-- Liens et Documents -->
            <div class="mt-8 flex flex-wrap gap-4">
                @if($NotificationTache->lien_action)
                    <a href="{{ $NotificationTache->lien_action }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded transition">
                        Effectuer l'action
                    </a>
                @endif

                @if($NotificationTache->document)
                    <a href="{{ asset('storage/' . $NotificationTache->document) }}" target="_blank" class="bg-gray-800 hover:bg-black text-white px-4 py-2 rounded transition flex items-center">
                        📄 Voir le document
                    </a>
                @endif
            </div>
        </div>

        <!-- Pied de page : Historique des dates -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 text-xs text-gray-400 grid grid-cols-2 gap-4">
            <p>Lu le : {{ $NotificationTache->date_lecture ?? 'Non lu' }}</p>
            <p>Complété le : {{ $NotificationTache->date_completion ?? 'En attente' }}</p>
        </div>
    </div>

    <!-- Actions de retour -->
    <div class="mt-6 text-center">
        <a href="{{ route('notifications.index') }}" class="text-indigo-600 hover:underline font-medium">
            ← Retour à la liste
        </a>
    </div>
</div>
@endsection
