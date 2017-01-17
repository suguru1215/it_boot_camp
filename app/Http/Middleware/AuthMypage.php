<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use Redirect;

class AuthMypage
{
    /**
     * ログインが必要なページヘの非ログインユーザのアクセスを拒否
     *
     */
    public function handle($request, Closure $next)
    {
        // ログイン確認
        if (Session::has("user_id") !== true) {
            // ログイン後に元のページが表示されるように保持
            if (isset($_SERVER["REQUEST_URI"]) && !empty($_SERVER["REQUEST_URI"])) {
                Session::set("route_request_uri", $_SERVER["REQUEST_URI"]);
            } else {
                Session::set("route_request_uri", "/");
            }

            return Redirect::to("/logout");
        }

        return $next($request);
    }
}
