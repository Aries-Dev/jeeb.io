<?php

namespace Aries\Jeeb\Utils\Methods;

use Exception;
use Illuminate\Support\Facades\Http;

class Issue {
    private $orderNo,
            $value,
            $coins,
            $callbackUrl,
            $webhookUrl,
            $expiration,
            $allowReject,
            $allowTestNet,
            $language;

    public function __construct()
    {
        $this->expiration = 15;
        $this->allowReject = true;
        $this->allowTestNet = false;
        $this->language = 'en';
    }

    public function orderNumber($number) {
        $this->orderNo = $number;
        return $this;
    }

    public function btcValue($value) {
        $this->value = $value;
        return $this;
    }

    public function with($coins) {
        $this->coins == null ? $this->coins = $coins : $this->coins = $this->coins . "/" . $coins;
        return $this;
    }

    public function acceptBitCoin() {
        $this->coins == null ? $this->coins = 'btc' : $this->coins .= '/btc';
        return $this;
    }

    public function acceptTestBitCoin() {
        $this->coins == null ? $this->coins = 'tbtc' : $this->coins .= '/tbtc';
        $this->allowTestNet = true;
        return $this;
    }

    public function acceptDogeCoin() {
        $this->coins == null ? $this->coins = 'doge' : $this->coins .= '/doge';
        return $this;
    }

    public function acceptTestDogeCoin() {
        $this->coins == null ? $this->coins = 'tdoge' : $this->coins .= '/tdoge';
        $this->allowTestNet = true;
        return $this;
    }

    public function acceptLiteCoin() {
        $this->coins == null ? $this->coins = 'ltc' : $this->coins .= '/ltc';
        return $this;
    }

    public function acceptTestLiteCoin() {
        $this->coins == null ? $this->coins = 'tltc' : $this->coins .= '/tltc';
        $this->allowTestNet = true;
        return $this;
    }

    public function acceptEtherium() {
        $this->coins == null ? $this->coins = 'eth' : $this->coins .= '/eth';
        return $this;
    }

    public function callbackUrl($url) {
        $this->callbackUrl = $url;
        return $this;
    }

    public function webhookUrl($url) {
        $this->webhookUrl = $url;
        return $this;
    }

    public function expirationInMinutes($minutes) {
        $this->expiration = $minutes;
        return $this;
    }

    public function denyRejecting() {
        $this->allowReject = false;
        return $this;
    }

    public function disableTestNet() {
        $this->allowTestNet = false;
        return $this;
    }

    public function language($language) {
        $this->language = $language;
        return $this;
    }

    public function init() {
        if(($this->orderNo && $this->value && $this->coins && $this->webhookUrl) == null) {
            throw new Exception('orderNumber, btcValue, accept*coin & webhookUrl is required');
        }

        $signature = config('jeeb.signature');
        $url = config('jeeb.base_url') . "/payments/{$signature}/issue";

        $params['orderNo']      = $this->orderNo;
        $params['value']        = $this->value;
        $params['coins']        = $this->coins;
        if($this->callbackUrl != null) {
            $params['callbackUrl'] = $this->callbackUrl;
        }
        $params['webhookUrl']   = $this->webhookUrl;
        $params['expiration']   = $this->expiration;
        $params['allowReject']  = $this->allowReject;
        $params['allowTestNet'] = $this->allowTestNet;
        $params['language']     = $this->language;


        $result = Http::post($url, $params);


        if($result['status'] != 200) {
            throw new Exception($result['errorMessage']);
        }

        $response['refrenceNo'] = $result['result']['referenceNo'];
        $response['token'] = $result['result']['token'];
        $response['expirationTime'] = $result['result']['expirationTime'];
        $response['addresses'] = $result['result']['addresses'];
        $response['redirect_url'] = config('jeeb.base_url') . '/payments/invoice?token=' . $result['result']['token'];

        return $response;
    }
}