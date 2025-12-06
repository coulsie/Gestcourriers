@extends('layouts.app')

@section('title', 'Edit Affectation')

@section('content')
    <h1>Edit Affectation #{{ $affectation->id }}</h1>

    <!-- Display Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Display Success Messages -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Affectation Form -->
    <form action="{{ route('affectations.update', $affectation->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Method spoofing for PUT request -->

        <!-- Courrier Selection -->
        <div class="mb-3">
            <label for="courrier_id" class="form-label">Courrier (Mail Item):</label>
            <select class="form-control" id="courrier_id" name="courrier_id" required {{ $affectation->statut != 'pending' ? 'disabled' : '' }}>
                <option value="">Select a Courrier</option>
                @foreach($courriers as $courrier)
                    <option value="{{ $courrier->id }}" {{ (old('courrier_id', $affectation->courrier_id) == $courrier->id) ? 'selected' : '' }}>
                        {{ $courrier->subject ?? 'Courrier ID: ' . $courrier->id }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Agent Selection -->
        <div class="mb-3">
            <label for="agent_id" class="form-label">Agent (Recipient):</label>
            <select class="form-control" id="agent_id" name="agent_id" required>
                <option value="">Select an Agent</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ (old('agent_id', $affectation->agent_id) == $agent->id) ? 'selected' : '' }}>
                        {{ $agent->name}}  {{ $agent->last_name}} {{ $agent->first_name}}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Statut Input -->
        <div class="mb-3">
            <label for="statut" class="form-label">Status:</label>
            <select class="form-control" id="statut" name="statut" required>
                <option value="pending" {{ (old('statut', $affectation->statut) == 'pending') ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ (old('statut', $affectation->statut) == 'in_progress') ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ (old('statut', $affectation->statut) == 'completed') ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <!-- Commentaires Input -->
        <div class="mb-3">
            <label for="commentaires" class="form-label">Comments:</label>
            <textarea class="form-control" id="commentaires" name="commentaires" rows="3">{{ old('commentaires', $affectation->commentaires) }}</textarea>
        </div>

        <!-- Date Traitement Input -->
        <div class="mb-3">
            <label for="date_traitement" class="form-label">Date Traitement (Optional):</label>
            <input type="datetime-local" class="form-control" id="date_traitement" name="date_traitement" 
                   value="{{ old('date_traitement', $affectation->date_traitement ? $affectation->date_traitement->format('Y-m-d\TH:i') : '') }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Affectation</button>
        <a href="{{ route('affectations.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
