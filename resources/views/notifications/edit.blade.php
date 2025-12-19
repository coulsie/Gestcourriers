@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-primary">Modifier la Notification #{{ $NotificationTache->id_notification }}</h4>
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary btn-sm">Retour</a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('notifications.update', $NotificationTache->id_notification) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            {{-- Titre --}}
                            <div class="col-md-8">
                                <label class="form-label fw-bold">Titre</label>
                                <input type="text" name="titre" class="form-control @error('titre') is-invalid @enderror"
                                       value="{{ old('titre', $NotificationTache->titre) }}" required>
                                @error('titre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Priorité (Enum) --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Priorité</label>
                                <select name="priorite" class="form-select @error('priorite') is-invalid @enderror">
                                    @foreach(['Faible', 'Moyenne', 'Élevée', 'Urgent'] as $prio)
                                        <option value="{{ $prio }}" {{ old('priorite', $NotificationTache->priorite) == $prio ? 'selected' : '' }}>
                                            {{ $prio }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="col-12">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $NotificationTache->description) }}</textarea>
                                @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- Statut (Enum) --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Statut</label>
                                <select name="statut" class="form-select @error('statut') is-invalid @enderror">
                                    @foreach(['Non lu', 'En cours', 'Complétée', 'Annulée'] as $stat)
                                        <option value="{{ $stat }}" {{ old('statut', $NotificationTache->statut) == $stat ? 'selected' : '' }}>
                                            {{ $stat }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Suivi par --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Suivi par</label>
                                <input type="text" name="suivi_par" class="form-control" value="{{ old('suivi_par', $NotificationTache->suivi_par) }}" required>
                            </div>

                            {{-- Date Échéance --}}
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Date d'échéance</label>
                                <input type="datetime-local" name="date_echeance" class="form-control"
                                       value="{{ old('date_echeance', $NotificationTache->date_echeance ? date('Y-m-d\TH:i', strtotime($NotificationTache->date_echeance)) : '') }}">
                            </div>

                            {{-- Lien Action --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Lien d'action (URL)</label>
                                <input type="url" name="lien_action" class="form-control" value="{{ old('lien_action', $NotificationTache->lien_action) }}">
                            </div>

                            {{-- Document --}}
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Document (Actuel : {{ $NotificationTache->document ?? 'Aucun' }})</label>
                                <input type="file" name="document" class="form-control @error('document') is-invalid @enderror">
                                <small class="text-muted">Laissez vide pour conserver le document actuel.</small>
                            </div>

                            {{-- Dates de lecture/complétion (Lecture seule ou automatique via bouton) --}}
                            @if($NotificationTache->date_lecture)
                                <div class="col-md-6">
                                    <label class="form-label text-muted">Lu le : {{ date('d/m/Y H:i', strtotime($NotificationTache->date_lecture)) }}</label>
                                </div>
                            @endif
                        </div>

                        <div class="mt-5 border-top pt-3 d-flex justify-content-end gap-2">
                            <button type="reset" class="btn btn-light border">Réinitialiser</button>
                            <button type="submit" class="btn btn-primary px-4">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
