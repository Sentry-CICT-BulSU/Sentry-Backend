<?php

namespace App\Http\Controllers;

use App\Actions\Admin\UpdateUser;
use App\Http\Requests\Api\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function update(UpdateUserRequest $request, UpdateUser $updateuser): UserResource|JsonResponse
    {
        try {
            $id = Auth::id();
            $user = User::findOrFail($id);
            DB::beginTransaction();
            $user = $updateuser->handle($request, $user);
            DB::commit();
            return response()->json(['data' => $user]);
        } catch (\Exception $err) {
            DB::rollBack();
            return response()->json([
                'message' => 'User update failed',
                'error' => $err->getMessage()
            ], 500);
        }
    }
}
