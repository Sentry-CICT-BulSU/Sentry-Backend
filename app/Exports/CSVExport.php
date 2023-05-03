<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CSVExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    use Exportable;

    private Builder|Relation $query;
    private array $headers;

    public function __construct($query, $headers)
    {
        $this->query = $query;
        $this->headers = $headers;
    }
    /**
     * @return Builder|Relation|mixed
     */
    public function query()
    {
        return $this->query;
    }
    public function headings(): array
    {
        return $this->headers;
    }
}
