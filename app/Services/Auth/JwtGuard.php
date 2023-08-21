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
     *
     * @return bool
     */
    public function check() {
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
     *
     * @return bool
     */
    public function guest(){
        $this->getTokenFromRequest() ? false: true;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user(){
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
     *
     * @return int|string|null
     */
    public function id() {
        try {

            if ($this->user) {
                return $this->user->id;
            }

            $token = $this->getTokenFromRequest();

            $id = app(JwtService::class)->getSubFromToken($token);

            return $id;

        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = []){
        return (bool) $this->attempt($credentials, false);
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser(){
        return $this->user ? true: false;
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user){
        $this->user = $user;
    }

    /**
     * Attempt to authenticate the user using the given credentials and return the token.
     *
     * @param  array  $credentials
     * @param  bool  $login
     * @return bool|string
     */
    public function attempt(array $credentials = [], $login = true)
    {
        $user = $this->provider->retrieveByCredentials($credentials);

        if ($this->hasValidCredentials($user, $credentials)) {
            $this->setUser($user);
            return $login ? $this->login($user) : true;
        }

        return false;
    }

    /**
     * Get token from request
     */
    private function getTokenFromRequest(): string|null {
        return request()->bearerToken();
    }

    /**
     * Determine if the user matches the credentials.
     *
     * @param  Authenticatable|null  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials(Authenticatable|null $user, array $credentials)
    {
        return $user !== null && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Get user token
     */
    protected function login(Authenticatable|null $user): string {
        return app(JwtService::class)->getTokenForUser($user->id);
    }
}
