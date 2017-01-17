<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Mypage\BaseController;
use App\Http\Logics\UserLogic;
use App\Http\Logics\MessageLogic;
use App\Http\Logics\MessageStatusLogic;
use App\Http\Logics\MessageContentLogic;
use App\Http\Models\MessageStatusModel;
use Session;
use Redirect;
use Request;

class MessageController extends BaseController
{
    private $userLogic;
    private $messageLogic;
    private $messageStatusLogic;
    private $messageContentLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userLogic = new UserLogic;
        $this->messageLogic = new MessageLogic;
        $this->messageStatusLogic = new MessageStatusLogic;
        $this->messageContentLogic = new MessageContentLogic;
    }

    /**
     * トップージ
     *
     */
    public function index()
    {
        return $this->render(
            "mypage/message/index",
            [
                "message_data_list" => $this->messageLogic->getDataList(Session::get("user_id")),]
        );
    }

    /**
     * 詳細ページ
     *
     */
    public function detail($message_id)
    {
        $message_data = $this->messageLogic->getData($message_id);

        // 既読情報を更新する
        if ((int)$message_data["message_status"]["message_status_read_status"] === MessageStatusModel::READ_STATUS_YET) {
            $this->messageStatusLogic->updateReadStatus($message_data["message_status"]["message_status_id"], MessageStatusModel::READ_STATUS_DONE);
        }

        return $this->render(
            "mypage/message/detail",
            [
                "message_data" => $message_data,]
        );
    }

    /**
     * 投稿処理
     *
     */
    public function confirm($message_id)
    {
        $input = Request::all();

        // validation
        if (!isset($input["text"]) || empty($input["text"])) {
            return Redirect::to("/mypage/message/" . $message_id)->with(["error_message" => "メッセージの本文を入力してください。",]);
        }

        // 投稿処理
        $this->messageContentLogic->createData($message_id, $input["text"]);

        return Redirect::to("/mypage/message/" . $message_id);
    }
}
