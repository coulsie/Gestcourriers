<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horaire extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés massivement.
     */
    protected $fillable = [
        'jour',
        'heure_debut',
        'heure_fin',
        'tolerance_retard',
        'est_ouvre',
    ];

    /**
     * Les types de cast pour les attributs.
     * Permet de manipuler heure_debut et heure_fin comme des objets Carbon.
     */
    protected $casts = [
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
        'est_ouvre' => 'boolean',
        'tolerance_retard' => 'integer',
    ];

    /**
     * Scope pour récupérer uniquement les jours travaillés.
     */
    public function scopeJoursOuvres($query)
    {
        return $query->where('est_ouvre', true);
    }

    /**
     * Méthode d'aide pour savoir si un agent est en retard.
     * @param string $heureArriveeReelle (ex: '07:45')
     */
    public function estEnRetard($heureArriveeReelle)
    {
        if (!$this->est_ouvre) return false;

        $debutTheorique = \Carbon\Carbon::parse($this->heure_debut);
        $arrivee = \Carbon\Carbon::parse($heureArriveeReelle);

        // On ajoute la tolérance à l'heure de début
        $limite = $debutTheorique->copy()->addMinutes($this->tolerance_retard);

        return $arrivee->greaterThan($limite);
    }
}
