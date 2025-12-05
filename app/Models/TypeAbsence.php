<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeAbsence extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     * Par défaut, Laravel suppose 'type_absences'.
     * Cette ligne est donc facultative si vous respectez la convention.
     *
     * @var string
     */
    protected $table = 'type_absences';

    /**
     * Le nom de la clé primaire personnalisée de la table.
     * Par défaut, Laravel suppose 'id'. Nous devons le spécifier ici.
     *
     * @var string
     */
    protected $primaryKey = 'TypeAbsenceID';

    /**
     * Indique si les IDs sont auto-incrémentés.
     * Par défaut à true.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Le type de données de la clé primaire.
     * Par défaut à 'int'.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Les attributs qui sont massivement assignables (mass assignable).
     * Ceci est crucial pour utiliser des méthodes comme TypeAbsence::create($data)
     * dans votre contrôleur sans erreur d'assignation de masse.
     *
     * @var array
     */
    protected $fillable = [
        'NomType',
        'Code',
        'Description',
        'EstPaye',
    ];

    /**
     * Les attributs qui devraient être castés en types natifs.
     * La colonne 'EstPaye' doit être traitée comme un booléen en PHP.
     *
     * @var array
     */
    protected $casts = [
        'EstPaye' => 'boolean',
    ];

    // Note: Les champs 'created_at' et 'updated_at' sont gérés automatiquement par $timestamps = true (qui est la valeur par défaut).
   public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }

}
