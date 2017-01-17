<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Logics\UserLogic;
use App\Http\Logics\UserGroupLogic;
use App\Http\Logics\MessageLogic;
use App\Http\Models\UserModel;
use App\Http\Logics\SendMailLogic;
use Request;
use Validator;
use Redirect;
use Config;
use Log;
use Exception;
use Session;

class SendMessageController extends Controller
{
    private $userLogic;
    private $userGroupLogic;
    private $messageLogic;
    private $sendMailLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userLogic = new UserLogic;
        $this->userGroupLogic = new UserGroupLogic;
        $this->messageLogic = new MessageLogic;
        $this->sendMailLogic = new SendMailLogic;
    }

    /**
     * トップ画面
     *
     */
    public function index()
    {
        return $this->render(
            "admin/send_message/index",
            []
        );
    }

    /**
     * 学生向け送信入力画面
     *
     */
    public function toStudent()
    {
        $error_message = "";
        if (Session::has("toStudentConfirm_error_message") === true) {
            $error_message = Session::pull("toStudentConfirm_error_message");
        }

        return $this->render(
            "admin/send_message/to_student",
            [
                "error_message" => $error_message,
                "student_data_list" => $this->userLogic->getDataList(),
                "user_group_data_list" => $this->userGroupLogic->getDataList(),]
        );
    }

    /**
     * 学生向け送信確認画面
     *
     */
    public function toStudentConfirm()
    {
        $input = Request::all();

        // 入力情報の確認
        $validator = Validator::make(
            $input,
            [
                "student_id" => ["required",],
                "message_content" => ["required",],
                "is_send_mail" => ["required",],]
        );

        if ($validator->fails() === true) {
            return Redirect::to("/admin/send_message/to_student")->with("toStudentConfirm_error_message", "全ての項目を入力してください。");
        }

        // 対象学生の宛先リストを取得
        // 送信対象者が全員の場合
        if ($input["student_id"] === "all") {
            $student_data_list = $this->userLogic->getDataList();
        // 送信対象者が指定されている場合
        } else {
            $student_data_list[] = $this->userLogic->getData($input["student_id"]);
        }

        // 投稿処理
        foreach ($student_data_list as $student_data) {
            $this->messageLogic->insertData($input, UserModel::USER_ID_ADMIN, $student_data["user_id"]);

            // メール送信処理
            if ($input["is_send_mail"] === "true") {
                $mail_info = Config::get("mail_info")["message"];
                $replace_data = [
                    "title" => "【ITブートキャンプ】 メッセージが届いています。",
                    "input" => $input,];
                $this->sendMailLogic->sendNotificationMail($mail_info, $student_data["user_email"], $replace_data);
            }
        }

        return Redirect::to("/admin/send_message/to_student_complete");
    }

    /**
     * 学生向け送信確認画面
     *
     */
    public function toStudentComplete()
    {
        // 直叩き禁止
        if (!isset($_SERVER["HTTP_REFERER"]) || empty($_SERVER["HTTP_REFERER"])) {
            return Redirect::to("/admin/send_message/to_student");
        }

        return $this->render(
            "admin/send_message/to_student_complete",
            []
        );
    }
}
