<?php

namespace App\Http\Models;

use Eloquent;
use DB;

class MessageContentModel extends Eloquent
{
    protected $table = "message_content";
    protected $primaryKey = "message_content_id";
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
     * メッセージ情報を作成
     *
     */
    public function insertData(array $insert_data)
    {
        return $this->insertGetId($insert_data);
    }

    /**
     * メッセージ情報を取得
     *
     */
    public function getData(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("message_content.deleted_at")
        ->first(
            [
                "message_content.message_content_id",
                "message_content.message_content_message_id",
                "message_content.message_content_user_id",
                "message_content.message_content_text",
                "message_content.created_at",
                "message_content.updated_at",]
        );
    }

    /**
     * メッセージ情報をリストで取得
     *
     */
    public function getDataList(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("message_content.deleted_at")
        ->leftJoin("user", "user.user_id", "=", "message_content.message_content_user_id")
        ->orderBy("message_content.message_content_id", "desc")
        ->get(
            [
                "message_content.message_content_id",
                "message_content.message_content_message_id",
                "message_content.message_content_user_id",
                "message_content.message_content_text",
                "message_content.created_at",
                "user.user_id",
                "user.user_name",
                "user.user_email",
                "user.user_image",
                "user.user_role",
                "user.user_pr_text",]
        );
    }
}
