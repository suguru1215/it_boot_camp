<?php

namespace App\Http\Logics;

use App\Http\Models\MessageStatusModel;
use Session;
use Exception;

class MessageStatusLogic
{
    private $messageStatusModel;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $this->messageStatusModel = new MessageStatusModel;
    }

    /**
     * 投稿
     *
     */
    public function updateReadStatus($message_status_id, $status)
    {
        // 既読情報を更新する
        $result = $this->messageStatusModel->updateData(
            [
                [
                    "key" => "message_status.message_status_id",
                    "operator" => "=",
                    "value" => $message_status_id,],],
            [
                "message_status_read_status" => $status,
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        if (count($result) < 1) {
            throw new Exception("既読情報の更新に失敗しました。");
        }

        return true;
    }
}
