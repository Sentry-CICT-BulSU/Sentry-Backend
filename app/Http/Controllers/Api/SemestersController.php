<?php

namespace App\Http\Controllers\Api;

use App\Actions\Semesters\{StoreNewSemester};
use App\Http\Resources\SemestersResource;
use App\Models\Semesters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Semesters\{
    StoreSemestersRequest,
    UpdateSemestersRequest
};
use Illuminate\Http\JsonResponse;
use \Illuminate\Support\Facades\DB;

class SemestersController extends Controller
{
    public function index(): JsonResponse
    {
        $semesters = Semesters::paginate(15);
        return SemestersResource::collection($semesters)->response();
    }

    public function store(
        StoreSemestersRequest $request,
        StoreNewSemester $storeNewSemester
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $semester = $storeNewSemester->handle($request);
            DB::commit();
            return response()->json([
                'message' => 'Semester created successfully',
                'semester' => $semester
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Semester not created',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Semesters $semester): SemestersResource
    {
        return new SemestersResource($semester);
    }
    public function update(
        UpdateSemestersRequest $request,
        Semesters $semester
    ): SemestersResource|JsonResponse {
        try {
            DB::beginTransaction();
            $semester->update($request->validated());
            DB::commit();
            return new SemestersResource($semester);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Semester update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Semesters $semester): JsonResponse
    {
        return !$semester->trashed()
            ? response()->json([
                'message' => 'Semester deleted successfully',
                'deleted' => $semester->delete()
            ], 200)
            : response()->json([
                'message' => 'The semester has already been restored',
            ], 403);
    }
    public function restore($semester): JsonResponse
    {
        $restore = Semesters::withTrashed()->find($semester);
        return $restore->trashed()
            ? response()->json([
                'message' => 'Semester restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : response()->json([
                'message' => 'The semester has already been restored',
            ], 403);
    }
}
