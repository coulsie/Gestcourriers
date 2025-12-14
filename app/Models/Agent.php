<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Service;


class Agent extends Model
{
    use HasFactory;

    /**
     * Les attributs qui peuvent être massivement assignés (Mass Assignable).
     * Ces noms de colonnes doivent correspondre à ceux définis dans votre migration 'agents'.
     */
    protected $fillable = [
        'email_professionnel',
        'matricule',
        'first_name',
        'last_name',
        'status',
        'sexe',
        'date_of_birth',
        'place_birth',
        'photo',
        'email',
        'phone_number',
        'address',
        'Emploi',
        'Grade',
        'Date_Prise_de_service',
        'Personne_a_prevenir',
        'Contact_personne_a_prevenir',
        'service_id', // Clé étrangère vers le service d'affectation
        'user_id',    // Clé étrangère optionnelle vers le compte utilisateur pour l'authentification
        ];

    /**
     * Définit la relation : un Agent appartient à un seul Service.
     * C'est la relation inverse de Service::hasMany(Agent).
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Définit la relation : un Agent appartient à un seul User (pour la connexion).
     * C'est une relation optionnelle (nullable) vers le modèle User par défaut de Laravel.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Définit la relation : un Agent peut être responsable de plusieurs Directions.
     * Inverse de la relation Direction::belongsTo(Agent, 'head_id').
     */
    public function directionsResponsable(): HasMany
    {
        // L'agent est le responsable (head_id) de plusieurs directions
        return $this->hasMany(Direction::class, 'head_id');
    }

    /**
     * Définit la relation : un Agent peut être responsable de plusieurs Services.
     * Inverse de la relation Service::belongsTo(Agent, 'head_id').
     */
    public function servicesResponsable(): HasMany
    {
        // L'agent est le responsable (head_id) de plusieurs services
        return $this->hasMany(Service::class, 'head_id');
    }
     public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }
     public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);

    }
     public function affectations()
    {
        return $this->hasMany(Affectation::class);
    }

}
