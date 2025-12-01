<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @var array<int, string>
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
        'date_courrier' => 'date',
    ];

    // Vous pouvez ajouter ici des relations, des accesseurs, ou d'autres méthodes personnalisées.

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class)->latest();
    }
    public function currentAffectation()
    {
        return $this->hasOne(Affectation::class)->latestOfMany();
    }

}
