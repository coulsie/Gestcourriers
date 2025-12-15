<?php

namespace App\Enums;

enum StatutEnum: string
{
    case NonLu = 'Non lu';
    case EnCours = 'En cours';
    case Completee = 'Complétée';
    case Annulee = 'Annulée';
}
