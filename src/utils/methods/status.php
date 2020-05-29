<?php

namespace Aries\Jeeb\Utils\Methods;

use Aries\Jeeb\Facades\State;
use Aries\Jeeb\Models\JeebTransaction;
use Illuminate\Support\Facades\Http;

class Status {

    private $status;

    public function check($token) {
        $signature = config('jeeb.signature');
        $url = config('jeeb.base_url') . "/payments/{$signature}/status";

        $result = Http::post($url, [
            'token' => $token
        ]);

        if($result['hasError']) {
            throw new \Exception($result['errorMessage'], $result['errorCode']);
        }

        $transaction = JeebTransaction::where('refrenceNo', $result['result']['referenceNo'])
            ->where('stateId', '<>', $result['result']['stateId'])
            ->first();
        if($transaction) {
            // update transaction model
            $transaction->stateId = $result['result']['stateId'];
            $transaction->coin = $result['result']['coin'];
            $transaction->address = $result['result']['address'];
            $transaction->transactionId = $result['result']['transactionId'];
            $transaction->value = $result['result']['value'];
            $transaction->paidValue = $result['result']['paidValue'];
            $transaction->isConfirmed = $result['result']['isConfirmed'];
            $transaction->finalizedTime = $result['result']['finalizedTime'];
            $transaction->save();
        }

        $this->status = $result;

        return $this;
    }

    public function json() {
        return $this->status->json();
    }

    public function message() {
        return State::message($this->status['result']['stateId']);
    }
}