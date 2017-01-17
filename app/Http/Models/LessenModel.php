<?php

namespace App\Http\Models;

use Eloquent;
use DB;

class LessenModel extends Eloquent
{
    protected $table = "lessen";
    protected $primaryKey = "lessen_id";
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
     * レッスン情報を登録する
     *
     */
    public function createData(array $insert_data)
    {
        return $this
            ->insertGetId($insert_data);
    }

    /**
     * レッスンを取得する
     *
     */
    public function getData(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("lessen.deleted_at")
        ->join("caliculam", "caliculam.caliculam_id", "=", "lessen.lessen_caliculam_id")
        ->whereNull("caliculam.deleted_at")
        ->orderBy("lessen.lessen_id", "desc")
        ->first(
            [
                "lessen.lessen_id",
                "lessen.lessen_caliculam_id",
                "lessen.lessen_title",
                "lessen.lessen_pr_text",
                "lessen.lessen_text",
                "lessen.lessen_image",
                "lessen.created_at",
                "lessen.updated_at",
                "caliculam.caliculam_title",]
        );
    }

    /**
     * レッスンリストを取得する
     *
     */
    public function getDataList(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("lessen.deleted_at")
        ->join("caliculam", "caliculam.caliculam_id", "=", "lessen.lessen_caliculam_id")
        ->whereNull("caliculam.deleted_at")
        ->orderBy("lessen.lessen_id", "asc")
        ->get(
            [
                "lessen.lessen_id",
                "lessen.lessen_caliculam_id",
                "lessen.lessen_title",
                "lessen.lessen_pr_text",
                "lessen.lessen_text",
                "lessen.lessen_image",
                "lessen.created_at",
                "lessen.updated_at",
                "caliculam.caliculam_title",]
        );
    }

    /**
     * レッスン情報を更新する
     *
     */
    public function updateData(array $where_data, array $update_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("lessen.deleted_at")
        ->update($update_data);
    }

    /**
     * レッスンを論理削除する
     *
     */
    public function deleteData(array $where_data, array $insert_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("lessen.deleted_at")
        ->update($insert_data);
    }
}
