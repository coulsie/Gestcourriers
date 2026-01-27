@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0 border-top border-4 border-success">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 text-success"><i class="fas fa-plus-circle me-2"></i>Créer un nouveau Rôle</h5>
        </div>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <!-- Nom du Rôle -->
                <div class="mb-4">
                    <label for="name" class="form-label fw-bold">Nom du Rôle</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Ex: Superviseur, Editeur..." value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Attribution des Permissions -->
                <div class="mb-3">
                    <label class="form-label fw-bold d-block">Attribuer des permissions initiales :</label>
                    <div class="row">
                        @foreach($permissions as $permission)
                            <div class="col-md-3 mb-2">
                                <div class="form-check border p-2 rounded-3 hover-shadow-sm">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                           value="{{ $permission->name }}" id="perm-{{ $permission->id }}">
                                    <label class="form-check-label small" for="perm-{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light py-3 text-end">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary px-4">Annuler</a>
                <button type="submit" class="btn btn-success px-4 shadow-sm">
                    <i class="fas fa-save me-1"></i> Créer le rôle
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
