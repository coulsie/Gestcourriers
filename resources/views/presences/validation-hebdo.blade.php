@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Bandeau récapitulatif de la semaine --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-dark text-white shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="letter-spacing: 1px; color: #ffc107;">Période de contrôle</h6>
                        <h4 class="mb-0">
                            Semaine du
                            <span class="text-info">{{ \Carbon\Carbon::now()->subWeek()->startOfWeek()->locale('fr')->translatedFormat('d F') }}</span>
                            au
                            <span class="text-info">{{ \Carbon\Carbon::now()->subWeek()->endOfWeek()->subDays(2)->locale('fr')->translatedFormat('d F Y') }}</span>
                        </h4>
                    </div>
                    <div class="text-end">
                        <i class="fas fa-calendar-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow border-warning">
        <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0"><i class="fas fa-calendar-exclamation me-2"></i>Absences journalières détectées</h5>
            <span class="badge bg-dark px-3 py-2">Traitement Hebdomadaire</span>
        </div>

        <div class="card-body p-0">
            <form action="{{ route('presences.valider-hebdo') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4" width="50">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll" checked>
                                    </div>
                                </th>
                                <th>Agent</th>
                                <th>Date de l'absence</th>
                                <th class="text-center">Statut suggéré</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absencesDetectees as $index => $abs)
                            @php $dateAbs = \Carbon\Carbon::parse($abs['date'])->locale('fr'); @endphp
                                <tr class="{{ $abs['est_justifie'] ? 'table-success-subtle' : '' }}">
                                    <td class="ps-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="absences[{{ $index }}][selected]" value="1"
                                                {{ $abs['est_justifie'] ? '' : 'checked' }}
                                                class="form-check-input abs-checkbox shadow-sm">
                                        </div>
                                        <input type="hidden" name="absences[{{ $index }}][agent_id]" value="{{ $abs['agent_id'] }}">
                                        <input type="hidden" name="absences[{{ $index }}][date]" value="{{ $abs['date'] }}">
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $abs['nom'] }}</div>
                                        @if($abs['est_justifie'])
                                            <small class="text-success fw-bold"><i class="fas fa-info-circle"></i> {{ $abs['motif'] }}</small>
                                        @else
                                            <small class="text-muted">ID Agent: #{{ $abs['agent_id'] }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light text-dark rounded px-2 py-1 border border-secondary-subtle">
                                                <i class="far fa-calendar-minus me-2 {{ $abs['est_justifie'] ? 'text-success' : 'text-danger' }}"></i>
                                                <span class="fw-bold">{{ ucwords($dateAbs->translatedFormat('l d F Y')) }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($abs['est_justifie'])
                                            <span class="badge bg-success text-white px-3 py-2 shadow-sm">
                                                <i class="fas fa-check-circle me-1"></i> JUSTIFIÉ
                                            </span>
                                        @else
                                            <span class="badge bg-danger text-white px-3 py-2 shadow-sm">
                                                <i class="fas fa-user-times me-1"></i> ABSENT
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        @if($abs['est_justifie'])
                                            <small class="text-muted italic">Déjà en règle</small>
                                        @else
                                            <small class="text-danger fw-bold">À confirmer</small>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-check-circle text-success me-2"></i> Aucune absence à valider pour cette période.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if(count($absencesDetectees) > 0)
                <div class="p-4 bg-light border-top">
                    <div class="alert alert-custom border-warning bg-white shadow-sm mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Confirmation requise</h6>
                                <p class="small mb-0 text-muted">
                                    L'enregistrement sera daté du <strong>{{ \Carbon\Carbon::now()->locale('fr')->translatedFormat('l d F Y') }}</strong> pour les abences de la semaine passée.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <a href="{{ route('presences.index') }}" class="btn btn-outline-secondary w-100 py-2 fw-bold">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-warning w-100 py-2 fw-bold shadow border-dark border-opacity-10">
                                <i class="fas fa-save me-2"></i>Enregistrer les {{ count($absencesDetectees) }} absences
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

<style>
    .alert-custom { border-left: 5px solid #ffc107; }
    .table-hover tbody tr:hover { background-color: #f8f9fa; }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const form = document.querySelector('form[action*="valider-hebdo"]');

    // 1. Gestion combinée du bouton "Tout sélectionner"
    if (selectAll) {
        selectAll.addEventListener('click', function() {
            // Sélectionne toutes les cases à cocher des agents
            const checkboxes = document.querySelectorAll('.abs-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });
    }

    // 2. Validation avant l'envoi du formulaire
    if (form) {
        form.addEventListener('submit', function (e) {
            // On compte combien de cases sont cochées au moment du clic sur Valider
            const checkedCount = document.querySelectorAll('.abs-checkbox:checked').length;

            if (checkedCount === 0) {
                e.preventDefault(); // Bloque l'envoi vers le contrôleur
                alert('Attention : Veuillez cocher au moins une case pour enregistrer les absences.');
            } else {
                // Optionnel : Feedback visuel sur le bouton pour confirmer l'action
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enregistrement en cours...';
                    submitBtn.disabled = true;
                }
            }
        });
    }
});
</script>
@endpush
@endsection
