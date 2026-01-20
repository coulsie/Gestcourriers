@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            {{-- Carte avec bordure supérieure très prononcée --}}
            <div class="card shadow-2xl border-0 border-top border-5 border-primary">
                <div class="card-header bg-dark py-3">
                    <h5 class="mb-0 text-white fw-bold">
                        <i class="fas fa-user-plus me-2 text-warning"></i>Créer un Nouvel Utilisateur
                    </h5>
                </div>

                <div class="card-body p-4 bg-white">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nom complet -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-bolder text-primary small text-uppercase">Nom complet</label>
                            <div class="input-group border border-primary rounded">
                                <span class="input-group-text bg-primary text-white border-0"><i class="fas fa-user"></i></span>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Ex: Jean Dupont" value="{{ old('name') }}" required>
                                @error('name') <span class="invalid-feedback px-2">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bolder text-primary small text-uppercase">Adresse Email</label>
                            <div class="input-group border border-primary rounded">
                                <span class="input-group-text bg-primary text-white border-0"><i class="fas fa-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       placeholder="exemple@domaine.com" value="{{ old('email') }}" required>
                                @error('email') <span class="invalid-feedback px-2">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Rôle (Ajouté) -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bolder text-primary small text-uppercase">Rôle / Privilège Système</label>
                            <div class="input-group border border-primary rounded">
                                <span class="input-group-text bg-primary text-white border-0"><i class="fas fa-user-shield"></i></span>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="" selected disabled>Choisir un rôle...</option>
                                    {{-- Récupération dynamique des rôles en 2026 --}}
                                    @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role') <span class="invalid-feedback px-2">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <!-- Mot de passe -->
                            <div class="col-md-6 mb-4">
                                <label for="password" class="form-label fw-bolder text-danger small text-uppercase">Mot de passe</label>
                                <div class="input-group border border-danger rounded">
                                    <span class="input-group-text bg-danger text-white border-0"><i class="fas fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                                    @error('password') <span class="invalid-feedback px-2">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Confirmation -->
                            <div class="col-md-6 mb-4">
                                <label for="password_confirmation" class="form-label fw-bolder text-danger small text-uppercase">Confirmation</label>
                                <div class="input-group border border-danger rounded">
                                    <span class="input-group-text bg-danger text-white border-0"><i class="fas fa-check-double"></i></span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning border-start border-warning border-5 shadow-sm d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle me-3 fs-4 text-warning"></i>
                            <div class="text-dark fw-bold small">
                                L'utilisateur recevra ses identifiants par email immédiatement après la validation.
                            </div>
                        </div>

                        <hr class="my-4 border-2 border-dark opacity-25">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-dark px-4 fw-bold shadow-sm">
                                <i class="fas fa-times-circle me-1"></i> Annuler
                            </a>
                            <button type="submit" class="btn btn-success px-5 fw-bold shadow-lg">
                                <i class="fas fa-check-circle me-1"></i> VALIDER LA CRÉATION
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { border-radius: 15px; overflow: hidden; }
    .card-header { border-bottom: 2px solid rgba(0,0,0,0.1); }
    .input-group-text { min-width: 45px; justify-content: center; }

    /* Animation au focus */
    .input-group:focus-within {
        box-shadow: 0 0 10px rgba(13, 110, 253, 0.25);
        transform: scale(1.01);
        transition: all 0.2s ease-in-out;
    }

    .form-control, .form-select {
        border: none !important;
        padding: 12px;
        font-weight: 500;
    }

    .form-control::placeholder { color: #adb5bd; font-weight: normal; }

    .btn-success {
        background-color: #198754;
        border: none;
        letter-spacing: 1px;
        transition: all 0.3s;
    }

    .btn-success:hover {
        background-color: #146c43;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(25, 135, 84, 0.4);
    }
</style>
@endsection
