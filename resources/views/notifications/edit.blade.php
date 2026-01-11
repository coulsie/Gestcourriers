@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @php
                // Détermination de la couleur de thème selon la priorité actuelle
                $prioColor = match($NotificationTache->priorite) {
                    'Urgent' => 'danger',
                    'Élevée' => 'warning',
                    'Moyenne' => 'info',
                    'Faible' => 'success',
                    default => 'primary',
                };
            @endphp

            <div class="card shadow-lg border-0">
                {{-- En-tête coloré dynamiquement --}}
                <div class="card-header bg-{{ $prioColor }} py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white">
                        <i class="fas fa-bell me-2"></i>Modifier la Notification #{{ $NotificationTache->id_notification }}
                    </h4>
                    <a href="{{ route('notifications.index') }}" class="btn btn-light btn-sm shadow-sm text-{{ $prioColor }} fw-bold">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                </div>

                <div class="card-body p-4 bg-light-subtle">
                    <form action="{{ route('notifications.update', $NotificationTache->id_notification) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            {{-- Titre --}}
                            <div class="col-md-8">
                                <label class="form-label fw-bold text-dark">Titre de la tâche</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-heading text-{{ $prioColor }}"></i></span>
                                    <input type="text" name="titre" class="form-control border-start-0 @error('titre') is-invalid @enderror"
                                           value="{{ old('titre', $NotificationTache->titre) }}" required>
                                </div>
                                @error('titre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Priorité avec indicateur de couleur --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Niveau de Priorité</label>
                                <select name="priorite" id="prioriteSelect" class="form-select fw-bold border-2 @error('priorite') is-invalid @enderror" 
                                        style="border-left: 8px solid var(--bs-{{ $prioColor }})">
                                    <option value="Faible" class="text-success" {{ old('priorite', $NotificationTache->priorite) == 'Faible' ? 'selected' : '' }}>🟢 Faible</option>
                                    <option value="Moyenne" class="text-info" {{ old('priorite', $NotificationTache->priorite) == 'Moyenne' ? 'selected' : '' }}>🔵 Moyenne</option>
                                    <option value="Élevée" class="text-warning" {{ old('priorite', $NotificationTache->priorite) == 'Élevée' ? 'selected' : '' }}>🟡 Élevée</option>
                                    <option value="Urgent" class="text-danger" {{ old('priorite', $NotificationTache->priorite) == 'Urgent' ? 'selected' : '' }}>🔴 Urgent</option>
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label fw-bold text-dark">Description détaillée</label>
                                <textarea name="description" class="form-control shadow-sm @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $NotificationTache->description) }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Statut avec Badges --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Statut actuel</label>
                                <select name="statut" class="form-select border-primary-subtle shadow-sm">
                                    <option value="Non lu" {{ old('statut', $NotificationTache->statut) == 'Non lu' ? 'selected' : '' }}>⚪ Non lu</option>
                                    <option value="En cours" {{ old('statut', $NotificationTache->statut) == 'En cours' ? 'selected' : '' }}>🟠 En cours</option>
                                    <option value="Complétée" {{ old('statut', $NotificationTache->statut) == 'Complétée' ? 'selected' : '' }}>✅ Complétée</option>
                                    <option value="Annulée" {{ old('statut', $NotificationTache->statut) == 'Annulée' ? 'selected' : '' }}>❌ Annulée</option>
                                </select>
                            </div>

                            {{-- Suivi par --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Agent responsable</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                    <input type="text" name="suivi_par" class="form-control" value="{{ old('suivi_par', $NotificationTache->suivi_par) }}" required>
                                </div>
                            </div>

                            {{-- Date Échéance --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark">Date d'échéance</label>
                                <input type="datetime-local" name="date_echeance" class="form-control border-{{ $prioColor }}-subtle"
                                       value="{{ old('date_echeance', $NotificationTache->date_echeance ? date('Y-m-d\TH:i', strtotime($NotificationTache->date_echeance)) : '') }}">
                            </div>

                            {{-- Lien Action --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Lien d'action (URL)</label>
                                <div class="input-group">
                                    <span class="input-group-text text-primary"><i class="fas fa-link"></i></span>
                                    <input type="url" name="lien_action" class="form-control" placeholder="https://..." value="{{ old('lien_action', $NotificationTache->lien_action) }}">
                                </div>
                            </div>

                            {{-- Document --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark">Document joint</label>
                                <input type="file" name="document" class="form-control @error('document') is-invalid @enderror">
                                <div class="mt-2">
                                    @if($NotificationTache->document)
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-file-alt me-1"></i> Actuel : {{ $NotificationTache->document }}
                                        </span>
                                    @else
                                        <small class="text-muted italic">Aucun document joint</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 border-top pt-4 d-flex justify-content-between align-items-center">
                            <div>
                                @if($NotificationTache->date_lecture)
                                    <span class="text-muted small">
                                        <i class="fas fa-check-double text-success"></i> Lu le {{ date('d/m/Y à H:i', strtotime($NotificationTache->date_lecture)) }}
                                    </span>
                                @endif
                            </div>
                            <div class="gap-2 d-flex">
                                <button type="reset" class="btn btn-outline-secondary px-4">Annuler les saisies</button>
                                <button type="submit" class="btn btn-{{ $prioColor }} px-5 text-white shadow">
                                    <i class="fas fa-save me-2"></i>Mettre à jour
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Petit script pour changer la couleur de la bordure du select en temps réel
    document.getElementById('prioriteSelect').addEventListener('change', function() {
        const colors = {
            'Urgent': '#dc3545',
            'Élevée': '#ffc107',
            'Moyenne': '#0dcaf0',
            'Faible': '#198754'
        };
        this.style.borderLeftColor = colors[this.value] || '#0d6efd';
    });
</script>
@endsection
