@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Diagnostic des erreurs -->
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-start border-4 border-danger mb-4">
                    <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Erreurs de saisie :</h6>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <h4 class="mb-0 font-weight-bold">
                        <i class="fas fa-file-signature me-2"></i> FORMULAIRE D'IMPUTATION OFFICIELLE
                    </h4>
                </div>

                <div class="card-body p-4" style="background-color: #f8fafc;">
                    <form action="{{ route('imputations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- SECTION 1 : DOCUMENTS & DATES -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-4 border-primary">
                                    <h5 class="text-primary mb-4 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-folder-open me-2"></i> 1. Référence & Chronologie
                                    </h5>

                                    {{-- CAS 1 : Courrier déjà sélectionné depuis l'index --}}
                                   {{-- CAS 1 : Courrier déjà sélectionné depuis l'index --}}
                                @if(isset($courrierSelectionne))
                                    <div class="mb-3 p-0 rounded-3 overflow-hidden border border-dark shadow-sm">
                                        <!-- En-tête Noir avec Texte Blanc -->
                                        <div class="bg-dark text-white p-2 px-3 d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold text-uppercase"><i class="fas fa-file-alt me-2"></i>Courrier Sélectionné</span>
                                            <span class="badge bg-primary text-white border border-light">Réf: {{ $courrierSelectionne->reference }}</span>
                                        </div>

                                        <!-- Corps Bleu avec Texte Blanc -->
                                        <div class="p-3 text-white" style="background-color: #1e40af;">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="small fw-bold opacity-75 text-uppercase">Date du Document</label>
                                                    <div class="fw-bold fs-6 border-bottom border-white border-opacity-25 pb-1">
                                                        {{ $courrierSelectionne->date_courrier->format('d/m/Y') }}
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="small fw-bold opacity-75 text-uppercase">Objet / Sujet</label>
                                                    <div class="fw-bold fs-6 border-bottom border-white border-opacity-25 pb-1 text-truncate">
                                                        {{ $courrierSelectionne->objet }}
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ID caché indispensable pour le store -->
                                            <input type="hidden" name="courrier_id" value="{{ $courrierSelectionne->id }}">
                                        </div>
                                    </div>
                                @else
                                    {{-- CAS 2 : Choix manuel (Inchangé) --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Sélectionner le Courrier *</label>
                                        <select name="courrier_id" class="form-select border-primary @error('courrier_id') is-invalid @enderror shadow-sm" required>
                                            <option value="">-- Choisir le document --</option>
                                            @foreach($courriers as $courrier)
                                                <option value="{{ $courrier->id }}" {{ old('courrier_id') == $courrier->id ? 'selected' : '' }}>
                                                    📦 [{{ $courrier->reference }}] - {{ Str::limit($courrier->objet, 60) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                    <div class="row mt-3">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Date d'Imputation</label>
                                            <input type="date" name="date_imputation" class="form-control bg-light" value="{{ date('Y-m-d') }}" readonly>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase text-danger">Échéancier / Délai *</label>
                                            <input type="date" name="echeancier" class="form-control border-danger @error('echeancier') is-invalid @enderror" value="{{ old('echeancier') }}" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Statut d'imputation</label>
                                        <select name="statut" class="form-select bg-light">
                                            <option value="en_attente" selected>🟠 En attente</option>
                                            <option value="en_cours">🔵 En cours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2 : AFFECTATION DES AGENTS -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-4 border-success">
                                    <h5 class="text-success mb-4 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-users-cog me-2"></i> 2. Affectation & Services
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Filtrer par Service</label>
                                        <select id="service_filter" class="form-select border-success shadow-sm">
                                            <option value="">-- Tous les services --</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">🏢 {{ $service->code }} - {{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold small text-muted text-uppercase text-success">Agents Destinataires *</label>
                                        <select name="agent_ids[]" id="agent_ids" class="form-select border-success shadow-sm" multiple style="height: 180px;" required>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}" data-service="{{ $agent->service_id }}">
                                                    👤 {{ strtoupper($agent->last_name) }} {{ $agent->first_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3 : INSTRUCTIONS & PIÈCES -->
                            <div class="col-12">
                                <div class="p-4 bg-white rounded-3 shadow-sm border-top border-4 border-warning">
                                    <h5 class="text-warning mb-4 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-edit me-2"></i> 3. Instructions & Annexes
                                    </h5>

                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Instructions Précises *</label>
                                            <textarea name="instructions" class="form-control border-warning shadow-sm" rows="4" required>{{ old('instructions') }}</textarea>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Documents Joints</label>
                                            <input type="file" name="annexes[]" class="form-control border-warning shadow-sm" multiple>
                                            <div class="form-text small italic">PDF, Images, Word (Max 10Mo)</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BOUTONS D'ACTION -->
                        <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                            <a href="{{ route('courriers.index') }}" class="btn btn-outline-secondary px-4 fw-bold">Annuler</a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                                <i class="fas fa-check-double me-2"></i> VALIDER L'IMPUTATION
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Logique de filtrage des agents par service
    document.getElementById('service_filter').addEventListener('change', function() {
        const serviceId = this.value;
        const options = document.querySelectorAll('#agent_ids option');

        options.forEach(option => {
            if (serviceId === "" || option.getAttribute('data-service') === serviceId) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    });
</script>
@endsection
