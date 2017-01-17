<?php

Route::group(["middleware" => ["web",]], function () {
    /**
     * トップページ
     *
     */
    Route::get("/", "IndexController@index");

    /**
     * 静的ページ
     *
     */
    // ITブートキャンプとは
    Route::get("/about", "StaticController@about");
    // キャンペーン (学割･授業料 半額返金制度)
    Route::get("/campaign_01", "StaticController@campaign");
    // キャンペーン (毎月10名限定・飛行機代無料)
    Route::get("/campaign_02", "StaticController@campaign");
    // キャンペーン (ウェブデザイン 女性限定キャンペーン)
    Route::get("/cam_lp-2", "StaticController@campaign");
    // カリキュラム
    Route::get("/curriculum", "StaticController@curriculum");
    // 合宿で受講
    Route::get("/camp", "StaticController@camp");
    // 通って受講
    Route::get("/school", "StaticController@school");
    // オンラインコース
    Route::get("/on_line", "StaticController@onLine");
    // Twitterタイムライン
    Route::get("/twitter-timeline", "StaticController@twitterTimeline");
    // 奮闘記
    Route::get("/struggles", "StaticController@struggles");
    // 参加者の声
    Route::get("/voice", "StaticController@voice");
    // インタビュー
    Route::get("/interview", "StaticController@interview");
    // 体験Blog
    Route::get("/experience", "StaticController@experience");
    // フリーランス＆業務委託
    Route::get("/future_01", "StaticController@future");
    // 職業斡旋
    Route::get("/future_02", "StaticController@future");
    // サポートメンバー
    Route::get("/future_03", "StaticController@future");
    // IBC認定
    Route::get("/future_04", "StaticController@future");
    // FAQ
    Route::get("/faq", "StaticController@faq");
    // BLOG
    Route::get("/blog", "StaticController@blog");
    // 研修として社員を参加させたい
    Route::get("/company_01", "StaticController@company");
    // 採用したい
    Route::get("/company_02", "StaticController@company");
    // 卒業生に制作をお願いしたい
    Route::get("/company_03", "StaticController@company");
    // 内定者研修として
    Route::get("/company_04", "StaticController@company");
    // IBCとは
    Route::get("/ibc_faq", "StaticController@ibcFaq");
    // フロントエンジニア
    Route::get("/front_end_engineer", "StaticController@frontEndEngineer");
    // Webデザイン
    Route::get("/web_design", "StaticController@webDesign");
    // WordPress
    Route::get("/word_press", "StaticController@webDesign");
    // Ruby
    Route::get("/ruby", "StaticController@ruby");
    // MBC
    Route::get("/mbc", "StaticController@mbc");
    // 就活ITブートキャンプ
    Route::get("/ibc-for-recruitment", "StaticController@ibcForRecruitment");
    // 石垣島 IT BOOTCAMP
    Route::get("/lp", "StaticController@lp");

    /**
     * コンタクト
     *
     */
    // 入力画面
    Route::get("/contact", "ContactController@index");
    // コンタクト (キャンペーン01からのリダイレクト(現状ママ))
    Route::get("/campaign_01/contact", "ContactController@campaign01");
    // コンタクト (キャンペーン02からのリダイレクト(現状ママ))
    Route::get("/campaign_02/contact", "ContactController@campaign02");
    // コンタクト (合宿で受講からのリダイレクト(現状ママ))
    Route::get("/camp/contact", "ContactController@camp");
    // コンタクト (オンラインコースからのリダイレクト(現状ママ))
    Route::get("/on_line/contact", "ContactController@onLine");
    // コンタクト (奮闘記からのリダイレクト(現状ママ))
    Route::get("/struggles/contact", "ContactController@struggles");
    // コンタクト (参加者の声からのリダイレクト(現状ママ))
    Route::get("/voice/contact", "ContactController@voice");
    // コンタクト (インタビューからのリダイレクト(現状ママ))
    Route::get("/interview/contact", "ContactController@interview");
    // コンタクト (フリーランス＆業務委託からのリダイレクト(現状ママ))
    Route::get("/future_01/contact", "ContactController@future01");
    // コンタクト (職業斡旋からのリダイレクト(現状ママ))
    Route::get("/future_02/contact", "ContactController@future02");
    // コンタクト (サポートメンバーからのリダイレクト(現状ママ))
    Route::get("/future_03/contact", "ContactController@future03");
    // コンタクト (IBC認定からのリダイレクト(現状ママ))
    Route::get("/future_04/contact", "ContactController@future04");
    // コンタクト (FAQからのリダイレクト(現状ママ))
    Route::get("/faq/contact", "ContactController@faq");
    // コンタクト (卒業生に制作をお願いしたいからのリダイレクト(現状ママ))
    Route::get("/company_03/contact", "ContactController@company03");
    // コンタクト (IBCとはからのリダイレクト(現状ママ))
    Route::get("/ibc_faq/contact", "ContactController@ibcFaq");
    // コンタクト (フロントエンジニアからのリダイレクト(現状ママ))
    Route::get("/front_end_engineer/contact", "ContactController@frontEndEngineer");
    // コンタクト (Webデザインからのリダイレクト(現状ママ))
    Route::get("/web_design/contact", "ContactController@webDesign");
    // コンタクト (Rubyからのリダイレクト(現状ママ))
    Route::get("/ruby/contact", "ContactController@ruby");
    // コンタクト (MBCからのリダイレクト(現状ママ))
    Route::get("/mbc/contact", "ContactController@mbc");

    /**
     * カリキュラムページ
     *
     */
    // リスト
    Route::get("/caliculam_list/{page?}", "CaliculamController@index")->where("page", "[0-9]+");
    // 詳細
    Route::get("/caliculam/{caliculam_id}", "CaliculamController@detail")->where("caliculam_id", "[0-9]+");

    /**
     * 認証
     *
     */
    // 会員登録
    Route::get("/signup", "AuthController@signup");
    // 会員登録確認
    Route::post("/signup_confirm", "AuthController@signupConfirm");
    // ログイン
    Route::get("/login", "AuthController@login");
    // ログイン確認
    Route::post("/login_confirm", "AuthController@loginConfirm");
    // ツイッターログイン
    Route::get("/twitter_login", "AuthController@twitterLogin");
    // ツイッターログインコールバック
    Route::get("/twitter_login_callback", "AuthController@twitterLoginCallback");
    // ログアウト
    Route::get("/logout", "AuthController@logout");
});

/**
 * マイページ
 *
 */
Route::group(["prefix" => "/mypage/", "namespace" => "Mypage", "middleware" => ["web", "auth_mypage",]], function () {
    // トップページ
    Route::get("/", "IndexController@index");
    // プロフィール入力
    Route::get("/profile/input", "ProfileController@input");
    // プロフィール登録
    Route::post("/profile/confirm", "ProfileController@confirm");
    // プロフィール画像の登録
    Route::post("/profile/upload_profile_image", "ProfileController@uploadProfileImage");
    // パスワード入力
    Route::get("/password/input", "PasswordController@input");
    // パスワード入力確認
    Route::post("/password/confirm", "PasswordController@confirm");
    // カリキュラム一覧
    Route::get("/caliculam_list", "CaliculamController@index");
    // カリキュラム詳細
    Route::get("/caliculam/{caliculam_id}", "CaliculamController@detail")->where("caliculam_id", "[0-9]+");
    // カリキュラム申し込み
    Route::get("/caliculam/regist/{caliculam_id}", "CaliculamController@regist")->where("caliculam_id", "[0-9]+");
    // カリキュラム申し込み完了
    Route::get("/caliculam/regist/complete", "CaliculamController@regist");
    // メッセージ一覧
    Route::get("/message_list", "MessageController@index");
    // メッセージ詳細
    Route::get("/message/{message_id}", "MessageController@detail")->where("message_id", "[0-9]+");
    // メッセージ投稿
    Route::post("/message/confirm", "MessageController@confirm");
});

/**
 * 管理画面
 *
 */
Route::group(["prefix" => "/admin/", "namespace" => "Admin", "middleware" => ["web", "auth_admin",]], function () {
    // トップページ
    Route::get("/", "IndexController@index");
    // ユーザー一覧画面
    Route::get("/user_list/{page?}", "UserController@index")->where("page", "[0-9]+");
    // ユーザー詳細画面
    Route::get("/user/{user_id}", "UserController@detail")->where("user_id", "[0-9]+");
    // ユーザー編集画面
    Route::post("/user/confirm", "UserController@confirm");
    // ユーザー権限変更 (ajax)
    Route::post("/user/change_user_role", "UserController@changeUserRole");
    // カリキュラム一覧画面
    Route::get("/caliculam_list/{page?}", "CaliculamController@index")->where("page", "[0-9]+");
    // カリキュラム詳細画面
    Route::get("/caliculam/{caliculam_id}", "CaliculamController@detail")->where("caliculam_id", "[0-9]+");
    // カリキュラム作成画面
    Route::get("/caliculam/edit/{caliculam_id?}", "CaliculamController@edit")->where("caliculam_id", "[0-9]+");
    // カリキュラム登録
    Route::post("/caliculam/confirm", "CaliculamController@confirm");
    // レッスン一覧画面
    Route::get("/lessen_list/{page?}", "LessenController@index")->where("page", "[0-9]+");
    // レッスン詳細画面
    Route::get("/lessen/{lessen_id}", "LessenController@detail")->where("lessen_id", "[0-9]+");
    // レッスン作成画面
    Route::get("/lessen/edit/{lessen_id?}", "LessenController@edit")->where("lessen_id", "[0-9]+");
    // レッスン登録
    Route::post("/lessen/confirm", "LessenController@confirm");
    // メッセージ送信
    Route::get("/send_message", "SendMessageController@index");
    // メッセージ送信 (学生)
    Route::get("/send_message/to_student", "SendMessageController@toStudent");
    // メッセージ送信確認 (学生)
    Route::post("/send_message/to_student_confirm", "SendMessageController@toStudentConfirm");
    // メッセージ送信完了 (学生)
    Route::get("/send_message/to_student_complete", "SendMessageController@toStudentComplete");
    // メッセージ一覧画面
    Route::get("/message_list/{page}", "MessageController@index");
    // メッセージ詳細画面
    Route::get("/message/{message_id}", "MessageController@detail");
    // グループ一覧(作成)画面
    Route::get("/user_group/", "UserGroupController@index");
    // グループ一覧(確認)画面
    Route::post("/user_group/confirm", "UserGroupController@confirm");
    // グループ詳細画面
    Route::get("/user_group/detail/{user_group_id}", "UserGroupController@detail")->where("user_group_id", "[0-9]+");
});
