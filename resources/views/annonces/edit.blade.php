@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- En-tête avec bordure orange (Warning) -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 p-3 bg-white shadow-sm rounded-left" style="border-left: 5px solid #f6c23e;">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-edit text-warning mr-2"></i>Modifier l'Annonce #{{ $annonce->id }}
        </h1>
        <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Annuler et retourner
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <!-- En-tête de carte avec dégradé orange -->
                <div class="card-header py-3 bg-gradient-warning border-0">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-save mr-2"></i>Mise à jour des informations</h6>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('annonces.update', $annonce->id) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Indispensable pour la modification --}}

                        <div class="row">
                            <!-- Section Gauche : Contenu principal -->
                            <div class="col-md-8">
                                {{-- Champ Titre --}}
                                <div class="form-group mb-4">
                                    <label class="text-dark font-weight-bold" for="titre">Titre de l'annonce</label>
                                    <input type="text" class="form-control border-left-warning @error('titre') is-invalid @enderror"
                                           id="titre" name="titre" value="{{ old('titre', $annonce->titre) }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Champ Contenu --}}
                                <div class="form-group mb-4">
                                    <label class="text-dark font-weight-bold" for="contenu">Détails du message</label>
                                    <textarea class="form-control border-left-warning @error('contenu') is-invalid @enderror"
                                              id="contenu" name="contenu" rows="8" required>{{ old('contenu', $annonce->contenu) }}</textarea>
                                    @error('contenu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section Droite : Paramètres -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded shadow-sm border" style="border-top: 3px solid #f6c23e;">
                                    <h6 class="font-weight-bold text-warning mb-3"><i class="fas fa-cog mr-2"></i>Configuration</h6>

                                    {{-- Champ Type --}}
                                    <div class="form-group">
                                        <label class="small font-weight-bold" for="type">Catégorie actuelle</label>
                                        <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                                            <option value="information" {{ $annonce->type == 'information' ? 'selected' : '' }}>🔵 Information</option>
                                            <option value="urgent" {{ $annonce->type == 'urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                                            <option value="evenement" {{ $annonce->type == 'evenement' ? 'selected' : '' }}>🟢 Événement</option>
                                            <option value="avertissement" {{ $annonce->type == 'avertissement' ? 'selected' : '' }}>🟡 Avertissement</option>
                                        </select>
                                    </div>

                                    {{-- Champ Expiration --}}
                                    <div class="form-group">
                                        <label class="small font-weight-bold" for="expires_at">Expire le :</label>
                                        <input type="date" class="form-control @error('expires_at') is-invalid @enderror"
                                               id="expires_at" name="expires_at"
                                               value="{{ $annonce->expires_at ? \Carbon\Carbon::parse($annonce->expires_at)->format('Y-m-d') : '' }}">
                                        <small class="text-muted">Laisser vide pour permanent</small>
                                    </div>

                                    {{-- Switch Actif/Inactif --}}
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Statut de l'annonce</label>
                                        <div class="custom-control custom-switch">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ $annonce->is_active ? 'checked' : '' }}>
                                            <label class="custom-control-label {{ $annonce->is_active ? 'text-success' : 'text-danger' }} font-weight-bold" for="is_active">
                                                {{ $annonce->is_active ? 'En ligne' : 'Désactivée' }}
                                            </label>
                                        </div>
                                    </div>

                                    <hr>
                                    <p class="small text-muted text-center">
                                        Dernière modification : <br>
                                        <strong>{{ $annonce->updated_at->format('d/m/Y H:i') }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Pied de formulaire -->
                        <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <span class="text-muted small italic">ID Unique: {{ $annonce->id }}</span>
                            <div>
                                <a href="{{ route('annonces.index') }}" class="btn btn-light mr-2">Abandonner</a>
                                <button type="submit" class="btn btn-warning px-5 shadow-sm font-weight-bold">
                                    <i class="fas fa-sync-alt mr-2"></i>Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles spécifiques 2026 */
    .bg-gradient-warning { background: linear-gradient(180deg, #f6c23e 10%, #dda20a 100%); }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .form-control:focus {
        border-color: #f6c23e;
        box-shadow: 0 0 10px rgba(246, 194, 62, 0.2);
    }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
</style>
@endsection
