<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Logics\CaliculamLogic;
use App\Http\Models\CaliculamModel;
use Session;
use Config;
use Request;
use Redirect;

class CaliculamController extends Controller
{
    // カリキュラム一覧ページの表示上限
    const caliculam_LIST_DISPLAY_LIMIT = 48;

    private $caliculamLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->caliculamLogic = new CaliculamLogic;
    }

    /**
     * 一覧画面
     *
     */
    public function index($page = 1)
    {
        return $this->render(
            "caliculam/index",
            [
                // カリキュラム情報を取得
                "caliculam_data_list" => $this->caliculamLogic->getDataList(self::caliculam_LIST_DISPLAY_LIMIT, $page),]
        );
    }

    /**
     * 詳細画面
     *
     */
    public function detail($caliculam_id)
    {
        // カリキュラム情報を取得
        $caliculam_data = $this->caliculamLogic->getData($caliculam_id);

        // 非表示，プレビューのカリキュラム場合
        if ((int)$caliculam_data["caliculam_display_status"] !== CaliculamModel::STATUS_SHOW) {
            if (Request::get("is_preview") !== "true") {
                return Redirect::to("/");
            }
        }

        return $this->render(
            "caliculam/detail",
            [
                "caliculam_data" => $caliculam_data,]
        );
    }
}
