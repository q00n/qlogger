<?php
namespace Q00n\QLogger;

use Illuminate\Support\Facades\Facade;

class QLoggerFacade extends Facade
{
    protected static function getFacadeAccessor() { 
        return 'q00n-qlogger';
    }
}