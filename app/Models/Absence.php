<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Absence extends Model
{
    use HasFactory;

    // Nom de la table dans la base de données (Laravel utilise 'absences' par défaut)
    protected $table = 'absences';

    // Nom de la clé primaire personnalisée
    protected $primaryKey = 'AbsenceID';

    // Les champs autorisés pour l'assignation de masse (Mass Assignment)
    protected $fillable = [
        'AgentID',
        'TypeAbsenceID',
        'DateDebut',
        'DateFin',
        'Approuvee',
    ];

    // Conversion automatique des types de données
    protected $casts = [
        'DateDebut' => 'date',
        'DateFin'   => 'date',
        'Approuvee' => 'boolean', // Convertit 0/1 ou false/true en booléen PHP
    ];

    // --- Relations Eloquent ---

    /**
     * Définit la relation : Une absence appartient à un agent.
     */
    public function agent(): BelongsTo
    {
        // Assurez-vous d'avoir un modèle App\Models\Agent existant
        return $this->belongsTo(Agent::class, 'AgentID', 'id');
    }

    /**
     * Définit la relation : Une absence a un type.
     */
    public function type(): BelongsTo
    {
        // Assurez-vous d'avoir un modèle App\Models\TypeAbsence existant
        return $this->belongsTo(TypeAbsence::class, 'TypeAbsenceID', 'TypeAbsenceID');
    }
}
