<?php

namespace App\Providers\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class PlainTextUserProvider extends EloquentUserProvider implements UserProvider
{
    /**
     * Проверка учетных данных пользователя без использования хэширования пароля.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return $credentials['password'] === $user->getAuthPassword();
    }
}
