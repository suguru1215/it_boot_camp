<?php

namespace App\Http\Logics;

use App\Http\Models\LessenModel;
use Exception;
use Redirect;

class LessenLogic
{
    private $lessenModel;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $this->lessenModel = new LessenModel;
    }

    /**
     * レッスン情報を登録
     *
     */
    public function upsertData(array $input)
    {
        if (isset($input["lessen_id"]) && !empty($input["lessen_id"])) {
            $where_data = [
                [
                    "key" => "lessen.lessen_id",
                    "operator" => "=",
                    "value" => $input["lessen_id"],],];

            $update_data = [
                "lessen_caliculam_id" => $input["lessen_caliculam_id"],
                "lessen_title" => $input["lessen_title"],
                "lessen_pr_text" => $input["lessen_pr_text"],
                "lessen_text" => $input["lessen_text"],
                "updated_at" => date("Y-m-d H:i:s"),];

            $this->updateData($input["lessen_id"], $where_data, $update_data);

            return $input["lessen_id"];

        } else {
            return $this->insertData($input);
        }
    }

    /**
     * レッスン情報を取得
     *
     */
    public function getData($lessen_id)
    {
        $result = $this->lessenModel->getData(
            [
                [
                    "key" => "lessen.lessen_id",
                    "operator" => "=",
                    "value" => $lessen_id,]]
        );

        if (count($result) < 1) {
            throw new Exception("lessen_id: " . $lessen_id . "のレッスン情報の取得に失敗しました。");
        }

        return $result->toArray();
    }

    /**
     * レッスン情報を取得
     *
     */
    public function getDataWithWhere(array $where_data)
    {
        $result = $this->lessenModel->getData($where_data);

        // 0件の場合がある
        if (count($result) < 1) {
            return [];
        }

        return $result->toArray();
    }

    /**
     * レッスン一覧情報を取得
     *
     */
    public function getDataList()
    {
        $result = $this->lessenModel->getDataList([]);

        // 0件の場合が有る
        if (count($result) < 1) {
            return [];
        }

        return  $result->toArray();
    }

    /**
     * レッスン一覧情報をカリキュラムIDで取得
     *
     */
    public function getDataListByCaliculamId($caliculam_id)
    {
        $result = $this->lessenModel->getDataList(
            [
                [
                    "key" => "lessen.lessen_caliculam_id",
                    "operator" => "=",
                    "value" => $caliculam_id,],]
        );

        // 0件の場合が有る
        if (count($result) < 1) {
            return [];
        }

        return  $result->toArray();
    }

    /**
     * レッスン画像を登録
     *
     */
    public function updateLessenImage($lessen_id, $file_name)
    {
        $where_data = [
            [
                "key" => "lessen.lessen_id",
                "operator" => "=",
                "value" => $lessen_id,],];

        $update_data = [
            "lessen_image" => $file_name,
            "updated_at" => date("Y-m-d H:i:s"),];

        return $this->updateData($lessen_id, $where_data, $update_data);
    }

    /**
     * レッスン情報を新規登録
     *
     */
    private function insertData(array $input)
    {
        $lessen_id = $this->lessenModel->createData(
            [
                "lessen_caliculam_id" => $input["lessen_caliculam_id"],
                "lessen_title" => $input["lessen_title"],
                "lessen_pr_text" => $input["lessen_pr_text"],
                "lessen_text" => $input["lessen_text"],
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($lessen_id) < 1) {
            throw new Exception("レッスン情報の登録に失敗しました。");
        }

        return $lessen_id;
    }

    /**
     * レッスン情報の更新
     *
     */
    private function updateData($lessen_id, array $where_data, array $update_data)
    {
        $result = $this->lessenModel->updateData($where_data, $update_data);

        if (count($result) < 1) {
            throw new Exception("lessen_id: " . $lessen_id . "のレッスン情報更新に失敗しました。");
        }

        return true;
    }
}
