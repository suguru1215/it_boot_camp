<?php

namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Http\Logics\UserLogic;
use App\Http\Logics\AuthLogic;
use Session;
use Redirect;
use Request;
use Hash;
use Socialite;

class AuthController extends Controller
{
    private $userLogic;
    private $authLogic;

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->userLogic = new UserLogic();
        $this->authLogic = new AuthLogic();
    }

    /**
     * ログイン画面
     *
     */
    public function login()
    {
        // ログイン済みの場合はトップページにリダイレクト
        if (Session::has("user_id")) {
            return Redirect::to("/");
        }

        return $this->render(
            "auth/login",
            []
        );
    }

    /**
     * メールアドレスでログイン確認
     *
     */
    public function loginConfirm()
    {
        // validation
        if ((Request::has("email") !== true) || (Request::has("password") !== true)) {
            return back()->with(["AuthController_login_error_message" => "メールアドレスとパスワードを入力してください。",]);
        }

        // ログイン処理
        if ($this->authLogic->login(Request::all()) !== true) {
            return back()->with(["AuthController_login_error_message" => "ログインに失敗しました。",]);
        }

        // ログイン時間を記録
        $this->userLogic->insertLoginTime(Session::get("user_id"));

        // ログインが必要なページにログインせずにアクセスした場合
        if (Session::has("route_request_uri") ===  true) {
            $previous_url = Session::pull("route_request_uri");
        }

        return Redirect::to("/mypage/");
    }

    /**
     * 会員登録画面
     *
     */
    public function signup()
    {
        // ログイン済みの場合はトップページにリダイレクト
        if (Session::has("user_id")) {
            return Redirect::to("/");
        }

        return $this->render(
            "auth/signup",
            []
        );
    }

    /**
     * メールアドレスでアカウント作成
     *
     */
    public function signupConfirm()
    {
        // validation
        if ((Request::has("email") !== true) || (Request::has("password") !== true) || Request::has("name") !== true) {
            return back()->with(["AuthController_signup_error_message" => "メールアドレス、パスワードと氏名を入力してください。",]);
        }

        // アカウント作成
        if ($this->authLogic->signup(Request::all()) !== true) {
            return back()->with(["AuthController_signup_error_message" => "アカウントの作成に失敗しました。",]);
        }

        // アカウント作成完了メール送信
        $this->authLogic->sendNotificationMail(Request::get("email"));

        return Redirect::to("/mypage/");
    }

    /**
     * Twitterでアカウント作成/ログイン
     *
     */
    public function twitterLogin()
    {
        return Socialite::driver("twitter")->redirect();
    }

    /**
     * Twitterログインのコールバック
     *
     */
    public function twitterLoginCallback()
    {
        // ユーザー情報を取得
        $user = Socialite::driver("twitter")->user();

        // 既存Twitterユーザーか確認
        $user_data = $this->userLogic->getDataWithWhere(
            [
                [
                    "key" => "user.user_twitter_id",
                    "operator" => "=",
                    "value" => $user->getId(),],]
        );

        // 登録済みの場合
        if (isset($user_data) && !empty($user_data)) {
            // ログイン処理
            Session::set("user_id", $user_data["user_id"]);

            $previous_url = "/";
            // ログインが必要なページにログインせずにアクセスした場合
            if (Session::has("route_request_uri") ===  true) {
                $previous_url = Session::pull("route_request_uri");
           }

            return Redirect::to($previous_url);
        }

        // メールアドレスが登録済みか確認
        $user_data = $this->userLogic->getDataWithWhere(
            [
                [
                    "key" => "user.user_email",
                    "operator" => "=",
                    "value" => $user->getEmail(),],]
        );

        // メールアドレスが登録済みの場合
        if (isset($user_data) && !empty($user_data)) {
            // Twitterの情報を登録する
            if ($this->userLogic->updateTwitterData($user_data, $user) !== true) {
                return Redirect::to($previous_url)->with(["AuthController_login_error_message" => "ログインに失敗しました。",]);
            }

            // ログイン処理
            Session::set("user_id", $user_data["user_id"]);

            $previous_url = "/";
            // ログインが必要なページにログインせずにアクセスした場合
            if (Session::has("route_request_uri") ===  true) {
                $previous_url = Session::pull("route_request_uri");
            }

            return Redirect::to($previous_url);
        }

        // アカウント作成
        if ($this->authLogic->twitterSignup($user) !== true) {
            return Redirect::to("/")->with(["AuthController_login_error_message" => "アカウントの作成に失敗しました。",]);
        }

        // アカウント作成完了メール送信
        $this->authLogic->sendNotificationMail(Request::get("email"));

        return Redirect::to("/");
    }

    /**
     * ログアウト
     *
     */
    public function logout()
    {
        Session::forget("user_id");
        Session::forget("UserLogic_getDataSimple_user_data");

        return Redirect::to("/");
    }
}
