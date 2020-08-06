<?php

if (!function_exists('stripBbCode')) {
    function stripBbCode($string)
    {
        $pattern = '|[[\/\!]*?[^\[\]]*?]|si';
        $replace = '';
        return preg_replace($pattern, $replace, $string);
    }
}
