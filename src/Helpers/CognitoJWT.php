<?php

namespace Iwouldrathercode\Cognito\Helpers;

use Firebase\JWT\JWT;
use phpseclib\Crypt\RSA;
use phpseclib\Math\BigInteger;
use Iwouldrathercode\Cognito\Models\PublicKey;

class CognitoJWT
{
    public static function getPublicKey($kid, $region, $userPoolId)
    {
        $jwksUrl = sprintf('https://cognito-idp.%s.amazonaws.com/%s/.well-known/jwks.json', $region, $userPoolId);
        $ch = curl_init($jwksUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 3,
        ]);
        $jwks = curl_exec($ch);
        if ($jwks) {
            $json = json_decode($jwks, false);
            if ($json && isset($json->keys) && is_array($json->keys)) {
                foreach ($json->keys as $jwk) {
                    if ($jwk->kid === $kid) {
                        return static::jwkToPem($jwk);
                    }
                }
            }
        }
        return null;
    }

    public static function jwkToPem($jwk)
    {
        if (isset($jwk->e) && isset($jwk->n)) {
            $rsa = new RSA();
            $rsa->loadKey([
                'e' => new BigInteger(JWT::urlsafeB64Decode($jwk->e), 256),
                'n' => new BigInteger(JWT::urlsafeB64Decode($jwk->n),  256)
            ]);
            return $rsa->getPublicKey();
        }
        return null;
    }

    public static function getKid($jwt)
    {
        $tks = explode('.', $jwt);
        if (count($tks) === 3) {
            $header = JWT::jsonDecode(JWT::urlsafeB64Decode($tks[0]));
            if (isset($header->kid)) {
                return $header->kid;
            }
        }
        return null;
    }

    public static function verifyToken($jwt, $region, $userPoolId)
    {
        $publicKey = null;
        $kid = static::getKid($jwt);
        if ($kid) {
            $row = PublicKey::find($kid);
            if ($row) {
                $publicKey = $row->public_key;
            }
            else {
                $publicKey = static::getPublicKey($kid, $region, $userPoolId);
                $row = PublicKey::create(['kid' => $kid, 'public_key' => $publicKey]);
            }
        }

        if ($publicKey) {
            return JWT::decode($jwt, array('RS256'));
        }
        return null;
    }
}
