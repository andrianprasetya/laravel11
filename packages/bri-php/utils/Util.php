<?php

namespace Bri\utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class Util
{

    public function generateSignatureAsymmetric(string $consumerKey, $timestamp): string
    {

        $privateKeyPath = storage_path('app/bankBriKeys/bankbri_privkey.pem');

        $stringToSign = $consumerKey . "|" . $timestamp;

        $privateKey = file_get_contents($privateKeyPath);
        if (!$privateKey) {
            #Failed to read private key from file
            return 1;
        }

        $privateKeyResource = openssl_pkey_get_private($privateKey);
        if (!$privateKeyResource) {
            #Failed to load private key
            return 2;
        }

        $signature = '';
        if (!openssl_sign($stringToSign, $signature, $privateKeyResource, OPENSSL_ALGO_SHA256)) {
            #Failed to sign string
            return 3;
        }

        openssl_free_key($privateKeyResource);
        return base64_encode($signature);
    }

    public function generateSignatureSymmetric($client_secret, $timestamp, $httpMethod, $endpoint, $access_token, $body): string
    {
        $minifiedRequestBody = json_encode($body);

        $sha256Hash = hash('sha256', $minifiedRequestBody);

        $hexEncodedHash = strtolower($sha256Hash);

        $stringToSign = $httpMethod
            . ':' . $endpoint
            . ':' . $access_token
            . ':' . $hexEncodedHash
            . ':' . $timestamp;

        $signature = hash_hmac('sha512', $stringToSign, $client_secret, true);
        return base64_encode($signature);
    }

    public function generateExternalID(): string
    {
        $timestamp = Carbon::now('Asia/Jakarta')->timestamp;
        return substr($timestamp, -6) . random_int(100, 999);
    }

    public function generateCustomerReference()
    {
        // Get current timestamp with second precision (YearMonthDayHourMinuteSecond)
        $timestamp = Carbon::now()->format('YmdHis');

        // Generate a random alphanumeric string (20 characters)
        $randomString = Str::random(10);

        // Combine the timestamp and random string
        return $timestamp . $randomString;
    }

    public function generatePartnerReferenceNo(): string
    {
        $yearSuffix = Carbon::now()->format('y');
        $daySuffix = Carbon::now()->format('d');

        $uuid = Uuid::uuid4()->toString();

        $numericUuid = preg_replace_callback('/[a-f0-9]/', function ($matches) {
            return str_pad(ord($matches[0]), 2, '0', STR_PAD_LEFT);
        }, str_replace('-', '', $uuid));


        $referenceNo = substr($numericUuid, 0, 23);

        $partnerReferenceNo = $yearSuffix . $daySuffix . $referenceNo;

        return $partnerReferenceNo;
    }
}
