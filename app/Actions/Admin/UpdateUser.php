<?php
namespace App\Actions\Admin;

use App\Http\Requests\Api\Users\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateUser
{
    public function handle(Request $request, User $user): bool|User
    {
        $data = $request->validated();
        // dump($request->toArray());
        // dump($request->file());
        // dd($request->all());
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
