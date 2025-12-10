<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationTache extends Model
{
    use HasFactory;

    // Nom de la table dans la base de données
    protected $table = 'notifications_taches';

    // Nom de la clé primaire personnalisée
    protected $primaryKey = 'id_notification';

    // Indique que la clé primaire est auto-incrémentée (true par défaut)
    public $incrementing = true;

    // Type de la clé primaire (bigint(20) UNSIGNED)
    protected $keyType = 'int';

    // Les colonnes qui peuvent être remplies massivement (Mass Assigned)
    protected $fillable = [
        'id_agent',
        'titre',
        'description',
        'date_echeance',
        'suivi_par',
        'priorite',
        'statut',
        'lien_action',
        'date_lecture',
        'date_completion',
    ];

    // Les colonnes qui doivent être castées en types PHP natifs (dates, booléens, etc.)
    protected $casts = [
        'date_creation'   => 'datetime',
        'date_echeance'   => 'datetime',
        'date_lecture'    => 'datetime',
        'date_completion' => 'datetime',
    ];

    // Laravel gère 'created_at' et 'updated_at' par défaut.
    // Comme nous avons nos propres colonnes de date spécifiques ('date_creation'),
    // nous pouvons désactiver les timestamps automatiques de Laravel
    // si nous n'utilisons pas la colonne 'updated_at'.
    // public $timestamps = false;
    // Si vous décidez de désactiver timestamps, assurez-vous de le faire aussi dans la migration.

    /**
     * Définit la relation Eloquent avec l'Agent (Utilisateur) assigné à la tâche.
     */
    public function agent(): BelongsTo
    {
        // Supposons que votre modèle User est dans App\Models\User
        // et que la clé étrangère est 'id_agent_assigne'
        // et la clé locale est l'ID dans la table 'users'.
        return $this->belongsTo(Agent::class, 'id_agent', 'id');
    }
}
