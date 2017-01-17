<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Logics\UserLogic;
use App\Http\Logics\UserGroupLogic;
use Request;
use Redirect;

class UserController extends Controller
{
    private $userLogic;
    private $userGroupLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userLogic = new UserLogic;
        $this->userGroupLogic = new UserGroupLogic;
    }

    /**
     * トップページ
     *
     */
    public function index($page = 1)
    {
        return $this->render(
            "admin/user/index",
            [
                "user_data_list" => $this->userLogic->getDataList(),]
        );
    }

    /**
     * 詳細ページ
     *
     */
    public function detail($user_id)
    {
        return $this->render(
            "admin/user/detail",
            [
                // ユーザー情報 (アクセスユーザのuser_dataと区別するために'account'を使用)
                "account_data" => $this->userLogic->getData($user_id),
                // ユーザーグループ情報
                "user_group_data_list" => $this->userGroupLogic->getDataList(),]
        );
    }

    /**
     * 入力内容確認
     *
     */
    public function confirm()
    {
        $input = Request::all();

        // 登録処理
        $this->userLogic->updateData(
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $input["user_id"],],],
            [
                "user_user_group_id" => $input["user_user_group_id"],
                "updated_at" => date("Y-m-d H:i:s"),]);

        return Redirect::to("/admin/user/" . $input["user_id"]);
    }
}
