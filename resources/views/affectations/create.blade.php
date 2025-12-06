<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Affectation</title>
    <!-- Optional: Include a CSS framework like Bootstrap for better styling -->
    <link href="cdn.jsdelivr.net" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Create New Affectation</h1>

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
                        <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                            {{ $agent->name ?? 'Agent ID: ' . $agent->id }}
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
            
            <!-- date_affectation is handled automatically by the controller/migration default -->

            <button type="submit" class="btn btn-primary">Create Affectation</button>
            <a href="{{ route('affectations.index') }}" class="btn btn-secondary">Back to List</a>
        </form>
    </div>
</body>
</html>
