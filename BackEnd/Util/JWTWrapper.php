<?php

namespace Delco\Util;


use \Firebase\JWT\JWT;

/**
 * Gerenciamento de tokens JWT
 */
class JWTWrapper
{
    const KEY = '7Fsxc2A865V67'; // chave

    /**
     * Geracao de um novo token jwt
        "iss" (Issuer) Emissor
        "sub" (Subject) assunto
        "aud" (Audience) audiência
        "exp" (Expiration Time) tempo de espiração
        "nbf" (Not Before) Não antes
        "iat" (Issued At) Emitido em
        "jti" (JWT ID)
     */
    public static function encode(array $options)
    {
        $issuedAt = time();
        $expire = $issuedAt + $options['expirationSec']; // tempo de expiracao do token

        $tokenParam = [
            'iat' => $issuedAt,   //Emitido em timestamp de geracao do token
            'iss' => $options['domain'], // dominio, pode ser usado para descartar tokens de outros dominios
            'exp' => $expire,  // expiracao do token
            'nbf' => $issuedAt - 1,   // token nao é valido Antes de
            'data' => $options['userdata'], // Dados do usuario logado
        ];

        return JWT::encode($tokenParam, self::KEY);
    }

    /**
     * Decodifica token jwt
     */
    public static function decode($jwt)
    {
        return JWT::decode($jwt, self::KEY, ['HS256']);
    }
}