<?php

namespace Aries\Jeeb\Utils\Methods;

use Exception;
use Illuminate\Support\Facades\Http;

class Convert {
    private $from, $to, $amount;

    public $base_url = "https://core.jeeb.io/api";

    public function from($from) {
        $this->from = $from;
        return $this;
    }

    public function to($to) {
        $this->to = $to;
        return $this;
    }

    public function amount($amount) {
        $this->amount = $amount;
        return $this;
    }

    public function convert() {
        $url = config('jeeb.base_url') . '/currency';
        $result = Http::get($url, [
            'base' => $this->from,
            'target' => $this->to,
            'value' => $this->amount
        ]);

        if($result['hasError']) {
            throw new Exception($result['errorMessage'], $result['errorCode']);
        }

        return $result['result'];
    }
}