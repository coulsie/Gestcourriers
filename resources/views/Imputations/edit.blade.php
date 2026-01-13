@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-3">
                <!-- En-tête avec dégradé ambre/orange pour le mode édition -->
                <div class="card-header text-dark py-3 d-flex justify-content-between align-items-center"
                     style="background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%); border-radius: 8px 8px 0 0;">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-edit me-2"></i> Modification de l'Imputation #{{ $imputation->id }}
                    </h4>
                    <span class="badge bg-dark px-3 py-2 shadow-sm">
                        <i class="fas fa-layer-group me-1"></i> NIVEAU : {{ strtoupper($imputation->niveau) }}
                    </span>
                </div>

                <div class="card-body p-4" style="background-color: #fcfcfc;">
                    <form action="{{ route('imputations.update', $imputation->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1 : RÉFÉRENCE COURRIER (Lecture seule - Style Alerte Douce) -->
                        <div class="mb-4 p-3 rounded-3 border-start border-4 border-warning shadow-sm" style="background-color: #fffbeb;">
                            <label class="form-label fw-bold text-warning-700 small text-uppercase mb-2">
                                <i class="fas fa-info-circle"></i> Rappel du Courrier Associé
                            </label>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="mb-0 small"><strong>Référence :</strong> <span class="text-primary">{{ $imputation->courrier->reference }}</span></p>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-0 small"><strong>Objet :</strong> {{ $imputation->courrier->objet }}</p>
                                </div>
                            </div>
                            <input type="hidden" name="courrier_id" value="{{ $imputation->courrier_id }}">
                        </div>

                        <div class="row">
                            <!-- SECTION 2 : AFFECTATION (Bordure Verte) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-white rounded-3 shadow-sm border border-success h-100">
                                    <h6 class="fw-bold text-success mb-3"><i class="fas fa-users-cog me-2"></i> Réaffectation des Agents</h6>

                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Filtrer par Service</label>
                                        <select id="service_filter" class="form-select border-success-subtle shadow-sm">
                                            <option value="">-- Tous les services --</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">[{{ $service->code }}] {{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label small fw-bold text-muted">Agents Destinataires *</label>
                                        <select name="agent_ids[]" id="agent_ids" class="form-select border-success @error('agent_ids') is-invalid @enderror shadow-sm" multiple style="height: 180px;" required>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}"
                                                    data-service="{{ $agent->service_id }}"
                                                    {{ in_array($agent->id, $imputation->agents->pluck('id')->toArray()) ? 'selected' : '' }}>
                                                    👤 {{ strtoupper($agent->last_name) }} {{ $agent->first_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-2 small text-success bg-success-subtle p-2 rounded">
                                            <i class="fas fa-mouse-pointer me-1"></i> Ctrl + Clic pour multi-sélection.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3 : ÉCHÉANCIER & STATUT (Bordure Rouge/Bleue) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-3 bg-white rounded-3 shadow-sm border border-primary h-100">
                                    <h6 class="fw-bold text-primary mb-3"><i class="fas fa-tasks me-2"></i> Suivi & Délais</h6>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-danger small"><i class="fas fa-calendar-alt me-1"></i> ÉCHÉANCIER (Date Limite)</label>
                                        <input type="date" name="echeancier" class="form-control border-danger shadow-sm"
                                            value="{{ old('echeancier', $imputation->echeancier ? $imputation->echeancier->format('Y-m-d') : '') }}">
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-primary small">STATUT DE TRAITEMENT</label>
                                        <select name="statut" class="form-select border-primary shadow-sm fw-bold">
                                            <option value="en_attente" {{ $imputation->statut == 'en_attente' ? 'selected' : '' }} class="text-danger">🔴 En attente</option>
                                            <option value="en_cours" {{ $imputation->statut == 'en_cours' ? 'selected' : '' }} class="text-primary">🔵 En cours</option>
                                            <option value="termine" {{ $imputation->statut == 'termine' ? 'selected' : '' }} class="text-success">🟢 Terminé</option>
                                        </select>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold text-muted small">OBSERVATIONS</label>
                                        <input type="text" name="observations" class="form-control shadow-sm" value="{{ old('observations', $imputation->observations) }}" placeholder="Note additionnelle...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4 : INSTRUCTIONS (Largeur Totale) -->
                        <div class="mb-4 p-3 bg-white rounded-3 shadow-sm border border-warning">
                            <label class="form-label fw-bold text-warning-700"><i class="fas fa-comment-dots me-2"></i> Instructions / Annotations de l'Imputation</label>
                            <textarea name="instructions" class="form-control border-warning-subtle shadow-sm" rows="4" required>{{ old('instructions', $imputation->instructions) }}</textarea>
                        </div>

                        <!-- SECTION 5 : DOCUMENTS -->
                        <div class="mb-4 p-3 bg-white rounded-3 shadow-sm border border-secondary">
                            <label class="form-label fw-bold text-secondary"><i class="fas fa-paperclip me-2"></i> Documents & Pièces Jointes</label>
                            <input type="file" name="annexes[]" class="form-control shadow-sm mb-3" multiple>

                            @if($imputation->documents_annexes)
                                <div class="p-2 rounded bg-light border">
                                    <span class="small fw-bold text-muted d-block mb-2">Fichiers actuellement en ligne :</span>
                                    @php
                                        $fichiers = is_string($imputation->documents_annexes)
                                                    ? json_decode($imputation->documents_annexes, true)
                                                    : $imputation->documents_annexes;
                                    @endphp
                                    @if(is_array($fichiers))
                                        @foreach($fichiers as $file)
                                            <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-sm btn-outline-dark me-2 mb-1 shadow-sm">
                                                <i class="fas fa-file-pdf text-danger me-1"></i> {{ basename($file) }}
                                            </a>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- BOUTONS D'ACTION -->
                        <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-3 shadow-inner">
                            <a href="{{ route('imputations.index') }}" class="btn btn-outline-secondary px-4 fw-bold">
                                <i class="fas fa-times me-2"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-warning px-5 fw-bold shadow text-dark">
                                <i class="fas fa-sync-alt me-2"></i> METTRE À JOUR L'IMPUTATION
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Filtrage dynamique identique pour cohérence UI
    document.getElementById('service_filter').addEventListener('change', function() {
        let serviceId = this.value;
        let agentOptions = document.querySelectorAll('#agent_ids option');

        agentOptions.forEach(option => {
            if (serviceId === "" || option.getAttribute('data-service') === serviceId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        });
    });
</script>

<style>
    .text-warning-700 { color: #b45309; }
    .bg-success-subtle { background-color: #d1fae5; }
    .shadow-inner { box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06); }
    .form-select:focus, .form-control:focus { border-color: #f59e0b; box-shadow: 0 0 0 0.25rem rgba(245, 158, 11, 0.2); }
</style>
@endsection
