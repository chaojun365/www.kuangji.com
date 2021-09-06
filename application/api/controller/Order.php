<?php

namespace app\api\controller;

class Order extends Common
{
    //获取钻石订单
    public function getjewelorder()
    {
        if ($this->request->isPost()) {

            $data            = $this->request->post();
            //$uid  = 1;
            $is_lock  = $data['is_lock'];
            $status  = $data['status'];
            $result = model('JewelOrder')->getjewelorder($status,$is_lock);
            //dump($result);
            if (count($result) == 0){
                return $this->return_msg('',400,'无数据');
            }else{
                return $this->return_msg($result);
            }
        }
    }

    //获取指定用户的购买订单
    public function userjewelorder()
    {
        if ($this->request->isPost()) {

            $data            = $this->request->post();
            $buy_id    =  $this->uid();
            $status = $data['status'];
            $is_lock   = $data['is_lock'];
            $result = model('JewelOrder')->userjewelorder($buy_id,$status,$is_lock);
            //dump($result);
            if (count($result) == 0) return $this->return_msg('',404,'无数据');
            return $this->return_msg($result);
        }
    }

    //获取指定用户的出售订单
    public function userselljewelorder()
    {
        if ($this->request->isPost()) {

            $data            = $this->request->post();
            $uid = $this->uid();
            $status = $data['status'];
            $is_lock   = $data['is_lock'];
            $result = model('JewelOrder')->userselljewelorder($uid,$status,$is_lock);
            //dump($result);
            if (count($result) == 0) return $this->return_msg('',404,'无数据');
            return $this->return_msg($result);
        }
    }
}