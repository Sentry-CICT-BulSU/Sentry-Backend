<?php

namespace App\Models\Passport;

class Client extends \Laravel\Passport\Client
{
    /**
     * Determine if the client should skip the authorization prompt.
     */
    // public function skipsAuthorization(): bool
    // {
    //     // return $this->firstParty();
    //     return $this->isFirstParty();
    // }

    private function isFirstParty(): bool
    {
        return match ($this->name) {
            config('app.front_name') => true,
            default => $this->firstParty()
        };
    }
}
