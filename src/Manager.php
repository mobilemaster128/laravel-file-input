<?php

namespace MobileMaster\LaravelFileInput;

use Closure;
use Illuminate\Http\Request;

class Manager
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function make(array $settings = null)
    {
        return Builder::make($settings);
    }

    public function init(array $settings = null)
    {
        return Builder::init($settings);
    }
}
