<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Auth\JwtService;
use Lcobucci\JWT\Validation\Validator;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * Can JWT generate token
     */
    public function test_can_generate_jwt_token(): void {
        $token = app(JwtService::class)->issue('12');

        $this->assertNotEmpty($token);
    }
}
