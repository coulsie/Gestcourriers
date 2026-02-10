@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête de page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">
            <i class="fas fa-calendar-check text-primary me-2"></i>Demande de Congés & Permissions
        </h1>
        <a href="{{ route('absences.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm fw-bold">
            <i class="fas fa-arrow-left fa-sm me-1"></i> Retour à la liste
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <!-- Header avec dégradé -->
                <div class="card-header bg-gradient-primary py-3 border-0">
                    <h6 class="m-0 font-weight-bold text-white text-uppercase tracking-wide">
                        <i class="fas fa-edit me-2"></i>Nouveau Formulaire d'Absence
                    </h6>
                </div>

                <div class="card-body p-5 bg-white">
                    <form action="{{ route('absences.monstore') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Bloc Agent : INFO (Bleu) -->
                        <div class="mb-5 p-3 rounded-4 bg-light border-start border-5 border-info shadow-sm">
                            <label class="form-label fw-bold small text-info text-uppercase mb-2">Agent Demandeur</label>
                            <div class="d-flex align-items-center">
                                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 50px; height: 50px;">
                                    <i class="fas fa-user-tie fa-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <div class="fw-black text-dark fs-5" style="letter-spacing: -0.5px;">{{ strtoupper(auth()->user()->name) }}</div>
                                    <div class="badge bg-white text-info border border-info-subtle shadow-sm">
                                        Matricule : {{ auth()->user()->agent->matricule ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-4">
                            <!-- Bloc Nature : INDIGO -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark fs-6"><i class="fas fa-tag text-indigo me-1"></i> Nature de l'absence <span class="text-danger">*</span></label>
                                <select name="type_absence_id" class="form-select form-select-lg border-2 border-indigo fw-bold text-indigo shadow-sm" style="background-color: #f5f3ff;" required>
                                    <option value="" selected disabled>— Sélectionner le motif —</option>
                                    @foreach($typeAbsences as $type)
                                        <option value="{{ $type->id }}" class="fw-bold">
                                            {{ strtoupper($type->nom_type ?? 'Motif inconnu') }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_absence_id') <div class="text-danger small fw-bold mt-1">{{ $message }}</div> @enderror
                            </div>

                            <!-- Bloc Dates : WARNING / ORANGE -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark"><i class="fas fa-calendar-day text-warning me-1"></i> Date de début <span class="text-danger">*</span></label>
                                <input type="date" name="date_debut" class="form-control form-control-lg border-2 border-warning shadow-sm fw-bold"
                                       style="background-color: #fffbeb;" value="{{ old('date_debut') }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark"><i class="fas fa-calendar-check text-warning me-1"></i> Date de fin <span class="text-danger">*</span></label>
                                <input type="date" name="date_fin" class="form-control form-control-lg border-2 border-warning shadow-sm fw-bold"
                                       style="background-color: #fffbeb;" value="{{ old('date_fin') }}" required>
                            </div>

                            <!-- Bloc Fichier : SUCCESS / VERT -->
                            <div class="col-md-12">
                                <label class="form-label fw-bold text-dark">
                                    <i class="fas fa-paperclip text-success me-1"></i> Pièce justificative
                                    <small class="text-muted">(Tout type de document accepté)</small>
                                </label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text bg-success text-white border-success"><i class="fas fa-upload"></i></span>
                                    <input type="file" name="document_justificatif"
                                        class="form-control form-control-lg border-2 border-success @error('document_justificatif') is-invalid @enderror">
                                </div>

                                {{-- Message d'erreur spécifique au fichier --}}
                                @error('document_justificatif')
                                    <div class="text-danger fw-bold mt-1 small">
                                        <i class="fas fa-exclamation-circle"></i> {{ $message }}
                                    </div>
                                @enderror

                                <div class="form-text text-success fw-bold" style="font-size: 0.75rem;">
                                    Format libre. Taille maximale autorisée : <span class="text-danger">8 Mo</span>.
                                </div>
                            </div>
                        </div>

                        <hr class="my-5 border-2">

                        <!-- Boutons d'action -->
                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-black shadow-lg py-3 text-uppercase" style="letter-spacing: 1px;">
                                <i class="fas fa-paper-plane me-2"></i> Envoyer la demande à la DRH
                            </button>
                            <button type="reset" class="btn btn-light btn-sm text-muted fw-bold">
                                <i class="fas fa-undo me-1"></i> Effacer les saisies
                            </button>
                        </div>
                    </form>
                    <div class="d-flex justify-content-center mt-4 mb-3">
                        {{ $absences->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-indigo { border-color: #6610f2 !important; }
    .text-indigo { color: #6610f2 !important; }
    .bg-indigo-subtle { background-color: #e7d1ff !important; }
    .fw-black { font-weight: 900; }
    .bg-gradient-primary { background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); }
</style>
@if(session('success'))
    <script>
        // On affiche d'abord une alerte de confirmation propre (optionnel)
        alert("{{ session('success') }}");

        // Délai de 1 seconde puis fermeture
        setTimeout(function() {
            // Si c'est une fenêtre surgissante (popup)
            window.close();

            // Si window.close() échoue (sécurité navigateurs), on redirige
            if (!window.closed) {
                window.location.href = "{{ route('home') }}"; // Redirection de secours
            }
        }, 1000);
    </script>
@endif

@endsection
