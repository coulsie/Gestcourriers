{{-- resources/views/type_absences/show.blade.php --}}

@extends('layouts.app') {{-- Assuming a main layout file --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Absence Type Details: {{ $typeAbsence->nom_type }}
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>ID:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $typeAbsence->id }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Name:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $typeAbsence->nom_type }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Code:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $typeAbsence->code }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Description:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $typeAbsence->description ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Paid Status:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                @if ($typeAbsence->est_paye)
                                    <span class="badge bg-success text-white">Paid</span>
                                @else
                                    <span class="badge bg-danger text-white">Unpaid</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Created At:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $typeAbsence->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Last Updated:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $typeAbsence->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('typeabsences.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('typeabsences.edit', $typeAbsence->id) }}" class="btn btn-warning">Edit Type</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
