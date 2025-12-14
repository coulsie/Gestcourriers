{{-- resources/views/absences/show.blade.php --}}

@extends('layouts.app') {{-- Assuming a main layout file --}}

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Absence Details #{{ $absence->id }}
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Agent Name:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $absence->agent->name}} {{ $absence->agent->last_name }} {{ $absence->agent->first_name}}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Absence Type:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $absence->typeAbsence->name}} {{ $absence->typeAbsence->nom_type}}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Start Date:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ \Carbon\Carbon::parse($absence->date_debut)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>End Date:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ \Carbon\Carbon::parse($absence->date_fin)->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Status:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">
                                @if ($absence->approuvee)
                                    <span class="badge bg-success text-white">Approved</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Created At:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $absence->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-4 col-form-label"><strong>Last Updated:</strong></label>
                        <div class="col-sm-8">
                            <p class="form-control-static">{{ $absence->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="{{ route('absences.index') }}" class="btn btn-secondary">Back to List</a>
                    <a href="{{ route('absences.edit', $absence->id) }}" class="btn btn-warning">Edit Absence</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
