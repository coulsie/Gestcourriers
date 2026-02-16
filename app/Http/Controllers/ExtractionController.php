<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DynamicQueryExport;
use Illuminate\Support\Facades\Config;
use App\Models\ScriptExtraction;

class ExtractionController extends Controller
{
    // Affichage de l'écran avec la liste des scripts
    public function index(Request $request)
    {
        $scripts = ScriptExtraction::orderBy('nom')->get();
        $data = null;
        $type = $request->input('connection_type', 'mariadb');
        $query = $request->input('query', '');

        return view('extraction.index', compact('scripts', 'data', 'type', 'query'));
    }

       // Exécution ou Enregistrement
    public function execute(Request $request)
    {
        // --- ACTION : ENREGISTRER / MODIFIER ---
        if ($request->input('action') === 'save_script') {
            $request->validate([
                'nom_script' => 'required|string|max:255',
                'query' => 'required'
            ]);

            \App\Models\ScriptExtraction::updateOrCreate(
                ['nom' => $request->nom_script], 
                [
                    'type_entreprise'   => $request->type_entreprise,
                    'type_impot'        => $request->type_impot,
                    'type_contribuable' => $request->type_contribuable,
                    'date_debut'        => $request->date_debut,
                    'date_fin'          => $request->date_fin,
                    'parametres'        => [
                        'query'           => $request->query,
                        'connection_type' => $request->connection_type,
                        'ora_host'        => $request->ora_host,
                        'ora_db'          => $request->ora_db,
                        'ora_user'        => $request->ora_user,
                        'ora_as'          => $request->ora_as,
                    ]
                ]
            );
            return back()->with('success', "Script '" . $request->nom_script . "' enregistré avec succès !");
        }

        // --- ACTION : EXÉCUTER OU TESTER ---
        $request->validate([
            'connection_type' => 'required|in:mariadb,oracle_custom',
            'query' => 'required_unless:action,test_connection',
        ]);

        $query = $request->input('query');
        $type = $request->input('connection_type');

        // Config Oracle dynamique
        if ($type === 'oracle_custom') {
            $privilege = null;
            if ($request->input('ora_as') === 'SYSDBA') $privilege = OCI_SYSDBA;
            elseif ($request->input('ora_as') === 'SYSOPER') $privilege = OCI_SYSOPER;

            \Illuminate\Support\Facades\Config::set('database.connections.oracle_runtime', [
                'driver'   => 'oracle',
                'host'     => $request->input('ora_host'),
                'port'     => $request->input('ora_port', '1521'),
                'database' => $request->input('ora_db'),
                'username' => $request->input('ora_user'),
                'password' => $request->input('ora_pass'),
                'charset'  => 'AL32UTF8',
                'prefix'   => '',
                'options'  => ['privilege' => $privilege],
            ]);
            $connection = 'oracle_runtime';
        } else {
            $connection = 'mariadb';
        }

        try {
            // Test de connexion
            if ($request->input('action') === 'test_connection') {
                \Illuminate\Support\Facades\DB::connection($connection)->getPdo();
                return back()->with('success', "✅ Connexion réussie à $type !")->withInput();
            }

            // Exécution SQL
            if (!str_starts_with(strtolower(trim($query)), 'select')) {
                throw new \Exception("Sécurité : Seuls les SELECT sont autorisés.");
            }

            $results = \Illuminate\Support\Facades\DB::connection($connection)->select($query);
            $data = collect($results)->map(fn($x) => (array) $x);
            $lineCount = $data->count();
            $headers = $data->isEmpty() ? [] : array_keys($data->first());
            $scripts = \App\Models\ScriptExtraction::orderBy('nom')->get();

            // FORCER LE MESSAGE DANS LA SESSION AVANT LE VIEW()
            $msg = "✅ Extraction réussie ($lineCount lignes trouvées).";
            session()->flash('success', $msg);

            return view('extraction.index', [
                'scripts' => $scripts,
                'data' => $data,
                'headers' => $headers,
                'query' => $query,
                'type' => $type,
                'connection' => $connection,
                'lineCount' => $lineCount
            ]);
            

        } catch (\Exception $e) {
            return back()->withErrors(['sql_error' => $e->getMessage()])->withInput();
        }
    }



    // --- NOUVELLE MÉTHODE : SUPPRIMER ---
    public function destroy($id)
    {
        $script = ScriptExtraction::findOrFail($id);
        $script->delete();
        return back()->with('success', "Script supprimé.");
    }

    // Export Excel
    public function export(Request $request)
    {
        $query = $request->input('query');
        $connection = $request->input('connection');
        $results = DB::connection($connection)->select($query);
        $data = collect($results)->map(fn($x) => (array) $x);

        return Excel::download(new DynamicQueryExport($data), 'extraction_' . date('Ymd_His') . '.xlsx');
    }
}
