<?php

namespace App\Http\Logics;

use App\Http\Logics\RoleLogic;
use App\Http\Models\UserModel;
use App\Http\Models\MessageModel;
use App\Http\Models\MessageContentModel;
use App\Http\Models\MessageStatusModel;
use Session;
use Exception;
use Config;
use Mail;

class MessageContentLogic
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
     * 投稿
     *
     */
    public function createData($message_id, $message_text, $is_admin = false)
    {
        try {
            $user_id = Session::get("user_id");

            // スタッフ以上の権限者の投稿は管理者IDで投稿
            $role_id = $this->roleLogic->getData($user_id);
            if ($is_admin === true && (int)$role_id <= UserModel::ROLE_STAFF) {
                $user_id = UserModel::USER_ID_ADMIN;
            }

            // 登録処理
            $this->messageContentModel->createData(
                [
                    "message_content_message_id" => $message_id,
                    "message_content_user_id" => $user_id,
                    "message_content_writer_id" => $user_id,
                    "message_content_reader_id" => $user_id,
                    "message_content_text" => $message_text,
                    "created_at" => date("Y-m-d H:i:s"),
                    "updated_at" => date("Y-m-d H:i:s"),]
            );

            // 親データを更新
            $this->messageModel->updateData(
                [
                    [
                        "key" => "message.message_id",
                        "operator" => "=",
                        "value" => $message_id,],],
                [
                    "updated_at" => date("Y-m-d H:i:s"),]
            );

            // 既読情報を更新する
            $this->messageStatusModel->updateData(
                [
                    [
                        "key" => "message_status.message_status_message_id",
                        "operator" => "=",
                        "value" => $message_id,],
                    // 投稿者以外の既読状態を未読にする
                    [
                        "key" => "message_status.message_status_user_id",
                        "operator" => "!=",
                        "value" => $user_id,],],
                [
                    "message_status_read_status" => MessageStatusModel::READ_STATUS_YET,
                    "updated_at" => date("Y-m-d H:i:s"),]
            );

            // メッセージの内容をメールで送信する
            $replace_data = [
                "title" => "新着メッセージ",
                "message_id" => $message_id,
                "message_content" => $message_text,];

            $this->notificationNewMessage($replace_data, $user_id);

        } catch (Exception $e) {
            \Log::info("Error: " . $e->getMessage());
            throw $e;
        }

        return true;
    }

    /**
     * メッセージ通知メールを送信
     *
     */
    private function notificationNewMessage(array $replace_data, $user_id)
    {
        // メッセージ書き込み者以外のメッセージ情報を取得
        $message_status_data_list = $this->messageStatusModel->getDataList(
            [
                [
                    "key" => "message_status.message_status_message_id",
                    "operator" => "=",
                    "value" => $replace_data["message_id"],],
                [
                    "key" => "message_status.message_status_user_id",
                    "operator" => "!=",
                    "value" => $user_id,],]
        );

        foreach ($message_status_data_list as $message_status_data) {
            // 運営へメールを送信
            if ((int)$message_status_data["message_status_user_id"] === UserModel::USER_ID_ADMIN) {
                // 運営の宛先を取得
                $mail_address_admin = Config::get("mail_address")["mail_address_no_reply"];
                // メールの題名，本文を取得
                $mail_info = Config::get("mail_info")["message_admin"];
                $this->sendNotificationMail($mail_info, $mail_address_admin, $replace_data);

            // 運営及びメッセージ書き込み者以外にメールを送信
            } else {
                $receiver_data = $this->userModel->getData([
                    [
                        "key" => "user.user_id",
                        "operator" => "=",
                        "value" => $message_status_data["message_status_user_id"],],]
                );

                $mail_info = Config::get("mail_info")["message_receiver"];
                $this->sendNotificationMail($mail_info, $receiver_data["user_email"], $replace_data);
            }
        }

        return true;
    }

    /**
     * メールを送信
     *
     */
    private function sendNotificationMail($mail_info, $address_to, array $replace_data)
    {
        // メールの宛先を取得
        $mail_address = Config::get("mail_address");

        // メールを送信する
        $result = Mail::send($mail_info["view_file"], $replace_data, function ($message) use ($mail_info, $address_to, $mail_address) {
            $message
                ->to($address_to)
                ->from($mail_address["mail_address_no_reply"], "ibc")
                ->subject($mail_info["subject"]);
        });

        return true;
    }
}
