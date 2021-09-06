<?php

namespace app\api\controller;

use think\Db;

class User extends Index
{
    //实名验证
    public function authentication()
    {
        if ($this->request->isPost()) {

            $data            = $this->request->post();
            //先检查是否有记录
            $useranme     = $data['username'];
            $idcard       = $data['idcard'];
            $uid          = $this->uid();
            $field        = ['status'];
            $status       = Db::name('authentication')->where('uid',$uid)->field($field)->find();

            switch ($status['status']){
                case 1://已提交过
                    return $this->return_msg('',401,'请不要重复提交!');
                    break;
                case 2://已通过
                    return $this->return_msg('',401,'已通过认证,请不要重复提交!');
                    break;
                default://提交申请
                    $result = $this->reviseauth($uid,$useranme,$idcard,1);
                    if ($result) return $this->return_msg('',200,'提交成功!');
                    $this->return_msg('',401,'提交失败!');
            }

        }
    }
    //提交认证
    public function reviseauth(int $uid,string $username,string $idcard,int $status)
    {
        $save['uid'] = $uid;
        $save['username'] = $username;
        $save['idcard'] = $idcard;
        $save['status'] = $status;
        $save['create_time'] = time();
        $result = Db::name('authentication')->insertGetId($save);
        if ($result > 0) return true;
        return false;
    }
}