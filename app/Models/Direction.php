<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Direction extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être massivement assignés (Mass Assignable).
     * C'est essentiel pour la méthode Direction::create($validatedData) dans le contrôleur.
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'head_id',
    ];

    /**
     * Définit la relation "une Direction a plusieurs Services".
     */
    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    /**
     * Définit la relation "une Direction appartient à un Agent" (le responsable).
     * Utilise head_id comme clé étrangère.
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'head_id');
    }
}
