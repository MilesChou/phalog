<?php

namespace MilesChou\Phalog\Facades;

use Illuminate\Support\Facades\Facade;
use League\CommonMark\Converter;

/**
 * @mixin Converter
 */
class Markdown extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Converter::class;
    }
}
