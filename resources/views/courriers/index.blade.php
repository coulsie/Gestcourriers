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
                            <label class="small fw-bold text-muted text-uppercase">Référence / Nom</label>
                            <input type="text" name="search" class="form-control form-control-sm border-primary" placeholder="Rechercher..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted text-uppercase">Type</label>
                            <select name="type" class="form-select form-select-sm border-primary">
                                <option value="">Tous les types</option>
                                <option value="Incoming" {{ request('type') == 'Incoming' ? 'selected' : '' }}>📩 Entrant</option>
                                <option value="Outgoing" {{ request('type') == 'Outgoing' ? 'selected' : '' }}>📤 Sortant</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted text-uppercase">Statut</label>
                            <select name="statut" class="form-select form-select-sm border-primary">
                                <option value="">Tous les statuts</option>
                                @foreach(['affecté', 'reçu', 'Archivé'] as $st)
                                    <option value="{{ $st }}" {{ request('statut') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- FILTRE PAR DATE --}}
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted text-uppercase">Du (Date)</label>
                            <input type="date" name="date_debut" class="form-control form-control-sm border-primary" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="small fw-bold text-muted text-uppercase">Au (Date)</label>
                            <input type="date" name="date_fin" class="form-control form-control-sm border-primary" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-2 d-flex gap-1">
                            <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">
                                <i class="fas fa-filter"></i> Filtrer
                            </button>
                            <a href="{{ route('courriers.index') }}" class="btn btn-sm btn-danger px-3 shadow-sm" title="Réinitialiser">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </form>
                </div>

                <div class="card-body bg-light p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-dark text-white shadow-sm" style="background-color: #1e293b !important;">
                                <tr class="text-uppercase small fw-black">
                                    <th class="ps-3 py-3">ID</th>
                                    <th>Référence</th>
                                    <th>Type</th>
                                    <th>Expéditeur</th>
                                    <th>Objet du Courrier</th>
                                    <th>Destinataire</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white">
                                @forelse ($courriers as $courrier)
                                <tr class="border-bottom">
                                    <td class="ps-3 text-muted fw-bold small">#{{ $courrier->id }}</td>
                                    <td style="min-width: 140px;">
                                        <span class="badge w-100 py-2 shadow-sm border border-2 border-success text-success bg-white fw-black">
                                            {{ $courrier->reference }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($courrier->type == 'Incoming')
                                            <span class="badge w-100 py-2 text-white bg-primary border-primary small">ENTRANT</span>
                                        @else
                                            <span class="badge w-100 py-2 text-white bg-warning border-warning small">SORTANT</span>
                                        @endif
                                    </td>
                                    <td class="fw-bold text-indigo small">{{ Str::limit($courrier->expediteur_nom, 20) }}</td>
                                    <td class="text-dark fw-bold small" style="max-width: 200px;">{{ Str::limit($courrier->objet, 45) }}</td>
                                    <td class="fw-bold text-dark small">{{ Str::limit($courrier->destinataire_nom, 20) }}</td>
                                    <td>
                                        @php
                                            $color = match(strtolower($courrier->statut)) {
                                                'affecté', 'affecte' => '#198754',
                                                'reçu', 'recu'       => '#dc3545',
                                                'archivé'      => '#6c757d',
                                                default              => '#0dcaf0',
                                            };
                                        @endphp
                                        <span class="badge w-100 py-2 rounded-3 text-white text-uppercase shadow-sm" style="background-color: {{ $color }}; font-size: 0.7rem;">
                                            {{ $courrier->statut }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap fw-bold text-secondary small">{{ $courrier->date_courrier->format('d/m/Y') }}</td>
                                    <td class="text-center py-3">
                                        <div class="btn-group shadow-sm">
                                            <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-sm btn-info text-white"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('imputations.create', ['courrier_id' => $courrier->id]) }}" class="btn btn-sm btn-primary text-white"><i class="fas fa-file-signature"></i></a>
                                            <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-sm btn-warning text-white"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger text-white" onclick="return confirm('Supprimer ?')"><i class="fas fa-trash-alt"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 bg-light fw-bold text-muted">
                                        <i class="fas fa-search me-2"></i> Aucun courrier ne correspond à vos critères.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .fw-black { font-weight: 900 !important; }
    .text-indigo { color: #4e73df; }
    .form-control-sm, .form-select-sm { border-width: 2px; }
    .form-control-sm:focus { box-shadow: none; border-color: #f59e0b; }
</style>
@endsection
