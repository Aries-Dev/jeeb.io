<?php

namespace Aries\Jeeb\Facades;

use Illuminate\Support\Facades\Facade;

class Jeeb extends Facade {
    public static function getFacadeAccessor() {
        return 'Jeeb';
    }
}