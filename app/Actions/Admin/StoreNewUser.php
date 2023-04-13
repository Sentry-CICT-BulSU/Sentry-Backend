<?php
namespace App\Actions\Admin;

use App\Http\Requests\Api\Users\StoreUser;
use App\Models\User;

class StoreNewUser
{
    public function handle(StoreUser $request): User
    {
        $data = $request->validated();
        if ($$request->file('profile_img')) {
            $fileName = $request->file('profile_img')->storePublicly('profile_imgs');
            if (!$fileName) {
                throw new \Exception('Failed to upload profile image', 500);
            }
            $data['profile_img'] = $fileName;
        }
        return User::create($data);
    }
}
