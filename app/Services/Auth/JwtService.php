<?php

declare(strict_types=1);

namespace App\Services\Auth;

use DateTimeImmutable;
use Exception;
use Illuminate\Support\Facades\Log;
use Lcobucci\JWT\Encoding\CannotDecodeContent;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;
use Lcobucci\JWT\Token\InvalidTokenStructure;
use Lcobucci\JWT\Token\Parser;
use Lcobucci\JWT\Token\UnsupportedHeaderFound;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validation\RequiredConstraintsViolated;
use Lcobucci\JWT\Validation\Validator;
use Str;

class JwtService
{
    public function issue(string $id): UnencryptedToken
    {
        $tokenBuilder = $this->getTokenBuilder();
        $algorithm = $this->signer();
        $signingKey = $this->getSigningKey();

        $now = new DateTimeImmutable();
        return $tokenBuilder
            ->issuedBy($this->iss())
            ->relatedTo($id)
            ->issuedAt($now)
            ->identifiedBy($this->jti())
            ->expiresAt($now->modify('+'. config('jwt.expiration') .' minute'))
            ->getToken($algorithm, $signingKey);
    }

    public function token(UnencryptedToken $token): string
    {
        return $token->toString();
    }

    public function parse(?string $jwt): UnencryptedToken
    {
        $parser = new Parser(new JoseEncoder());

        try {
            $token = $parser->parse($jwt);
        } catch (CannotDecodeContent | InvalidTokenStructure | UnsupportedHeaderFound $e) {
            Log::info('An invalid token could not be decoded', [$e->getTrace()]);
            throw new Exception('Token could not be parsed');
        }

        try {
            $validator = new Validator();

            $validator->validate($token, new SignedWith($this->signer(), $this->getSigningKey()));
        } catch (RequiredConstraintsViolated $e) {
            Log::info('A token could not be validated', [$e->getTrace()]);
            throw new Exception('Invalid token');
        }

        return $token;
    }

    public function getSigningKey(): InMemory
    {
        $secret = config('jwt.secret_key');

        if (! $secret) {
            throw new Exception('Jwt Secret is not set');
        }

        return $this->getKey($secret);
    }

    public function getKey($content): InMemory
    {
        return InMemory::plainText($content);
    }

    public function getTokenBuilder(): Builder
    {
        return new Builder(new JoseEncoder(), ChainedFormatter::default());
    }

    public function iss(): string
    {
        return request()->url();
    }

    public function jti(): string
    {
        return Str::random();
    }

    public function getSubFromToken(?string $jwt): string
    {
        $token = $this->parse($jwt);

        return $token->claims()->get('sub');
    }

    public function getTokenForUser(int $id): string
    {
        return $this->token($this->issue((string) $id));
    }

    private function signer(): Sha256
    {
        return new Sha256();
    }
}
