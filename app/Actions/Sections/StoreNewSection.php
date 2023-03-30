<?php

namespace App\Actions\Sections;

use App\Http\Requests\Api\Sections\StoreSectionsRequest;
use App\Models\Sections;
use Illuminate\Support\Arr;

class StoreNewSection
{
    public function handle(StoreSectionsRequest $request): Sections
    {
        $data = $request->validated();
        $data['adviser_id'] = $data['faculty_adviser'];
        return Sections::create(Arr::except($data, ['faculty_adviser']));
    }
}
