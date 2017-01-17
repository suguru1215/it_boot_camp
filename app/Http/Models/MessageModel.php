<?php

namespace App\Http\Models;

use Eloquent;
use DB;
use Session;

class MessageModel extends Eloquent
{
    protected $table = "message";
    protected $primaryKey = "message_id";
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
        return $this
            ->insertGetId($insert_data);
    }

    /**
     * メッセージ情報を更新
     *
     */
    public function updateData(array $where_data, array $input)
    {
        return $this
            ->where(function ($query) use ($where_data) {
                foreach ($where_data as $where) {
                    $query->where($where["key"], $where["operator"], $where["value"]);
                }
            })
            ->whereNull("message.deleted_at")
            ->update($input);
    }

    /**
     * メッセージ情報を取得 (各メッセージの詳細)
     *
     */
    public function getData(array $where_data)
    {
        return $this
            ->where(function ($query) use ($where_data) {
                foreach ($where_data as $where) {
                    $query->where($where["key"], $where["operator"], $where["value"]);
                }
            })
            ->join("lessen", "lessen.lessen_id", "=", "message.message_lessen_id")
            ->whereNull("message.deleted_at")
            ->join("payment", "payment.payment_id", "=", "message.message_payment_id")
            ->orderBy("message.message_id", "desc")
            ->first([
                "message.message_id",
                "message.message_lessen_id",
                "message.created_at",
                "message.updated_at",
                "lessen.lessen_id",
                "lessen.lessen_title",]
            );
    }

    /**
     * メッセージ情報をリストで取得 (マイページで見る各ユーザのメッセージトップ一覧)
     *
     */
    public function getDataList(array $where_data)
    {
        $message_id_list = $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("message.deleted_at")
        ->join("message_content", "message_content.message_content_message_id", "=", "message_id")
        ->whereNull("message_content.deleted_at")
        ->distinct("message.message_id")
        ->orderBy("message.message_id", "desc")
        ->pluck("message.message_id");

        if (count($message_id_list) < 1) {
            return [];
        }

        $user_id = Session::get("user_id");

        foreach ($message_id_list as $message_id) {
            $message_list[$message_id] = $this->where("message.message_id", "=", $message_id)
                ->whereNull("message.deleted_at")
                ->join("message_content", "message_content.message_content_message_id", "=", "message.message_id")
                ->whereNull("message_content.deleted_at")
                ->join("message_status", "message_status.message_status_message_id", "=", "message.message_id")
                ->where("message_status.message_status_user_id", "=", $user_id)
                ->whereNull("message_status.deleted_at")
                ->join("user", "user.user_id", "=", "message_content.message_content_user_id")
                ->whereNull("user.deleted_at")
                ->orderBy("message.message_id", "desc")
                ->orderBy("message_content.message_content_id", "desc")
                ->get();
        }

        return $message_list;
    }

    /**
     * 全てのメッセージ情報をリストで取得 (管理画面で見る全てのメッセージ一覧)
     *
     */
    public function getIdList($paginate = false, $page = null)
    {
        $result = $this->whereNull("message.deleted_at")
            ->orderBy("message.message_id", "desc");

        // ページャ
        if (isset($paginate) && !empty($paginate)) {
             return $result->paginate($paginate, ["message.message_id",], "pagename", $page);
         } else {
             return $result->pluck("message.message_id");
         }
     }
}
