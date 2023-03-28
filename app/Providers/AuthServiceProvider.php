<?php

namespace App\Providers;

use App\Models\Passport\{
    AuthCode,
    Client,
    PersonalAccessClient,
    RefreshToken,
    Token
};
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        // Passport::hashClientSecrets();

        Passport::tokensExpireIn(now()->addHours(24));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addDays(30));

        Passport::useTokenModel(Token::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::useClientModel(Client::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);


        /* Passport::tokensCan([
            'create-keys' => 'Create Room Keys',
            'read-keys' => 'Check Room Keys',
            'update-keys' => 'Update Room Keys',
            'delete-keys' => 'Delete Room Keys',

            'create-attendance' => 'Create Attendance Schedule',
            'read-attendance' => 'Check Attendance Schedule',
            'update-attendance' => 'Update Attendance Schedule',
            'delete-attendance' => 'Delete Attendance Schedule',

            'create-logs' => 'Create Logs',
            'read-logs' => 'Check Logs',
            'update-logs' => 'Update Logs',
            'delete-logs' => 'Delete Logs',

            'admin' => 'Access all app features'
        ]);

        Passport::setDefaultScope([

            // 'create-keys',
            'read-keys',
            // 'update-keys',
            // 'delete-keys',

            // 'create-attendance',
            'read-attendance',
            // 'update-attendance',
            // 'delete-attendance',

            // 'create-logs',
            'read-logs',
            // 'update-logs',
            // 'delete-logs',
        ]); */

        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url') . "/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        //
    }
}
