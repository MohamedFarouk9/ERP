<?php

declare(strict_types=1);

namespace Modules\Users\Services;

final class TotpService
{
    private const BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * @param int<1, max> $byteLength
     */
    public function generateSecret(int $byteLength = 20): string
    {
        $randomBytes = random_bytes($byteLength);

        return $this->base32Encode($randomBytes);
    }

    /**
     * @return array<int, string>
     */
    public function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(5)));
        }

        return $codes;
    }

    public function verify(string $secret, string $code, int $window = 1): bool
    {
        $code = trim($code);
        if (strlen($code) !== 6 || ! ctype_digit($code)) {
            return false;
        }

        $binarySecret = $this->base32Decode($secret);
        if ($binarySecret === '') {
            return false;
        }

        $currentTimeSlice = (int) floor(time() / 30);

        for ($drift = -$window; $drift <= $window; $drift++) {
            $slice = $currentTimeSlice + $drift;
            $counter = pack('N*', 0).pack('N*', $slice);
            $hmac = hash_hmac('sha1', $counter, $binarySecret, true);

            $offset = ord($hmac[19]) & 0x0F;
            $truncated = (
                ((ord($hmac[$offset + 0]) & 0x7F) << 24) |
                ((ord($hmac[$offset + 1]) & 0xFF) << 16) |
                ((ord($hmac[$offset + 2]) & 0xFF) << 8) |
                (ord($hmac[$offset + 3]) & 0xFF)
            ) % 1000000;

            $computed = str_pad((string) $truncated, 6, '0', STR_PAD_LEFT);

            if (hash_equals($computed, $code)) {
                return true;
            }
        }

        return false;
    }

    public function getProvisioningUri(string $company, string $accountName, string $secret): string
    {
        $encodedCompany = rawurlencode($company);
        $encodedAccount = rawurlencode($accountName);

        return "otpauth://totp/{$encodedCompany}:{$encodedAccount}?secret={$secret}&issuer={$encodedCompany}&algorithm=SHA1&digits=6&period=30";
    }

    private function base32Encode(string $data): string
    {
        if ($data === '') {
            return '';
        }

        $binaryString = '';
        foreach (str_split($data) as $char) {
            $binaryString .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
        }

        $fiveBitChunks = str_split($binaryString, 5);
        $base32 = '';

        foreach ($fiveBitChunks as $chunk) {
            $chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
            $base32 .= self::BASE32_ALPHABET[bindec($chunk)];
        }

        return $base32;
    }

    private function base32Decode(string $base32): string
    {
        $base32 = strtoupper(trim($base32));
        if ($base32 === '') {
            return '';
        }

        $binaryString = '';
        $alphabet = self::BASE32_ALPHABET;

        for ($i = 0; $i < strlen($base32); $i++) {
            $char = $base32[$i];
            $position = strpos($alphabet, $char);
            if ($position === false) {
                return '';
            }
            $binaryString .= str_pad(decbin($position), 5, '0', STR_PAD_LEFT);
        }

        $eightBitChunks = str_split($binaryString, 8);
        $result = '';

        foreach ($eightBitChunks as $chunk) {
            if (strlen($chunk) === 8) {
                $result .= chr((int) bindec($chunk));
            }
        }

        return $result;
    }
}
