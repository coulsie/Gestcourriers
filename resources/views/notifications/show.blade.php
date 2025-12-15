{{-- resources/views/notifications_taches/show.blade.php --}}

@extends('layouts.app') {{-- Assurez-vous d'avoir ce layout de base --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Détails de la Tâche/Notification</h1>
        <a href="{{ route('notifications.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md">
            Retour à la liste
        </a>
    </div>

    <div class="bg-white shadow-lg rounded-lg p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">{{ $notificationTache->titre }}</h2>
                <p class="text-sm text-gray-500">
                    @if($notificationTache->date_creation)
                       {{ $notificationTache->date_creation->format('d/m/Y') }}
                    @else
                        Date inconnue
                    @endif
               </p>
            </div>

            {{-- Badge de Priorité --}}
            @php
                $priorityColor = [
                    'Faible' => 'bg-green-100 text-green-800',
                    'Moyenne' => 'bg-yellow-100 text-yellow-800',
                    'Élevée' => 'bg-orange-100 text-orange-800',
                    'Urgent' => 'bg-red-100 text-red-800',
                ][$notificationTache->priorite] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $priorityColor }}">
                {{ $notificationTache->priorite }}
            </span>
        </div>

        <hr class="my-4">

        <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-700">Description</h3>
            <p class="text-gray-600 mt-1">{!! nl2br(e($notificationTache->description)) !!}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
                <p class="text-sm font-medium text-gray-500">Assigné à l'Agent ID</p>
                <p class="mt-1 text-sm text-gray-900">{{ $notificationTache->agent_id }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Suivi par</p>
                <p class="mt-1 text-sm text-gray-900">{{ $notificationTache->suivi_par }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Date d'échéance</p>
                <p class="mt-1 text-sm text-gray-900">
                    @if($notificationTache->date_echeance)
                        {{ $notificationTache->date_echeance->format('d M Y') }}
                    @else
                        Non définie
                    @endif
                </p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Statut</p>
                {{-- Badge de Statut --}}
                @php
                    $statusColor = [
                        'Non lu' => 'bg-blue-100 text-blue-800',
                        'En cours' => 'bg-yellow-100 text-yellow-800',
                        'Complétée' => 'bg-green-100 text-green-800',
                        'Annulée' => 'bg-red-100 text-red-800',
                    ][$notificationTache->statut] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $statusColor }}">
                    {{ $notificationTache->statut }}
                </span>
            </div>
        </div>

        {{-- Section Action/Lien --}}
        @if($notificationTache->lien_action)
        <div class="mt-6 pt-4 border-t">
            <a href="{{ $notificationTache->lien_action }}" target="_blank" rel="noopener noreferrer"
               class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md">
                Effectuer l'action requise
            </a>
        </div>
        @endif
    </div>
    <div class="mt-6 pt-4 border-t">
        <a href="{{ route('notifications.visualiser', $notificationTache->id_notification) }}" target="_blank" rel="noopener noreferrer"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md">
            Visualiser le document
        </a>
    </div>

    {{-- Boutons d'action (Modifier, Supprimer) --}}
    <div class="flex space-x-4">
        <a href="{{ route('notifications.edit', $notificationTache->id_notification) }}"
           class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded shadow-md">
            Modifier la tâche
        </a>

        <form action="{{ route('notifications.destroy', $notificationTache->id_notification) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded shadow-md">
                Supprimer la tâche
            </button>
        </form>
    </div>
</div>
@endsection
