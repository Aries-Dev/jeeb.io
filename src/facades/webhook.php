<?php

namespace Aries\Jeeb\Facades;

use Illuminate\Support\Facades\Facade;

class Webhook extends Facade {
    public static function getFacadeAccessor() {
        return 'Webhook';
    }
}