<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Mypage\BaseController;
use App\Http\Logics\UserLogic;
use Request;
use Hash;
use Exception;
use Mail;
use Session;
use Validator;
use Config;
use Redirect;

class PasswordController extends BaseController
{
    private $userLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userLogic = new UserLogic;
    }

    /**
     * パスワード入力画面
     *
     */
    public function input()
    {
        if (Session::has("MypagePasswordController_confirm_notification_header") === true) {
            $is_notification_header = true;
            Session::forget("MypagePasswordController_confirm_notification_header");
        } else {
            $is_notification_header = false;
        }

        return $this->render(
            "mypage/password",
            [
                "notification_header" => $is_notification_header,]
        );
    }

    /**
     * パスワードの登録
     *
     */
    public function confirm()
    {
        $input = Request::all();

        // ヴァリデーション
        // 入力情報の確認
        $validator = Validator::make(
            $input,
            [
                "current_password" => ["required",],
                "new_password" => ["required",],]
        );

        if ($validator->fails() === true) {
            return Redirect::back()->withInput();
        }

        // ユーザー情報の取得
        $user_data = $this->userLogic->getData(Session::get("user_id"));

        // 現在のパスワードの確認
        if (Hash::check($input["current_password"], $user_data["user_password"]) !== true) {
            return Redirect::back()->withInput();
        }

        // ユーザー情報の更新
        $this->userLogic->updateData(
            // where_data
            [
                [
                    "key" => "user.user_id",
                    "operator" => "=",
                    "value" => Session::get("user_id"),],],
            [
                "user_password" => Hash::make($input["new_password"]),
                "updated_at" => date("Y-m-d H:i:s"),]
        );

        // 仮パスワードを通知
        $this->notifiyPasswordChange($user_data["user_email"]);

        return Redirect::to("/mypage/password/input")->with(
            [
                // 通知
                "MypagePasswordController_confirm_notification_header" => true,]);
    }

    /**
     * メールの送信
     *
     */
    private function notifiyPasswordChange($email)
    {
        // メールの題名を取得
        $mail_info = Config::get("mail_info")["change_password"];

        // メールの宛先を取得
        $mail_address = Config::get("mail_address");
        $replace_data = [
            "title" => "パスワード変更",];

        // メールを送信する
        Mail::send($mail_info["view_file"], $replace_data, function ($message) use ($email, $mail_info, $mail_address) {
            $message
                ->to($email)
                ->from($mail_address["mail_address_no_reply"], "ITブートキャンプ")
                ->subject($mail_info["subject"]);
        });

        return true;
    }
}
