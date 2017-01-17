<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class IndexController extends Controller
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
            "admin/index",
            []
        );
    }
}
