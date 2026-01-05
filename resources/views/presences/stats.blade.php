@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Statistiques des Présences - {{ $annee }}</h2>

    {{-- Filtre de recherche --}}
    <form action="{{ route('presences.stats') }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <input type="date" name="date_debut" class="form-control" value="{{ $dateDebut }}">
        </div>
        <div class="col-md-3">
            <input type="date" name="date_fin" class="form-control" value="{{ $dateFin }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Total</th>
                    <th class="text-center">Présents</th>
                    <th class="text-center">Retards</th>
                    <th class="text-center">Absents</th>
                </tr>
            </thead>
            <tbody>
                @foreach($journalier as $stat)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}</td>
                        <td><span class="badge bg-secondary">{{ $stat->total }}</span></td>

                        {{-- Utilisation de couleurs contextuelles --}}
                        <td class="text-center">
                            <span class="badge bg-success text-white">
                                {{ $stat->presents }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning text-dark">
                                {{ $stat->retards }}
                            </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-danger text-white">
                                {{ $stat->absents }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
