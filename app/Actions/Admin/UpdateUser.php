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
        // if ($request->password) {
        //     $user->password = $request->password;
        // }
        // if ($request->type) {
        //     $user->type = $request->type;
        // }
        // if ($request->email) {
        //     $user->email = $request->email;
        // }
        // if ($request->first_name) {
        //     $user->first_name = $request->first_name;
        // }
        // if ($request->last_name) {
        //     $user->last_name = $request->last_name;
        // }
        // if ($request->position) {
        //     $user->position = $request->position;
        // }
        // if ($request->college) {
        //     $user->college = $request->college;
        // }
        // if ($request->contact) {
        //     $user->contact = $request->contact;
        // }
        $user->update($data);
        dd($user);
        return $user;
    }
}
