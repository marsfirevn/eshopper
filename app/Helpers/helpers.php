<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 11/02/2017
 * Time: 08:37
 */

if (! function_exists('get_app_url')) {
    function get_app_url($type = 'web')
    {
        if (str_singular($type) == 'web') {
            return 'http://' . env('APP_DOMAIN');
        }

        return 'http://' . str_singular($type) . '.' . env('APP_DOMAIN');
    }
}

if (! function_exists('clear_pattern')) {
    function clear_pattern($pattern, $source)
    {
        return str_replace($pattern, '', $source);
    }
}

if (! function_exists('remove_special_character')) {
    function remove_special_character($string)
    {
        $regex = '/[\ `~!@#$%^&*()\-=+{}<>,._\-\\\\\/\?\|;:\"\'\[\]]+/';
        return trim(preg_replace($regex, '_', $string), '_');
    }
}
