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
    public function index()
    {
        $users = User::paginate(15);
        return response()->json([
            'message' => 'Welcome to the admin dashboard',
            'users' => $users
        ], 200);
    }
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
                $faculty->type => $faculty
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
    public function show(User $user): JsonResponse
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
    ): JsonResponse {
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
    public function destroy(User $user): JsonResponse
    {
        return !$user->trashed()
            ? response()->json([
                'message' => 'User deleted successfully',
                'deleted' => $user->delete()
            ], 200)
            : abort(403, 'The user has already been soft deleted');
    }

    public function restore($user): JsonResponse
    {
        $restore = User::withTrashed()->find($user);
        return $restore->trashed()
            ? response()->json([
                'message' => 'User restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : abort(403, 'The user has already been restored');
    }
}
