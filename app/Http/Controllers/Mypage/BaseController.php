<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use Session;
use Redirect;

class BaseController extends Controller
{
    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
        parent::__construct();
    }
}
