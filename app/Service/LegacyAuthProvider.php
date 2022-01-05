<?php

namespace App\Services\Auth;

use Illuminate\Auth\EloquentUserProvider as BaseUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class LegacyUserProvider extends BaseUserProvider {
    /**
     * Create a new database user provider.
     *
     * @param string $model
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Contracts\Auth\Authenticatable $user
     * @param array                                      $credentials
     *
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        // $matches = some method of matching $plain with $user->getAuthPassword();

        return dd($user, $credentials);
    }
}
