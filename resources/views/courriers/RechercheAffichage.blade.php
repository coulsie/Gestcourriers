@extends('layouts.app')

@section('title', "Recherche de Courriers")

@section('content')
<div class="container-fluid py-4">
    <h1 class="mb-4 text-primary fw-bold"><i class="fas fa-search me-2"></i>Recherche de Courriers</h1>

    {{-- Formulaire de Recherche Avancée --}}
    <div class="card mb-4 border-0 shadow-sm no-print">
        <div class="card-header bg-indigo text-white fw-bold" style="background-color: #4e73df;">
            <i class="fas fa-filter me-2"></i>Formulaire de Recherche Avancée
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('courriers.RechercheAffichage') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="search_term" class="form-label fw-bold text-secondary">Référence, Objet ou Noms</label>
                    <input type="text" name="search_term" class="form-control" value="{{ request('search_term') }}" placeholder="Mot clé...">
                </div>
                <div class="col-md-3">
                    <label for="statut" class="form-label fw-bold text-secondary">Statut Global</label>
                    <select name="statut" class="form-select border-primary">
                        <option value="">Tous les statuts</option>
                        @foreach(['reçu', 'affecté', 'archivé'] as $statut)
                           <option value="{{ $statut }}" {{ request('statut') == $statut ? 'selected' : '' }}>{{ ucfirst($statut) }}</option>
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
                    <button type="submit" class="btn btn-primary w-100 fw-bold">OK</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Résultats de la recherche --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="fas fa-list me-2"></i>{{ $courriers->total() }} Courriers trouvés</span>
            <button onclick="window.print()" class="btn btn-light btn-sm fw-bold no-print"><i class="fas fa-print me-1"></i>Imprimer</button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="courriersTable">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-3">Référence</th>
                            <th>Type</th>
                            <th>Objet</th>
                            <th>Expéditeur</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th class="text-center no-print">Actions</th>
                        </tr>
                        {{-- Filtres de colonnes --}}
                        <tr class="bg-warning-subtle no-print">
                            <td class="ps-3"><input type="text" id="filterReference" class="form-control form-control-sm" placeholder="Réf..."></td>
                            <td><input type="text" id="filterType" class="form-control form-control-sm" placeholder="Type..."></td>
                            <td><input type="text" id="filterObjet" class="form-control form-control-sm" placeholder="Objet..."></td>
                            <td><input type="text" id="filterExpediteur" class="form-control form-control-sm" placeholder="Nom..."></td>
                            <td>
                                 <select id="filterStatut" class="form-select form-select-sm">
                                    <option value="">Tous</option>
                                    @foreach(['reçu', 'affecté', 'archivé'] as $statut)
                                       <option value="{{ $statut }}">{{ ucfirst($statut) }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="date" id="filterDate" class="form-control form-control-sm"></td>
                            <td class="text-center pe-3">
                                <button type="button" onclick="resetColumnFilters()" class="btn btn-warning btn-sm w-100 text-white"><i class="fas fa-sync-alt"></i></button>
                            </td>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($courriers as $courrier)
                            <tr class="courrier-row border-bottom">
                                <td class="ps-3 ref-cell fw-bold text-primary">{{ $courrier->reference }}</td>
                                <td class="type-cell">
                                    <span class="badge border {{ $courrier->type === 'entrant' ? 'text-primary border-primary' : 'text-info border-info' }}">
                                        {{ ucfirst($courrier->type) }}
                                    </span>
                                </td>
                                <td class="objet-cell text-truncate" style="max-width: 200px;">{{ $courrier->objet }}</td>
                                <td class="expediteur-cell text-secondary">{{ $courrier->expediteur_nom }}</td>
                                <td class="statut-cell" data-statut="{{ $courrier->statut }}">
                                    @php
                                        $badgeClass = match($courrier->statut) {
                                            'reçu'    => 'bg-danger text-white',
                                            'affecté' => 'bg-success text-white',
                                            'archivé' => 'bg-secondary text-white',
                                            default   => 'bg-dark text-white',
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill shadow-sm w-100">
                                        {{ ucfirst($courrier->statut) }}
                                    </span>
                                </td>
                                <td class="date-cell" data-date="{{ \Carbon\Carbon::parse($courrier->date_courrier)->format('Y-m-d') }}">
                                    {{ \Carbon\Carbon::parse($courrier->date_courrier)->format('d/m/Y') }}
                                </td>
                                <td class="text-center no-print">
                                    {{-- Boutons d'actions --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bloc Pagination --}}
            <div class="card-footer bg-white border-top-0 p-3 no-print">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-secondary small">
                        Affichage de {{ $courriers->firstItem() }} à {{ $courriers->lastItem() }} sur {{ $courriers->total() }} courriers
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            @if ($courriers->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">Précédent</span></li>
                            @else
                                <li class="page-item"><a class="page-link shadow-sm" href="{{ $courriers->appends(request()->query())->previousPageUrl() }}">Précédent</a></li>
                            @endif

                            @foreach ($courriers->getUrlRange(max(1, $courriers->currentPage() - 2), min($courriers->lastPage(), $courriers->currentPage() + 2)) as $page => $url)
                                <li class="page-item {{ $page == $courriers->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $courriers->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                </li>
                            @endforeach

                            @if ($courriers->hasMorePages())
                                <li class="page-item"><a class="page-link shadow-sm" href="{{ $courriers->appends(request()->query())->nextPageUrl() }}">Suivant</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">Suivant</span></li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('courriersTable');
    const inputs = table.querySelectorAll('thead input, thead select');

    inputs.forEach(input => {
        input.addEventListener('keyup', filterTable);
        input.addEventListener('change', filterTable);
    });

    function filterTable() {
        const rows = table.querySelectorAll('tbody .courrier-row');
        const fRef = document.getElementById('filterReference').value.toLowerCase();
        const fType = document.getElementById('filterType').value.toLowerCase();
        const fObjet = document.getElementById('filterObjet').value.toLowerCase();
        const fExp = document.getElementById('filterExpediteur').value.toLowerCase();
        const fStatut = document.getElementById('filterStatut').value.toLowerCase();
        const fDate = document.getElementById('filterDate').value;

        rows.forEach(row => {
            const matches = row.querySelector('.ref-cell').textContent.toLowerCase().includes(fRef) &&
                            row.querySelector('.type-cell').textContent.toLowerCase().includes(fType) &&
                            row.querySelector('.objet-cell').textContent.toLowerCase().includes(fObjet) &&
                            row.querySelector('.expediteur-cell').textContent.toLowerCase().includes(fExp) &&
                            (fStatut === "" || row.querySelector('.statut-cell').getAttribute('data-statut').toLowerCase() === fStatut) &&
                            (fDate === "" || row.querySelector('.date-cell').getAttribute('data-date') === fDate);
            row.style.display = matches ? '' : 'none';
        });
    }
});

function resetColumnFilters() {
    document.querySelectorAll('#courriersTable thead input, #courriersTable thead select').forEach(i => i.value = '');
    document.querySelectorAll('.courrier-row').forEach(r => r.style.display = '');
}
</script>
@endsection
