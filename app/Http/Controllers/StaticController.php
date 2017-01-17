<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Request;
use Exception;
use Redirect;
use Config;

class StaticController extends Controller
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
     * キャンペーン
     *
     */
    public function campaign()
    {
        // 呼び出しパスの取得 (campaign_01など)
        $request_path = Request::path();

        // 呼び出しパスに合わせてViewファイルを変更
        if ($request_path === "campaign_01") {
            $view_file = "static/campaign/01";
        } elseif ($request_path === "campaign_02") {
            $view_file = "static/campaign/02";
        } elseif ($request_path === "cam_lp-2") {
            $view_file = "static/lp/cam_lp-2";
        } else {
            throw new Exception("呼び出しパス: " . $request_path ." 存在しないキャンペーンへのアクセスが有りました。");
        }

        return $this->render(
            $view_file,
            []
        );
    }

    /**
     * カリキュラム
     *
     */
    public function curriculum()
    {
        return $this->render(
            "static/curriculum",
            []
        );
    }

    /**
     * 合宿で受講
     *
     */
    public function camp()
    {
        return $this->render(
            "static/camp",
            []
        );
    }

    /**
     * 通って受講
     *
     */
    public function school()
    {
        return $this->render(
            "static/school",
            []
        );
    }

    /**
     * オンラインコース
     *
     */
    public function onLine()
    {
        return $this->render(
            "static/on_line",
            []
        );
    }

    /**
     * Twitterタイムライン
     *
     */
    public function twitterTimeline()
    {
        return $this->render(
            "static/twitter_timeline",
            []
        );
    }

    /**
     * 奮闘記
     *
     */
    public function struggles()
    {
        return $this->render(
            "static/struggles",
            []
        );
    }

    /**
     * 参加者の声
     *
     */
    public function voice()
    {
        return $this->render(
            "static/voice",
            []
        );
    }

    /**
     * インタビュー
     *
     */
    public function interview()
    {
        return $this->render(
            "static/interview",
            []
        );
    }

    /**
     * 体験Blog
     *
     */
    public function experience()
    {
        return $this->render(
            "static/experience",
            []
        );
    }

    /**
     * 卒業後
     *
     */
    public function future()
    {
        // 呼び出しパスの取得 (campaign_01など)

        $request_path = Request::path();

        // 呼び出しパスに合わせてViewファイルを変更
        if ($request_path === "future_01") {
            $view_file = "static/future/01";
        } elseif ($request_path === "future_02") {
            $view_file = "static/future/02";
        } elseif ($request_path === "future_03") {
            $view_file = "static/future/03";
        } elseif ($request_path === "future_04") {
            $view_file = "static/future/04";
        } else {
            throw new Exception("呼び出しパス: " . $request_path ." 存在しない卒業後ページへのアクセスが有りました。");
        }

        return $this->render(
            $view_file,
            []
        );
    }

    /**
     * FAQ
     *
     */
    public function faq()
    {
        return $this->render(
            "static/faq",
            []
        );
    }

    /**
     * FAQ
     *
     */
    public function blog()
    {
        return $this->render(
            "static/blog",
            []
        );
    }

    /**
     * 企業様
     *
     */
    public function company()
    {
        // 呼び出しパスの取得 (campaign_01など)

        $request_path = Request::path();

        // 呼び出しパスに合わせてViewファイルを変更
        if ($request_path === "company_01") {
            $view_file = "static/company/01";
        } elseif ($request_path === "company_02") {
            $view_file = "static/company/02";
        } elseif ($request_path === "company_03") {
            $view_file = "static/company/03";
        } elseif ($request_path === "company_04") {
            $view_file = "static/company/04";
        } else {
            throw new Exception("呼び出しパス: " . $request_path ." 存在しない卒業後ページへのアクセスが有りました。");
        }

        return $this->render(
            $view_file,
            []
        );
    }

    /**
     * IBCとは
     *
     */
    public function ibcFaq()
    {
        return $this->render(
            "static/ibc_faq",
            []
        );
    }

    /**
     * フロントエンジニア
     *
     */
    public function frontEndEngineer()
    {
        return $this->render(
            "static/curriculum/front_end_engineer",
            []
        );
    }

    /**
     * Webデザイン
     *
     */
    public function webDesign()
    {
        return $this->render(
            "static/curriculum/web_design",
            []
        );
    }

    /**
     * WordPress
     *
     */
    public function wordPress()
    {
        return $this->render(
            "static/curriculum/word_press",
            []
        );
    }

    /**
     * Ruby
     *
     */
    public function ruby()
    {
        return $this->render(
            "static/curriculum/ruby",
            []
        );
    }

    /**
     * MBC
     *
     */
    public function mbc()
    {
        return $this->render(
            "static/curriculum/mbc",
            []
        );
    }

    /**
     * 就活ITブートキャンプ
     *
     */
    public function ibcForRecruitment()
    {
        return $this->render(
            "static/curriculum/ibc_for_recruitment",
            [
                "industry_data_list" => Config::get("industry"),
                "channel_data_list" => Config::get("channel"),]
        );
    }

    /**
     * 石垣島 IT BOOTCAMP
     *
     */
    public function lp()
    {
        return $this->render(
            "static/class_room/lp",
            []
        );
    }
}
