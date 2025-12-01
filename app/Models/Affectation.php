<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'affectations';

    /**
     * Les attributs qui peuvent être assignés massivement (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'courrier_id',
        'user_id',
        'statut',
        'commentaires',
        'date_affectation',
        'date_traitement',
    ];

    /**
     * Les attributs qui doivent être convertis en types natifs (casting).
     *
     * @var array
     */
    protected $casts = [
        'date_affectation' => 'datetime',
        'date_traitement' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // --- Relations Eloquent ---

    /**
     * Récupère le courrier auquel appartient cette affectation.
     *
     * @return BelongsTo
     */
    public function courrier(): BelongsTo
    {
        return $this->belongsTo(Courrier::class);
    }

    /**
     * Récupère l'utilisateur à qui cette affectation est assignée.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        // Assurez-vous que votre modèle User est importé et existe
        return $this->belongsTo(User::class);
    }
}
