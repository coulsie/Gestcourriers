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
                        <i class="fas fa-file-signature me-2"></i>
                        {{ request('parent_id') ? 'FORMULAIRE DE RÉIMPUTATION' : "FORMULAIRE D'IMPUTATION OFFICIELLE (2026)" }}
                    </h4>
                </div>

                <div class="card-body p-4" style="background-color: #f8fafc;">
                    <form action="{{ route('imputations.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- CHAMPS TECHNIQUES -->
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="parent_id" value="{{ request('parent_id') }}">
                        {{-- Cette ligne est CRUCIALE pour récupérer le fichier lors de la réimputation --}}
                        <input type="hidden" name="chemin_fichier" value="{{ request('chemin_fichier', $courrierSelectionne->fichier_chemin ?? '') }}">



                        @php
                            $userRole = auth()->user()->role;
                            $niveauEnum = match($userRole) {
                                'Directeur' => 'tertiaire',
                                'Chef de Service' => 'secondaire',
                                default => 'primaire'
                            };

                            // Détection forcée du fichier
                            $fichierAffiche = request('chemin_fichier') ?: ($courrierSelectionne->fichier_chemin ?? null);
                        @endphp

                        <input type="hidden" name="niveau" value="{{ $niveauEnum }}">
                        <input type="hidden" name="chemin_fichier" value="{{ $fichierAffiche }}">

                        <div class="row">
                            <!-- SECTION 1 : DOCUMENTS & DATES -->
                            <div class="col-md-5 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-4 border-primary">
                                    <h5 class="text-primary mb-4 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-folder-open me-2"></i> 1. Référence & Chronologie
                                    </h5>

                                    @if(isset($courrierSelectionne) || request('courrier_id'))
                                        @php
                                            $cId = request('courrier_id', $courrierSelectionne->id ?? '');
                                            $cRef = $courrierSelectionne->reference ?? 'Réimputation';
                                        @endphp
                                        <div class="mb-3 p-0 rounded-3 overflow-hidden border border-dark shadow-sm">
                                            <div class="bg-dark text-white p-2 px-3 d-flex justify-content-between align-items-center text-uppercase small fw-bold">
                                                <span><i class="fas fa-file-alt me-2"></i>Document</span>
                                                <span class="badge bg-primary shadow-sm">Réf: {{ $cRef }}</span>
                                            </div>
                                            <div class="p-3 text-white" style="background-color: #1e40af;">
                                                <label class="small fw-bold opacity-75 text-uppercase">Objet</label>
                                                <div class="fw-bold fs-6 text-truncate">
                                                    {{ $courrierSelectionne->objet ?? 'Chargement du document...' }}
                                                </div>
                                                <input type="hidden" name="courrier_id" value="{{ $cId }}">

                                                {{-- Affichage du bouton de consultation --}}
                                                @if($fichierAffiche)
                                                    <div class="mt-2 pt-2 border-top border-white-50">
                                                        <a href="{{ asset($fichierAffiche) }}" target="_blank" class="badge bg-danger text-decoration-none p-2 shadow-sm">
                                                            <i class="fas fa-file-pdf me-1"></i> CONSULTER LE FICHIER PRINCIPAL
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="mt-2 small text-warning italic">
                                                        <i class="fas fa-exclamation-circle"></i> Aucun fichier lié
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Sélectionner le Courrier *</label>
                                            <select name="courrier_id" class="form-select border-primary shadow-sm @error('courrier_id') is-invalid @enderror" required>
                                                <option value="">-- Choisir --</option>
                                                @foreach($courriers as $courrier)
                                                    <option value="{{ $courrier->id }}" {{ old('courrier_id') == $courrier->id ? 'selected' : '' }}>
                                                        [{{ $courrier->reference }}] - {{ Str::limit($courrier->objet, 40) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Date Imputation *</label>
                                            <input type="date" name="date_imputation" class="form-control bg-light" value="{{ date('Y-m-d') }}" required>
                                        </div>
  <div class="col-md-6 mb-3">
    <label class="form-label fw-bold small text-muted text-uppercase text-danger">Échéancier *</label>

    @php
        // On récupère la date de l'URL
        $dateParam = request('echeancier');
        // On force le format YYYY-MM-DD indispensable pour l'input HTML5
        $valueDate = $dateParam ? date('Y-m-d', strtotime($dateParam)) : old('echeancier');
    @endphp

    <input type="date"
           name="echeancier"
           class="form-control shadow-sm {{ request('parent_id') ? 'bg-light border-secondary' : 'border-danger' }}"
           value="{{ $valueDate }}"
           {{-- Verrouillage si c'est une réimputation --}}
           @if(request('parent_id')) readonly style="pointer-events: none;" @endif
           required>

    @if(request('parent_id'))
        <small class="text-danger fw-bold d-block mt-1" style="font-size: 0.65rem;">
            <i class="fas fa-lock me-1"></i> ÉCHÉANCE VERROUILLÉE
        </small>
    @endif
</div>


                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Statut d'imputation *</label>
                                        <select name="statut" class="form-select bg-white border-primary shadow-sm" required>
                                            <option value="en_attente" {{ old('statut') == 'en_attente' ? 'selected' : '' }}>🟠 En attente</option>
                                            <option value="en_cours" {{ request('parent_id') || old('statut') == 'en_cours' ? 'selected' : '' }}>🔵 En cours</option>
                                            <option value="termine" {{ old('statut') == 'termine' ? 'selected' : '' }}>🟢 Terminé</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Reste du formulaire (Agents et Section 3 Instructions) --}}
                            <!-- ... -->

                                                      <!-- SECTION 2 : DESTINATAIRES (AGENTS) -->
                            <div class="col-md-7 mb-4">
                                <div class="p-4 bg-white rounded-3 shadow-sm h-100 border-top border-4 border-warning">
                                    <h5 class="text-warning mb-4 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-users me-2"></i> 2. Attribution aux Agents
                                    </h5>

                                    {{-- La suite de votre code pour les agents reste ici --}}

                                     <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Direction</label>
                                            <select id="filter_direction" class="form-select shadow-sm border-warning">
                                                <option value="">Toutes les Directions</option>
                                                @foreach($directions as $dir)
                                                    <option value="{{ $dir->id }}">{{ $dir->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Service</label>
                                            <select id="filter_service" class="form-select shadow-sm border-warning">
                                                <option value="">Tous les Services</option>
                                                @foreach($services as $ser)
                                                    <option value="{{ $ser->id }}" data-dir="{{ $ser->direction_id }}">{{ $ser->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold small text-muted text-uppercase">Choisir l'Agent (ou les Agents) *</label>
                                        {{-- CORRECTION : Changement de user_id[] vers agent_ids[] --}}
                                        <select name="agent_ids[]" id="agent_select" class="form-select border-primary shadow-sm @error('agent_ids') is-invalid @enderror" multiple style="height: 160px;" required>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}"
                                                        data-dir="{{ $agent->service->direction_id ?? '' }}"
                                                        data-ser="{{ $agent->service_id ?? '' }}"
                                                        {{ (collect(old('agent_ids'))->contains($agent->id)) ? 'selected':'' }}>
                                                    {{ strtoupper($agent->last_name) }} {{ $agent->first_name }}
                                                    ({{ $agent->service->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-primary italic mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Maintenez CTRL pour une sélection multiple.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3 : TRAITEMENT -->
                        <!-- SECTION 3 : TRAITEMENT -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="p-4 bg-white rounded-3 shadow-sm border-top border-4 border-success">
                                    <h5 class="text-success mb-3 border-bottom pb-2 fw-bold">
                                        <i class="fas fa-edit me-2"></i> 3. Instructions & Annexes
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Instructions</label>
                                            {{-- On récupère les instructions de l'URL si elles existent --}}
                                            <textarea name="instructions" class="form-control mb-3" rows="3" placeholder="Directives à suivre...">{{ request('instructions', old('instructions')) }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Observations</label>
                                            <textarea name="observations" class="form-control mb-3" rows="3" placeholder="Remarques éventuelles...">{{ old('observations') }}</textarea>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Documents Annexes</label>
                                            <input type="file" name="documents_annexes" class="form-control shadow-sm">

                                            {{-- Affichage de l'annexe parente si elle existe --}}
                                            @if(request('doc_annexe'))
                                                <div class="mt-2 p-2 bg-light border rounded small">
                                                    <i class="fas fa-paperclip text-primary me-1"></i>
                                                    Annexe actuelle : <a href="{{ asset(request('doc_annexe')) }}" target="_blank" class="fw-bold">Consulter l'annexe</a>
                                                    <input type="hidden" name="old_annexe" value="{{ request('doc_annexe') }}">
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Rappel du fichier principal --}}
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold small text-muted text-uppercase">Fichier Principal</label>
                                            <div class="p-2 bg-light border rounded small">
                                                <i class="fas fa-file-pdf text-danger me-1"></i>
                                                @if(request('chemin_fichier'))
                                                    Fichier lié : <a href="{{ asset(request('chemin_fichier')) }}" target="_blank" class="fw-bold">Voir le document original</a>
                                                @else
                                                    <span class="text-muted">Aucun fichier principal détecté</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('courriers.index') }}" class="btn btn-outline-secondary px-4 fw-bold">ANNULER</a>
                            <button type="submit" class="btn btn-primary btn-lg px-5 shadow fw-bold">
                                <i class="fas fa-save me-2"></i> ENREGISTRER L'IMPUTATION
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Script de filtrage identique au précédent
    const dirF = document.getElementById('filter_direction');
    const serF = document.getElementById('filter_service');
    const agS = document.getElementById('agent_select');
    const agOpts = Array.from(agS.options);

    function filter() {
        const d = dirF.value;
        const s = serF.value;

        Array.from(serF.options).forEach(o => {
            if(o.value === "") return;
            o.style.display = (d === "" || o.dataset.dir === d) ? "block" : "none";
        });

        agOpts.forEach(o => {
            const mD = d === "" || o.dataset.dir === d;
            const mS = s === "" || o.dataset.ser === s;
            o.style.display = (mD && mS) ? "block" : "none";
        });
    }

    dirF.addEventListener('change', () => { serF.value = ""; filter(); });
    serF.addEventListener('change', filter);
</script>
@endsection


