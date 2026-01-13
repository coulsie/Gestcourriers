<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Imputation extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $table = 'imputations';

   protected $fillable = [
    'courrier_id',
    'user_id',
    'niveau',
    'instructions',
    'observations',
    'documents_annexes',
    'date_imputation',
    'echeancier',
    'statut'
];

// Relation indispensable pour la table agent_imputation
 public function agents(): BelongsToMany
    {
        return $this->belongsToMany(Agent::class, 'agent_imputation', 'imputation_id', 'agent_id');
    }

    /**
     * Les attributs qui doivent être castés (convertis).
     */
    protected $casts = [
        'date_imputation' => 'date',
        'date_traitement' => 'date',
        'echeancier'      => 'date',
        'documents_annexes' => 'array', // Utile si vous stockez plusieurs chemins en JSON
    ];
    

    /**
     * Relation avec le Courrier (Le document associé).
     */
    public function courrier(): BelongsTo
    {
        return $this->belongsTo(Courrier::class);
    }

    /**
     * Relation avec l'Utilisateur (L'auteur de l'imputation).
     */
    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec les Agents (Assignation à un ou plusieurs agents).
     * Utilise la table pivot 'agent_imputation'.
     */

    public function assignedAgents(): BelongsToMany
    {
        return $this->belongsToMany(Agent::class, 'agent_imputation');
}
}
