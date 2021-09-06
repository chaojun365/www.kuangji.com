<?php

namespace app\api\model;

use think\Model;
class User extends Model
{
    //检查激活状态
    public function checkactivate($uid)
    {
        $where['id']     = $uid;
        $where['status'] = 1;
        $result = $this->where($where)->find();
        return $result;
    }
    //获取用户信息
    public function getuserinfo(array $where,array $field)
    {
        $result = $this->where($where)->field($field)->find();
        return $result;
    }

    //修改用户表信息
    public function changeuserinfo(array $where,array $setfield)
    {
        $result = $this->where($where)->setfield($setfield);
        return $result;
    }
}