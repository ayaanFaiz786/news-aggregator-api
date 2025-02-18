<?php

use Illuminate\Support\Facades\Log;

function logException(Exception $exception)
{
    Log::error('FILE - ' . $exception->getFile());
    Log::error('FUNCTION - ' . __FUNCTION__);
    Log::error('LINE NO. - ' . $exception->getLine());
    Log::error('Error Message - ' . $exception->getMessage());
    Log::error('Stack trace - ');
    Log::error($exception->getTrace());
}
