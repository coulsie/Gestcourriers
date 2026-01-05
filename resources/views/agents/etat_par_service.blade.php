@extends('layouts.app')

<style>
@media print {
    .btn, .navbar, .sidebar, footer {
        display: none !important;
    }
}
</style>

@section('content')
<div class="container-fluid py-4">
    <!-- En-tête avec dégradé -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-3 shadow-sm rounded-3 bg-gradient" style="background-color: #f8f9fa; border-left: 5px solid #0d6efd;">
                <h2 class="text-primary fw-bold mb-0">
                    <i class="fas fa-users-cog me-2"></i>État des Agents par Service
                </h2>
                <small class="text-muted">Consultation en temps réel des effectifs</small>
            </div>
        </div>
    </div>

    <!-- Formulaire de filtrage stylisé -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body bg-light rounded shadow-sm">
            <form action="{{ route('agents.par.service') }}" method="GET">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="service_id" class="form-label fw-bold text-secondary">
                            <i class="fas fa-filter me-1"></i> Filtrer par Service
                        </label>
                        <select name="service_id" id="service_id" class="form-select border-primary shadow-none">
                            <option value="">Tous les services (Global)</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ $serviceId == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} — [{{ $service->code }}]
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="fas fa-search me-1"></i> Actualiser
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tableau des résultats -->
    <div class="card border-0 shadow">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
            <span class="fs-5">
                <i class="fas fa-list me-2"></i>
                Effectif : <b>{{ $serviceSelectionne ? $serviceSelectionne->name : 'Tous les services' }}</b>
            </span>
            <span class="badge bg-info text-dark fw-bold">Total: {{ $agents->count() }} agent(s)</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3 text-uppercase small fw-bold">Matricule</th>
                            <th class="text-uppercase small fw-bold">Nom & Prénoms</th>
                            <th class="text-uppercase small fw-bold text-center">Statut</th>
                            <th class="text-uppercase small fw-bold">Grade / Emploi</th>
                            <th class="text-uppercase small fw-bold text-end pe-3">Service</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agents as $agent)
                            <tr>
                                <<td class="ps-3">
                                    <span class="badge bg-dark text-white font-monospace" style="letter-spacing: 1px;">
                                        {{ $agent->matricule }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-navy">{{ strtoupper($agent->last_name) }}</div>
                                    <div class="text-muted small">{{ $agent->first_name }}</div>
                                </td>
                                <td class="text-center">
    {{-- Couleur dynamique selon le statut --}}
                                        @php
                                            $color = match($agent->status) {
                                                'Chef de service' => 'danger',
                                                'Sous-directeur' => 'warning', // On retire text-dark ici
                                                default => 'info',            // On retire text-dark ici
                                            };
                                        @endphp

                                        {{-- Ajout de la classe text-white pour forcer la couleur blanche --}}
                                        <span class="badge bg-{{ $color }} text-white rounded-pill">
                                            {{ $agent->status }}
                                        </span>
                                    </td>
                                <td>
                                    <div class="text-primary small fw-bold">{{ $agent->Grade }}</div>
                                    <div class="text-muted" style="font-size: 0.85rem;">{{ $agent->Emploi }}</div>
                                </td>
                                <td class="text-end pe-3">
                                    <span class="badge border text-primary border-primary bg-white">
                                        {{ $agent->service->name ?? 'Aucun' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="fas fa-user-slash fa-3x text-light mb-3"></i>
                                    <p class="text-muted">Aucun agent trouvé dans ce service.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-navy { color: #001f3f; }
    .table-hover tbody tr:hover { background-color: #f0f7ff !important; transition: 0.3s; }
    .bg-gradient { background: linear-gradient(45deg, #ffffff, #f1f4f9); }
</style>

@endsection
