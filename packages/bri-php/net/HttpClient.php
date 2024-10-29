<?php

namespace Bri\net;

use Illuminate\Support\Facades\Log;
use Unirest\Request;


class HttpClient
{

    public function __construct()
    {

    }

    public function request(
        string $method,
        string $url,
        array  $headers,
        $body
    )
    {
        Log::info("Send Unirest Request", [
            'method' => $method,
            'url' => $url,
            'headers' => $headers,
            'body' => $body
        ]);

        $response = Request::$method($url, $headers, $body);

        Log::info("Unirest Response Received", [
            'status' => $response->code,
            'body' => $response->body
        ]);
        return $response;

    }
}
