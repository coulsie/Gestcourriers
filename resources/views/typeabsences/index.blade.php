{{-- resources/views/type_absences/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Carte principale pour un rendu plus propre -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-primary font-weight-bold">
                        <i class="fas fa-calendar-times mr-2"></i> Liste des Types d'Absence
                    </h5>
                    <a href="{{ route('typeabsences.create') }}" class="btn btn-success shadow-sm">
                        <i class="fas fa-plus-circle"></i> Créer un nouveau type
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4">
                            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="bg-light text-secondary">
                                <tr>
                                    <th class="border-0">#</th>
                                    <th class="border-0">Nom</th>
                                    <th class="border-0">Code</th>
                                    <th class="border-0">Description</th>
                                    <th class="border-0 text-center">Statut Rémunération</th>
                                    <th class="border-0 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($type_Absences as $type)
                                    <tr>
                                        <td class="font-weight-bold text-muted">{{ $type->id }}</td>
                                        <td class="font-weight-bold text-dark">{{ $type->nom_type }}</td>
                                        <td><span class="badge badge-soft-info p-2" style="background-color: #e1f5fe; color: #01579b;">{{ $type->code }}</span></td>
                                        <td class="text-muted">{{ Str::limit($type->description, 50) }}</td>
                                        <td class="text-center">
                                            @if ($type->est_paye)
                                                <span class="badge badge-pill bg-success-soft text-success px-3 py-2" style="background-color: #e8f5e9;">
                                                    <i class="fas fa-money-bill-wave mr-1"></i> Payé
                                                </span>
                                            @else
                                                <span class="badge badge-pill bg-danger-soft text-danger px-3 py-2" style="background-color: #ffebee;">
                                                    <i class="fas fa-hand-holding-usd mr-1"></i> Non payé
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <div class="btn-group shadow-sm" role="group">
                                                <a href="{{ route('typeabsences.show', $type->id) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('typeabsences.edit', $type->id) }}" class="btn btn-sm btn-outline-warning" title="Modifier">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="if(confirm('Voulez-vous vraiment supprimer ce type ?')) { document.getElementById('delete-form-{{ $type->id }}').submit(); }"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            {{-- Formulaire de suppression invisible --}}
                                            <form id="delete-form-{{ $type->id }}" action="{{ route('typeabsences.destroy', $type->id) }}" method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i><br>
                                            Aucun type d'absence trouvé.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{-- $type_Absences->links() --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
