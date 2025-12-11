<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Courrier;
use App\Models\Agent;

class Affectation extends Model
{
    protected $fillable = [
        'courrier_id',
         'agent_id', 
         'statut', 
         'commentaires', 
         'date_affectation', 
         'date_traitement',
    ];

    // ... (protected $table, $fillable, $casts definitions from previous messages) ...

    protected $table = 'affectations';
    protected $fillable = [
        'courrier_id', 'agent_id', 'statut', 'commentaires',
        'date_affectation', 'date_traitement',
    ];
    protected $casts = [
        'date_affectation' => 'datetime',
        'date_traitement' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // --- Constantes de Statut ---

    public const STATUT_PENDING = 'pending';
    public const STATUT_IN_PROGRESS = 'in_progress';
    public const STATUT_COMPLETED = 'completed'; // The constant we will use

   
    // --- Méthodes d'aide (Helper Methods) ---

    /**
     * Check if the affectation is currently pending.
     */
    public function isPending(): bool
    {
        return $this->statut === self::STATUT_PENDING;
    }

    /**
     * Check if the affectation is currently in progress.
     */
    public function isInProgress(): bool
    {
        return $this->statut === self::STATUT_IN_PROGRESS;
    }

    /**
     * Check if the affectation is completed.
     *
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->statut === self::STATUT_COMPLETED;
    }
    public function courrier(): BelongsTo
    {
        return $this->belongsTo(Courrier::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

}
