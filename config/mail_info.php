<?php

// 本番はなにもつけない
$pre_fix  = "";
if (config("app.env") === "dev_sengoku") {
    $pre_fix  = "【テスト】";
} elseif (config("app.env") === "dev") {
    $pre_fix  = "【テスト】";
} elseif (config("app.env") === "stg") {
    $pre_fix  = "【STG】";
}

return [
    // 会員登録
    "signup" => [
        "view_file" => "email.signup",
        "subject" => "【ITブートキャンプ】" . $pre_fix . "ITブートキャンプへようこそ！",],
    // パスワード忘れ
    "forget_password" => [
        "view_file" => "email.password.forget_password",
        "subject" => "【ITブートキャンプ】" . $pre_fix . "仮パスワードが発行されました。",],
    // パスワード変更
    "change_password" => [
        "view_file" => "email.password.change_password",
        "subject" => "【ITブートキャンプ】" . $pre_fix . "パスワードが変更されました。",],
    // レッスン申し込み
    "reservation_apply_admin" => [
        "view_file" => "email.applycation.admin",
        "subject" => "【ITブートキャンプ】" . $pre_fix . "「{{lesson_title}}」に申し込みがありました。",],
    "reservation_apply_student" => [
        "view_file" => "email.applycation.student",
        "subject" => "【ITブートキャンプ】" . $pre_fix . "「{{lesson_title}}」にお申し込みいただきありがとうございます。",],
    // 生徒向け一斉メール
    "to_student" => [
        // subjectはフォームから入力する
        "view_file" => "email.to_student",],
    // メッセージ送信
    "message" => [
        "view_file" => "email.message",],];
