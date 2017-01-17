<?php

namespace App\Http\Logics;

use App\Http\Models\CaliculamModel;
use Exception;
use Redirect;

class CaliculamLogic
{
    private $caliculamModel;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $this->caliculamModel = new CaliculamModel;
    }

    /**
     * カリキュラム情報を登録
     *
     */
    public function upsertData(array $input)
    {
        if (isset($input["caliculam_id"]) && !empty($input["caliculam_id"])) {
            $where_data = [
                [
                    "key" => "caliculam.caliculam_id",
                    "operator" => "=",
                    "value" => $input["caliculam_id"],],];

            $update_data = [
                "caliculam_title" => $input["caliculam_title"],
                "caliculam_pr_text" => $input["caliculam_pr_text"],
                "caliculam_text" => $input["caliculam_text"],
                "caliculam_price" => $input["caliculam_price"],
                "updated_at" => date("Y-m-d H:i:s"),];

            $this->updateData($input["caliculam_id"], $where_data, $update_data);

            return $input["caliculam_id"];

        } else {
            return $this->insertData($input);
        }
    }

    /**
     * カリキュラム情報を取得
     *
     */
    public function getData($caliculam_id)
    {
        $result = $this->caliculamModel->getData(
            [
                [
                    "key" => "caliculam.caliculam_id",
                    "operator" => "=",
                    "value" => $caliculam_id,]]
        );

        if (count($result) < 1) {
            throw new Exception("caliculam_id: " . $caliculam_id . "のカリキュラム情報の取得に失敗しました。");
        }

        return $result->toArray();
    }

    /**
     * カリキュラム情報を取得
     *
     */
    public function getDataWithWhere(array $where_data)
    {
        $result = $this->caliculamModel->getData($where_data);

        // 0件の場合がある
        if (count($result) < 1) {
            return [];
        }

        return $result->toArray();
    }

    /**
     * カリキュラム一覧情報を取得
     *
     */
    public function getDataList()
    {
        $result = $this->caliculamModel->getDataList([]);

        // 0件の場合が有る
        if (count($result) < 1) {
            return [];
        }

        return  $result->toArray();
    }

    /**
     * カリキュラム画像を登録
     *
     */
    public function updateCaliculamImage($caliculam_id, $file_name)
    {
        $where_data = [
            [
                "key" => "caliculam.caliculam_id",
                "operator" => "=",
                "value" => $caliculam_id,],];

        $update_data = [
            "caliculam_image" => $file_name,
            "updated_at" => date("Y-m-d H:i:s"),];

        return $this->updateData($caliculam_id, $where_data, $update_data);
    }

    /**
     * カリキュラム情報を新規登録
     *
     */
    private function insertData(array $input)
    {
        $caliculam_id = $this->caliculamModel->createData(
            [
                "caliculam_title" => $input["caliculam_title"],
                "caliculam_price" => $input["caliculam_price"],
                "caliculam_pr_text" => $input["caliculam_pr_text"],
                "caliculam_text" => $input["caliculam_text"],
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($caliculam_id) < 1) {
            throw new Exception("カリキュラム情報の登録に失敗しました。");
        }

        return $caliculam_id;
    }

    /**
     * カリキュラム情報の更新
     *
     */
    private function updateData($caliculam_id, array $where_data, array $update_data)
    {
        $result = $this->caliculamModel->updateData($where_data, $update_data);

        if (count($result) < 1) {
            throw new Exception("caliculam_id: " . $caliculam_id . "のカリキュラム情報更新に失敗しました。");
        }

        return true;
    }
}
