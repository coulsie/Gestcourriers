@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-lg">
                <!-- En-tête -->
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-envelope-open-text me-2"></i>{{ __('Gestion des Courriers') }}</h4>
                    <a href="{{ route('courriers.create') }}" class="btn btn-warning btn-lg fw-bold shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i> {{ __('Nouveau Courrier') }}
                    </a>
                </div>

                <div class="card-body bg-light">
                    <div class="table-responsive rounded bg-white shadow-sm">
                        <form action="{{ route('courriers.index') }}" method="GET">
                            <table class="table table-hover align-middle mb-0" style="font-size: 1.1rem;">
                                <thead class="table-primary text-primary">
                                    <tr class="text-uppercase" style="font-size: 0.9rem;">
                                        <th>ID</th>
                                        <th>Référence</th>
                                        <th>Type</th>
                                        <th>Objet</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th class="text-center">Actions & Imputation</th>
                                    </tr>
                                    <tr class="bg-light">
                                        <td></td>
                                        <td><input type="text" name="reference" class="form-control" placeholder="Réf..." value="{{ request('reference') }}" onchange="this.form.submit()"></td>
                                        <td>
                                            <select name="type" class="form-select fw-bold" onchange="this.form.submit()">
                                                <option value="">Tous</option>
                                                <option value="Incoming" {{ request('type') == 'Incoming' ? 'selected' : '' }}>Entrant</option>
                                                <option value="Outgoing" {{ request('type') == 'Outgoing' ? 'selected' : '' }}>Sortant</option>
                                            </select>
                                        </td>
                                        <td></td>
                                        <td>
                                            <select name="statut" class="form-select fw-bold" onchange="this.form.submit()">
                                                <option value="">Tous</option>
                                                @foreach(['affecté', 'reçu', 'en_traitement', 'traité'] as $st)
                                                    <option value="{{ $st }}" {{ request('statut') == $st ? 'selected' : '' }}>{{ ucfirst($st) }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td colspan="2" class="text-center">
                                            <a href="{{ route('courriers.index') }}" class="btn btn-sm btn-secondary rounded-pill">
                                                <i class="fas fa-sync-alt me-1"></i> Réinitialiser
                                            </a>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody class="fw-bold">
                                    @forelse ($courriers as $courrier)
                                    <tr>
                                        <td class="text-muted small">#{{ $courrier->id }}</td>
                                        <!-- RÉFÉRENCE : ÉCRITURE BLANCHE -->
                                        <td><span class="badge bg-success px-3 py-2 fs-6 shadow-sm text-white">{{ $courrier->reference }}</span></td>

                                        <td>
                                            @if($courrier->type == 'Incoming')
                                                <span class="text-primary"><i class="fas fa-arrow-circle-down"></i> ENTRANT</span>
                                            @else
                                                <span class="text-warning"><i class="fas fa-arrow-circle-up"></i> SORTANT</span>
                                            @endif
                                        </td>

                                        <td class="text-dark" style="max-width: 250px;">{{ Str::limit($courrier->objet, 50) }}</td>

                                        <!-- STATUT : ÉCRITURE BLANCHE -->
                                        <td>
                                            @php
                                                $color = match(strtolower($courrier->statut)) {
                                                    'affecté', 'affecte' => 'success',
                                                    'reçu', 'recu'       => 'danger',
                                                    'en_traitement'      => 'warning',
                                                    'traité', 'traite'   => 'info',
                                                    default              => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill shadow-sm text-white text-uppercase" style="font-size: 0.85rem;">
                                                {{ $courrier->statut }}
                                            </span>
                                        </td>

                                        <td class="text-nowrap">{{ $courrier->date_courrier->format('d/m/Y') }}</td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-info btn-lg text-white shadow-sm" title="Consulter">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <a href="{{ route('imputations.create', ['courrier_id' => $courrier->id]) }}"
                                                   class="btn btn-primary btn-lg shadow-sm text-white"
                                                   title="Imputer (Transférer)">
                                                    <i class="fas fa-file-export"></i>
                                                </a>

                                                <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-warning btn-lg text-white shadow-sm" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-lg shadow-sm text-white" onclick="return confirm('Supprimer ?')">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            @if($courrier->chemin_fichier)
                                                <div class="mt-2">
                                                    <a href="{{ asset('Documents/' . $courrier->chemin_fichier) }}" target="_blank" class="btn btn-sm btn-outline-danger fw-bold border-2">
                                                        <i class="fas fa-file-pdf"></i> VOIR LE PDF
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5 fs-4 text-muted">Aucun courrier trouvé.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
<!-- LIGNE DE PAGINATION PERSONNALISÉE -->
                <div class="d-flex justify-content-between align-items-center mt-4 px-3 pb-3">
                    <div class="text-muted fw-bold">
                        Affichage de {{ $courriers->firstItem() }} à {{ $courriers->lastItem() }} sur {{ $courriers->total() }}
                    </div>

                    <ul class="pagination mb-0 shadow-sm">
                        {{-- Lien Précédent --}}
                        <li class="page-item {{ $courriers->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link fw-bold px-4 py-2" href="{{ $courriers->appends(request()->query())->previousPageUrl() }}" style="border-radius: 50px 0 0 50px;">
                                <i class="fas fa-chevron-left me-1"></i> Précédent
                            </a>
                        </li>

                        {{-- Lien Suivant --}}
                        <li class="page-item {{ !$courriers->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link fw-bold px-4 py-2" href="{{ $courriers->appends(request()->query())->nextPageUrl() }}" style="border-radius: 0 50px 50px 0;">
                                Suivant <i class="fas fa-chevron-right ms-1"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
