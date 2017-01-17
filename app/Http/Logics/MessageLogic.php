<?php

namespace App\Http\Logics;

use App\Http\Logics\RoleLogic;
use App\Http\Models\MessageModel;
use App\Http\Models\MessageContentModel;
use App\Http\Models\MessageStatusModel;
use App\Http\Models\UserModel;
use Session;
use Exception;
use Config;

class MessageLogic
{
    private $roleLogic;
    private $messageModel;
    private $messageContentModel;
    private $messageStatusModel;
    private $userModel;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $this->roleLogic = new RoleLogic;
        $this->messageModel = new MessageModel;
        $this->messageContentModel = new MessageContentModel;
        $this->messageStatusModel = new MessageStatusModel;
        $this->userModel = new UserModel;
    }

    /**
     * メッセージ情報を登録
     *
     */
    public function insertData(array $input, $user_id_from, $user_id_to)
    {
        // 親データを登録
        $message_id = $this->messageModel->insertData(
            [
                "message.created_at" => date("Y-m-d H:i:s"),
                "message.updated_at" => date("Y-m-d H:i:s"),]
        );

        // メッセージ情報を登録
        $this->messageContentModel->insertData(
            [
                "message_content_message_id" => $message_id,
                "message_content_user_id" => $user_id_from,
                "message_content_text" => $input["message_content"],
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        // メッセージの既読状態(from)を登録
        $this->messageStatusModel->insertData(
            [
                "message_status_message_id" => $message_id,
                "message_status_user_id" => $user_id_from,
                "message_status_read_status" => MessageStatusModel::READ_STATUS_YET,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        // メッセージの既読状態(to)を登録
        $this->messageStatusModel->insertData(
            [
                "message_status_message_id" => $message_id,
                "message_status_user_id" => $user_id_to,
                "message_status_read_status" => MessageStatusModel::READ_STATUS_YET,
                "created_at" => date("Y-m-d H:i:s"),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        return true;
    }

    /**
     * メッセージ情報を取得
     *
     */
    public function getData($message_id, $is_admin = false)
    {
        $result = $this->messageModel->getData(
            [
                [
                    "key" => "message.message_id",
                    "operator" => "=",
                    "value" => $message_id,]]
        );

        if (count($result) < 1) {
            throw new Exception("message_id: " . $message_id . "がメッセージ情報の取得に失敗しました。");
        }

        $result = $result->toArray();

        // メンターデータの取得
        $student_data = $this->userModel->getData(
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $result["payment_student_id"],],]
        );

        if (count($student_data) < 1) {
            throw new Exception("message_id: " . $message_id . "がメンター情報の取得に失敗しました。");
        }

        $result["student_data"] = $student_data->toArray();

        // 学生情報の取得
        $mentor_data = $this->userModel->getData(
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => $result["payment_user_id"],],]
        );

        if (count($mentor_data) < 1) {
            throw new Exception("message_id: " . $message_id . "が学生情報の取得に失敗しました。");
        }

        $result["mentor_data"] = $mentor_data->toArray();

        $user_id = Session::get("user_id");

        if ($is_admin === true) {
            // ユーザーの権限を確認
            $user_role = $this->roleLogic->getData($user_id);

            // 管理者の時はid = 0にする必要がある
            if ($user_role <= UserModel::ROLE_STAFF) {
                $user_id = UserModel::USER_ID_ADMIN;
            }
        }

        // 既読状態の取得 (空配列は例外処理)
        $result["message_status"] = $this->getMessageStatusData($result, $user_id);
        if (empty($result["message_status"])) {
            // ゴミデータがある
            \Log::info("message_id: " . $result["message_id"] . "に既読状態のないスレッドが有ります。");
        }

        // メッセージ情報の取得 (空配列は例外処理)
        $result["message_sub_list"] = $this->getMessageContentData($result);
        if (count($result["message_sub_list"]) < 1) {
            // ゴミデータがある
            \Log::info("message_id: " . $result["message_id"] . "にメッセージのないスレッドが有ります。");
        }

        return $result;
    }

    /**
     * メッセージ一覧情報を取得
     *
     */
    public function getDataList($user_id)
    {
        // 0件の場合がある
        $message_list = $this->messageModel->getDataList(
            [
                [
                    "key" => "message_content.message_content_writer_id",
                    "operator" => "=",
                    "value" => $user_id,],
                [
                    "key" => "message_content.message_reader_writer_id",
                    "operator" => "=",
                    "value" => $user_id,
                    "is_or" => true,],]
        );
dd($message_main_list);
        if (count($message_main_list) < 1) {
            return [];
        }

        $message_main_list = $message_main_list->toArray();

        foreach ($message_main_list as $key => $message_main) {
            // メッセージのステータスを取得
            $message_main_list[$key]["message_status"] = $this->getMessageStatusData($message_main, $user_id);
            if (empty($message_main_list[$key]["message_status"])) {
                // ゴミデータがある
                \Log::info("message_id: " . $message_main["message_id"] . "にメッセージのないスレッドが有ります。");
            }

            // メッセージの内容を取得
            $message_main_list[$key]["message_sub_list"] = $this->getMessageContentData($message_main);
            if (count($message_main_list[$key]["message_sub_list"]) < 1) {
                // ゴミデータがある
                \Log::info("message_id: " . $message_main["message_id"] . "にメッセージのないスレッドが有ります。");
            }
        }

        return $message_main_list;
    }

    /**
     * 全てのメッセージ一覧情報を取得
     *
     */
    public function getDataListAll($paginate = false, $page = 1)
    {
        $result = $this->messageModel->getIdList($paginate, $page);

        if (count($result) < 1) {
            throw new Exception("メッセージの取得に失敗しました．");
        }

        $message_data_list = $result->toArray();

        foreach ($message_data_list["data"] as $message) {
            $message_data_list["thread"][$message["message_id"]] = $this->getData($message["message_id"], true);
        }

        unset($message_data_list["data"]);

        return $message_data_list;
    }

    /**
     * メッセージのステータスを取得
     *
     */
    private function getMessageStatusData($message_main, $user_id)
    {
        $result = $this->messageStatusModel->getData(
           [
               [
                   "key" => "message_status.message_status_message_id",
                   "operator" => "=",
                   "value" => $message_main["message_id"],],
               [
                   "key" => "message_status.message_status_user_id",
                   "operator" => "=",
                   "value" => $user_id,],]
        );

        if (count($result) < 1) {
            return [];
        }

        return $result->toArray();
    }

    /**
     * メッセージの内容を取得
     *
     */
    private function getMessageContentData($message_main)
    {
        $result = $this->messageContentModel->getDataList(
            [
                [
                    "key" => "message_content.message_content_message_id",
                    "operator" => "=",
                    "value" => $message_main["message_id"],],]
        );

        if (count($result) < 1) {
            return [];
        }

        return $result->toArray();
    }
}
