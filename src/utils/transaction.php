<?php

namespace Aries\Jeeb\Utils;

use Aries\Jeeb\Models\JeebTransaction;

class Transaction {

    private $transaction;

    public function __construct()
    {
        $this->transaction = JeebTransaction::query();
    }

    public function confirmed() {
        $this->transaction = $this->transaction->where('stateId', 4)->where('isConfirmed', true);
        return $this;
    }

    public function unConfirmed() {
        $this->transaction = $this->transaction->where('stateId', 4)->where('isConfirmed', false);
        return $this;
    }

    public function pending() {
        $this->transaction = $this->transaction->where('stateId', 3);
        return $this;
    }

    public function rejected() {
        $this->transaction = $this->transaction->where('stateId', 5);
        return $this;
    }

    public function lessPaid() {
        $this->transaction = $this->transaction->where('stateId', 6);
        return $this;
    }

    public function overPaid() {
        $this->transaction = $this->transaction->where('stateId', 7);
        return $this;
    }

    public function get() {
        return $this->transaction->get();
    }

    public function model() {
        return $this->transaction;
    }
}