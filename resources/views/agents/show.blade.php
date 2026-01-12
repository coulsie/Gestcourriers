@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    Détails de l'Agent : {{ $agent->first_name }} {{ $agent->last_name }}
                    <a href="{{ route('agents.index') }}" class="btn btn-secondary btn-sm float-end ms-2">Retour à la liste</a>
                    <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-warning btn-sm float-end">Modifier</a>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- Photo de l'agent (Optionnel, si vous gérez le stockage des images) --}}

                        <div class="col-md-3 text-center">
                            {{-- On vérifie si l'agent a une photo enregistrée --}}
                            @if($agent->photo && file_exists(public_path('agents_photos/' . $agent->photo)))
                                {{-- asset() pointera directement vers le dossier public/ --}}
                               <img src="{{ asset('agents_photos/' . $agent->photo) }}?v={{ time() }}" alt="Photo de {{ $agent->last_name }} {{ $agent->first_name }} "
                                class="img-fluid rounded-circle mb-3"
                                style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                {{-- Image par défaut si aucune photo n'est trouvée --}}
                                <div class="bg-light p-5 rounded-circle mb-3 mx-auto" style="width: 150px; height: 150px;">
                                    <i class="fas fa-user fa-4x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        {{-- Informations principales --}}
                        <div class="col-md-9">
                            <h3>{{ $agent->first_name }} {{ $agent->last_name }}</h3>
                            <p><strong>Matricule :</strong> {{ $agent->matricule }}</p>
                            <p><strong>Statut :</strong> {{ $agent->status }}</p>
                            <p><strong>Sexe :</strong> {{ $agent->sexe }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Détails complémentaires --}}
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations de Contact</h5>
                            <p><strong>Email :</strong> {{ $agent->email }}</p>
                            <p><strong>Téléphone :</strong> {{ $agent->phone_number }}</p>
                            <p><strong>Adresse :</strong> {{ $agent->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Détails d'Affectation</h5>
                            <p><strong>Service :</strong>
                                @if($agent->service)
                                    {{ $agent->service->name }} ({{ $agent->service->direction->name ?? 'N/A' }})
                                @else
                                    Non affecté
                                @endif
                            </p>
                            <p><strong>Emploi/Poste :</strong> {{ $agent->Emploi }}</p>
                            <p><strong>Grade :</strong> {{ $agent->Grade }}</p>
                            <p><strong>Date prise de service :</strong> {{ $agent->Date_Prise_de_service }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Détails personnels et urgence --}}
                    <div class="row">
                        <div class="col-md-6">
                            <h5>État Civil</h5>
                            <p><strong>Date de naissance :</strong> {{ $agent->date_of_birth }}</p>
                            <p><strong>Lieu de naissance :</strong> {{ $agent->place_birth }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Contact d'Urgence</h5>
                            <p><strong>Personne à prévenir :</strong> {{ $agent->Personne_a_prevenir }}</p>
                            <p><strong>Contact :</strong> {{ $agent->Contact_personne_a_prevenir }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Compte Utilisateur --}}
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Compte Utilisateur (Authentification)</h5>
                            @if($agent->user)
                                <span class="badge bg-success">Lié à : {{ $agent->user->email }}</span>
                            @else
                                <span class="badge bg-danger">Pas de compte utilisateur lié</span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
