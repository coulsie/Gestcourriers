@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Éditer la Tâche #{{ $tache->id_notification }}</div>

                <div class="card-body">
                    {{-- Formulaire d'édition utilisant la méthode PATCH --}}
                    {{-- Assurez-vous d'avoir une route nommée 'notifications_taches.update' --}}
                    <form action="{{ route('notifications_taches.update', $tache->id_notification) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        {{-- Champ Titre --}}
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre de la tâche</label>
                            <input type="text" class="form-control @error('titre') is-invalid @enderror" id="titre" name="titre" value="{{ old('titre', $tache->titre) }}" required>
                            @error('titre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $tache->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Agent Assigné (lecture seule ou liste déroulante si modifiable) --}}
                        <div class="mb-3">
                            <label for="id_agent" class="form-label">Agent Assigné (ID)</label>
                            {{-- On pourrait utiliser une liste déroulante ici pour changer d'agent,
                                 mais pour l'exemple, c'est un champ en lecture seule. --}}
                            <input type="number" class="form-control" id="id_agent" name="id_agent" value="{{ $tache->id_agent }}" readonly>
                        </div>

                        {{-- Champ Suivi Par --}}
                        <div class="mb-3">
                            <label for="suivi_par" class="form-label">Responsable/Suivi par</label>
                            <input type="text" class="form-control @error('suivi_par') is-invalid @enderror" id="suivi_par" name="suivi_par" value="{{ old('suivi_par', $tache->suivi_par) }}" required>
                            @error('suivi_par')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Champ Priorité (ENUM) --}}
                            <div class="col-md-4 mb-3">
                                <label for="priorite" class="form-label">Priorité</label>
                                <select class="form-select @error('priorite') is-invalid @enderror" id="priorite" name="priorite" required>
                                    @foreach(['Faible', 'Moyenne', 'Élevée', 'Urgent'] as $priorite)
                                        <option value="{{ $priorite }}" {{ old('priorite', $tache->priorite) == $priorite ? 'selected' : '' }}>
                                            {{ $priorite }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('priorite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Champ Statut (ENUM) --}}
                            <div class="col-md-4 mb-3">
                                <label for="statut" class="form-label">Statut</label>
                                <select class="form-select @error('statut') is-invalid @enderror" id="statut" name="statut" required>
                                    @foreach(['Non lu', 'En cours', 'Complétée', 'Annulée'] as $statut)
                                        <option value="{{ $statut }}" {{ old('statut', $tache->statut) == $statut ? 'selected' : '' }}>
                                            {{ $statut }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('statut')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Champ Date d'Échéance (Datetime local pour faciliter la sélection) --}}
                            <div class="col-md-4 mb-3">
                                <label for="date_echeance" class="form-label">Date d'Échéance</label>
                                @php
                                    // Formater la date pour l'input type datetime-local (ex: 2025-12-10T15:30)
                                    $echeanceValue = $tache->date_echeance ? \Carbon\Carbon::parse($tache->date_echeance)->format('Y-m-d\TH:i') : '';
                                @endphp
                                <input type="datetime-local" class="form-control @error('date_echeance') is-invalid @enderror" id="date_echeance" name="date_echeance" value="{{ old('date_echeance', $echeanceValue) }}">
                                @error('date_echeance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Champ Lien d'action --}}
                        <div class="mb-3">
                            <label for="lien_action" class="form-label">Lien d'action (URL)</label>
                            <input type="url" class="form-control @error('lien_action') is-invalid @enderror" id="lien_action" name="lien_action" value="{{ old('lien_action', $tache->lien_action) }}">
                            @error('lien_action')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Mettre à jour la tâche</button>
                            <a href="{{ route('notifications_taches.show', $tache->id_notification) }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
