<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Logics\LessenLogic;
use App\Http\Logics\CaliculamLogic;
use Request;
use Redirect;

class LessenController extends Controller
{
    // レッスン画像の保存パス
    const PATH_IMAGE_LESSEN = "uploads/lessen/";

    private $lessenLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->lessenLogic = new LessenLogic;
        $this->caliculamLogic = new CaliculamLogic;
    }

    /**
     * レッスン一覧ページ
     *
     */
    public function index($page = 1)
    {
        return $this->render(
            "admin/lessen/index",
            [
                "lessen_data_list" => $this->lessenLogic->getDataList(),]
        );
    }

    /**
     * レッスン詳細ページ
     *
     */
    public function detail($lessen_id)
    {
        return $this->render(
            "admin/lessen/detail",
            [
                "lessen_data" => $this->lessenLogic->getData($lessen_id),]
        );
    }

    /**
     * レッスン作成ページ
     *
     */
    public function edit($lessen_id = null)
    {
        $lessen_data = [];
        if (isset($lessen_id) && !empty($lessen_id)) {
            $lessen_data = $this->lessenLogic->getData($lessen_id);
        }

        // カリキュラム詳細ページから遷移してきた場合は，カリキュラムのIDを初期選択する
        if (Request::has("caliculam_id") === true) {
            $lessen_data["lessen_caliculam_id"] = Request::get("caliculam_id");
        }

        return $this->render(
            "admin/lessen/edit",
            [
                "caliculam_data_list" => $this->caliculamLogic->getDataList(),
                "lessen_data" => $lessen_data,]
        );
    }

    /**
     * レッスン登録
     *
     */
    public function confirm()
    {
        // 画像関係の処理
        $input = Request::all();

        // 基本情報を登録
        $lessen_id = $this->lessenLogic->upsertData($input);

        if (Request::hasFile("lessen_image") === true) {
            // 画像を保存
            $file = Request::file("lessen_image");
            $ext = strtolower($file->getClientOriginalExtension());
            $file_name = $lessen_id . "_" . md5(openssl_random_pseudo_bytes(20)) . "." . $ext;
            $result = $file->move(self::PATH_IMAGE_LESSEN, $file_name);

            if (!isset($result) || empty($result)) {
                throw new Exception("画像の登録に失敗しました。");
            }

            // 画像名を登録
            $this->lessenLogic->updateLessenImage($lessen_id, $file_name);
        }

        return Redirect::to("/admin/caliculam/" . $input["lessen_caliculam_id"]);
    }
}
