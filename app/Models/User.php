<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Affectation;
use App\Models\Agent;
use App\Models\user;
use App\Models\NotificationTache;
use Spatie\Permission\Traits\HasRoles; // <--- Importation





class User extends Authenticatable
{
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'email_verified_at',
        'remember_token',
        'password_changed_at',
        'bio',
        'profile_picture',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $casts = [
        'role' => \App\Enums\UserRole::class,
    ];

    public function affectations(): HasMany
    {
        return $this->hasMany(Affectation::class);
    }
    public function agent()
    {
        // Logique de la méthode ou définition de la relation (par exemple, hasOne, belongsTo, etc.)
        return $this->hasOne(Agent::class); // Exemple de relation
    }
    public function affectation()
    {
        return $this->belongsTo(Affectation::class, 'agent_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function notificationtache()
    {
      // S'il s'agit d'une relation (ex: HasMany)
        return $this->hasMany(NotificationTache::class);
    }
    public function notificationtaches() {
        return $this->hasManyThrough(
            NotificationTache::class, // Modèle final
            Agent::class,             // Modèle intermédiaire
            'user_id',                // Clé étrangère sur Agent
            'agent_id',               // Clé étrangère sur NotificationTache
            'id',                     // Clé locale sur User
            'id'                      // Clé locale sur Agent
        );
    }

    }
