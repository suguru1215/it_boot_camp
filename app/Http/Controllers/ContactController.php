<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Redirect;
use Config;

class ContactController extends Controller
{
    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * トップページ
     *
     */
    public function index()
    {
        return $this->render(
            "contact/index",
            [
                "prefecture_data_list" => Config::get("prefecture"),
                "generation_data_list" => Config::get("generation"),
                "gender_data_list" => Config::get("gender"),
                "course_data_list" => Config::get("course"),]
        );
    }

    /**
     * コンタクト (キャンペーン01からのリダイレクト)
     *
     */
    public function campaign01()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (キャンペーン02からのリダイレクト)
     *
     */
    public function campaign02()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (合宿で受講からのリダイレクト)
     *
     */
    public function camp()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (オンラインコースからのリダイレクト)
     *
     */
    public function onLine()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (奮闘記からのリダイレクト)
     *
     */
    public function struggles()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (参加者の声からのリダイレクト)
     *
     */
    public function voice()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (インタビューからのリダイレクト)
     *
     */
    public function interview()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (フリーランス＆業務委託からのリダイレクト)
     *
     */
    public function future01()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (職業斡旋からのリダイレクト)
     *
     */
    public function future02()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (サポートメンバーからのリダイレクト)
     *
     */
    public function future03()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (IBC認定からのリダイレクト)
     *
     */
    public function future04()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (FAQからのリダイレクト)
     *
     */
    public function faq()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (卒業生に制作をお願いしたいからのリダイレクト)
     *
     */
    public function company03()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (IBCとはからのリダイレクト)
     *
     */
    public function ibcFaq()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (フロントエンジニアからのリダイレクト)
     *
     */
    public function frontEndEngineer()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (Webデザインからのリダイレクト)
     *
     */
    public function webDesign()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (Rubyからのリダイレクト)
     *
     */
    public function ruby()
    {
        return Redirect::to("/contact", 301);
    }

    /**
     * コンタクト (MBCからのリダイレクト)
     *
     */
    public function mbc()
    {
        return Redirect::to("/contact", 301);
    }
}
