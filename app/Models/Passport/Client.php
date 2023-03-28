<?php

namespace App\Models\Passport;

class Client extends \Laravel\Passport\Client
{
    /**
     * Determine if the client should skip the authorization prompt.
     */
    // public function skipsAuthorization(): bool
    // {
    //     return $this->firstParty();
    // }
}
