<?php

namespace App\Http\Logics;

use App\Http\Logics\UserLogic;
use Session;
use Hash;
use Exception;
use Config;
use Mail;

class AuthLogic
{
    private $userLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        $this->userLogic = new UserLogic();
    }

    /**
     * 会員登録
     *
     */
    public function signup($input)
    {
        // メンバー情報が存在するか確認
        $user_data = $this->userLogic->getDataWithWhere(
            [
                [
                    "key" => "user.user_email",
                    "operator" => "=",
                    "value" => $input["email"],],]
        );

        // メールアドレスの重複登録は禁止
        if (isset($user_data) && !empty($user_data)) {
            return false;
        }

        try {
            // ユーザー登録
            $this->userLogic->registData($input);
            // 登録確認
            $user_data = $this->userLogic->getDataWithWhere(
                [
                    [
                        "key" => "user.user_email",
                        "operator" => "=",
                        "value" => $input["email"],],]
            );

            if (count($user_data) < 1) {
                return false;
            }

            // ログイン
            Session::set("user_id", $user_data["user_id"]);

        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * ログイン
     *
     */
    public function login($input)
    {
        // アカウント情報の確認
        $user_data = $this->userLogic->getDataWithWhere(
             [
                 [
                     "key" => "user.user_email",
                     "operator" => "=",
                     "value" => $input["email"],],]
        );

        if (count($user_data) < 1) {
            return false;
        }

        if (Hash::check($input["password"], $user_data["user_password"]) !== true) {
            return false;
        }

        Session::set("user_id", $user_data["user_id"]);

        return true;
    }

    /**
     * Facebookで会員登録
     *
     */
    public function facebookSignup($input)
    {
        try {
            // ユーザー登録
            $this->userLogic->registFacebookData($input);
            // 登録確認
            $user_data = $this->userLogic->getDataWithWhere(
                [
                    [
                        "key" => "user.user_email",
                        "operator" => "=",
                        "value" => $input->getEmail(),],]
            );

            if (count($user_data) < 1) {
                return false;
            }

            // ログイン
            Session::set("user_id", $user_data["user_id"]);

        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * メールを送信
     *
     */
    public function sendNotificationMail($address_to)
    {
        // メールの題名，本文を取得
        $mail_info = Config::get("mail_info")["signup"];

        // メールの宛先を取得
        $mail_address = Config::get("mail_address");
        $replace_data["title"] = "ITブートキャンプへようこそ！";

        // メールを送信する
        $result = Mail::send($mail_info["view_file"], $replace_data, function ($message) use ($mail_info, $address_to, $mail_address) {
            $message
                ->to($address_to)
                ->from($mail_address["mail_address_no_reply"], "ITブートキャンプ")
                ->subject($mail_info["subject"]);
        });

        return true;
    }
}
