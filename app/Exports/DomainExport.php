<?php

namespace App\Exports;

use App\Models\Domain;
use Maatwebsite\Excel\Concerns\FromCollection;

class DomainExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Domain::all();
    }
}
