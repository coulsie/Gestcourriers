<!-- resources/views/pointage/enregistrementpointatge.blade.php -->
@extends('layouts.app')
@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enregistrer un Pointage') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Affichage des messages de succès ou d'erreur -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('presences.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Agent ID (Selection de l'agent) -->
                        <div>
                            <label for="agent_id" class="block text-sm font-medium text-gray-700">Agent</label>
                            <select name="agent_id" id="agent_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Choisir un agent...</option>
                                @foreach($agents as $agent)
                                    <option value="{{ $agent->id }}">{{ $agent->nom }} {{ $agent->prenom }}</option>
                                @endforeach
                            </select>
                            @error('agent_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Statut (Enum) -->
                        <div>
                            <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select name="statut" id="statut" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Présent" selected>Présent</option>
                                <option value="En Retard">En Retard</option>
                                <option value="Absent">Absent</option>
                            </select>
                            @error('statut') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Heure d'arrivée -->
                        <div>
                            <label for="heure_arrivee" class="block text-sm font-medium text-gray-700">Heure d'arrivée</label>
                            <input type="datetime-local" name="heure_arrivee" id="heure_arrivee" required
                                   value="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('heure_arrivee') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Heure de départ (Optionnel) -->
                        <div>
                            <label for="heure_depart" class="block text-sm font-medium text-gray-700">Heure de départ (Optionnel)</label>
                            <input type="datetime-local" name="heure_depart" id="heure_depart"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            @error('heure_depart') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes (Text) -->
                        <div class="md:col-span-2">
                            <label for="notes" class="block text-sm font-medium text-gray-700">Notes / Commentaires</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Informations complémentaires..."></textarea>

                            @error('notes')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                            Enregistrer le Pointage
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
