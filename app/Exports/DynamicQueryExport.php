<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class DynamicQueryExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->data;
    }

    /**
    * Définition des en-têtes Excel à partir des clés du tableau
    */
    public function headings(): array
    {
        if ($this->data->isEmpty()) {
            return [];
        }

        // On récupère les clés du premier élément de la collection
        return array_keys((array) $this->data->first());
    }
}
