@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white text-center py-4 rounded-top-4">
                    <h4 class="mb-0 fw-bold"><i class="fas fa-lock-open me-2"></i>Mot de passe oublié</h4>
                </div>

                <div class="card-body p-4">
                    <p class="text-muted small mb-4">
                        Entrez votre adresse e-mail. Nous vous enverrons un lien de réinitialisation qui vous permettra d'en choisir un nouveau.
                    </p>

                    @if (session('status'))
                        <div class="alert alert-success shadow-sm mb-4" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-uppercase">Adresse E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-primary"></i></span>
                                <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="votre@mail.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                                Envoyer le lien de récupération
                            </button>
                            <a href="{{ route('login') }}" class="btn btn-link text-decoration-none text-muted small">
                                <i class="fas fa-arrow-left me-1"></i> Retour à la connexion
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
