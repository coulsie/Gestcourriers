<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Courrier extends Model
{
   use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'courriers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'reference',
        'type',
        'objet',
        'description',
        'date_courrier',
        'expediteur_nom',
        'expediteur_contact',
        'destinataire_nom',
        'destinataire_contact',
        'statut',
        'assigne_a',
        'chemin_fichier',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date_courrier' => 'date', // Cast en objet Date PHP (sans heure)
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // --- Constantes pour le champ 'type' ENUM ---
    public const TYPE_INCOMING = 'Incoming';
    public const TYPE_OUTGOING = 'Outgoing';
    public const TYPE_INFORMATION = 'Information';
    public const TYPE_OTHER = 'Other';

    // --- Constantes pour le champ 'statut' ENUM ---
    public const STATUT_RECU = 'reçu';
    public const STATUT_EN_TRAITEMENT = 'en_traitement';
    public const STATUT_TRAITE = 'traité';
    public const STATUT_ARCHIVE = 'archivé';
    public const STATUT_ENVOYE = 'envoyé';

    // --- Relations Eloquent ---

    /**
     * Get the agent assigned to this courrier.
     */
    public function assigneA(): BelongsTo
    {
        // Suppose que le modèle Agent existe (ou User, selon votre structure)
        return $this->belongsTo(Agent::class, 'assigne_a');
    }

    /**
     * Get the affectations for the courrier.
     */

    public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }
    public function currentAffectation()
    {
        return $this->hasOne(Affectation::class)->latestOfMany();
    }

}
