<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Http\Logics\CaliculamLogic;
use App\Http\Logics\LessenLogic;
use Request;
use Redirect;
use Session;
use Validator;

class CaliculamController extends Controller
{
    // カリキュラム画像の保存パス
    const PATH_IMAGE_caliculam = "uploads/caliculam/";

    private $caliculamLogic;
    private $lessenLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->caliculamLogic = new CaliculamLogic;
        $this->lessenLogic = new LessenLogic;
    }

    /**
     * カリキュラム一覧ページ
     *
     */
    public function index($page = 1)
    {
        return $this->render(
            "mypage/caliculam/index",
            [
                "caliculam_data_list" => $this->caliculamLogic->getDataList(),]
        );
    }

    /**
     * カリキュラム詳細ページ
     *
     */
    public function detail($caliculam_id)
    {
        return $this->render(
            "mypage/caliculam/detail",
            [
                "lessen_data_list" => $this->lessenLogic->getDataListByCaliculamId($caliculam_id),
                "caliculam_data" => $this->caliculamLogic->getData($caliculam_id),]
        );
    }

    /**
     * カリキュラム申し込み
     *
     */
    public function regist($caliculam_id)
    {
        // 登録処理

        return Redirect::to("/mypage/regist/complete");
    }

    /**
     * カリキュラム申し込み完了
     *
     */
    public function complete()
    {
        return $this->render(
            "mypage/caliculam/regist_complete",
            []
        );
    }
}
