@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <!-- Affichage des erreurs de validation pour le diagnostic -->
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm border-start border-4 border-danger mb-4">
                    <h6 class="fw-bold"><i class="fas fa-exclamation-triangle me-2"></i> Erreurs de saisie :</h6>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-3">
                <!-- En-tête Royal Bleu -->
                <div class="card-header text-white py-3" style="background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);">
                    <h4 class="mb-0 font-weight-bold">
                        <i class="fas fa-file-signature me-2"></i> FORMULAIRE D'IMPUTATION OFFICIELLE
                    </h4>
                </div>

                <div class="card-body p-4" style="background-color: #f8fafc;">
                    <form action="{{ route('imputations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- SECTION 1 : DOCUMENTS & DATES (Bleu) -->
                            <div class="col-md-6 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-4 border-primary">
                                    <h5 class="text-primary mb-4 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-folder-open me-2"></i> 1. Référence & Chronologie
                                    </h5>

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

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Date d'Imputation</label>
                                            <input type="date" name="date_imputation" class="form-control bg-light" value="{{ date('Y-m-d') }}" readonly>
                                            <small class="text-primary italic">Date du jour (automatique)</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase text-danger">Échéancier / Délai *</label>
                                            <input type="date" name="echeancier" class="form-control border-danger @error('echeancier') is-invalid @enderror" value="{{ old('echeancier') }}" min="{{ date('Y-m-d') }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Statut Initial</label>
                                        <select name="statut" class="form-select bg-light">
                                            <option value="en_attente" selected>🟠 En attente</option>
                                            <option value="en_cours">🔵 En cours</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2 : AFFECTATION DES AGENTS (Vert) -->
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
                                        <label class="form-label fw-bold small text-muted text-uppercase text-success">Choisir les Agents Destinataires *</label>
                                        <select name="agent_ids[]" id="agent_ids" class="form-select border-success @error('agent_ids') is-invalid @enderror shadow-sm" multiple style="height: 160px;" required>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}" data-service="{{ $agent->service_id }}">
                                                    👤 {{ strtoupper($agent->last_name) }} {{ $agent->first_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="mt-2 py-1 px-2 bg-success-subtle rounded small text-success">
                                            <i class="fas fa-info-circle"></i> Maintenez <b>Ctrl</b> pour sélectionner plusieurs agents.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 3 : INSTRUCTIONS & PIÈCES (Orange/Noir) -->
                            <div class="col-12 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm border-top border-4 border-warning">
                                    <h5 class="text-warning mb-4 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-edit me-2"></i> 3. Instructions & Documents Annexes
                                    </h5>

                                    <div class="row">
                                        <div class="col-md-7 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Instructions Précises *</label>
                                            <textarea name="instructions" class="form-control border-warning shadow-sm" rows="5" placeholder="Décrivez les actions à mener par les agents..." required>{{ old('instructions') }}</textarea>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-muted text-uppercase">Observations Particulières</label>
                                                <textarea name="observations" class="form-control" rows="2">{{ old('observations') }}</textarea>
                                            </div>
                                            <div class="mb-0">
                                                <label class="form-label fw-bold small text-muted text-uppercase"><i class="fas fa-paperclip"></i> Documents Annexes (Fichiers)</label>
                                                <input type="file" name="annexes[]" class="form-control shadow-sm" multiple>
                                                <small class="text-muted italic small">PDF, JPG, PNG acceptés (Max 4Mo par fichier)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="d-flex justify-content-between align-items-center bg-white p-3 rounded-3 shadow-sm">
                            <a href="{{ route('imputations.index') }}" class="btn btn-outline-secondary px-4 fw-bold">
                                <i class="fas fa-times me-2"></i> ANNULER
                            </a>
                            <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                                <i class="fas fa-check-circle me-2"></i> VALIDER ET TRANSMETTRE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Filtrage dynamique des agents par service
    document.getElementById('service_filter').addEventListener('change', function() {
        let serviceId = this.value;
        let agentOptions = document.querySelectorAll('#agent_ids option');

        agentOptions.forEach(option => {
            if (serviceId === "" || option.getAttribute('data-service') === serviceId) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
                option.selected = false;
            }
        });
    });
</script>

<style>
    .form-select:focus, .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(30, 58, 138, 0.15);
    }
    .shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .fw-bold { font-weight: 700!important; }
</style>
@endsection
