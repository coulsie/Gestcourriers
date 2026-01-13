<?php

// app/Enums/UserRole.php
namespace App\Enums;

enum UserRole: string {
    case DIRECTEUR = 'directeur';
    case SOUS_DIRECTEUR = 'sous_directeur';
    case CHEF_DE_SERVICE = 'chef_de_service';
    case AGENT = 'agent';
}
