<?php

namespace App\Http\Models;

use Eloquent;
use DB;

class MessageStatusModel extends Eloquent
{
    // 未読
    const READ_STATUS_YET = 0;
    // 既読
    const READ_STATUS_DONE = 1;

    protected $table = "message_status";
    protected $primaryKey = "message_status_id";
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
     * メッセージ既読状態を作成
     *
     */
    public function insertData(array $insert_data)
    {
        return $this->insertGetId($insert_data);
    }

    /**
     * メッセージ既読状態を更新
     *
     */
    public function updateData(array $where_data, array $input)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("message_status.deleted_at")
        ->update($input);
    }

    /**
     * メッセージ既読状態を取得
     *
     */
    public function getData(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("message_status.deleted_at")
        ->first(
            [
                "message_status.message_status_id",
                "message_status.message_status_message_id",
                "message_status.message_status_user_id",
                "message_status.message_status_read_status",
                "message_status.created_at",
                "message_status.updated_at",]
        );
    }

    /**
     * メッセージ既読状態をリストで取得
     *
     */
    public function getDataList(array $where_data)
    {
        return $result = $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("message_status.deleted_at")
        ->orderBy("message_status.message_status_id", "desc")
        ->get(
            [
                "message_status.message_status_id",
                "message_status.message_status_message_id",
                "message_status.message_status_user_id",
                "message_status.message_status_read_status",
                "message_status.created_at",
                "message_status.updated_at",]
        );
    }
}
