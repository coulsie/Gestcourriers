<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PrioriteEnum;
use App\Enums\StatutEnum;
use App\Models\Agent;


class NotificationTache extends Model
{
    
   
    protected $table = 'notifications_taches';

    // Indique explicitement le nom de la clé primaire
    protected $primaryKey = 'id_notification';

    // Laravel s'attend par défaut à des clés auto-incrémentées
    public $incrementing = true;

    // Laravel s'attend par défaut à des colonnes 'created_at' et 'updated_at'
    // que nous n'avons pas ici (nous avons date_creation, date_lecture, etc.)
    public $timestamps = false;

    // Définissez les colonnes qui peuvent être assignées massivement (Mass Assignment)
    protected $fillable = [

        'agent_id',
        'titre',
        'description',
        'date_creation',
        'date_echeance',
        'suivi_par',
        'priorite',
        'statut',
        'lien_action',
        'document',
        'date_lecture',
        'date_completion',
        'is_archived',

    ];

    // Cast des attributs de date en instances Carbon pour une manipulation facile
    protected $casts = [
        'date_creation'   => 'datetime',
        'date_echeance'   => 'datetime',
        'date_lecture'    => 'datetime',
        'date_completion' => 'datetime',
        'is_archived'     => 'boolean',
        'priorite'        => \App\Enums\PrioriteEnum::class, // Nécessite l'Enum ci-dessous
        'statut'          => \App\Enums\StatutEnum::class,   // Nécessite l'Enum ci-dessous
    ];

    // Vous pouvez définir des relations ici, par exemple avec l'Agent
    // public function agent()
    // {
    //     return $this->belongsTo(Agent::class, 'id_agent');
    // }
    public function agent(): BelongsTo
    {
        // Assurez-vous d'avoir un modèle App\Models\Agent existant
        return $this->belongsTo(Agent::class, 'agent_id');
    }
    public function getPrioriteLabelAttribute(): string
    {
        return $this->priorite->value;
    }

    public function getStatutLabelAttribute(): string
    {
        return $this->statut->value;
    }
   public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

            public function getProgressionAttribute()
        {
            $start = Carbon::parse($this->date_creation);
            $end = Carbon::parse($this->date_echeance);
            $now = Carbon::now();

            // Si la date actuelle est avant la création
            if ($now->lt($start)) return 0;
            // Si l'échéance est dépassée
            if ($now->gt($end)) return 100;

            $totalDays = $start->diffInDays($end);
            $daysPassed = $start->diffInDays($now);

            return ($totalDays > 0) ? round(($daysPassed / $totalDays) * 100) : 100;
        }

}
