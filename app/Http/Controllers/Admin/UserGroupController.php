<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Logics\UserGroupLogic;
use App\Http\Logics\UserLogic;
use Request;
use Redirect;

class UserGroupController extends Controller
{
    private $userGroupLogic;
    private $userLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userGroupLogic = new UserGroupLogic;
        $this->userLogic = new UserLogic;
    }

    /**
     * トップページ
     *
     */
    public function index()
    {
        return $this->render(
            "admin/user_group/index",
            [
                "user_group_data_list" => $this->userGroupLogic->getDataList(),]
        );
    }

    /**
     * 詳細画面
     *
     */
    public function detail($user_group_id)
    {
        return $this->render(
            "admin/user_group/detail",
            [
                // ユーザーグループ情報
                "user_group_data" => $this->userGroupLogic->getData($user_group_id),
                // ユーザ情報
                "group_user_data_list" => $this->userLogic->getGroupUserDataList($user_group_id),]
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
        $this->userGroupLogic->upsertData($input);

        if (isset($input["user_group_id"]) && !empty($input["user_group_id"])) {
            return Redirect::to("/admin/user_group/" . $input["user_group_id"]);
        }

        return Redirect::to("/admin/user_group/");
    }
}
