<?php

namespace App\Http\Controllers;

use App\Actions\Admin\StoreNewUser;
use App\Http\Requests\Api\Users\StoreUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUser $request, StoreNewUser $storeNewUser): JsonResponse
    {
        dd($request->user());
        $faculty = $storeNewUser->handle($request);
        return response()->json([
            'message' => 'Admin created successfully',
            'faculty' => $faculty
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
