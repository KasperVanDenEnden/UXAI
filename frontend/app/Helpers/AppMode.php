<?php

use Illuminate\Support\Facades\Request;

function isGoodMode(): bool
{
    return Request::cookie('app_mode', 'good') === 'good';
}
function currentMode(): string
{
    return Request::cookie('app_mode', 'good');
}
