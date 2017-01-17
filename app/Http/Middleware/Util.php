<?php

namespace App\Http\Middleware;

use Redirect;

class Util
{
    /**
     * redirect先のURLを取得する
     *
     */
    public static function getPreviousUrl($default_url = "/")
    {
        if (isset($_SERVER["HTTP_REFERER"]) && !empty($_SERVER["HTTP_REFERER"])) {
            return $_SERVER["HTTP_REFERER"];

        } else {
            return $default_url;
        }
    }
}

