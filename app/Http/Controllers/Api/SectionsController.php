<?php

namespace App\Http\Controllers\Api;

use App\Actions\Sections\StoreNewSection;
use App\Http\Controllers\Controller;
use App\Models\Sections;
use App\Http\Requests\Api\Sections\{
    StoreSectionsRequest,
    UpdateSectionsRequest
};
use Illuminate\Support\Arr;
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
        return response()->json([
            'message' => 'Section retrieved successfully',
            'section' => $section
        ], 200);
    }
    public function update(UpdateSectionsRequest $request, Sections $section)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['adviser_id'] = $data['faculty_adviser'];
            $section->update(Arr::except($data, ['faculty_adviser']));
            DB::commit();
            return response()->json([
                'message' => 'Section updated successfully',
                'section' => $section
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Section update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Sections $section)
    {
        return !$section->trashed()
            ? response()->json([
                'message' => 'Section deleted successfully',
                'deleted' => $section->delete()
            ], 200)
            : abort(403, 'The section has already been soft deleted');
    }

    public function restore($section)
    {
        $restore = Sections::withTrashed()->find($section);
        return $restore->trashed()
            ? response()->json([
                'message' => 'Section restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : abort(403, 'The section has already been restored');
    }
}
