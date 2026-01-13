@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Détails de la Notification</h1>

    <div class="row">
        <!-- Colonne de gauche : Contenu de la notification -->
        <div class="col-lg-7">
            <div class="card shadow mb-4 border-left-primary">
                <div class="card-header py-3 d-flex justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $notification->titre }}</h6>
                    <span class="badge badge-{{ $notification->priorite == 'Urgent' ? 'danger' : 'info' }}">
                        Priorité : {{ $notification->priorite }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Émis par : <strong>{{ $notification->suivi_par }}</strong></small><br>
                        <small class="text-muted">Date : {{ \Carbon\Carbon::parse($notification->date_creation)->format('d/m/Y H:i') }}</small>
                    </div>

                    <p class="text-dark" style="white-space: pre-line;">{{ $notification->description }}</p>

                    @if($notification->document)
                    <div class="mt-4 p-3 bg-light rounded border">
                        <h6><i class="fas fa-paperclip me-2"></i> Document associé</h6>
                        <a href="{{ asset('storage/' . $notification->document) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-file-pdf"></i> Visualiser le document
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne de droite : Formulaire d'Imputation -->
        <div class="col-lg-5">
            @if(count($subordonnes) > 0)
            <div class="card shadow mb-4 border-bottom-danger">
                <div class="card-header py-3 bg-danger">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-share-square mr-2"></i>Transmettre / Imputer</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('notifications.transmettre', $notification->id_notification) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Assigner à un subordonné :</label>
                            <select name="agent_id" class="form-control select2" required>
                                <option value="">-- Choisir le responsable --</option>
                                @foreach($subordonnes as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->first_name }} {{ $sub->last_name }} ({{ $sub->status }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Instructions complémentaires :</label>
                            <textarea name="description" class="form-control" rows="4" placeholder="Saisir vos instructions ici..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="font-weight-bold">Échéance souhaitée :</label>
                            <input type="date" name="date_echeance" class="form-control" value="{{ $notification->date_echeance ? \Carbon\Carbon::parse($notification->date_echeance)->format('Y-m-d') : '' }}">
                        </div>

                        <button type="submit" class="btn btn-danger btn-block shadow-sm">
                             Valider l'Imputation
                        </button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-warning shadow">
                <i class="fas fa-exclamation-triangle"></i> Vous n'avez pas de subordonnés directs pour transmettre cette tâche.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
