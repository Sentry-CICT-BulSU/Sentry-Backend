<?php
namespace App\Actions\Admin;

use App\Http\Requests\Api\Users\StoreUser;
use App\Models\User;
use Illuminate\Http\Request;

class StoreNewUser
{
    public function handle(StoreUser $request): User
    {
        return User::create($request->validated());
    }
}
