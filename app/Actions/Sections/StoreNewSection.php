<?php

namespace App\Actions\Sections;

use App\Http\Requests\Api\Sections\StoreSectionsRequest;
use App\Models\Sections;

class StoreNewSection
{
    public function handle(StoreSectionsRequest $request): Sections
    {
        return Sections::create($request->validated());
    }
}
