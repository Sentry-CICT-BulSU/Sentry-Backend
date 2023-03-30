<?php

namespace App\Http\Controllers\Api;

use App\Actions\Subjects\StoreNewSubject;
use App\Http\Controllers\Controller;
use App\Models\Subjects;
use App\Http\Requests\StoreSubjectsRequest;
use App\Http\Requests\UpdateSubjectsRequest;
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
        //
    }
    public function destroy(Subjects $subject): JsonResponse
    {
        //
    }
    public function restore($subject): JsonResponse
    {
        //
    }
}
