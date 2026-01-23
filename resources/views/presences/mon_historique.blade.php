@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <!-- BOUTON FERMER -->
            <a href="{{ url('/home') }}" class="btn btn-outline-danger me-3 shadow-sm" title="Fermer et quitter">
                <i class="fas fa-times"></i>
            </a>
            <h3 class="mb-0 fw-bold text-dark">Mon Historique de Présences</h3>
        </div>
        <a href="{{ route('presences.monPointage') }}" class="btn btn-primary shadow-sm px-4">
            <i class="fas fa-clock"></i> Aller au Pointage
        </a>
    </div>

    <!-- ZONE DE FILTRES -->
    <div class="card shadow-sm border-0 mb-4 bg-light">
        <div class="card-body">
            <form action="{{ route('presences.monHistorique') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Du (Début)</label>
                    <input type="date" name="date_debut" class="form-control" value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Au (Fin)</label>
                    <input type="date" name="date_fin" class="form-control" value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-uppercase text-muted">Statut</label>
                    <select name="statut" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="Présent" {{ request('statut') == 'Présent' ? 'selected' : '' }}>Présent</option>
                        <option value="En Retard" {{ request('statut') == 'En Retard' ? 'selected' : '' }}>En Retard</option>
                        <option value="Absent" {{ request('statut') == 'Absent' ? 'selected' : '' }}>Absent</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="fas fa-filter me-1"></i> Filtrer
                    </button>
                    <a href="{{ route('presences.monHistorique') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- ... (Reste du tableau inchangé) ... -->
    <div class="card shadow border-0 rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-white">
                        <tr>
                            <th class="py-3 ps-4 border-0">Date</th>
                            <th class="py-3 border-0">Arrivée</th>
                            <th class="py-3 border-0">Départ</th>
                            <th class="py-3 border-0 text-center">Statut</th>
                            <th class="py-3 pe-4 border-0">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mesPresences as $p)
                            <tr class="border-bottom">
                                <td class="ps-4 fw-bold text-secondary">
                                    <i class="far fa-calendar-alt me-2 text-primary"></i>
                                    {{ \Carbon\Carbon::parse($p->heure_arrivee)->translatedFormat('d M 2026') }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border p-2 fw-normal">
                                        <i class="far fa-clock me-1 text-success"></i>
                                        {{ \Carbon\Carbon::parse($p->heure_arrivee)->format('H:i') }}
                                    </span>
                                </td>
                                <td>
                                    @if($p->heure_depart)
                                        <span class="badge bg-light text-dark border p-2 fw-normal">
                                            <i class="far fa-clock me-1 text-danger"></i>
                                            {{ \Carbon\Carbon::parse($p->heure_depart)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark animate-pulse shadow-sm px-2 fw-normal">
                                            <i class="fas fa-spinner fa-spin me-1"></i> En cours
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->statut == 'Présent')
                                        <span class="badge bg-success text-white px-3 py-2 w-75">Présent</span>
                                    @elseif($p->statut == 'En Retard')
                                        <span class="badge bg-danger text-white px-3 py-2 w-75">En Retard</span>
                                    @else
                                        <span class="badge bg-secondary text-white px-3 py-2 w-75">{{ $p->statut }}</span>
                                    @endif
                                </td>
                                <td class="pe-4 text-muted small italic">
                                    {{ $p->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-5 text-muted">Aucun résultat trouvé.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
