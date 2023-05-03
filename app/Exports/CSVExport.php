<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class CSVExport implements FromQuery
{
    use Exportable;

    private Builder|Relation $query;

    public function __construct($query)
    {
        $this->query = $query;
    }
    /**
     * @return Builder|Relation|mixed
     */
    public function query()
    {
        return $this->query;
    }
}
