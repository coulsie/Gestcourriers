<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScriptExtraction extends Model
{
   
   // Indique le nom exact de la table créée dans la migration
protected $table = 'scripts_extraction'; 

protected $fillable = [
    'nom', 'description', 'type_entreprise', 
    'type_impot', 'type_contribuable', 
    'date_debut', 'date_fin', 'parametres'
];

protected $casts = [
    'parametres' => 'array', // Convertit automatiquement le JSON en tableau PHP
    'date_debut' => 'date',
    'date_fin' => 'date',
];

}
