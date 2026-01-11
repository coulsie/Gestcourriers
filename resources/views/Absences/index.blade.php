@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            {{-- Card avec bordure supérieure colorée --}}
            <div class="card shadow-lg border-0 border-top border-4 border-primary">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary fw-bold">
                        <i class="fas fa-calendar-alt me-2"></i>Gestion des Absences
                    </h4>
                    <a href="{{ route('absences.create') }}" class="btn btn-primary shadow-sm">
                        <i class="fas fa-plus-circle me-1"></i> Ajouter une Absence
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success') || session('status'))
                        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') ?? session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-secondary">ID</th>
                                    <th class="text-secondary">Agent</th>
                                    <th class="text-secondary">Type & Justificatif</th>
                                    <th class="text-secondary text-center">Période</th>
                                    <th class="text-secondary text-center">Approuvée</th>
                                    <th class="text-secondary text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($absences as $absence)
                                    <tr>
                                        <td class="fw-bold text-muted">#{{ $absence->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary-subtle text-primary rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
                                                    <i class="fas fa-user small"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">
                                                        @if($absence->agent)  
                                                            {{ $absence->agent->last_name }} {{ $absence->agent->first_name }}
                                                        @else
                                                            <span class="badge bg-danger">ID #{{ $absence->agent_id }} non trouvé</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted">
                                                        {{-- On utilise l'opérateur null-safe (?->) pour éviter l'erreur --}}
                                                        {{ $absence->agent?->service?->nom ?? 'Service non défini' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 mb-1">
                                                {{ $absence->typeAbsence->nom ?? $absence->typeAbsence->nom_type }}
                                            </span>
                                            <br>
                                            @if($absence->document_justificatif)
                                                <a href="{{ asset('storage/' . $absence->document_justificatif) }}" target="_blank" class="text-success small text-decoration-none">
                                                    <i class="fas fa-paperclip me-1"></i>Voir le scan
                                                </a>
                                            @else
                                                <small class="text-danger small italic"><i class="fas fa-times-circle me-1"></i>Sans document</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="small fw-bold">{{ \Carbon\Carbon::parse($absence->date_debut)->translatedFormat('d M Y') }}</div>
                                            <div class="text-muted small">au {{ \Carbon\Carbon::parse($absence->date_fin)->translatedFormat('d M Y') }}</div>
                                        </td>
                                        <td class="text-center">
                                            @if ($absence->approuvee)
                                                {{-- Blanc sur Vert --}}
                                                <span class="badge bg-success text-white px-3 py-2 shadow-sm">
                                                    <i class="fas fa-check-circle me-1"></i> OUI
                                                </span>
                                            @else
                                                {{-- BLANC SUR ROUGE --}}
                                                <span class="badge bg-danger text-white px-3 py-2 shadow-sm" style="letter-spacing: 0.5px;">
                                                    <i class="fas fa-times-circle me-1"></i> NON
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group shadow-sm">
                                                <a href="{{ route('absences.show', $absence->id) }}" class="btn btn-sm btn-outline-info" title="Détails">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('absences.edit', $absence->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="if(confirm('Supprimer cette absence ?')) document.getElementById('delete-{{ $absence->id }}').submit();" title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            <form id="delete-{{ $absence->id }}" action="{{ route('absences.destroy', $absence->id) }}" method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination stylisée --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $absences->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr:hover { background-color: #f8f9ff; transition: 0.2s; }
    .badge { font-weight: 600; letter-spacing: 0.3px; }
    .bg-primary-subtle { background-color: #e7f0ff !important; }
    .bg-info-subtle { background-color: #e0f7fa !important; }
</style>
@endsection
