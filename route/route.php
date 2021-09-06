<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});
//登入视图
Route::rule('/login','index/login/index');
//设置视图
Route::rule('/set','index/index/set');
//首页视图
Route::rule('/','index/index/index');
Route::rule('/index','index/index/index');
//市场视图
Route::rule('/shop','index/index/shop');
//仓库视图
Route::rule('/cangku','index/index/cangku');
//市场视图
Route::rule('/my','index/index/my');
//转账视图
Route::rule('/transfer','index/index/transfer');
//实名认证视图
Route::rule('/authentication','index/index/authentication');
//收款账号视图
Route::rule('/payment','index/index/payment');
//出售钻石视图
Route::rule('/sell','index/index/sell');
//市场订单 购买记录视图
Route::rule('/jewelorder','index/index/jewelorder');
//市场订单 出售记录视图
Route::rule('/selljewelorder','index/index/selljewelorder');
//获取系统配置
Route::post('/api/getsysteminfo','api/SystemConfig/getsysteminfo');
//获取矿机列表
Route::post('/api/getlist','api/index/getlist');
//获取用户信息
Route::post('/api/userinfo','api/index/userinfo');
//激活账号
route::post('/api/activateaccount','api/index/activateaccount');
//获取指定状态订单
route::post('/api/getjewelorder','api/order/getjewelorder');
//购买钻石
route::post('/api/buyjewel','api/index/buyjewel');
//出售钻石
route::post('/api/selljewel','api/index/selljewel');
//转账钻石
Route::rule('/api/transfer','api/index/transfer');
//获取用户购买钻石的订单
route::post('/api/userjewelorder','api/order/userjewelorder');
//获取用户出售钻石的订单
route::post('/api/userselljewelorder','api/order/userselljewelorder');
//实名认证
Route::rule('/api/authentication','api/user/authentication');
//用户登录
Route::post('/api/login','api/login/login');
//用户退出
Route::post('/api/signout','api/login/signout');
return [

];
