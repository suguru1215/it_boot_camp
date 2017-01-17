<?php

namespace App\Http\Logics;

use App\Http\Models\UserModel;
use Session;
use Exception;
use Config;
use Mail;

class RoleLogic
{
    private $userModel;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $this->userModel = new UserModel;
    }

    /**
     * 権限情報を取得
     *
     */
    public function getData($user_id)
    {
        $result = $this->userModel->getData(
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $user_id,]]
        );

        if (count($result) < 1) {
            throw new Exception("ユーザー情報の取得に失敗しました。");
        }

        return $result["user_role"];
    }
}
