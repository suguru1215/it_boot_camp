<?php

namespace App\Http\Models;

use Eloquent;
use DB;

class UserModel extends Eloquent
{
    // 権限: 所有者
    const ROLE_SUPER_ADMIN = 1;
    // 権限: 管理者
    const ROLE_ADMIN = 2;
    // 権限: 運営
    const ROLE_STAFF = 3;
    // 権限: 生徒
    const ROLE_STUDENT = 4;
    // メルマガ登録
    const IS_MAIL_MAGAZINE_TRUE = 1;
    const IS_MAIL_MAGAZINE_FALSE = 0;
    // 運営の擬似ユーザID
    const USER_ID_ADMIN = 0;

    protected $table = "user";
    protected $primaryKey = "user_id";
    protected $softDelete = true;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * ユーザー情報を登録する
     *
     */
    public function createData(array $insert_data)
    {
        return $this
            ->insertGetId($insert_data);
    }

    /**
     * ユーザーを取得する
     *
     */
    public function getData(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user.deleted_at")
        ->first(
            [
                "user.user_id",
                "user.user_name",
                "user.user_real_name",
                "user.user_email",
                "user.user_image",
                "user.user_birthday",
                "user.user_gender",
                "user.user_address",
                "user.user_role",
                "user.user_user_group_id",
                "user.user_pr_text",
                "user.user_twitter_id",
                "user.user_is_mail_magazine",
                "user.user_password",
                "user.created_at",
                "user.updated_at",]
        );
    }

    /**
     * 表示に必要なユーザーを取得する
     *
     */
    public function getDataSimple(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user.deleted_at")
        ->first(
            [
                "user.user_id",
                "user.user_name",
                "user.user_email",
                "user.user_image",
                "user.user_role",
                "user.user_user_group_id",
                "user.created_at",
                "user.updated_at",]
        );
    }

    /**
     * 対象データ数を取得する
     *
     */
    public function getDataCount(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user.deleted_at")
        ->count("user.user_id");
    }

    /**
     * ユーザーリストを取得する
     *
     */
    public function getDataList(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user.deleted_at")
        ->orderBy("user.user_id", "desc")
        ->get([
            "user.user_id",
            "user.user_name",
            "user.user_real_name",
            "user.user_email",
            "user.user_image",
            "user.user_birthday",
            "user.user_gender",
            "user.user_address",
            "user.user_role",
            "user.user_user_group_id",
            "user.user_pr_text",
            "user.user_facebook_id",
            "user.user_twitter_id",
            "user.user_is_mail_magazine",
            "user.created_at",
            "user.updated_at",]
        );
    }

    /**
     * 全てのユーザーリストを取得する
     *
     */
    public function getDataListAll($paginate = false, $page = 1)
    {
        $result =  $this
            ->whereNull("user.deleted_at")
            ->orderBy("user.user_id", "desc");

        $select_column = [
            "user.user_id",
            "user.user_name",
            "user.user_real_name",
            "user.user_email",
            "user.user_image",
            "user.user_birthday",
            "user.user_gender",
            "user.user_address",
            "user.user_role",
            "user.user_user_group_id",
            "user.user_pr_text",
            "user.user_facebook_id",
            "user.user_twitter_id",
            "user.user_is_mail_magazine",
            "user.created_at",
            "user.updated_at",];

        // ページャ
        if (isset($paginate) && !empty($paginate)) {
            return $result->paginate($paginate, $select_column, "pagename", $page);
        } else {
            return $result->get($select_column);
        }
    }

    /**
     * ユーザー情報を更新する
     *
     */
    public function updateData(array $where_data, array $update_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user.deleted_at")
        ->update($update_data);
    }

    /**
     * ユーザーを論理削除する
     *
     */
    public function deleteData(array $where_data, array $insert_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user.deleted_at")
        ->update($insert_data);
    }
}
