<?php

namespace App\Http\Controllers;

use App\Actions\Admin\UpdateUser;
use App\Http\Requests\Api\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function update(UpdateUserRequest $request, User $user, UpdateUser $updateuser): UserResource|JsonResponse
    {
        try {
            DB::beginTransaction();
            $user = $updateuser->handle($request, $user);
            DB::commit();
            return new UserResource($user);
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json([
                'message' => 'User update failed',
                'error' => $err->getMessage()
            ], 500);
        }
    }
}
