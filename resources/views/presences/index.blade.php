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

    <div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nom de l'Agent</th>
                <th>Prénoms de l'Agent</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Statut</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presences as $presence)
                <tr>
                    <td>{{ $presence->id }}</td>

                   <td class="text-uppercase fw-bold">
                        @if($presence->agent)
                            {{ $presence->agent->name }} {{ $presence->agent->last_name }}
                        @else
                            <span class="text-muted">Agent inconnu</span>
                        @endif
                    </td>
                    <td>
                        {{ $presence->agent?->first_name ?? '—' }}
                    </td>

                    {{-- Formatage des dates si elles sont castées en Carbon --}}
                    <td>{{ \Carbon\Carbon::parse($presence->heure_arrivee)->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($presence->heure_depart)
                            {{ \Carbon\Carbon::parse($presence->heure_depart)->format('H:i') }}
                        @else
                            <span class="text-muted small"></span>
                        @endif
                    </td>

                    <td>
                        @php
                            $badgeClass = match($presence->statut) {
                                'Présent' => 'bg-success',
                                'En Retard' => 'bg-warning text-dark',
                                'Absent' => 'bg-danger',
                                default => 'bg-secondary',
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">
                            {{ $presence->statut }}
                        </span>
                    </td>

                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('presences.show', $presence->id) }}" class="btn btn-sm btn-info text-white" title="Détails">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            <a href="{{ route('presences.edit', $presence->id) }}" class="btn btn-sm btn-primary" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                        Aucun enregistrement de présence trouvé.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    {{-- <div class="mt-4">
        {{ $presences->links() }}
    </div> --}}
</div>
@endsection
