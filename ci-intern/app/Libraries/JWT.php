<?php
namespace App\Libraries;

class JWT
{
    private static $secret_key = "MY_SECRET_KEY"; // change this!

    // Encode data into JWT
    public static function encode($data, $exp = 3600)
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(array_merge($data, ['exp' => time() + $exp]));

        $base64UrlHeader = self::base64UrlEncode($header);
        $base64UrlPayload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, self::$secret_key, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // Decode JWT
    public static function decode($jwt)
    {
        $parts = explode('.', $jwt);
        if (count($parts) != 3) return null;

        $payload = self::base64UrlDecode($parts[1]);
        $signatureProvided = $parts[2];

        $expiration = json_decode($payload, true)['exp'] ?? 0;
        if ($expiration < time()) return null; // Token expired

        $signature = hash_hmac('sha256', $parts[0] . "." . $parts[1], self::$secret_key, true);
        $base64UrlSignature = self::base64UrlEncode($signature);

        if ($base64UrlSignature !== $signatureProvided) return null;

        return json_decode($payload, true);
    }

    private static function base64UrlEncode($text)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
    }

    private static function base64UrlDecode($text)
    {
        $remainder = strlen($text) % 4;
        if ($remainder) $text .= str_repeat('=', 4 - $remainder);
        return base64_decode(strtr($text, '-_', '+/'));
    }
}
