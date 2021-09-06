<?php

namespace app\api\controller;

use think\captcha\Captcha;
class Login extends Common
{
//    //图形验证码设置
//    public function verify()
//    {
//
//        $captcha = new Captcha();
//        $captcha->length   = 4;
//        $captcha->codeSet  = '0123456789';
//        $captcha->useCurve = false;
//        $captcha->useNoise = false;
//        return $captcha->entry();
//    }
    //登入
    public function login()
    {
        if ($this->request->isPost()) {

            $data              = $this->request->post();

            $mobile   = $data['mobile'];
            $password = $data['password'];
            $code     = $data['code'];
            //图形验证码校验
            if(!captcha_check($code)){

                return $this->return_msg('','401','验证码错误!');
            };
            $result = $this->checkpassword($mobile,$password);
            if (!$result) return $this->return_msg('',401,'用户名或密码有误!');
            if ($result['is_lock'] == 1) return $this->return_msg('',401,'用户已冻结!');
            //清除原session作用域
            session(null);
            //登入成功 写入session
            session('user.id',$result['id']);
            if (!session('user.id')){
                //dump($this->uid);
                return $this->return_msg('',401,'登录失败!');
            }else{

                return $this->return_msg('',200,'登入成功');
            }

        }
    }
    //退出登录
    public function signout()
    {
        if ($this->request->isPost()) {

            $data              = $this->request->post();
            session(null);
            if (session('?user.id')){

                return $this->return_msg('',401,'退出失败!');
            }else{
                return $this->return_msg('',200,'退出成功!');
            }
        }
    }
}