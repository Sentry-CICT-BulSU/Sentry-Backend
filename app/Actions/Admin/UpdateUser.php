<?php
namespace App\Actions\Admin;

use App\Http\Requests\Api\Users\UpdateUserRequest;
use App\Models\User;

class UpdateUser
{
    public function handle(UpdateUserRequest $request, User $user): bool|User
    {
        $data = $request->validated();
        if ($request->file('profile_img')) {
            $fileName = $request->file('profile_img')->storePublicly('profile_imgs');
            if (!$fileName) {
                throw new \Exception('Failed to upload profile image', 500);
            }
            $data['profile_img'] = $fileName;
        }
        $user->update($data);
        return $user;
    }
}
