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
                    <!-- FORMULAIRE DE CONNEXION ORACLE (MISE À JOUR) -->
                <<div id="oracle_fields" class="col-12" style="{{ (old('connection_type') == 'oracle_custom' || (isset($type) && $type == 'oracle_custom')) ? '' : 'display:none;' }}">
    <div class="card border-warning shadow-sm mb-3">
        <div class="card-body p-3 bg-white rounded">
            <!-- En-tête de la zone -->
            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                <span class="badge bg-warning text-dark fw-bold">
                    <i class="fas fa-key me-1"></i> AUTHENTIFICATION ORACLE LOGON
                </span>
                <small class="text-muted italic"><i class="fas fa-info-circle me-1"></i>Paramètres de session active</small>
            </div>

            <!-- Grille des champs alignés -->
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">HÔTE (IP/HOST)</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-warning"><i class="fas fa-server"></i></span>
                        <input type="text" name="ora_host" class="form-control border-warning" placeholder="192.168.1.10" value="{{ old('ora_host') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="small fw-bold text-muted mb-1">BASE (SID)</label>
                    <input type="text" name="ora_db" class="form-control form-control-sm border-warning fw-bold" placeholder="orcl" value="{{ old('ora_db') }}">
                </div>

                <div class="col-md-2">
                    <label class="small fw-bold text-muted mb-1">USERNAME</label>
                    <input type="text" name="ora_user" class="form-control form-control-sm border-warning" placeholder="SYSTEM" value="{{ old('ora_user') }}">
                </div>

                <div class="col-md-3">
                    <label class="small fw-bold text-muted mb-1">PASSWORD</label>
                    <input type="password" name="ora_pass" class="form-control form-control-sm border-warning" placeholder="*******">
                </div>

                <div class="col-md-2">
                    <label class="small fw-bold text-muted mb-1">CONNECT AS</label>
                    <select name="ora_as" class="form-select form-select-sm border-warning fw-bold text-primary">
                        <option value="NORMAL" {{ old('ora_as') == 'NORMAL' ? 'selected' : '' }}>NORMAL</option>
                        <option value="SYSDBA" {{ old('ora_as') == 'SYSDBA' ? 'selected' : '' }}>SYSDBA</option>
                        <option value="SYSOPER" {{ old('ora_as') == 'SYSOPER' ? 'selected' : '' }}>SYSOPER</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
                    <!-- ÉDITEUR SQL -->
                    <!-- ÉDITEUR SQL AGRANDI -->
                <div class="col-md-12">
                    <label class="form-label fw-bold text-muted small text-uppercase">
                        <i class="fas fa-code me-1 text-primary"></i> Console de Rédaction SQL / PL-SQL
                    </label>
                    <textarea name="query" 
                            class="form-control font-monospace shadow-lg border-2" 
                            placeholder="SELECT * FROM table ..." 
                            style="background: #1e1e1e; 
                                    color: #00ff00; 
                                    font-size: 16px; 
                                    line-height: 1.6; 
                                    border-color: #333; 
                                    height: 50vh; /* Utilise 50% de la hauteur de l'écran */
                                    min-height: 400px; 
                                    resize: vertical; 
                                    padding: 20px;
                                    border-radius: 10px;">{{ old('query', $query ?? '') }}</textarea>
                    <div class="form-text text-end small italic text-muted">
                        <i class="fas fa-info-circle"></i> Astuce : Étirez le coin inférieur droit pour agrandir davantage.
                    </div>
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
