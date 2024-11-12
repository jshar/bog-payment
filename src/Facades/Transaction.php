<?php

namespace Jshar\BogPayment\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Jshar\BogPayment\BogPayment
 */
class Transaction extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Jshar\BogPayment\Transaction::class;
    }
}
