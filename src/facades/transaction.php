<?php

namespace Aries\Jeeb\Facades;

use Illuminate\Support\Facades\Facade;

class Transaction extends Facade {
    public static function getFacadeAccessor() {
        return 'Transaction';
    }
}