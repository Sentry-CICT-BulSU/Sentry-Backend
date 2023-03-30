<?php

namespace App\Http\Controllers\Api;

use App\Actions\Subjects\StoreNewSubject;
use App\Http\Controllers\Controller;
use App\Models\Subjects;
use App\Http\Requests\Api\Subjects\{
    StoreSubjectsRequest,
    UpdateSubjectsRequest
};
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubjectsController extends Controller
{
    public function index(): JsonResponse
    {
        // $subjects = Subjects::paginate(15);
        $subjects = Subjects::all();
        return response()->json([
            'message' => 'Subjects retrieved successfully',
            'subjects' => $subjects
        ], 200);
    }
    public function store(StoreSubjectsRequest $request, StoreNewSubject $storeNewSubject): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subject = $storeNewSubject->handle($request);
            DB::commit();
            return response()->json([
                'message' => 'Subject created successfully',
                'subject' => $subject
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating subject',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(Subjects $subject): JsonResponse
    {
        return response()->json([
            'message' => 'Subject retrieved successfully',
            'subject' => $subject
        ], 200);

    }
    public function update(UpdateSubjectsRequest $request, Subjects $subject): JsonResponse
    {
        try {
            DB::beginTransaction();
            $subject->update($request->validated());
            DB::commit();
            return response()->json([
                'message' => 'Subject updated successfully',
                'subject' => $subject
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Subject update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Subjects $subject): JsonResponse
    {
        return response()->json([
            'message' => 'The subject has already been soft deleted',
            'deleted' => $subject->delete()
        ], 200);
    }
    public function restore($subject): JsonResponse
    {
        $restore = Subjects::withTrashed()->find($subject);
        return $restore->trashed()
            ? response()->json([
                'message' => 'Subject restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : abort(403, 'The subject has already been restored');
    }
}
