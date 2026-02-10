<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeAbsence extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'type_absences';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nom_type',
        'code',
        'description',
        'est_paye',
    ];


    protected $primaryKey = 'id'; // Assurez-vous que c'est bien 'id' en base de données
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        // Casts the tinyint(1) field to a PHP boolean true/false
        'est_paye' => 'boolean',
        // Optional: Laravel handles timestamps by default, but you can explicitly cast if needed
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'date_champ' => 'date',
    ];

    // Optional: Define a constant for the enum values for better code consistency
    public const TYPE_CONGE = 'Congé';
    public const TYPE_MALADIE = 'Repos Maladie';
    public const TYPE_MISSION = 'Mission';
    public const TYPE_PERMIS = 'Permis';

    public static function getTypes()
    {
        return [
            self::TYPE_CONGE,
            self::TYPE_MALADIE,
            self::TYPE_MISSION,
            self::TYPE_PERMIS,
        ];
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }

    public function presences(): HasMany
    {
        return $this->hasMany(Presence::class);
    }


}
