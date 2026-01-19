@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-lock me-2 text-warning"></i> SÉCURITÉ : PREMIÈRE CONNEXION</h5>
                </div>
                <div class="card-body p-4 bg-light">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nouveau mot de passe *</label>
                            <input type="password" name="password" class="form-control border-2 shadow-sm fs-5" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold">Confirmer le nouveau mot de passe *</label>
                            <input type="password" name="password_confirmation" class="form-control border-2 shadow-sm fs-5" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-black shadow border-2">
                            ACTIVER MON COMPTE
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
