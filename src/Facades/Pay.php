<?php

namespace Jshar\BogPayment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jshar\BogPayment\BogPayment
 */
class Pay extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jshar\BogPayment\Pay::class;
    }
}
