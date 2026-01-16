<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reponse extends Model
{
    protected $fillable = [
        'imputation_id',
        'agent_id',
        'contenu',
        'fichiers_joints',
        'date_reponse',
        'pourcentage_avancement',
        'validation',
        'document_final_signe',
        'date_approbation',
    ];

    protected $casts = [
        'fichiers_joints' => 'array', // Convertit automatiquement le tableau en JSON pour MariaDB
        'date_reponse' => 'datetime'

    ];


    // Relation vers l'imputation
    public function imputation(): BelongsTo
    {
        return $this->belongsTo(Imputation::class);
    }

    // Relation vers l'agent auteur de la réponse
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}

