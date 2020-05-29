<?php

namespace Aries\Jeeb\Utils\Methods;

use Exception;
use Illuminate\Support\Facades\Http;

class Confrim {
    public function token($token) {
        $signature = config('jeeb.signature');
        $url = config('jeeb.base_url') . "/payments/{$signature}/confirm";

        $result = Http::post($url, [
            'token' => $token
        ]);

        if($result['hasError']) {
            throw new Exception($result['errorMessage'], $result['errorCode']);
        }

        return $result;
    }
}