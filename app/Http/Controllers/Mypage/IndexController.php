<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Mypage\BaseController;
use App\Http\Logics\UserLogic;
use Session;
use Redirect;
use Request;

class IndexController extends BaseController
{
    private $userLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userLogic = new UserLogic;
    }

    /**
     * トップページ
     *
     */
    public function index()
    {
        return $this->render(
            "mypage/index",
            []
        );
    }
}
