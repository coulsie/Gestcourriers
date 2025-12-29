<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReponseNotification extends Model
{
    // Nom de la table si différent du pluriel automatique
    protected $table = 'reponse_notifications';

    // Champs autorisés pour le remplissage de masse (mass assignment)
    protected $fillable = [
        'id_notification',
        'agent_id',
        'message',
        'Reponse_Piece_jointe',
        'approuvee',
        'appreciation_du_superieur',
    ];

    /**
     * Relation avec l'Agent (User)
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    /**
     * Relation avec la Notification parente
     */
    public function notification(): BelongsTo
    {
        // Remplacez 'NotificationTache' par le nom réel de votre modèle de notification
        return $this->belongsTo(NotificationTache::class, 'id_notification');
    }
}
