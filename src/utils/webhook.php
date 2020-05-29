<?php

namespace Aries\Jeeb\Utils;

use Aries\Jeeb\Facades\Confirm;
use Aries\Jeeb\Facades\Status;
use Aries\Jeeb\Models\JeebTransaction;
use Exception;
use Illuminate\Support\Facades\Log;

class Webhook {
    public function process() {
        try {
            // get webhook data
            $data = request()->all();

            // check transaction status
            $status = Status::check($data['token'])->json();

            // get transaction model
            $transaction = JeebTransaction::where('orderNo', $status['result']['orderNo'])
                ->where('refrenceNo', $status['result']['referenceNo'])
                ->where('token', $data['token'])
                ->firstOrFail();

            // check stateId value and confirm if possible
            if($status['result']['stateId'] == 4) {
                Confirm::token($data['token']);
                $status = Status::check($data['token'])->json();
            }
            
            // update transaction model
            $transaction->stateId = $status['result']['stateId'];
            $transaction->coin = $status['result']['coin'];
            $transaction->address = $status['result']['address'];
            $transaction->transactionId = $status['result']['transactionId'];
            $transaction->value = $status['result']['value'];
            $transaction->paidValue = $status['result']['paidValue'];
            $transaction->isConfirmed = $status['result']['isConfirmed'];
            $transaction->finalizedTime = $status['result']['finalizedTime'];
            $transaction->save();

            // store a log
            Log::notice('a webhook message recived');
            Log::notice(request()->all());
            
            // return a status 200 json for jeeb.io server
            return response(['message' => 'webhook recived, thanks :)'], 200);
        } catch(Exception $e) {
            Log::error($e->getMessage());
            Log::error(request()->all());
            return response(['message' => 'something wrong!'], 500);
        }
    }
}