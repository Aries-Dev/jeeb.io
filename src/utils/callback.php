<?php

namespace Aries\Jeeb\Utils;

use Aries\Jeeb\Facades\Status;
use Aries\Jeeb\Facades\State;
use Aries\Jeeb\Models\JeebTransaction;

class Callback {
    private $model;

    public function process() {
        $data = request()->all();
        // find model
        $transaction = JeebTransaction::where('orderNo', $data['orderNo'])
            ->where('refrenceNo', $data['referenceNo'])
            ->where('token', $data['token'])
            ->firstOrFail();
        // check status from jeeb.io server
        $status = Status::check($data['token'])->json();

        // get stateId from status
        $stateId = $status['result']['stateId'];
        $finalizedTime = $status['result']['finalizedTime'];

        // update model stateId
        $transaction->stateId = $stateId;
        $transaction->finalizedTime = $finalizedTime;
        $transaction->save();

        //$this->model = $transaction;


        return $transaction;
    }

    // public function view() {
    //     return $this->model;
    // }

    // public function message() {
    //     return State::message($this->model->stateId);
    // }
}