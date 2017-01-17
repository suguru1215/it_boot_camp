<?php

namespace App\Http\Models;

use Eloquent;
use DB;

class CaliculamModel extends Eloquent
{
    protected $table = "caliculam";
    protected $primaryKey = "caliculam_id";
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
     * カリキュラム情報を登録する
     *
     */
    public function createData(array $insert_data)
    {
        return $this
            ->insertGetId($insert_data);
    }

    /**
     * カリキュラムを取得する
     *
     */
    public function getData(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("caliculam.deleted_at")
        ->first(
            [
                "caliculam.caliculam_id",
                "caliculam.caliculam_price",
                "caliculam.caliculam_title",
                "caliculam.caliculam_pr_text",
                "caliculam.caliculam_text",
                "caliculam.caliculam_image",
                "caliculam.created_at",
                "caliculam.updated_at",]
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
        ->whereNull("caliculam.deleted_at")
        ->count("caliculam.caliculam_id");
    }

    /**
     * カリキュラムリストを取得する
     *
     */
    public function getDataList(array $where_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("caliculam.deleted_at")
        ->orderBy("caliculam.caliculam_id", "desc")
        ->get(
            [
                "caliculam.caliculam_id",
                "caliculam.caliculam_price",
                "caliculam.caliculam_title",
                "caliculam.caliculam_pr_text",
                "caliculam.caliculam_text",
                "caliculam.caliculam_image",
                "caliculam.created_at",
                "caliculam.updated_at",]
        );
    }

    /**
     * カリキュラム情報を更新する
     *
     */
    public function updateData(array $where_data, array $update_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("caliculam.deleted_at")
        ->update($update_data);
    }

    /**
     * カリキュラムを論理削除する
     *
     */
    public function deleteData(array $where_data, array $insert_data)
    {
        return $this->where(function ($query) use ($where_data) {
            foreach ($where_data as $where) {
                $query->where($where["key"], $where["operator"], $where["value"]);
            }
        })
        ->whereNull("caliculam.deleted_at")
        ->update($insert_data);
    }
}
