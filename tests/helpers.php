<?php

if (!function_exists('dd')) {
    function dd($payload){
        print_r($payload);
        echo PHP_EOL;
        die;
    }
}

if (!function_exists('microtime_float')) {
    function microtime_float() {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}