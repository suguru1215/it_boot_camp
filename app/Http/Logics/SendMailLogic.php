<?php

namespace App\Http\Logics;

use Config;
use Mail;

class SendMailLogic
{
    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
    }

    /**
     * メールを送信
     *
     */
    public function sendNotificationMail(array $mail_info, $address_to, array $replace_data)
    {
        // fromのメールアドレスを取得
        $mail_address = Config::get("mail_address");

        // メールを送信する
        $result = Mail::send($mail_info["view_file"], $replace_data, function ($message) use ($mail_info, $address_to, $mail_address, $replace_data) {
            $message
                ->to($address_to)
                ->from($mail_address["mail_address_no_reply"], "ITブートキャンプ")
                ->subject($replace_data["title"]);
        });

        return true;
    }
}
