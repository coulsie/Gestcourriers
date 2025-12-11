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
    protected $primaryKey = 'PresenceID';

    // Les attributs qui peuvent être assignés en masse (Mass Assignment)
    protected $fillable = [
        'Agent_id',
        'HeureArrivee',
        'HeureDepart',
        'Statut',
        'Notes',
    ];

    // Les types de données pour la conversion automatique (casting)
    // Important pour que les dates soient manipulées comme des objets Carbon
    protected $casts = [
        'HeureArrivee',
        'HeureDepart',
        'Statut'       => 'string',
    ];

    // --- Relations Eloquent ---

    /**
     * Une présence appartient à un agent.
     */
    public function agent(): BelongsTo
    {
        // Assurez-vous que le modèle App\Models\Agent existe
        return $this->belongsTo(Agent::class, 'AgentID', 'id');
    }

    // --- Scopes Locaux (Utile pour filtrer facilement les absences) ---

    /**
     * Scope pour récupérer uniquement les absences.
     * Utilisation : Presence::absences()->get()
     */
    public function scopeAbsences($query)
    {
        return $query->where('Statut', 'Absent');
    }

    /**
     * Scope pour récupérer uniquement les présences.
     * Utilisation : Presence::presentes()->get()
     */
    public function scopePresentes($query)
    {
        return $query->where('Statut', 'Présent');
    }
}

