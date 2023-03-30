<?php

namespace App\Http\Controllers\Api;

use App\Actions\Semesters\{StoreNewSemester};
use App\Models\Semesters;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Semesters\{
    StoreSemestersRequest,
    UpdateSemestersRequest
};
use \Illuminate\Support\Facades\DB;

class SemestersController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index()
    {
        // $semesters = Semesters::paginate(15);
        $semesters = Semesters::all();
        return response()->json([
            'message' => 'Semesters retrieved successfully',
            'semesters' => $semesters
        ], 200);
    }

    public function store(StoreSemestersRequest $request, StoreNewSemester $storeNewSemester)
    {
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
    public function show(Semesters $semester)
    {
        return response()->json([
            'message' => 'Semester retrieved successfully',
            'semester' => $semester
        ], 200);
    }
    public function update(UpdateSemestersRequest $request, Semesters $semester)
    {
        try {
            DB::beginTransaction();
            $semester->update($request->validated());
            DB::commit();
            return response()->json([
                'message' => 'Semester updated successfully',
                'semester' => $semester
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Semester update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Semesters $semester)
    {
        //
    }
    public function restore($semester)
    {
        $restore = Semesters::withTrashed()->find($semester);
        return response()->json([
            'message' => 'Semester restored successfully',
            'restore' => $restore->restore()
        ], 200);
    }
}
