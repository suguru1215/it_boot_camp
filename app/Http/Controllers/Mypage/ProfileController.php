<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Mypage\BaseController;
use App\Http\Logics\UserLogic;
use App\Http\Models\UserModel;
use Session;
use Redirect;
use Request;
use Exception;

class ProfileController extends BaseController
{
    // プロフィール画像保存パス
    const PATH_IMAGE_PROFILE = "uploads/profile/";

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
     * プロフィール入力画面
     *
     */
    public function input()
    {
        return $this->render(
            "mypage/profile",
            [
                "user_profile_data" => $this->userLogic->getData(Session::get("user_id")),
                "role_student" => UserModel::ROLE_STUDENT,]
        );
    }

    /**
     * プロフィール入力確認
     *
     */
    public function confirm()
    {
        // 画像関係の処理
        $input = Request::all();

        if (Request::hasFile("user_image") === true) {
            // 画像を保存
            $file = Request::file("user_image");
            $ext = strtolower($file->getClientOriginalExtension());
            $file_name = $input["user_id"] . "_" . md5(openssl_random_pseudo_bytes(20)) . "." . $ext;
            $result = $file->move(self::PATH_IMAGE_PROFILE, $file_name);

            if (!isset($result) || empty($result)) {
                throw new Exception("画像の登録に失敗しました。");
            }

            // 画像名を登録
            $this->userLogic->updateProfileImage($input["user_id"], $file_name);
        }

        $this->userLogic->updateProfileData(Request::all());

        $user_data = $this->userLogic->getDataSimple(Session::get("user_id"));

        Session::set("UserLogic_getDataSimple_user_data", $user_data);

        return Redirect::to("/mypage/profile/input");
    }
}
