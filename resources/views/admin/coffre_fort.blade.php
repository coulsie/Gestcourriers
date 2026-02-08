@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow border-0">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-vault me-2"></i> Coffre-fort des Codes d'Accès</h5>
            <span class="badge bg-danger">Accès Administrateur</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Référence</th>
                            <th>Objet</th>
                            <th>Date d'Enreg.</th>
                            <th class="text-center">Code Confidentiel</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courriers as $courrier)
                            <tr>
                                <td><strong>{{ $courrier->reference }}</strong></td>
                                <td>{{ Str::limit($courrier->objet, 50) }}</td>
                                <td>{{ \Carbon\Carbon::parse($courrier->created_at)->format('d/m/Y') }}</td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm justify-content-center">
                                        <!-- Le code est décrypté ici mais caché par le type "password" -->
                                        <input type="password" 
                                               class="form-control text-center border-0 bg-light" 
                                               style="max-width: 100px;" 
                                               value="{{ Crypt::decryptString($courrier->code_acces) }}" 
                                               id="input-{{ $courrier->id }}" 
                                               readonly>
                                        <button class="btn btn-outline-secondary border-0" 
                                                type="button" 
                                                onclick="toggleCode('{{ $courrier->id }}')">
                                            <i class="fas fa-eye" id="icon-{{ $courrier->id }}"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Aucun courrier confidentiel enregistré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCode(id) {
    const input = document.getElementById('input-' + id);
    const icon = document.getElementById('icon-' + id);
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
