@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- En-tête avec dégradé -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 p-3 bg-white shadow-sm rounded-left" style="border-left: 5px solid #4e73df;">
        <h1 class="h3 mb-0 text-gray-800 font-weight-bold">
            <i class="fas fa-plus-circle text-primary mr-2"></i>Nouvelle Annonce
        </h1>
        <a href="{{ route('annonces.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill shadow-sm">
            <i class="fas fa-arrow-left fa-sm mr-1"></i> Retour à la liste
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0">
                <!-- En-tête de carte coloré -->
                <div class="card-header py-3 bg-gradient-primary">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-pen-fancy mr-2"></i>Rédiger le contenu de l'annonce</h6>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('annonces.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- Section Gauche -->
                            <div class="col-md-8">
                                {{-- Champ Titre --}}
                                <div class="form-group mb-4">
                                    <label class="text-dark font-weight-bold" for="titre">Titre de l'annonce</label>
                                    <input type="text" class="form-control form-control-user border-left-primary @error('titre') is-invalid @enderror"
                                           id="titre" name="titre" placeholder="Ex: Réunion de service..." value="{{ old('titre') }}" required>
                                    @error('titre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Champ Contenu --}}
                                <div class="form-group mb-4">
                                    <label class="text-dark font-weight-bold" for="contenu">Détails du message</label>
                                    <textarea class="form-control border-left-primary @error('contenu') is-invalid @enderror"
                                              id="contenu" name="contenu" rows="8" placeholder="Écrivez votre texte ici..." required>{{ old('contenu') }}</textarea>
                                    @error('contenu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Section Droite (Options) -->
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded shadow-sm border">
                                    <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-sliders-h mr-2"></i>Paramètres</h6>

                                    {{-- Champ Type --}}
                                    <div class="form-group">
                                        <label class="small font-weight-bold" for="type">Catégorie</label>
                                        <select id="type" name="type" class="form-control border-left-info @error('type') is-invalid @enderror" required>
                                            <option value="information" class="text-info">🔵 Information</option>
                                            <option value="urgent" class="text-danger">🔴 Urgent</option>
                                            <option value="evenement" class="text-success">🟢 Événement</option>
                                            <option value="avertissement" class="text-warning">🟡 Avertissement</option>
                                        </select>
                                    </div>

                                    {{-- Champ Expiration --}}
                                    <div class="form-group">
                                        <label class="small font-weight-bold" for="expires_at">Expire le :</label>
                                        <input type="date" class="form-control border-left-warning @error('expires_at') is-invalid @enderror"
                                               id="expires_at" name="expires_at" value="{{ old('expires_at') }}">
                                    </div>

                                    {{-- Switch Actif/Inactif --}}
                                    <div class="form-group">
                                        <label class="small font-weight-bold">Statut de publication</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
                                            <label class="custom-control-label text-success font-weight-bold" for="is_active">Actif par défaut</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pied de formulaire avec boutons -->
                        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
                            <button type="reset" class="btn btn-light mr-2">Effacer</button>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="fas fa-paper-plane mr-2"></i>Publier l'annonce
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styles pour améliorer le visuel en 2026 */
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 10px rgba(78, 115, 223, 0.15);
    }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .bg-gradient-primary { background: linear-gradient(180deg, #4e73df 10%, #224abe 100%); }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }
</style>
@endsection
