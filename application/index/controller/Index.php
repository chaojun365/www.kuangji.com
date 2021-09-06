<?php
namespace app\index\controller;

use app\api\controller\Common;

class Index extends Common
{

    protected $middleware = ['CheckLogin'];

    public function index()
    {
        return view();
    }

    public function shop()
    {
        return view();
    }

    public function cangku()
    {
        return view();
    }

    public function my()
    {
        return view();
    }

    public function sell()
    {
        return view();
    }

    public function jewelorder()
    {
        return view();
    }

    public function selljewelorder()
    {
        return view();
    }

    public function set()
    {
        return view();
    }

    public function transfer()
    {
        return view();
    }

    public function authentication()
    {
        return view();
    }

    public function payment()
    {
        return view();
    }
}
