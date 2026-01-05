@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0">
        <!-- En-tête avec dégradé -->
        <div class="card-header bg-gradient bg-primary text-white py-3">
            <h3 class="mb-0 h5"><i class="fas fa-bell me-2"></i>Créer une nouvelle notification</h3>
        </div>

        <div class="card-body p-4">
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('notifications.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Colonne Gauche : Informations de base -->
                    <div class="col-md-6 px-4">
                        <h5 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-2"></i>Contenu</h5>

                        <div class="mb-4">
                            <label for="agent_id" class="form-label fw-bold">Agent assigné</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-user-tie text-primary"></i></span>
                                <select id="agent_id" name="agent_id" class="form-select @error('agent_id') is-invalid @enderror" required>
                                    <option value="">Sélectionner un agent...</option>
                                    @foreach($agents as $agent)
                                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                            {{ strtoupper($agent->last_name) }} {{ $agent->first_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="titre" class="form-label fw-bold">Titre de la tâche</label>
                            <input type="text" name="titre" id="titre" class="form-control border-primary-subtle" placeholder="Ex: Rapport mensuel" value="{{ old('titre') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold">Description détaillée</label>
                            <textarea name="description" id="description" class="form-control" rows="5" placeholder="Détaillez la notification ici..." required>{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Colonne Droite : Paramètres et Urgence -->
                    <div class="col-md-6 px-4 border-start">
                        <h5 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-sliders-h me-2"></i>Paramètres</h5>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="priorite" class="form-label fw-bold text-danger">Niveau de Priorité</label>
                                <select name="priorite" id="priorite" class="form-select border-danger-subtle fw-bold" required>
                                    <option value="Faible" class="text-secondary">⚪ Faible</option>
                                    <option value="Moyenne" class="text-info" selected>🔵 Moyenne</option>
                                    <option value="Élevée" class="text-warning">🟠 Élevée</option>
                                    <option value="Urgent" class="text-danger">🔴 Urgent</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="statut" class="form-label fw-bold text-success">Statut Initial</label>
                                <select name="statut" id="statut" class="form-select border-success-subtle fw-bold" required>
                                    <option value="Non lu" class="text-danger">🆕 Non lu</option>
                                    <option value="En cours" class="text-primary">⏳ En cours</option>
                                    <option value="Complétée" class="text-success">✅ Complétée</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="date_echeance" class="form-label fw-bold">Date d'échéance</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <input type="datetime-local" name="date_echeance" id="date_echeance" class="form-control" value="{{ old('date_echeance') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="suivi_par" class="form-label fw-bold">Responsable du suivi</label>
                            <input type="text" name="suivi_par" id="suivi_par" class="form-control" value="{{ old('suivi_par', auth()->user()?->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="document" class="form-label fw-bold">Fichier joint (Optionnel)</label>
                            <div class="input-group">
                                <input type="file" name="document" id="document" class="form-control shadow-sm">
                            </div>
                            <small class="text-muted">PDF, Image ou Doc (Max 5Mo)</small>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light d-flex justify-content-end py-3 mt-4 gap-2">
                    <a href="{{ route('notifications.index') }}" class="btn btn-danger px-4">
                        <i class="fas fa-times me-1"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success px-5 shadow">
                        <i class="fas fa-paper-plane me-1"></i> Envoyer la notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Styles personnalisés pour améliorer l'UI */
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    .bg-gradient-primary {
        background: linear-gradient(45deg, #0d6efd, #0043a8);
    }
    .input-group-text {
        background-color: #f8f9fa;
    }
</style>
@endsection
