<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être massivement assignés (Mass Assignable).
     * Ces noms de colonnes doivent correspondre à ceux définis dans votre migration 'services'.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'direction_id', // Clé étrangère vers la direction parente
        'head_id',      // Clé étrangère vers l'agent responsable du service
    ];

    /**
     * Définit la relation : un Service appartient à une seule Direction.
     * C'est la relation inverse de Direction::hasMany(Service).
     */
    public function direction(): BelongsTo
    {
        return $this->belongsTo(Direction::class);
    }

    /**
     * Définit la relation : un Service a plusieurs Agents.
     * C'est la relation inverse de Agent::belongsTo(Service).
     */
    public function agents(): HasMany
    {
        return $this->hasMany(Agent::class);
    }

    /**
     * Définit la relation : un Service appartient à un Agent spécifique (le responsable du service).
     * Utilise la colonne 'head_id' dans la table 'services' comme clé étrangère.
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'head_id');
    }
}
