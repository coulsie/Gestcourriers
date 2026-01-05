<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HoraireSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $jours = [
        ['jour' => 'Lundi', 'heure_debut' => '07:30', 'heure_fin' => '16:30', 'est_ouvre' => true],
        ['jour' => 'Mardi', 'heure_debut' => '07:30', 'heure_fin' => '16:30', 'est_ouvre' => true],
        ['jour' => 'Mercredi', 'heure_debut' => '07:30', 'heure_fin' => '16:30', 'est_ouvre' => true],
        ['jour' => 'Jeudi', 'heure_debut' => '07:30', 'heure_fin' => '16:30', 'est_ouvre' => true],
        ['jour' => 'Vendredi', 'heure_debut' => '07:30', 'heure_fin' => '16:30', 'est_ouvre' => true],
        ['jour' => 'Samedi', 'heure_debut' => '00:00', 'heure_fin' => '00:00', 'est_ouvre' => false],
        ['jour' => 'Dimanche', 'heure_debut' => '00:00', 'heure_fin' => '00:00', 'est_ouvre' => false],
    ];

    foreach ($jours as $j) {
        \App\Models\Horaire::create($j);
    }
}

}
