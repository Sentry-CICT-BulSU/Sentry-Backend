<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Actions\Admin\StoreNewUser;
use App\Http\Requests\Api\Users\{
    StoreUser,
    UpdateUser
};
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, DB};

class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware('admin');
    }
    public function index(Request $request)
    {
        $users = User::where('id', '!=', $request->user()->id)
            ->paginate(15);
        return UserResource::collection($users);
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
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateUser $request,
        User $user
    ): UserResource|JsonResponse {
        try {
            DB::beginTransaction();
            $user->update(
                $request->has('password')
                ? $request->all()
                : $request->except(['password'])
            );
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
