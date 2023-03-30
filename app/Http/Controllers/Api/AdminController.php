<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Admin\StoreNewUser;
use App\Http\Requests\Api\Users\StoreUser;
use App\Http\Requests\Api\Users\UpdateUser;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreUser $request,
        StoreNewUser $storeNewUser
    ): JsonResponse {
        try {
            DB::beginTransaction();
            $faculty = $storeNewUser->handle($request);
            DB::commit();
            return response()->json([
                'message' => $request->type . ' created successfully',
                'faculty' => $faculty
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
            return response()->json([
                'message' => 'Admin creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json([
            'user' => $user
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUser $request,
        User $user
    ) {
        try {
            DB::beginTransaction();
            $user->update(
                $request->has('password')
                ? $request->all()
                : $request->except(['password'])
            );
            DB::commit();
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'User update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
