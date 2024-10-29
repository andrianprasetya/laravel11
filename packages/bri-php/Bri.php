<?php

namespace Bri;

use Bri\net\HttpClient;
use Bri\utils\Constant;
use Bri\utils\Util;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Unirest\Request\Body;


class Bri
{

    public $env;
    public $consumerKey;
    public $consumerSecret;
    public $utils;
    public $client;


    const SANDBOX_BASE_URL = "https://sandbox.partner.api.bri.co.id";
    const PRODUCTION_BASE_URL = "";

    const ENV_PRODUCTION = 'prod';


    function __construct(string $env, $consumerKey, $consumerSecret)
    {
        $this->env = $env;
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->utils = new Util;
        $this->client = new HttpClient;
    }

    public function getBaseUrl()
    {
        $baseUrl = self::SANDBOX_BASE_URL;

        if ($this->env === self::ENV_PRODUCTION)
            $baseUrl = self::PRODUCTION_BASE_URL;
        return $baseUrl;
    }

    public function getTokenB2B()
    {

        $url = $this->getBaseUrl() . Constant::URL_SNAP_GET_TOKEN_B2B;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureAsymmetric($this->consumerKey, $timestamps);

        $header = [
            'X-TIMESTAMP' => $timestamps,
            'X-CLIENT-KEY' => $this->consumerKey,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];
        $data = array('grantType' => 'client_credentials');

        $body = Body::Json($data);
        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }

    public function getBalanceInquiry(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_SNAP_GET_BALANCE_INQUIRY;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_SNAP_GET_BALANCE_INQUIRY, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-CLIENT-KEY' => $this->consumerKey,
            'X-PARTNER-ID' => "PSADEV",
            'CHANNEL-ID' => "1945",
            'X-EXTERNAL-ID' => $this->utils->generateExternalID(),
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }

    public function getStatement(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_SNAP_GET_STATEMENT;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_SNAP_GET_STATEMENT, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-CLIENT-KEY' => $this->consumerKey,
            'X-PARTNER-ID' => "PSADEV",
            'CHANNEL-ID' => "1945",
            'X-EXTERNAL-ID' => $this->utils->generateExternalID(),
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }

    public function internalAccountInquiry(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_SNAP_INTERNAL_ACCOUNT_INQUIRY;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_SNAP_INTERNAL_ACCOUNT_INQUIRY, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-CLIENT-KEY' => $this->consumerKey,
            'X-PARTNER-ID' => "PSADEV",
            'CHANNEL-ID' => "1945",
            'X-EXTERNAL-ID' => $this->utils->generateExternalID(),
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }

    public function externalAccountInquiry(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_BIFAST_ACCOUNT_INQUIRY;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_BIFAST_ACCOUNT_INQUIRY, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }

    public function transferIntrabank(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_SNAP_TRANSFER_INTRABANK;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_SNAP_TRANSFER_INTRABANK, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-CLIENT-KEY' => $this->consumerKey,
            'X-PARTNER-ID' => "PSADEV",
            'CHANNEL-ID' => "1945",
            'X-EXTERNAL-ID' => $this->utils->generateExternalID(),
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }

    public function transferInterbank(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_BIFAST_TRANSFER_INTERBANK;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_BIFAST_TRANSFER_INTERBANK, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }

    public function transferStatus(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_SNAP_TRANSFER_STATUS;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_SNAP_TRANSFER_STATUS, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-CLIENT-KEY' => $this->consumerKey,
            'X-PARTNER-ID' => "PSADEV",
            'CHANNEL-ID' => "1945",
            'X-EXTERNAL-ID' => $this->utils->generateExternalID(),
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }


    public function transferStatusInterbank(string $access_token, $data)
    {

        $url = $this->getBaseUrl() . Constant::URL_BIFAST_TRANSFER_STATUS;

        $timestamps = Carbon::now()->format('Y-m-d\TH:i:sP');

        $signature = $this->utils->generateSignatureSymmetric($this->consumerSecret, $timestamps, "POST", Constant::URL_BIFAST_TRANSFER_STATUS, $access_token, $data);

        $header = [
            'Authorization' => "Bearer ". $access_token,
            'X-TIMESTAMP' => $timestamps,
            'X-SIGNATURE' => $signature,
            'Content-Type' => 'application/json'
        ];

        $body = Body::Json($data);

        $response = $this->client->request("POST", $url, $header, $body);
        return response()->json($response->body, $response->code);
    }
}
