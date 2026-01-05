<?php

namespace App\Enums;

enum PrioriteEnum: string
{
    case Faible = 'Faible';
    case Moyenne = 'Moyenne';
    case Élevée = 'Élevée';
    case Urgent = 'Urgent';
    
public function label(): string {

    return match($this) {
        self::Urgent => 'Priorité Absolue',
        default => 'Normale',
    };
}



}


