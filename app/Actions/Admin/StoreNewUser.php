<?php
namespace App\Actions\Admin;

use App\Http\Requests\Api\Users\StoreUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreNewUser
{
    public function handle(StoreUser $request): User
    {
        $fileName = $request->file('profile_img')->storePublicly('profile_imgs');
        if (!$fileName) {
            throw new \Exception('Failed to upload profile image', 500);
        }
        $data = $request->validated();
        $data['profile_img'] = $fileName;
        return User::create($data);
    }
}
