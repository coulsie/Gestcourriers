@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <!-- En-tête avec bouton Scanner et Nouveau -->
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="fas fa-envelope-open-text me-2"></i>{{ __('Liste des Courriers') }}</h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-warning btn-sm fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#scanModal">
                            <i class="fas fa-camera"></i> {{ __('Scanner') }}
                        </button>
                        <a href="{{ route('courriers.create') }}" class="btn btn-light btn-sm fw-bold shadow-sm text-success">
                            <i class="fas fa-plus-circle"></i> {{ __('Nouveau Courrier') }}
                        </a>
                    </div>
                </div>

                <div class="card-body bg-light">
                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive rounded shadow-sm bg-white p-2">
                        <table class="table table-hover align-middle">
                            <thead class="table-primary text-primary">
                                <tr>
                                    <th>ID</th>
                                    <th>Référence</th>
                                    <th>Type</th>
                                    <th>Objet</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Expéditeur</th>
                                    <th class="text-center">Actions / Documents</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courriers as $courrier)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $courrier->id }}</td>
                                    <td><span class="badge bg-success text-white">{{ $courrier->reference }}</span></td>
                                    <td>
                                        @if($courrier->type == 'Incoming')
                                            <span class="text-primary fw-bold"><i class="fas fa-arrow-down"></i> Entrant</span>
                                        @else
                                            <span class="text-orange fw-bold" style="color: #fd7e14;"><i class="fas fa-arrow-up"></i> Sortant</span>
                                        @endif
                                    </td>
                                    <td class="text-truncate" style="max-width: 150px;">{{ $courrier->objet }}</td>
                                    <td>
                                        @php
                                            $color = match(strtolower($courrier->statut)) {
                                                'affecté', 'affecte' => 'success',
                                                'reçu', 'recu'       => 'danger',
                                                'en_traitement'      => 'warning',
                                                'traité', 'traite'   => 'info',
                                                'archivé', 'archive' => 'secondary',
                                                default              => 'dark',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $color }} text-white rounded-pill">{{ $courrier->statut }}</span>
                                    </td>
                                    <td class="text-nowrap">{{ $courrier->date_courrier->format('d/m/Y') }}</td>
                                    <td><small class="fw-bold">{{ $courrier->expediteur_nom }}</small></td>
                                    <td class="text-center">
                                        <!-- Groupe de boutons Actions -->
                                        <div class="d-flex gap-1 justify-content-center mb-2">
                                            <a href="{{ route('courriers.show', $courrier->id) }}" class="btn btn-outline-info btn-sm" title="Voir"><i class="fa fa-eye"></i></a>

                                            <!-- NOUVELLE ACTION : IMPUTATION -->
                                            

                                            <a href="{{ route('imputations.create', ['courrier_id' => $courrier->id]) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="fa fa-share-nodes"></i> Imputer
                                            </a>


                                            <a href="{{ route('courriers.edit', $courrier->id) }}" class="btn btn-outline-warning btn-sm" title="Modifier"><i class="fa fa-edit"></i></a>

                                            <form action="{{ route('courriers.destroy', $courrier->id) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer ?')"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </div>

                                        <!-- Section Document -->
                                        <div class="text-center pt-2 border-top">
                                            @if($courrier->chemin_fichier)
                                                <div class="btn-group btn-group-sm w-100">
                                                    <a href="{{ asset('Documents/' . $courrier->chemin_fichier) }}" target="_blank" class="btn btn-dark shadow-sm">
                                                        <i class="fas fa-file-pdf"></i> Ouvrir
                                                    </a>
                                                    <a href="{{ asset('Documents/' . $courrier->chemin_fichier) }}" download class="btn btn-outline-dark shadow-sm">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted fw-normal">Aucun document</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Scan (Intégré pour 2026) -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-camera me-2"></i>Numérisation rapide</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-dark">
                <video id="video" style="width: 100%; max-height: 400px;" autoplay></video>
                <canvas id="canvas" style="display:none;"></canvas>
                <img id="photo-preview" src="" class="img-fluid" style="display:none;">
            </div>
            <div class="modal-footer">
                <button type="button" id="btn-capture" class="btn btn-primary">Capturer</button>
                <button type="button" id="btn-confirm" class="btn btn-success" style="display:none;">Utiliser ce scan</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Logique simplifiée du scanner (comme vu précédemment)
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const photoPreview = document.getElementById('photo-preview');
    const btnCapture = document.getElementById('btn-capture');
    const btnConfirm = document.getElementById('btn-confirm');

    document.getElementById('scanModal').addEventListener('shown.bs.modal', async () => {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } });
        video.srcObject = stream;
    });

    btnCapture.addEventListener('click', () => {
        canvas.width = video.videoWidth; canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        photoPreview.src = canvas.toDataURL('image/jpeg');
        video.style.display = 'none'; photoPreview.style.display = 'block';
        btnCapture.style.display = 'none'; btnConfirm.style.display = 'inline-block';
    });

    btnConfirm.addEventListener('click', () => {
        localStorage.setItem('scanned_image', photoPreview.src);
        window.location.href = "{{ route('courriers.create') }}?from_scan=true";
    });
</script>
@endsection
