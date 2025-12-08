@extends('layouts.app')

@section('title', "Recherche d'Agents par Service et Absences")

@section('content')
    <h1>Rechercher les agents par service</h1>

    <div class="card mb-4">
        <div class="card-header">
            Sélectionnez un service
        </div>
        <div class="card-body">
            <form action="{{ url('/etat-agents-par-service') }}" method="GET" class="row g-3">
                <div class="col-md-8">
                    <select name="service_id" id="service_id" class="form-select" required>
                        <option value="">-- Choisir un service --</option>
                        @foreach ($servicesList as $service)
                            <option value="{{ $service->id }}" {{ $selectedService && $selectedService->id == $service->id ? 'selected' : '' }}>
                                {{ $service->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success">Afficher les agents</button>
                </div>
            </form>
        </div>
    </div>

    @if ($selectedService)
        <div class="mt-4">
            <h2>
                Agents du service : {{ $selectedService->name }}
            </h2>
            <p>
                Période hebdomadaire du {{ $dateDebutSemaine->translatedFormat('d M Y') }} au {{ $dateFinSemaine->translatedFormat('d M Y') }}
            </p>

            @if ($agents->count())
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Matricule</th>
                            <th>Nom complet</th>
                            <th>Statut</th>
                            <th>Email</th>
                            <th>Heures d'absence (Hebdo)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                            <tr>
                                <td>{{ $agent->matricule }}</td>
                                <td>{{ $agent->first_name }} {{ $agent->last_name }}</td>
                                <td>{{ $agent->status }}</td>
                                <td>{{ $agent->email }}</td>
                                <!-- Affiche la propriété dynamique calculée dans le contrôleur -->
                                <td><strong>{{ $agent->heuresAbsenceHebdo ?? '0h 0m' }}</strong></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning">
                    Aucun agent trouvé pour ce service ou aucun agent avec absence cette semaine.
                </div>
            @endif
        </div>
    @endif

@endsection
