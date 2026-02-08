@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Courrier Confidentiel</h5>
                </div>
                <div class="card-body p-4">
                    <p class="text-muted">Veuillez saisir le code numérique pour consulter ce document.</p>
                    
                    <form action="{{ route('courriers.unlock', $courrier->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <input type="password" name="code_saisi" 
                                   class="form-control form-control-lg text-center fw-bold" 
                                   placeholder="••••••" 
                                   inputmode="numeric" 
                                   pattern="[0-9]*" 
                                   maxlength="6" 
                                   required 
                                   autofocus>
                            @if(session('error'))
                                <div class="text-danger mt-2 small">{{ session('error') }}</div>
                            @endif
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                                <i class="fas fa-unlock-alt me-2"></i> Valider
                            </button>
                            <a href="{{ route('courriers.index') }}" class="btn btn-link text-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
