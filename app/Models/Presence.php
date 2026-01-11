<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    // Optionnel: Spécifie le nom de la table si ce n'est pas la convention 'presences'
    protected $table = 'presences';

    // Optionnel: Spécifie le nom de la clé primaire si ce n'est pas 'id'
    // protected $primaryKey = 'PresenceID';
    protected $primaryKey = 'id';

    // Les attributs qui peuvent être assignés en masse (Mass Assignment)
    protected $fillable = [
        // 'Agent_id', //Bien nommer ce champ, ce champ doit être identique au libelle dans la base et dans le formaulaire
        'agent_id',
        // 'HeureArrivee',//Bien nommer ce champ, ce champ doit être identique au libelle dans la base et dans le formaulaire
        'heure_arrivee',
        // 'HeureDepart',//Bien nommer ce champ, ce champ doit être identique au libelle dans la base et dans le formaulaire
        'heure_depart',
        // 'Statut',//Bien nommer ce champ, ce champ doit être identique au libelle dans la base et dans le formaulaire
        'statut',
        // 'Notes',//Bien nommer ce champ, ce champ doit être identique au libelle dans la base et dans le formaulaire
        'notes',
    ];

    // Les types de données pour la conversion automatique (casting)
    // Important pour que les dates soient manipulées comme des objets Carbon
    protected $casts = [
       'heure_arrivee' => 'datetime',
       'heure_depart' => 'datetime',
        'statut'       => 'string',
    ];
    protected $dates = [
        'heure_arrivee',
        'heure_depart',
    ];

    // --- Relations Eloquent ---

    /**
     * Une présence appartient à un agent.
     */
    
    // --- Scopes Locaux (Utile pour filtrer facilement les absences) ---

    /**
     * Scope pour récupérer uniquement les absences.
     * Utilisation : Presence::absences()->get()
     */
    public function scopeAbsences($query)
    {
        return $query->where('statut', 'Absent');
    }

    /**
     * Scope pour récupérer uniquement les présences.
     * Utilisation : Presence::presentes()->get()
     */
    public function scopePresentes($query)
    {
        return $query->where('statut', 'Présent');
    }

      public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }

        public function absence()
    {
        return $this->hasOne(Absence::class, 'agent_id', 'agent_id');
    }
}

