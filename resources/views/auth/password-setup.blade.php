@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4>Configuration initiale du mot de passe</h4>
                </div>
                <div class="card-body">
                    <p class="text-muted">Par sécurité, vous devez changer votre mot de passe lors de votre première connexion.</p>

                    <form method="POST" action="{{ route('password.setup.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Enregistrer et continuer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
