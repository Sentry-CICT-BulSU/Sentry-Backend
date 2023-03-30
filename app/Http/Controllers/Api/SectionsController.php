<?php

namespace App\Http\Controllers\Api;

use App\Actions\Sections\StoreNewSection;
use App\Http\Controllers\Controller;
use App\Models\Sections;
use App\Http\Requests\StoreSectionsRequest;
use App\Http\Requests\UpdateSectionsRequest;
use Illuminate\Support\Facades\DB;

class SectionsController extends Controller
{
    public function index()
    {
        // $sections = Sections::paginate(15);
        $sections = Sections::all();
        return response()->json([
            'message' => 'Sections retrieved successfully',
            'sections' => $sections
        ], 200);
    }
    public function store(StoreSectionsRequest $request, StoreNewSection $storeNewSection)
    {
        try {
            DB::beginTransaction();
            $section = $storeNewSection->handle($request);
            DB::commit();
            return response()->json([
                'message' => 'Section created successfully',
                'section' => $section
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Section not created',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Sections $section)
    {
        //
    }
    public function update(UpdateSectionsRequest $request, Sections $section)
    {
        //
    }
    public function destroy(Sections $section)
    {
        //
    }
    public function restore($section)
    {
        //
    }
}
