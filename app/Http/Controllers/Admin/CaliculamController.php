<?php

namespace App\Http\Controllers\Admin;

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
            "admin/caliculam/index",
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
            "admin/caliculam/detail",
            [
                "lessen_data_list" => $this->lessenLogic->getDataListByCaliculamId($caliculam_id),
                "caliculam_data" => $this->caliculamLogic->getData($caliculam_id),]
        );
    }

    /**
     * カリキュラム作成ページ
     *
     */
    public function edit($caliculam_id = null)
    {
        $caliculam_data = [];
        if (isset($caliculam_id) && !empty($caliculam_id)) {
            $caliculam_data = $this->caliculamLogic->getData($caliculam_id);
        }

        // エラーメッセージ
        $error_message = "";
        if (Session::has("Admin_CaliculamController_confirm_error_message") === true) {
            $error_message = Session::pull("Admin_CaliculamController_confirm_error_message");
        }

        return $this->render(
            "admin/caliculam/edit",
            [
                "error_message" => $error_message,
                "caliculam_data" => $caliculam_data,]
        );
    }

    /**
     * カリキュラム登録
     *
     */
    public function confirm()
    {
        // 画像関係の処理
        $input = Request::all();

        // 入力情報の確認
        $validator = Validator::make(
            $input,
            [
                "caliculam_title" => ["required",],
                "caliculam_price" => ["required",],
                "caliculam_pr_text" => ["required",],
                "caliculam_text" => ["required",],]
        );

        if ($validator->fails() === true) {
            return Redirect::to("/admin/caliculam/edit/")->with("Admin_CaliculamController_confirm_error_message", "すべての項目を入力してください。");
        }

        // 基本情報を登録
        $caliculam_id = $this->caliculamLogic->upsertData($input);

        if (Request::hasFile("caliculam_image") === true) {
            // 画像を保存
            $file = Request::file("caliculam_image");
            $ext = strtolower($file->getClientOriginalExtension());
            $file_name = $caliculam_id . "_" . md5(openssl_random_pseudo_bytes(20)) . "." . $ext;
            $result = $file->move(self::PATH_IMAGE_caliculam, $file_name);

            if (!isset($result) || empty($result)) {
                throw new Exception("画像の登録に失敗しました。");
            }

            // 画像名を登録
            $this->caliculamLogic->updateCaliculamImage($caliculam_id, $file_name);
        }

        return Redirect::to("/admin/caliculam/" . $caliculam_id);
    }
}
