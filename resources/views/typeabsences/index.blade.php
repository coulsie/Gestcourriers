{{-- resources/views/type_absences/index.blade.php --}}

@extends('layouts.app') {{-- Assuming a main layout file --}}

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Types d'absence</h1>
            <a href="{{ route('typeabsences.create') }}" class="btn btn-success">Créer un nouveau type</a>

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
                            <th>Name</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Paid Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($type_Absences as $type)
                            <tr>
                                <td>{{ $type->id }}</td>
                                <td>{{ $type->nom_type }}</td>
                                <td>{{ $type->code }}</td>
                                <td>{{ Str::limit($type->description, 50) }}</td> {{-- Using Str::limit for cleaner display --}}
                                <td>
                                    @if ($type->est_paye)
                                        <span class="badge bg-success text-white">Paid</span>
                                    @else
                                        <span class="badge bg-danger text-white">Unpaid</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('typeabsences.show', $type->id) }}" class="btn btn-info btn-sm">View</a>
                                    <a href="{{ route('typeabsences.edit', $type->id) }}" class="btn btn-warning btn-sm">Edit</a>

                                    {{-- Delete Form --}}
                                    <form action="{{ route('typeabsences.destroy', $type->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this absence type?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No absence types found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links if using pagination in controller --}}
            {{-- $typeAbsences->links() --}}
        </div>
    </div>
</div>
@endsection
