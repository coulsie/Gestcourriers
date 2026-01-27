@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Modifier les permissions : {{ $role->name }}</h5>
        </div>
        <form action="{{ route('roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    
                    @foreach($permissions as $permission)
                        <div class="col-md-3 mb-3">
                            <div class="form-check border p-2 rounded shadow-sm">
                                <input class="form-check-input" type="checkbox" name="permissions[]"
                                    value="{{ $permission->id }}" id="perm-{{ $permission->id }}"
                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                <label class="form-check-label ms-2 fw-bold" for="perm-{{ $permission->id }}">
                                    {{ $permission->name }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer bg-light text-end">
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Annuler</a>
                <button type="submit" class="btn btn-success">Enregistrer les permissions</button>
            </div>
        </form>
    </div>
</div>
@endsection
