<?php
namespace App\Actions\Semesters;

use App\Http\Requests\Api\Semesters\StoreSemestersRequest;
use App\Models\Semesters;

class StoreNewSemester
{
    public function handle(StoreSemestersRequest $request): Semesters
    {
        return Semesters::create($request->validated());
    }
}
