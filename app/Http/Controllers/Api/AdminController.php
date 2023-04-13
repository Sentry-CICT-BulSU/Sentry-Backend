<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Admin\{StoreNewUser, UpdateUser};
use App\Http\Requests\Api\Users\{
    StoreUser,
    UpdateUserRequest
};
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB};

class AdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::where('id', '!=', $request->user()->id)
            ->paginate(15);
        return UserResource::collection($users)->response();
    }
    public function store(
        StoreUser $request,
        StoreNewUser $storeNewUser
    ): UserResource|JsonResponse {
        try {
            DB::beginTransaction();
            $faculty = $storeNewUser->handle($request);
            DB::commit();
            return new UserResource($faculty);
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
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUserRequest $request,
        User $user,
        UpdateUser $updateUser
    ): UserResource|JsonResponse {
        try {
            DB::beginTransaction();
            $updateUser->handle($request, $user);
            DB::commit();
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'User update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy(User $user): JsonResponse
    {
        return !$user->trashed()
            ? response()->json([
                'message' => 'User deleted successfully',
                'deleted' => $user->delete()
            ], 200)
            : response()->json([
                'message' => 'The user has already been soft deleted',
            ], 403);
    }

    public function restore($user): JsonResponse
    {
        $restore = User::withTrashed()->find($user);
        return $restore->trashed()
            ? response()->json([
                'message' => 'User restored successfully',
                'restore' => $restore->restore()
            ], 200)
            : response()->json([
                'message' => 'The user has already been restored',
            ], 403);
    }
}
