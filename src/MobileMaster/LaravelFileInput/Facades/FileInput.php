<?php

namespace MobileMaster\LaravelFileInput\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MobileMaster\LaravelFileInput\Manager
 */
class FileInput extends Facade
{
        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor()
        {
            return 'plupload';
        }
}
