@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0"><i class="fas fa-database me-2"></i>Extracteur de Données Multi-Bases</h2>
        <span class="badge bg-outline-secondary border text-secondary px-3 py-2">
            <i class="fas fa-network-wired me-1"></i> Système : Laravel 12
        </span>
    </div>

    {{-- Affichage des erreurs SQL / Connexion --}}
    @if($errors->has('sql_error'))
        <div class="alert alert-danger border-start border-4 border-danger shadow-sm mb-4">
            <div class="d-flex">
                <i class="fas fa-exclamation-circle fa-2x me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Erreur d'exécution ou de connexion</h6>
                    <code class="small text-dark">{{ $errors->first('sql_error') }}</code>
                </div>
            </div>
        </div>
    @endif

    {{-- Affichage du succès --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-body bg-light p-4">
            <form action="{{ route('extraction.execute') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <!-- CHOIX DE LA CONNEXION -->
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Source de données</label>
                        <select name="connection_type" id="connection_type" class="form-select border-2 border-primary fw-bold shadow-sm" onchange="toggleOracleFields()">
                            <option value="mariadb" {{ (old('connection_type') == 'mariadb' || (isset($type) && $type == 'mariadb')) ? 'selected' : '' }}>
                                📦 MARIADB (Interne / .env)
                            </option>
                            <option value="oracle_custom" {{ (old('connection_type') == 'oracle_custom' || (isset($type) && $type == 'oracle_custom')) ? 'selected' : '' }}>
                                🏛️ ORACLE (Externe / Manuel)
                            </option>
                        </select>
                    </div>

                    <!-- FORMULAIRE DE CONNEXION ORACLE (MASQUÉ PAR DÉFAUT) -->
                    <div id="oracle_fields" class="col-12" style="{{ (old('connection_type') == 'oracle_custom' || (isset($type) && $type == 'oracle_custom')) ? '' : 'display:none;' }}">
                        <div class="row g-2 p-3 bg-white rounded border border-warning">
                            <div class="col-md-12 mb-2">
                                <span class="badge bg-warning text-dark"><i class="fas fa-key me-1"></i> Paramètres de connexion Oracle</span>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Hôte (IP/Host)</label>
                                <input type="text" name="ora_host" class="form-control form-control-sm" placeholder="192.168.1.10" value="{{ old('ora_host') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Nom Base (SID/Service)</label>
                                <input type="text" name="ora_db" class="form-control form-control-sm" placeholder="XE" value="{{ old('ora_db') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Utilisateur</label>
                                <input type="text" name="ora_user" class="form-control form-control-sm" placeholder="SYSTEM" value="{{ old('ora_user') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Mot de passe</label>
                                <input type="password" name="ora_pass" class="form-control form-control-sm" placeholder="*******">
                            </div>
                        </div>
                    </div>

                    <!-- ÉDITEUR SQL -->
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small text-uppercase">Script SQL / PL-SQL</label>
                        <textarea name="query" rows="8" class="form-control font-monospace shadow-sm border-2" 
                                  placeholder="SELECT * FROM table ..." 
                                  style="background: #1e1e1e; color: #dcdcdc; font-size: 14px; border-color: #333;">{{ old('query', $query ?? '') }}</textarea>
                    </div>

                    
                </div>
                <button type="submit" class="btn btn-primary px-5 fw-bold shadow">
                    <i class="fas fa-bolt me-2 text-warning"></i> EXÉCUTER LE SCRIPT
                </button>
               <button type="submit" name="action" value="test_connection" class="btn btn-warning px-4 fw-bold shadow-sm me-2 text-dark">
                    <i class="fas fa-plug-circle-check me-2"></i> TESTER LA CONNEXION
                </button>

            </form>
        </div>
    </div>

    @if(isset($data))
        {{-- BLOC RÉSULTATS (Inchangé mais avec ID dynamique) --}}
        <div class="card shadow border-0 rounded-3 overflow-hidden">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-table me-2 text-primary"></i>Résultats ({{ count($data) }})</h6>
                    <small class="text-muted text-uppercase">Source : {{ $type }}</small>
                </div>
                <form action="{{ route('extraction.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="query" value="{{ $query }}">
                    <input type="hidden" name="connection_type" value="{{ $type }}">
                    {{-- On renvoie les identifiants pour l'export si Oracle --}}
                    @if($type === 'oracle_custom')
                        <input type="hidden" name="ora_host" value="{{ request('ora_host') }}">
                        <input type="hidden" name="ora_db" value="{{ request('ora_db') }}">
                        <input type="hidden" name="ora_user" value="{{ request('ora_user') }}">
                        <input type="hidden" name="ora_pass" value="{{ request('ora_pass') }}">
                    @endif
                    <button type="submit" class="btn btn-success fw-bold"><i class="fas fa-file-excel me-2"></i>EXPORTER</button>
                </form>
            </div>
            <div class="table-responsive" style="max-height: 450px;">
                <table class="table table-sm table-hover table-bordered mb-0">
                    <thead class="table-dark sticky-top small">
                        <tr>@foreach($headers as $h) <th>{{ $h }}</th> @endforeach</tr>
                    </thead>
                    <tbody class="bg-white small">
                        @foreach($data as $row)
                            <tr>@foreach($row as $v) <td>{{ $v ?? 'NULL' }}</td> @endforeach</tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<script>
function toggleOracleFields() {
    const type = document.getElementById('connection_type').value;
    const fields = document.getElementById('oracle_fields');
    fields.style.display = (type === 'oracle_custom') ? 'block' : 'none';
}
</script>
@endsection
