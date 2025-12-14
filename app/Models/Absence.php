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
    protected $primaryKey = 'id';

    // Les champs autorisés pour l'assignation de masse (Mass Assignment)
    protected $fillable = [
        'agent_id',
        'type_absence_id',
        'date_debut',
        'date_fin',
        'Approuvee',
    ];
 
    // Conversion automatique des types de données
    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'Approuvee' => 'boolean', // Convertit 0/1 ou false/true en booléen PHP
    ];

    // --- Relations Eloquent ---

    /**
     * Définit la relation : Une absence appartient à un agent.
     */
    public function agent(): BelongsTo
    {
        // Assurez-vous d'avoir un modèle App\Models\Agent existant
        return $this->belongsTo(Agent::class, 'id', 'id');
    }

    /**
     * Définit la relation : Une absence a un type.
     */
    public function type(): BelongsTo
    {
        // Assurez-vous d'avoir un modèle App\Models\TypeAbsence existant
        return $this->belongsTo(TypeAbsence::class, 'type_absence_id', 'type_absence_id');
    }
    public function typeAbsence()
    {
        // Remplacez 'App\Models\TypeAbsence' par le nom correct de votre modèle lié,
        // et ajustez les clés étrangères si nécessaire.
        return $this->belongsTo(TypeAbsence::class, 'type_absence_id');
        // ou return $this->hasOne(TypeAbsence::class, 'absence_id');
    }

}
