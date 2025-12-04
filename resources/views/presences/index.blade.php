@extends('layouts.app')

@section('title', 'Liste des Pointages')

@section('header', 'Gestion des Pointages (Présences)')

@section('content')
<div class="bg-white shadow-lg rounded-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Enregistrements de Pointage</h2>
        
        <a href="{{ route('presences.create') }}" class="btn btn-success btn-sm float-end">
            Nouveau Pointage
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arrivée</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Départ</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($presences as $presence)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $presence->agent->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $presence->HeureArrivee ? $presence->HeureArrivee->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $presence->HeureDepart ? $presence->HeureDepart->format('d/m/Y H:i') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($presence->Statut === 'Présent') bg-green-100 text-green-800
                                @elseif($presence->Statut === 'Absent') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800
                                @endif">
                                {{ $presence->Statut }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ Str::limit($presence->Notes, 30) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('presences.show', $presence->PresenceID) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Voir</a>
                            <a href="{{ route('presences.edit', $presence->PresenceID) }}" class="text-blue-600 hover:text-blue-900 mr-3">Éditer</a>
                            <form action="{{ route('presences.destroy', $presence->PresenceID) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $presences->links() }}
    </div>
</div>
@endsection
