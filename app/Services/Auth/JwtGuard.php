<?php

declare(strict_types=1);

namespace App\Services\Auth;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Throwable;

class JwtGuard implements Guard
{
    private $user;

    public function __construct(public UserProvider $provider)
    {
    }

    /**
     * Determine if the current user is authenticated.
     */
    public function check(): bool
    {
        try {
            $token = $this->getTokenFromRequest();

            $id = app(JwtService::class)->getSubFromToken($token);

            $user = $this->provider->retrieveById($id);

            $this->setUser($user);

            return true;
        } catch (Throwable $e) {
            return false;
        }
    }

    /**
     * Determine if the current user is a guest.
     */
    public function guest(): bool
    {
        $this->getTokenFromRequest() ? false : true;
    }

    /**
     * Get the currently authenticated user.
     */
    public function user(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        try {
            if ($this->user) {
                return $this->user;
            }

            $token = $this->getTokenFromRequest();

            $id = app(JwtService::class)->getSubFromToken($token);

            $user = $this->provider->retrieveById($id);

            $this->setUser($user);

            return $user;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Get the ID for the currently authenticated user.
     */
    public function id(): int|string|null
    {
        try {
            if ($this->user) {
                return $this->user->id;
            }

            $token = $this->getTokenFromRequest();

            return app(JwtService::class)->getSubFromToken($token);
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     */
    public function validate(array $credentials = []): bool
    {
        return (bool) $this->attempt($credentials, false);
    }

    /**
     * Determine if the guard has a user instance.
     */
    public function hasUser(): bool
    {
        return $this->user ? true : false;
    }

    /**
     * Set the current user.
     */
    public function setUser(Authenticatable $user): void
    {
        $this->user = $user;
    }

    /**
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param  array  $credentials
     */
    public function attempt(array $credentials = [], bool $login = true): bool|string
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->setUser($user);
            return $login ? $this->login($user) : true;
        }

        return false;
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  array  $credentials
     */
    protected function hasValidCredentials(Authenticatable|null $user, array $credentials): bool
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Get user token
     */
    protected function login(Authenticatable|null $user): string
    {
        return app(JwtService::class)->getTokenForUser($user->id);
    }

    /**
     * Get token from request
     */
    private function getTokenFromRequest(): string|null
    {
        return request()->bearerToken();
    }
}
