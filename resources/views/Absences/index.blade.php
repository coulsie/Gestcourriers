{{-- resources/views/absences/index.blade.php --}}

@extends('layouts.app') {{-- Assuming you have a layout file --}}

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Enrégistrement d'autorisations d'absence</h1>
            <a href="{{ route('absences.create') }}" class="btn btn-success">Créer une autorisation d'absence</a>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Agent Name</th>
                            <th>Absence Type</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($absences as $absence)
                            <tr>
                                <td>{{ $absence->id }}</td>
                                <td>{{ $absence->agent->name ?? 'N/A' }}</td>
                                <td>{{ $absence->typeAbsence->name ?? 'N/A' }}</td>
                                <td>{{ $absence->date_debut }}</td>
                                <td>{{ $absence->date_fin }}</td>
                                <td>
                                    @if ($absence->approuvee)
                                        <span class="badge bg-success text-white">Approuvé</span>
                                    @else
                                        <span class="badge bg-warning text-dark">En attente</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('absences.show', $absence->id) }}" class="btn btn-info btn-sm">Voir</a>
                                    <a href="{{ route('absences.edit', $absence->id) }}" class="btn btn-warning btn-sm">Modifier</a>

                                    {{-- Delete Form (using a form for POST method) --}}
                                    <form action="{{ route('absences.destroy', $absence->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this absence record?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Aucun enregistrement d'absence trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            {{ $absences->links() }}
        </div>
    </div>
</div>
@endsection
