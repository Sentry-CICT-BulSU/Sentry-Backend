<?php

namespace App\Actions\Subjects;

use App\Http\Requests\StoreSubjectsRequest;
use App\Models\Subjects;

class StoreNewSubject
{
    public function handle(StoreSubjectsRequest $request): Subjects
    {
        return Subjects::create($request->validated());
    }
}
