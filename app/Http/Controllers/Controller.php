<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Logics\UserLogic;
use Session;
use View;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    private $userLogic;
    // 一般表示情報
    private $base_view_data = [];
    // メンバーデータ
    private $user_data = [];

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $userLogic = new UserLogic;

        // アカウント情報を取得
        if (Session::has("UserLogic_getDataSimple_user_data") === true) {
            $this->user_data = Session::get("UserLogic_getDataSimple_user_data");

        } elseif (Session::has("user_id") === true) {
            $this->user_data = $userLogic->getDataSimple(Session::get("user_id"));
        }
    }

    /**
     * 表示
     *
     */
    protected function render($view_file, array $view_data)
    {
        $this->base_view_data = [
            "csrf_token" => csrf_token(),
            "user_data" => $this->user_data,];

        return View::make($view_file, array_merge($this->base_view_data, $view_data));
    }
}
