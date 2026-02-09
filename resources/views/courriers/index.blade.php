@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <!-- En-tête Premium -->
                <div class="card-header d-flex justify-content-between align-items-center py-3"
                     style="background: linear-gradient(135deg, #0f172a 0%, #1e40af 100%); border-bottom: 4px solid #f59e0b;">
                    <h4 class="mb-0 text-white fw-bold">
                        <i class="fas fa-envelope-open-text me-2 text-warning"></i>{{ __('Gestion des Courriers') }}
                    </h4>
                    <a href="{{ route('courriers.create') }}" class="btn btn-warning btn-lg fw-bold shadow-lg border-white border-2">
                        <i class="fas fa-plus-circle me-1"></i> {{ __('NOUVEAU COURRIER') }}
                    </a>
                </div>

                <!-- BARRE DE FILTRES ET RECHERCHE -->
                <div class="card-body bg-white border-bottom shadow-sm py-3">
                    <form action="{{ route('courriers.index') }}" method="GET" class="row g-2 align-items-end">
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted text-uppercase">N° Enreg / Réf / Nom</label>
                            <input type="text" name="search" class="form-control form-control-sm border-primary shadow-sm" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted text-uppercase">Type</label>
                            <select name="type" class="form-select form-select-sm border-primary shadow-sm">
                                <option value="">Tous les types</option>
                                <option value="Incoming" {{ request('type') == 'Incoming' ? 'selected' : '' }}>📩 Entrant interne</option>
                                <option value="Incoming Externe" {{ request('type') == 'Incoming Externe' ? 'selected' : '' }}>📩 Entrant externe</option>
                                <option value="Incoming Mail" {{ request('type') == 'Incoming Mail' ? 'selected' : '' }}>📩 Entrant mail</option>
                                <option value="Outgoing" {{ request('type') == 'Outgoing' ? 'selected' : '' }}>📤 Sortant interne</option>
                                <option value="Outgoing Externe" {{ request('type') == 'Outgoing Externe' ? 'selected' : '' }}>📤 Sortant externe</option>
                                <option value="Outgoing Mail" {{ request('type') == 'Outgoing Mail' ? 'selected' : '' }}>📤 Sortant mail</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted text-uppercase">Statut</label>
                            <select name="statut" class="form-select form-select-sm border-primary shadow-sm">
                                <option value="">Tous les statuts</option>
                                @foreach(['affecté', 'reçu', 'Archivé'] as $st)
                                    <option value="{{ $st }}" {{ request('statut') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold shadow-sm">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <a href="{{ route('courriers.index') }}" class="btn btn-sm btn-light border w-auto px-3 shadow-sm"><i class="fas fa-sync"></i></a>
                        </div>
                    </form>
                </div>

                <div class="card-body bg-light p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white shadow-sm" style="background-color: #1e293b !important;">
                                <tr class="text-uppercase small fw-black">
                                    <th class="ps-3 py-3">ID</th>
                                    <th>N° Enreg.</th>
                                    <th>Référence</th>
                                    <th>Type</th>
                                    <th>Objet</th>

                                    <th>Statut</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white">
                                @forelse ($courriers as $courrier)
                                <tr class="border-bottom">
                                    <td class="ps-3 text-muted fw-bold small">#{{ $courrier->id }}</td>
                                    <td class="fw-bold text-primary small">{{ $courrier->num_enregistrement ?? '---' }}</td>
                                    <td><span class="badge py-2 border border-2 border-success text-success bg-white">{{ $courrier->reference }}</span></td>
                                    <td>
    @php
        // Mapping des couleurs basé sur les EXACTES "values" de votre SELECT
        $badgeStyle = match($courrier->type) {
            'Incoming'          => 'bg-primary',  // BLEU (Entrant interne)
            'Incoming Externe'  => 'bg-info',     // BLEU CLAIR
            'Incoming Mail'     => 'bg-warning',  // JAUNE
            'Outgoing'          => 'bg-success',  // VERT (Sortant interne)
            'Outgoing Externe'  => 'bg-danger',   // ROUGE
            'Outgoing Mail'     => 'bg-dark',     // NOIR
            default             => 'bg-secondary' // GRIS
        };
    @endphp

    <span class="badge py-2 px-3 shadow-sm {{ $badgeStyle }} text-white fw-bold">
        {{-- Icône dynamique : Entrant vs Sortant --}}
        <i class="fas {{ str_contains($courrier->type, 'Incoming') ? 'fa-file-import' : 'fa-file-export' }} me-1"></i>

        {{-- Traduction visuelle pour l'utilisateur --}}
        @php
            $labels = [
                'Incoming' => 'ENTRANT INTERNE',
                'Incoming Externe' => 'ENTRANT EXTERNE',
                'Incoming Mail' => 'ENTRANT MAIL',
                'Outgoing' => 'SORTANT INTERNE',
                'Outgoing Externe' => 'SORTANT EXTERNE',
                'Outgoing Mail' => 'SORTANT MAIL',
            ];
            echo $labels[$courrier->type] ?? strtoupper($courrier->type);
        @endphp
    </span>
</td>
                                    <td class="small fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $courrier->objet }}</td>

                                    <td>
                                        {{-- MODIFICATION : Statut en blanc sur couleur --}}
                                        @php
                                            $statutColor = match($courrier->statut) {
                                                'reçu' => 'bg-primary',
                                                'affecté' => 'bg-success',
                                                'Archivé' => 'bg-dark',
                                                'en_attente' => 'bg-warning text-dark',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $statutColor }} text-white px-3 py-2 shadow-sm fw-bold text-uppercase" style="min-width: 90px; font-size: 0.7rem;">
                                            {{ $courrier->statut }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group gap-1">
                                            <a href="{{ route('imputations.create', [
                                                    'courrier_id' => $courrier->id,
                                                    'chemin_fichier' => $courrier->fichier_chemin
                                                ]) }}"
                                               class="btn btn-sm btn-info text-white fw-bold shadow-sm"
                                               title="Imputer ce courrier">
                                                <i class="fas fa-share-square me-1"></i> IMPUTER
                                            </a>

                                            <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-light border shadow-sm"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-sm btn-outline-primary shadow-sm"><i class="fas fa-edit"></i></a>

                                            <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted italic">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>
                                        Aucun courrier trouvé pour l'année 2026.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($courriers->hasPages())
                <div class="card-footer bg-white py-3 border-top">
                    {{ $courriers->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
