<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Annonce extends Model
{
    use HasFactory;

    /**
     * Les attributs pouvant être assignés massivement.
     */
    protected $fillable = [
        'titre',
        'contenu',
        'type',
        'is_active',
        'expires_at'
        
    ];

    /**
     * Scope pour filtrer les annonces actives uniquement.
     * Utilisation : Annonce::active()->get();
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->latest();
    }
}
