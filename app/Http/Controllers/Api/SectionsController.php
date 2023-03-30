<?php

namespace App\Http\Controllers\Api;

use App\Actions\Sections\StoreNewSection;
use App\Http\Controllers\Controller;
use App\Models\Sections;
use App\Http\Requests\Api\Sections\{
    StoreSectionsRequest,
    UpdateSectionsRequest
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SectionsController extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }
    public function index(): JsonResponse
    {
        // $sections = Sections::paginate(15);
        $sections = Sections::all();
        return response()->json([
            'message' => 'Sections retrieved successfully',
            'sections' => $sections
        ], 200);
    }
    public function store(
        StoreSectionsRequest $request,
        StoreNewSection $storeNewSection
    ): JsonResponse {
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
    public function show(Sections $section): JsonResponse
    {
        return response()->json([
            'message' => 'Section retrieved successfully',
            'section' => $section
        ], 200);
    }
    public function update(
        UpdateSectionsRequest $request,
        Sections $section
    ): JsonResponse {
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
    public function destroy(Sections $section): JsonResponse
    {
        return !$section->trashed()
            ? response()->json([
                'message' => 'Section deleted successfully',
                'deleted' => $section->delete()
            ], 200)
            : abort(403, 'The section has already been soft deleted');
    }

    public function restore($section): JsonResponse
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
