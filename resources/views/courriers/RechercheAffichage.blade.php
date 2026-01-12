@extends('layouts.app')

@section('title', "Recherche de Courriers")

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4 text-primary fw-bold"><i class="fas fa-search me-2"></i>Recherche de Courriers</h1>

    {{-- Formulaire de Recherche Avancée (Couleur Dark/Indigo) --}}
    <div class="card mb-4 border-0 shadow-sm no-print">
        <div class="card-header bg-indigo text-white fw-bold" style="background-color: #4e73df;">
            <i class="fas fa-filter me-2"></i>Formulaire de Recherche Avancée
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('courriers.RechercheAffichage') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search_term" class="form-label fw-bold text-secondary">Référence, Objet ou Noms</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-keyboard text-muted"></i></span>
                        <input type="text" name="search_term" id="search_term" class="form-control border-start-0" value="{{ request('search_term') }}" placeholder="Mot clé...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="statut" class="form-label fw-bold text-secondary">Statut Global</label>
                    <select name="statut" id="statut" class="form-select border-primary">
                        <option value="">Tous les statuts</option>
                        @foreach(['reçu','en_traitement','traité','archivé','affecté'] as $statut)
                           <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-secondary">Date Début</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold text-secondary">Date Fin</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-1 align-self-end">
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">OK</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Résultats de la recherche (Couleur Success/Green) --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="fas fa-list me-2"></i>{{ $courriers->total() }} Courriers trouvés</span>
            <button onclick="window.print()" class="btn btn-light btn-sm fw-bold no-print"><i class="fas fa-print me-1"></i>Imprimer</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="courriersTable">
                    <thead class="table-dark">
                        <tr class="text-uppercase small">
                            <th class="ps-3">Référence</th>
                            <th>Type</th>
                            <th>Objet</th>
                            <th>Expéditeur</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center no-print">Actions</th>
                        </tr>
                        {{-- LIGNE DE FILTRES AVEC FOND COULEUR DOUCE --}}
                        <tr class="bg-warning-subtle no-print">
                            <td class="ps-3"><input type="text" id="filterReference" class="form-control form-control-sm border-warning" placeholder="Réf..."></td>
                            <td><input type="text" id="filterType" class="form-control form-control-sm border-warning" placeholder="Type..."></td>
                            <td><input type="text" id="filterObjet" class="form-control form-control-sm border-warning" placeholder="Objet..."></td>
                            <td><input type="text" id="filterExpediteur" class="form-control form-control-sm border-warning" placeholder="Nom..."></td>
                            <td>
                                 <select id="filterStatut" class="form-select form-select-sm border-warning">
                                    <option value="">Tous</option>
                                    @foreach(['reçu','en_traitement','traité','archivé','affecté'] as $statut)
                                       <option value="{{ $statut }}">{{ ucfirst(str_replace('_', ' ', $statut)) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="date" id="filterDate" class="form-control form-control-sm border-warning"></td>
                            <td class="text-center pe-3">
                                <button type="button" onclick="resetColumnFilters()" class="btn btn-warning btn-sm w-100 text-white shadow-sm" title="Réinitialiser">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($courriers as $courrier)
                            <tr class="courrier-row border-bottom">
                                <td class="ps-3 ref-cell fw-bold text-primary">{{ $courrier->reference }}</td>
                                <td class="type-cell">
                                    <span class="badge {{ $courrier->type === 'entrant' ? 'bg-outline-primary text-primary border border-primary' : 'bg-outline-info text-info border border-info' }} px-2">
                                        {{ $courrier->type === 'entrant' ? 'Entrant' : 'Sortant' }}
                                    </span>
                                </td>
                                <td class="objet-cell text-truncate" style="max-width: 200px;">{{ $courrier->objet }}</td>
                                <td class="expediteur-cell text-secondary">{{ $courrier->expediteur_nom }}</td>
                                <td class="statut-cell" data-statut="{{ $courrier->statut }}">
                                    @php
                                        $badgeClass = match($courrier->statut) {
                                            'reçu'          => 'bg-danger text-white',
                                            'affecté'       => 'bg-success text-white',
                                            'en_traitement' => 'bg-primary text-white',
                                            'traité'        => 'bg-info text-white',
                                            'archivé'       => 'bg-secondary text-white',
                                            default         => 'bg-dark text-white',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill shadow-sm w-100">
                                        {{ ucfirst(str_replace('_', ' ', $courrier->statut)) }}
                                    </span>
                                </td>
                                <td class="date-cell" data-date="{{ \Carbon\Carbon::parse($courrier->date_courrier)->format('Y-m-d') }}">
                                    <i class="far fa-calendar-alt me-1 text-muted"></i>{{ \Carbon\Carbon::parse($courrier->date_courrier)->format('d/m/Y') }}
                                </td>
                                <td class="text-center pe-3 no-print">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ asset('storage/' . $courrier->chemin_fichier) }}" class="btn btn-sm btn-outline-danger" target="_blank" title="PDF">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                         <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-primary text-white" title="Voir/Affecter">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0 py-3 no-print">
            <div class="d-flex justify-content-center">
                {{ $courriers->appends(request()->except('page'))->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
