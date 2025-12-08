<!-- Indique à cette vue d'utiliser le layout 'layouts.app' -->
@extends('layouts.app')

<!-- Définit le titre spécifique de cette page -->
@section('title', "État des Agents par Service")

<!-- Commence la section 'content' qui sera injectée dans le @yield('content') du layout -->
@section('content')
    <div class="card">
        <div class="card-header">
            <h1>État des Agents par Service</h1>
        </div>
        <div class="card-body">

            @foreach ($services as $service)
                <div class="mb-4">
                    <h2 class="alert alert-info">
                        Service : {{ $service->name }} <small>(Code: {{ $service->code }})</small>
                    </h2>

                    @if ($service->agents->count())
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Matricule</th>
                                    <th>Nom complet</th>
                                    <th>Statut</th>
                                    <th>Emploi / Grade</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($service->agents as $agent)
                                    <tr>
                                        <td>{{ $agent->matricule }}</td>
                                        <td>{{ $agent->first_name }} {{ $agent->last_name }}</td>
                                        <td>
                                            <!-- Exemple de badge Bootstrap basé sur le statut -->
                                            @if($agent->status === 'Actif')
                                                <span class="badge bg-success">{{ $agent->status }}</span>
                                            @else
                                                <span class="badge bg-warning">{{ $agent->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $agent->Emploi }} / {{ $agent->Grade }}</td>
                                        <td>{{ $agent->email }}</td>
                                        <td>{{ $agent->phone_number }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="alert alert-warning">
                            Aucun agent n'est actuellement affecté à ce service.
                        </p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
<!-- Fin de la section 'content' -->
