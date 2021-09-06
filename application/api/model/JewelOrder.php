<?php

namespace app\api\model;

use think\Model;
class JewelOrder extends Model
{
    //获取所有挂卖的钻石订单
    public function getjewelorder(int $status,int $is_lock)
    {
       // $where['uid'] = $uid;
        $where['status'] = $status;
        $where['is_lock'] = $is_lock;
        $result = $this->where($where)->select();
        return $result;
    }
    //锁定订单
    public function lockorder(int $order_id)
    {
        //先锁定订单
        $where['id'] = $order_id;
        $setField['is_lock'] = 1;
        $islock = $this->where($where)->setField($setField);
        return $islock;
    }
    //修改买家id和订单状态
    public function buyorder(int $order_id,int $buy_id,$is_lock=1,$status=1)
    {
        $where['id']         = $order_id;
        $setField['buy_id']  = $buy_id;
        $setField['is_lock'] = $is_lock;
        $setField['status']  = $status;
        $setField['create_time'] = time();
        $result = $this->where($where)->setField($setField);
        return $result;
    }

    //获取用户钻石购买订单
    public function userjewelorder(int $buy_id,int $status,int $is_lock)
    {
        $where['buy_id']     = $buy_id;
        $where['status']  = $status;
        $where['is_lock'] = $is_lock;
        $result = $this->where($where)->select();
        return $result;
    }

    //获取用户钻石出售订单
    public function userselljewelorder(int $uid,int $status,int $is_lock)
    {
        $where['uid']     = $uid;
        $where['status']  = $status;
        $where['is_lock'] = $is_lock;
        $result = $this->where($where)->select();
        return $result;
    }
}