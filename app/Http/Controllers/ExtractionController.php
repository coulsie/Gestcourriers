<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DynamicQueryExport;
use Illuminate\Support\Facades\Config;


class ExtractionController extends Controller
{
    // Affichage de l'écran de saisie
    public function index()
    {
        return view('extraction.index');
    }

    // Exécution du script SQL ou Oracle

public function execute(Request $request)
{
    // 1. Validation de base
    $request->validate([
        'connection_type' => 'required|in:mariadb,oracle_custom',
        'query' => 'required_unless:action,test_connection',
    ]);

    $query = $request->input('query');
    $type = $request->input('connection_type');

    // 2. Configuration dynamique si Oracle Externe
    if ($type === 'oracle_custom') {
        // Mapping du privilège Oracle (Connect AS)
        $privilege = null;
        if ($request->input('ora_as') === 'SYSDBA') {
            $privilege = OCI_SYSDBA;
        } elseif ($request->input('ora_as') === 'SYSOPER') {
            $privilege = OCI_SYSOPER;
        }

        \Illuminate\Support\Facades\Config::set('database.connections.oracle_runtime', [
            'driver'   => 'oracle',
            'host'     => $request->input('ora_host'),
            'port'     => $request->input('ora_port', '1521'),
            'database' => $request->input('ora_db'),
            'username' => $request->input('ora_user'),
            'password' => $request->input('ora_pass'),
            'charset'  => 'AL32UTF8',
            'prefix'   => '',
            'options'  => [
                // Cette option injecte le mode SYSDBA ou SYSOPER dans le driver OCI8
                'privilege' => $privilege,
            ],
        ]);
        $connection = 'oracle_runtime';
    } else {
        $connection = 'mariadb';
    }

    try {
        // === ACTION : TEST DE CONNEXION UNIQUEMENT ===
        if ($request->input('action') === 'test_connection') {
            \Illuminate\Support\Facades\DB::connection($connection)->getPdo();
            return back()->with('success', "✅ Connexion réussie à la base Oracle (" . $request->input('ora_as') . ") !")
                         ->withInput();
        }

        // === ACTION : EXÉCUTION DU SCRIPT ===
        
        // Sécurité : Vérifier que c'est bien un SELECT
        if (!str_starts_with(strtolower(trim($query)), 'select')) {
            throw new \Exception("Seules les requêtes de lecture (SELECT) sont autorisées par sécurité.");
        }

        // Exécution de la requête brute
        $results = \Illuminate\Support\Facades\DB::connection($connection)->select($query);
        
        $data = collect($results)->map(fn($x) => (array) $x);
        $headers = $data->isEmpty() ? [] : array_keys($data->first());

        return view('extraction.index', [
            'data' => $data,
            'headers' => $headers,
            'query' => $query,
            'connection' => $type,
            'type' => $type
        ])->with('success', 'Requête exécutée avec succès.');

    } catch (\Exception $e) {
        return back()->withErrors(['sql_error' => "Erreur : " . $e->getMessage()])
                     ->withInput();
    }
}





    // Exportation vers Excel
    public function export(Request $request)
    {
        $query = $request->input('query');
        $connection = $request->input('connection');

        $results = DB::connection($connection)->select($query);
        $data = collect($results)->map(function($x) { return (array) $x; });

        return Excel::download(new DynamicQueryExport($data), 'extraction_' . date('Ymd_His') . '.xlsx');
    }



}
