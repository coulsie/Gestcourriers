@extends('layouts.app')

@section('title', 'Create Affectation')

@section('content')

    <h1>Nouvel Affectation de courrier</h1>

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

    <!-- Affectation Form -->
    <form action="{{ route('affectations.store') }}" method="POST">
        @csrf

        <!-- Courrier Selection -->
        <div class="mb-3">
            <label for="courrier_id" class="form-label">Courrier (Mail Item):</label>
            <select class="form-control" id="courrier_id" name="courrier_id" required>
                <option value="">Select a Courrier</option>
                @foreach($courriers as $courrier)
                    <option value="{{ $courrier->id }}" {{ old('courrier_id') == $courrier->id ? 'selected' : '' }}>
                        {{ $courrier->subject ?? 'Courrier ID: ' . $courrier->id }}{{ $courrier->reference}} {{ $courrier->objet}} {{ $courrier->date_courrier}}{{ $courrier->expediteur}}{{ $courrier->destinataire}}{{ $courrier->assigne_agent_last_name}}{{ $courrier->assigne_agent_first_name}}
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
                    <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                        {{ $agent->name ?? 'Agent ID: ' . $agent->id }}{{ $agent->last_name}} {{ $agent->first_name}}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Statut Input -->
        <div class="mb-3">
            <label for="statut" class="form-label">Status:</label>
            <select class="form-control" id="statut" name="statut" required>
                <option value="pending" {{ old('statut') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ old('statut') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ old('statut') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        <!-- Commentaires Input -->
        <div class="mb-3">
            <label for="commentaires" class="form-label">Comments:</label>
            <textarea class="form-control" id="commentaires" name="commentaires" rows="3">{{ old('commentaires') }}</textarea>
        </div>

        <button type="submit" class="btn btn-success">Affecter le courrier</button>
        <a href="{{ route('affectations.index') }}" class="btn btn-danger">Retour à la liste</a>
    </form>

@endsection
