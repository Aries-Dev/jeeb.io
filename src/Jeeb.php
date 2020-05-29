<?php

namespace Aries\Jeeb;

use Aries\Jeeb\Utils\Callback;
use Aries\Jeeb\Utils\Pay;
use Aries\Jeeb\Utils\Transaction;
use Aries\Jeeb\Utils\Webhook;

class Jeeb {
    public function pay() {
        return new Pay();
    }

    public function callback() {
        return new Callback();
    }

    public function webhook() {
        return new Webhook();
    }

    public function transaction() {
        return new Transaction();
    }
}