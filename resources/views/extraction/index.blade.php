@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    {{-- En-tête --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0"><i class="fas fa-database me-2"></i>Extracteur de Données Multi-Bases</h2>
        <span class="badge bg-outline-secondary border text-secondary px-3 py-2">
            <i class="fas fa-network-wired me-1"></i> Système : Laravel 12
        </span>
    </div>

    {{-- Alertes --}}
   
    <!-- ZONE DES ALERTES (DOIT ÊTRE ICI POUR ÊTRE EN HAUT) -->
    <!-- ZONE DES ALERTES (MISE À JOUR) -->
        <div id="alert_container">
            @php 
                $msg = session('success') ?? $success_msg ?? null; 
            @endphp

            @if($msg)
                <div class="alert alert-success border-start border-4 border-success shadow-sm mb-4 d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-check-circle fa-lg me-2"></i>
                        <span class="fw-bold">{{ $msg }}</span>
                    </div>
                    {{-- On affiche le badge si lineCount existe --}}
                    @if(isset($lineCount))
                        <span class="badge bg-success shadow-sm px-3 py-2" style="font-size: 0.9rem;">
                            <i class="fas fa-list me-1"></i> {{ $lineCount }} lignes
                        </span>
                    @endif
                </div>
            @endif

            @if($errors->has('sql_error'))
                <div class="alert alert-danger border-start border-4 border-danger shadow-sm mb-4">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ $errors->first('sql_error') }}
                </div>
            @endif
        </div>


    <div class="row">
        <!-- COLONNE GAUCHE (3/12) : LISTE DES SCRIPTS -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-2">
                    <span class="fw-bold small"><i class="fas fa-list me-1"></i> SCRIPTS</span>
                    <!-- Bouton Nouveau très visible -->
                    <button type="button" class="btn btn-xs btn-light fw-bold shadow-sm" onclick="resetForm()" style="font-size: 0.7rem;">
                        <i class="fas fa-plus-circle text-success me-1"></i> NOUVEAU
                    </button>
                </div>
                <div class="list-group list-group-flush" style="max-height: 75vh; overflow-y: auto;">
                    @forelse($scripts as $script)
                        <div class="list-group-item list-group-item-action p-0 border-bottom">
                            <div class="d-flex align-items-center p-3">
                                <!-- Zone de clic pour charger -->
                                <div onclick="loadScript({{ $script->id }})" class="flex-grow-1" style="cursor: pointer;">
                                    <strong class="text-primary d-block small mb-0">{{ $script->nom }}</strong>
                                    <small class="text-muted" style="font-size: 0.7rem;">{{ $script->type_impot ?? 'Général' }}</small>
                                </div>
                                
                                <!-- Bouton Supprimer -->
                                <form action="{{ route('scripts.destroy', $script->id) }}" method="POST" onsubmit="return confirm('Supprimer ce script ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link text-danger p-0 ms-2">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-muted small">Aucun script enregistré</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- COLONNE DROITE (9/12) : FORMULAIRE ET RÉSULTATS -->
        <div class="col-md-9">
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body bg-light p-4">
                    <form action="{{ route('extraction.execute') }}" method="POST" id="extractionForm">
                        @csrf
                        
                        {{-- Filtres et Nomination --}}
                        <div class="row g-3 mb-4 p-3 bg-white rounded border border-primary shadow-sm">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-primary small text-uppercase">Nom du Script (pour enregistrement)</label>
                                <input type="text" name="nom_script" id="nom_script" class="form-control fw-bold border-primary shadow-sm" value="{{ old('nom_script') }}" placeholder="Donnez un nom pour sauvegarder...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">DATE DÉBUT</label>
                                <input type="date" name="date_debut" id="date_debut" class="form-control" value="{{ old('date_debut') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold small">DATE FIN</label>
                                <input type="date" name="date_fin" id="date_fin" class="form-control" value="{{ old('date_fin') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label small fw-bold">TYPE ENTREPRISE</label>
                                <select name="type_entreprise" id="type_entreprise" class="form-select">
                                    <option value="">-- Tous --</option>
                                    <option value="PME">PME</option>
                                    <option value="GE">Grande Entreprise</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">TYPE IMPÔT</label>
                                <input type="text" name="type_impot" id="type_impot" class="form-control" placeholder="TVA, IS, etc.">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">TYPE CONTRIBUABLE</label>
                                <input type="text" name="type_contribuable" id="type_contribuable" class="form-control" placeholder="Physique, Moral">
                            </div>
                        </div>

                        {{-- Connexion et SQL --}}
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Source de données</label>
                                <select name="connection_type" id="connection_type" class="form-select border-2 border-primary fw-bold" onchange="toggleOracleFields()">
                                    <option value="mariadb" {{ (isset($type) && $type == 'mariadb') ? 'selected' : '' }}>📦 MARIADB INTERNE</option>
                                    <option value="oracle_custom" {{ (isset($type) && $type == 'oracle_custom') ? 'selected' : '' }}>🏛️ ORACLE EXTERNE</option>
                                </select>
                            </div>

                            {{-- Bloc Oracle --}}
                            <div id="oracle_fields" class="col-12" style="display:none;">
                                <div class="card border-warning p-3 bg-white shadow-sm">
                                    <div class="row g-2">
                                        <div class="col-md-3"><label class="small fw-bold">HÔTE</label><input type="text" name="ora_host" id="ora_host" class="form-control form-control-sm border-warning"></div>
                                        <div class="col-md-2"><label class="small fw-bold">SID/BASE</label><input type="text" name="ora_db" id="ora_db" class="form-control form-control-sm border-warning fw-bold"></div>
                                        <div class="col-md-2"><label class="small fw-bold">USER</label><input type="text" name="ora_user" id="ora_user" class="form-control form-control-sm border-warning"></div>
                                        <div class="col-md-3"><label class="small fw-bold">PASSWORD</label><input type="password" name="ora_pass" class="form-control form-control-sm border-warning"></div>
                                        <div class="col-md-2">
                                            <label class="small fw-bold">MODE</label>
                                            <select name="ora_as" id="ora_as" class="form-select form-select-sm border-warning">
                                                <option value="NORMAL">NORMAL</option>
                                                <option value="SYSDBA">SYSDBA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Console SQL --}}
                            <div class="col-md-12 mt-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Console SQL / PL-SQL</label>
                                <textarea name="query" id="query_editor" class="form-control font-monospace shadow-lg" 
                                    style="background: #1e1e1e; color: #00ff00; height: 35vh; min-height: 250px; padding: 15px; border-radius: 8px;">{{ old('query', $query ?? '') }}</textarea>
                            </div>
                        </div>

                        {{-- Boutons d'action --}}
                        <div class="mt-4 d-flex">
                            <button type="submit" name="action" value="execute" class="btn btn-primary px-5 fw-bold shadow me-2">
                                <i class="fas fa-bolt me-2 text-warning"></i> EXÉCUTER
                            </button>
                            <button type="submit" name="action" value="save_script" class="btn btn-success px-4 fw-bold shadow-sm me-2">
                                <i class="fas fa-save me-2"></i> ENREGISTRER SCRIPT
                            </button>
                            <button type="submit" name="action" value="test_connection" class="btn btn-warning px-4 fw-bold shadow-sm">
                                <i class="fas fa-plug me-2"></i> TESTER CONNEXION
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bloc de Résultats Dynamique --}}
            @if(isset($data))
            <div id="results_section"> <!-- AJOUT DE CET ID -->
                <div class="card shadow-sm border-0 rounded-3 overflow-hidden mt-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h6 class="mb-0 fw-bold text-success">
                            <i class="fas fa-check-circle me-2"></i>Extraction réussie ({{ count($data) }} lignes)
                        </h6>
                        <form action="{{ route('extraction.export') }}" method="POST">
                            @csrf
                            <input type="hidden" name="query" value="{{ $query }}">
                            <input type="hidden" name="connection" value="{{ $connection ?? 'mariadb' }}">
                            <button type="submit" class="btn btn-sm btn-success fw-bold">
                                <i class="fas fa-file-excel me-1"></i> EXPORTER EXCEL
                            </button>
                        </form>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 500px;">
                            <table class="table table-hover table-striped mb-0 small">
                                <thead class="table-dark sticky-top">
                                    <tr>
                                        @foreach($headers as $header) <th>{{ strtoupper($header) }}</th> @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($data as $row)
                                        <tr>
                                            @foreach($row as $value) <td>{{ $value }}</td> @endforeach
                                        </tr>
                                    @empty
                                        <tr><td colspan="{{ count($headers) ?: 1 }}" class="text-center py-4">Aucune donnée trouvée.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
/**
 * Alterne l'affichage des champs spécifiques à Oracle
 */
function toggleOracleFields() {
    const connectionType = document.getElementById('connection_type');
    const oracleFields = document.getElementById('oracle_fields');
    if (connectionType && oracleFields) {
        oracleFields.style.display = (connectionType.value === 'oracle_custom') ? 'block' : 'none';
    }
}

/**
 * Réinitialise complètement l'interface (Formulaire + Résultats + Alertes)
 */
function resetForm() {
    // 1. Vide tous les champs du formulaire
    document.getElementById('nom_script').value = '';
    document.getElementById('query_editor').value = '';
    document.getElementById('date_debut').value = '';
    document.getElementById('date_fin').value = '';
    document.getElementById('type_impot').value = '';
    document.getElementById('type_entreprise').value = '';
    document.getElementById('type_contribuable').value = '';
    
    // 2. Remet la connexion par défaut
    document.getElementById('connection_type').value = 'mariadb';
    toggleOracleFields();

    // 3. EFFACE LE TABLEAU DE RÉSULTATS (si présent)
    const alertContainer = document.getElementById('alert_container');
    if (alertContainer) alertContainer.innerHTML = ''; 

    const results = document.getElementById('results_section');
    if (results) {
        results.style.display = 'none';


    }

    // 4. Supprime les messages d'alerte (succès/erreur) existants
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => alert.remove());

    console.log("Écran réinitialisé avec succès");
}

/**
 * Charge un script enregistré depuis l'API
 */
function loadScript(id) {
    fetch(`/extraction/scripts/${id}`)
        .then(res => {
            if (!res.ok) throw new Error('Erreur lors de la récupération du script');
            return res.json();
        })
        .then(data => {
            // Remplissage des champs de base
            document.getElementById('nom_script').value = data.nom || '';
            if(data.date_debut) document.getElementById('date_debut').value = data.date_debut.substring(0, 10);
            if(data.date_fin) document.getElementById('date_fin').value = data.date_fin.substring(0, 10);
            document.getElementById('type_entreprise').value = data.type_entreprise || '';
            document.getElementById('type_impot').value = data.type_impot || '';
            document.getElementById('type_contribuable').value = data.type_contribuable || '';
            
            if(data.parametres) {
                // Gestion si parametres est déjà un objet ou une string JSON
                const params = typeof data.parametres === 'string' ? JSON.parse(data.parametres) : data.parametres;
                
                document.getElementById('query_editor').value = params.query || '';
                document.getElementById('connection_type').value = params.connection_type || 'mariadb';
                
                // Remplissage des champs Oracle si les éléments existent
                if(params.ora_host && document.getElementById('ora_host')) 
                    document.getElementById('ora_host').value = params.ora_host;
                if(params.ora_db && document.getElementById('ora_db')) 
                    document.getElementById('ora_db').value = params.ora_db;
                if(params.ora_user && document.getElementById('ora_user')) 
                    document.getElementById('ora_user').value = params.ora_user;
                if(params.ora_as && document.getElementById('ora_as')) 
                    document.getElementById('ora_as').value = params.ora_as;
            }
            
            toggleOracleFields();
            
            // Remonte en haut de page pour voir le formulaire rempli
            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(err => {
            console.error("Erreur chargement script:", err);
            alert("Impossible de charger le script.");
        });
}

// Initialisation au chargement de la page
window.onload = toggleOracleFields;
</script>

@endsection
