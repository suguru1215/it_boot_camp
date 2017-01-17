<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Mypage\BaseController;
use App\Http\Logics\UserLogic;
use App\Http\Logics\LessenLogic;
use Session;
use Redirect;
use Request;

class LessenController extends BaseController
{
    private $userLogic;
    private $lessenLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userLogic = new UserLogic;
        $this->lessenLogic = new LessenLogic;
    }

    /**
     * トップージ
     *
     */
    public function index()
    {
        return $this->render(
            "mypage/lessen/index",
            [
                "lessen_data_list" => $this->lessenLogic->getDataList(),]
        );
    }
}
