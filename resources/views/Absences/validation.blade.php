@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-dark py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-white">
                <i class="fas fa-check-circle text-warning me-2"></i>Validations des Absences en Attente
            </h5>
            <span class="badge bg-warning text-dark fw-bold px-3 py-2">{{ $absences->total() }} Demandes</span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-uppercase small fw-black">
                        <tr>
                            <th class="ps-4">Agent</th>
                            <th>Nature</th>
                            <th>Période</th>
                            <th class="text-center">Justificatif</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absences as $absence)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ strtoupper($absence->agent->nom ?? '') }}{{ $absence->agent->last_name }} {{ $absence->agent->first_name }}</div>
                                <div class="small text-muted">Matricule: {{ $absence->agent->matricule }}</div>
                            </td>

                            <td>
                                <span class="badge bg-indigo-subtle text-indigo border border-indigo p-2">
                                    {{ $absence->type->nom_type ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="fw-bold">
                                <div class="small">Du : <span class="text-primary">{{ \Carbon\Carbon::parse($absence->date_debut)->format('d/m/Y') }}</span></div>
                                <div class="small">Au : <span class="text-primary">{{ \Carbon\Carbon::parse($absence->date_fin)->format('d/m/Y') }}</span></div>
                            </td>
                            <td class="text-center">
                                @if($absence->document_justificatif)
                                    <a href="{{ asset('JustificatifAbsences/' . $absence->document_justificatif) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-file-pdf"></i> Voir Justificatif
                                    </a>
                                @else
                                    <span class="text-muted small italic">Aucun Justificatif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <!-- Bouton Approuver -->
                                    <form action="{{ route('absences.approuver', $absence->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="1">
                                        <button type="submit" class="btn btn-success btn-sm fw-bold shadow-sm px-3">
                                            <i class="fas fa-check me-1"></i> Valider
                                        </button>
                                    </form>

                                    <!-- Bouton Rejeter -->
                                    <form action="{{ route('absences.approuver', $absence->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="2">
                                        <button type="submit" class="btn btn-danger btn-sm fw-bold shadow-sm px-3">
                                            <i class="fas fa-times me-1"></i> Rejeter
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-check fa-3x mb-3 d-block"></i>
                                <span class="fw-bold">Aucune demande en attente de validation.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($absences->hasPages())
            <div class="card-footer bg-white">{{ $absences->links() }}</div>
        @endif
    </div>
</div>

<style>
    .text-indigo { color: #6610f2; }
    .bg-indigo-subtle { background-color: #f1ecff; }
    .border-indigo { border-color: #d1c4e9 !important; }
    .fw-black { font-weight: 900; }
</style>
@endsection
