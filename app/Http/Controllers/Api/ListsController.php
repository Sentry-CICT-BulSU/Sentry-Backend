<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rooms;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Subjects;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ListsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if (!$request->has('q')) {
            return response()->json([
                'message' => 'Invalid query'
            ], 422);
        }
        return match ($request->get('q')) {
            'faculty' => $this->faculty(),
            'subject' => $this->subject(),
            'room' => $this->room(),
            'section' => $this->section(),
            'semester' => $this->semester(),
            default => response()->json([
                'message' => 'Invalid query'
            ], 422)
        };
    }

    public function faculty()
    {
        return response()->json(
            User::query()->whereNotIn('type', [USER::ADMIN])
                ->orderBy(['first_name', 'last_name'])
                ->get(['id', 'first_name', 'last_name'])
                ->append('full_name')
        );
    }
    public function subject()
    {
        return response()->json(Subjects::query()->orderBy(['title'])->get(['id', 'title']));
    }
    public function room()
    {
        return response()->json(Rooms::query()->orderBy(['name'])->get(['id', 'name']));
    }
    public function section()
    {
        return response()->json(Sections::query()->orderBy(['name'])->get(['id', 'name']));
    }
    public function semester()
    {
        return response()->json(
            Semesters::query()->select([
                'id', DB::raw('CONCAT(name,\' - \', academic_year) as name_acad_yr')
            ])->orderBy(['name_acad_yr'])->get()
        );
    }
}
