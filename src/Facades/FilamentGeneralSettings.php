<?php

namespace ninshikiProject\GeneralSettings\Facades;

use Illuminate\Support\Facades\Facade;
use ninshikiProject\GeneralSettings\GeneralSettings;

/**
 * @see \ninshikiProject\GeneralSettings\GeneralSettings
 */
class FilamentGeneralSettings extends Facade
{
    protected static function getFacadeAccessor()
    {
        return GeneralSettings::class;
    }
}
