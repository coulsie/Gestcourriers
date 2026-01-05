@extends('layouts.app')

<style>
@media print {
    .btn, .navbar, .sidebar, footer {
        display: none !important;
    }
}
</style>
@section('content')
<div class="container mt-4">
    <h2 class="mb-4">État des Présences - Année {{ $annee }}</h2>

    <!-- Navigation par onglets -->
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#daily">Journalier</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#weekly">Hebdomadaire</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#monthly">Mensuel</button></li>
    </ul>

    <div class="tab-content border p-3 bg-white rounded shadow-sm">

        <!-- SECTION JOURNALIÈRE -->
        <div class="tab-pane fade show active" id="daily">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>Date</th><th>Total</th><th>Présents</th><th>Retards</th><th>Absents</th><th>Taux %</th></tr>
                </thead>
                <tbody>
                    @foreach($journalier as $j)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($j->date)->translatedFormat('d F Y') }}</td>
                        <td>{{ $j->total }}</td>
                        <td><span class="badge bg-success text-white">{{ $j->presents }}</span></td>
                        <td><span class="badge bg-warning text-dark">{{ $j->retards }}</span></td>
                        <td><span class="badge bg-danger text-white">{{ $j->absents }}</span></td>
                        <td class="fw-bold">{{ number_format(($j->presents / $j->total) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- SECTION HEBDOMADAIRE -->
        <div class="tab-pane fade" id="weekly">
            <div class="row">
                @foreach($hebdo as $h)
                <div class="col-md-3 mb-3">
                    <div class="card text-center h-100">
                        <div class="card-header fw-bold text-primary">Semaine {{ $h->semaine }}</div>
                        <div class="card-body">
                            <h3 class="card-title">{{ $h->presents }}/{{ $h->total }}</h3>
                            <small class="text-muted">Présences cumulées</small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- SECTION MENSUELLE -->
        <div class="tab-pane fade" id="monthly">
            <table class="table align-middle">
                <thead><tr><th>Mois</th><th>Statistiques</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($mensuel as $m)
                    <tr>
                        <td class="fw-bold text-uppercase">{{ \Carbon\Carbon::create()->month($m->mois)->translatedFormat('F') }}</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                @php $pct = ($m->presents / $m->total) * 100; @endphp
                                <div class="progress-bar bg-success" style="width: {{ $pct }}%">{{ round($pct) }}%</div>
                            </div>
                        </td>
                        <td><button class="btn btn-sm btn-outline-info">Détails</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection
