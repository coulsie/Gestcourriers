{{-- resources/views/notifications_taches/edit.blade.php --}}

@extends('layouts.app') {{-- Assurez-vous d'avoir ce layout de base --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Modifier la Tâche/Notification</h1>
        <a href="{{ route('notifications_taches.show', $notificationTache->id_notification) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow-md">
            Annuler et revenir
        </a>
    </div>

    {{-- Affichage des erreurs de validation --}}
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('notifications_taches.update', $notificationTache->id_notification) }}" method="POST" class="bg-white shadow-lg rounded-lg p-6">
        @csrf
        @method('PUT') {{-- Utilise la méthode PUT pour la mise à jour --}}

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Titre --}}
            <div>
                <label for="titre" class="block text-sm font-medium text-gray-700">Titre</label>
                <input type="text" name="titre" id="titre" value="{{ old('titre', $notificationTache->titre) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>

            {{-- ID Agent --}}
            <div>
                <label for="id_agent" class="block text-sm font-medium text-gray-700">ID Agent Assigné</label>
                <input type="number" name="id_agent" id="id_agent" value="{{ old('id_agent', $notificationTache->id_agent) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>

            {{-- Suivi Par --}}
            <div>
                <label for="suivi_par" class="block text-sm font-medium text-gray-700">Suivi par (Responsable)</label>
                <input type="text" name="suivi_par" id="suivi_par" value="{{ old('suivi_par', $notificationTache->suivi_par) }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
            </div>

            {{-- Date d'échéance --}}
            <div>
                <label for="date_echeance" class="block text-sm font-medium text-gray-700">Date d'échéance</label>
                {{-- Formatte la date pour l'input type="datetime-local" --}}
                @php
                    $echeanceValue = old('date_echeance', $notificationTache->date_echeance ? $notificationTache->date_echeance->format('Y-m-d\TH:i') : '');
                @endphp
                <input type="datetime-local" name="date_echeance" id="date_echeance" value="{{ $echeanceValue }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            {{-- Priorité (Utilise les Enums passés par le contrôleur) --}}
            <div>
                <label for="priorite" class="block text-sm font-medium text-gray-700">Priorité</label>
                <select name="priorite" id="priorite" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @foreach($priorites as $priorite)
                        <option value="{{ $priorite->value }}" {{ old('priorite', $notificationTache->priorite) === $priorite->value ? 'selected' : '' }}>
                            {{ $priorite->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Statut (Utilise les Enums passés par le contrôleur) --}}
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                <select name="statut" id="statut" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                    @foreach($statuts as $statut)
                        <option value="{{ $statut->value }}" {{ old('statut', $notificationTache->statut) === $statut->value ? 'selected' : '' }}>
                            {{ $statut->value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Description --}}
        <div class="mt-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea name="description" id="description" rows="4"
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('description', $notificationTache->description) }}</textarea>
        </div>

        {{-- Lien d'action --}}
        <div class="mt-6">
            <label for="lien_action" class="block text-sm font-medium text-gray-700">Lien d'action (URL)</label>
            <input type="url" name="lien_action" id="lien_action" value="{{ old('lien_action', $notificationTache->lien_action) }}"
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        </div>

        {{-- Bouton de soumission --}}
        <div class="mt-6">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow-md">
                Mettre à jour la tâche
            </button>
        </div>
    </form>
</div>
@endsection
