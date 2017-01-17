<?php

namespace App\Http\Logics;

use App\Http\Models\UserModel;
use Session;
use Exception;
use Config;
use Hash;

class UserLogic
{
    // プロフィール画像保存パス
    const PATH_IMAGE_PROFILE = "uploads/profile/";

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
     * ユーザー情報を登録
     *
     */
    public function registData(array $input)
    {
        $result = $this->userModel->createData(
            [
                "user_name" => $input["name"],
                "user_real_name" => $input["name"],
                "user_email" => $input["email"],
                "user_password" => Hash::make($input["password"]),
                "user_image" => "",
                "user_birthday" => "",
                "user_gender" => "",
                "user_address" => "",
                "user_role" => UserModel::ROLE_STUDENT,
                "user_pr_text" => "",
                "user_facebook_id" => "",
                "user_facebook_access_token" => "",
                "user_is_mail_magazine" => UserModel::IS_MAIL_MAGAZINE_TRUE,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($result) < 1) {
            throw new Exception("ユーザー情報の登録に失敗しました。");
        }

        return true;
    }

    /**
     * ユーザーのFacebook情報を登録
     *
     */
    public function registFacebookData($input)
    {
        // ニックネームが未入力の場合は名前を使う
        $user_name = $input->getNickname();
        if (!isset($user_name) || empty($user_name)) {
            $user_name = $input->getName();
        }

        // Facebookの画像URLを生成 (getAvatar()だと画像サイズが小さい)
        $facebook_image_url = self::PATH_FACEBOOK_IMAGE_PREFIX  . $input->getId() . self::PATH_FACEBOOK_IMAGE_SUFFIX;
        // 新しい画像名を生成
        $file_name = $input->getId() . "_" . md5(openssl_random_pseudo_bytes(20)) . ".jpeg";
        // wgetでfacebookのプロフィール画像を取得
        shell_exec("wget -O " . self::PATH_IMAGE_PROFILE . $file_name . " " . $facebook_image_url);

        $result = $this->userModel->createData(
            [
                "user_name" => $user_name,
                "user_real_name" => $input->getName(),
                "user_email" => $input->getEmail(),
                "user_password" => "",
                "user_image" => $file_name,
                "user_birthday" => "",
                "user_gender" => "",
                "user_address" => "",
                "user_role" => UserModel::ROLE_STUDENT,
                "user_pr_text" => "",
                "user_facebook_id" => $input->getId(),
                "user_facebook_access_token" => $input->token,
                "user_is_mail_magazine" => UserModel::IS_MAIL_MAGAZINE_TRUE,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($result) < 1) {
            throw new Exception("ユーザー情報の登録に失敗しました。");
        }

        return true;
    }

    /**
     * ユーザーのFacebook情報をアップデート
     * 既存の情報を優先する
     *
     */
    public function updateFacebookData($user_data, $input)
    {
        // Facebookのニックネームを取得する
        $user_name = $input->getNickname();
        // ニックネームが既存か確認する
        if (isset($user_data["user_name"]) && !empty($user_data["user_name"])) {
            $user_name = $user_data["user_name"];
        // Facebookにニックネームが未入力の場合は名前を使う
        } elseif (!isset($user_name) || empty($user_name)) {
            $user_name = $input->getName();
        }

        // Facebookの名前を取得する
        $user_real_name = $input->getName();
        // 名前が既存か確認する
        if (isset($user_data["user_real_name"]) && !empty($user_data["user_real_name"])) {
            $user_real_name = $user_data["user_real_name"];
        }

        // Facebookのプロフィール画像を取得する
        $user_image = $input->getAvatar();
        if (isset($user_data["user_image"]) && !empty($user_data["user_image"])) {
            // サンプル画像の場合は上書き
            if ($user_data["user_image"] !== "no_image.png") {
                $user_image = $user_data["user_image"];
            }
        }

        $result = $this->userModel->updateData(
            // where_data
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $user_data["user_id"],],],
            // update_data
            [
                "user_name" => $user_name,
                "user_real_name" => $user_real_name,
                // Emailは登録済みしか無いので無視
                "user_image" => $user_image,
                "user_facebook_id" => $input->getId(),
                "user_facebook_access_token" => $input->token,
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($result) < 1) {
            throw new Exception("ユーザー情報の登録に失敗しました。");
        }

        return true;
    }

    /**
     * ユーザー情報を取得
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

        return $this->formatUserData($result->toArray());
    }

    /**
     * 表示に必要なユーザー情報を取得
     *
     */
    public function getDataSimple($user_id)
    {
        $result = $this->userModel->getDataSimple(
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $user_id,]]
        );

        if (count($result) < 1) {
            throw new Exception("user_id: " . $user_id . "のユーザー情報の取得に失敗しました。");
        }

        $result_formatted = $this->formatUserData($result->toArray());

        Session::set("UserLogic_getDataSimple_user_data", $result_formatted);

        return $result_formatted;
    }

    /**
     * ユーザー情報を取得
     *
     */
    public function getDataWithWhere(array $where_data)
    {
        $result = $this->userModel->getData($where_data);

        // 0件の場合がある
        if (count($result) < 1) {
            return false;
        }

        return $this->formatUserData($result->toArray());
    }

    /**
     * ユーザーの数を取得
     *
     */
    public function getDataUserCount()
    {
        return $this->userModel->getDataCount([]);
    }

    /**
     * ユーザー一覧情報を取得
     *
     */
    public function getDataList()
    {
        $result = $this->userModel->getDataList([]);

        if (count($result) < 1) {
            throw new Exception("ユーザー情報の取得に失敗しました．");
        }

        $user_data_list = $result->toArray();

        $config_gender = Config::get("gender");
        $config_role = Config::get("role");

        foreach ($user_data_list as $key => $user_data) {
            // 表示用に整形
            // 性別
            if (isset($user_data["user_gender"]) && (int)$user_data["user_gender"] !== 0) {
                $user_data_list[$key]["user_gender_display"] = $config_gender[$user_data["user_gender"]];
            }
            // 権限
            $user_data_list[$key]["user_role_display"] = $config_role[$user_data["user_role"]];
        }

        return $user_data_list;
    }

    /**
     * グループ登録ユーザー一覧情報を取得
     *
     */
    public function getGroupUserDataList($user_group_id)
    {
        $result = $this->userModel->getDataList(
            [
                [
                    "key" => "user.user_user_group_id",
                    "operator" => "=",
                    "value" => $user_group_id,],]
        );

        if (count($result) < 1) {
            return [];
        }

        return $result->toArray();
    }


    /**
     * ユーザー一覧情報を権限で取得
     *
     */
    public function getDataListWithRole($role_id)
    {
        $result = $this->userModel->getDataList(
            [
                [
                    "key" => "user.user_role",
                    "operator" => "=",
                    "value" => $role_id,],]
        );

        if (count($result) < 1) {
            throw new Exception("ユーザー情報の取得に失敗しました．");
        }

        $user_data_list = $result->toArray();

        $config_gender = Config::get("gender");
        $config_role = Config::get("role");

        foreach ($user_data_list as $key => $user_data) {
            // 表示用に整形
            // 性別
            if (isset($user_data["user_gender"]) && (int)$user_data["user_gender"] !== 0) {
                $user_data_list[$key]["user_gender_display"] = $config_gender[$user_data["user_gender"]];
            }
            // 権限
            $user_data_list[$key]["user_role_display"] = $config_role[$user_data["user_role"]];
        }

        return $user_data_list;
    }

    /**
     * 全てのユーザー一覧情報を取得
     *
     */
    public function getDataListAll($page = 1)
    {
        $result = $this->userModel->getDataListAll($paginate, $page);

        if (count($result) < 1) {
            throw new Exception("ユーザー情報の取得に失敗しました．");
        }

        $user_data_list = $result->toArray();

        $config_gender = Config::get("gender");
        $config_role = Config::get("role");

        foreach ($user_data_list["data"] as $key => $user_data) {
            // 表示用に整形
            // 性別
            if (isset($user_data["user_gender"]) && (int)$user_data["user_gender"] !== 0) {
                $user_data_list["data"][$key]["user_gender_display"] = $config_gender[$user_data["user_gender"]];
            }
            // 権限
            $user_data_list["data"][$key]["user_role_display"] = $config_role[$user_data["user_role"]];
        }

        return $user_data_list;
    }

    /**
     * ユーザーのプロフィール情報を更新
     *
     */
    public function updateProfileData(array $input)
    {
        $result = $this->userModel->updateData(
            // where_data
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $input["user_id"],],],
            // input_data
            [
                "user_name" => $input["user_name"],
                "user_real_name" => $input["user_real_name"],
                "user_email" => $input["user_email"],
                "user_birthday" => (isset($input["user_birthday"]) && !empty($input["user_birthday"])) ? $input["user_birthday"] : "",
                "user_gender" => (isset($input["user_gender"]) && !empty($input["user_birthday"])) ? $input["user_gender"] : "",
                "user_address" => (isset($input["user_address"]) && !empty($input["user_address"])) ? $input["user_address"] : "",
                "user_pr_text" => (isset($input["user_pr_text"]) && !empty($input["user_pr_text"])) ? $input["user_pr_text"] : "",
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($result) < 1) {
            throw new Exception("ユーザーのプロフィール情報の更新に失敗しました。");
        }

        return true;
    }

    /**
     * ユーザー情報の更新
     *
     */
    public function updateData(array $where_data, array $update_data)
    {
        $result = $this->userModel->updateData($where_data, $update_data);

        if (count($result) < 1) {
            throw new Exception("ユーザー権限の更新に失敗しました。");
        }

        return true;
    }

    /**
     * プロフィール画像登録
     *
     */
    public function updateProfileImage($user_id, $file_name)
    {
        $result = $this->userModel->updateData(
            // where_data
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $user_id,],],
            // update_data
            [
                "user_image" => $file_name,
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($result) < 1) {
            throw new Exception("プロフィール画像の更新に失敗しました。");
        }

        return true;
    }

    /**
     * ユーザー権限の更新
     *
     */
    public function updateRoleData($input)
    {
        $result = $this->userModel->updateData(
            // where_data
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $input["account_id"],],],
            // update_data
            [
                "user_role" => $input["role_key"],
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($result) < 1) {
            throw new Exception("ユーザー権限の更新に失敗しました。");
        }

        return true;
    }

    /**
     * ログイン時間を記録
     *
     */
    public function insertLoginTime($user_id)
    {
        $update_record = $this->userModel->updateData(
            // where_data
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $user_id,],],
            // update_data
            [
                "user_login_time" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if ($update_record < 1) {
            throw new Exception("ユーザのログイン時間を記録に失敗しました。");
        }

        return true;
    }

    /**
     * ユーザー情報を表示用に整形
     *
     */
    private function formatUserData(array $user_data)
    {
        if (isset($user_data["user_gender"]) && !empty($user_data["user_gender"])) {
            $user_data["user_gender_display"] = Config::get("gender")[$user_data["user_gender"]];
        }

        // プロフィール画像
        if (!isset($user_data["user_image"]) || empty($user_data["user_image"]))
        {
            $user_data["user_image"] = "no_image.png";
        }

        return $user_data;
    }
}
