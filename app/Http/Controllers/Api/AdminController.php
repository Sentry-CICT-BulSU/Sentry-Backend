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
use Illuminate\Support\Facades\{DB, Hash};

class AdminController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::where('id', '!=', $request->user()->id)
            ->when(($request->has('q') && $request->get('q') === 'trashed'), fn($q) => $q->onlyTrashed())
            ->orderBy('first_name')
            ->orderBy('last_name')
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
                'message' => 'User creation failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }
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

    public function resetPassword(Request $request): UserResource|JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'current_password' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', 'string', 'max:255'],
        ]);
        try {
            DB::beginTransaction();
            $user = User::withTrashed()->findOrFail($data['user_id'])->first();
            if (!Hash::check($data['curret_password'], $user->password))
                throw new \Exception('Current password is incorrect', 403);
            $user->update(['password' => Hash::make('cict-sentry-123')]);
            DB::commit();
            return new UserResource($user);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Password reset failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
