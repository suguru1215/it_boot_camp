<?php

namespace App\Http\Models;

use Eloquent;
use DB;

class UserGroupModel extends Eloquent
{
    protected $table = "user_group";
    protected $primaryKey = "user_group_id";
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
     * ユーザグループ情報を登録する
     *
     */
    public function insertData(array $insert_data)
    {
        return $this
            ->insertGetId($insert_data);
    }

    /**
     * ユーザグループ情報を更新する
     *
     */
    public function updateData(array $where_data, array $update_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user_group.deleted_at")
        ->update($update_data);
    }

    /**
     * ユーザグループを取得する
     *
     */
    public function getData(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user_group.deleted_at")
        ->first(
            [
                "user_group.user_group_id",
                "user_group.user_group_name",
                "user_group.created_at",
                "user_group.updated_at",]
        );
    }

    /**
     * ユーザグループリストを取得する
     *
     */
    public function getDataList(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user_group.deleted_at")
        ->orderBy("user_group.user_group_id", "desc")
        ->get(
            [
                "user_group.user_group_id",
                "user_group.user_group_name",
                "user_group.created_at",
                "user_group.updated_at",]
        );
    }

    /**
     * ユーザグループを論理削除する
     *
     */
    public function deleteData(array $where_data, array $update_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("user_group.deleted_at")
        ->update($update_data);
    }
}
