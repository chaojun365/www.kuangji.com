<?php

namespace app\api\model;

use think\Model;

class Wallet extends Model
{
    //检查钱包绑定状态
    public function checkwallet($uid)
    {
        $where['id']     = $uid;
        $where['type'] = 1;
        $where['status'] = 0;
        $result = $this->where($where)->find();
        return $result;
    }
}