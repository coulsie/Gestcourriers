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
    <!-- En-tête -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 shadow-sm rounded-4 bg-white border-start border-5 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-primary fw-bold mb-1">
                            <i class="fas fa-search me-2"></i>Recherche d'Agents
                        </h2>
                        <p class="text-muted mb-0">Filtrez les effectifs par service, nom ou matricule.</p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary fs-6 shadow-sm">{{ $agents->count() }} Agents trouvés</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Zone de Recherche et Filtres -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <form action="{{ route('agents.par.service') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-5">
                    <label class="form-label fw-bold"><i class="fas fa-sitemap text-primary me-2"></i>Service</label>
                    <select name="service_id" class="form-select border-2 shadow-none" onchange="this.form.submit()">
                        <option value="">Tous les services</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} ({{ $service->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-bold"><i class="fas fa-user text-primary me-2"></i>Recherche rapide</label>
                    <div class="input-group">
                        <input type="text" id="tableSearch" class="form-control border-2 shadow-none"
                               placeholder="Tapez un nom, un matricule ou un grade...">
                        <span class="input-group-text bg-white border-2 border-start-0 text-muted">
                            <i class="fas fa-keyboard"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-2 d-grid">
                    <label class="form-label invisible">Action</label>
                    <button type="submit" class="btn btn-primary fw-bold shadow-sm">
                        <i class="fas fa-sync-alt"></i> Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Résultats -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="agentsTable">
                <thead class="bg-dark text-white">
                    <tr class="text-uppercase small fw-bold">
                        <th class="py-3 ps-4">Matricule</th>
                        <th>Identité</th>
                        <th>Statut & Grade</th>
                        <th>Contact</th>
                        <th class="text-end pe-4">Service</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($agents as $agent)
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border fw-bold px-3 py-2">{{ $agent->matricule }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                         style="width: 40px; height: 40px; font-size: 1.2rem;">
                                        {{ substr($agent->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ strtoupper($agent->last_name) }}</div>
                                        <div class="text-muted small">{{ $agent->first_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $agent->status == 'Chef de service' ? 'bg-danger' : 'bg-info' }} text-white mb-1">
                                    {{ $agent->status }}
                                </span>
                                {{-- Si vous voulez aussi le grade en blanc, remplacez 'text-muted' par 'text-white' ci-dessous --}}
                                <div class="text-muted small fw-bold">{{ $agent->Grade }}</div>
                            </td>
                            <td>
                                <div class="small"><i class="fas fa-envelope text-muted me-2"></i>{{ $agent->email_professionnel }}</div>
                                <div class="small mt-1"><i class="fas fa-phone text-muted me-2"></i>{{ $agent->phone_number }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <span class="badge border border-primary text-primary px-3">{{ $agent->service->name ?? 'N/A' }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <img src="illustrations.popsy.co" style="width: 150px;" class="mb-3">
                                <p class="text-muted fs-5">Aucun agent ne correspond à votre recherche.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('tableSearch');
    const tableRows = document.querySelectorAll('#agentsTable tbody tr');

    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.toLowerCase();

        tableRows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
});
</script>

@endsection
