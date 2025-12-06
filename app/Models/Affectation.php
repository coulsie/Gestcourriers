<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    protected $fillable = [
        'courrier_id', 'agent_id', 'statut', 'commentaires', 'date_affectation', 'date_traitement',
    ];

    protected $casts = [
        'date_affectation' => 'datetime',
        'date_traitement' => 'datetime',
    ];

    public function courrier(): BelongsTo
    {
        return $this->belongsTo(Courrier::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
