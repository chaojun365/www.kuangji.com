<?php

namespace app\api\controller;

use think\Db;
class Index extends Common
{
   public function getlist()
   {
       //session(null);
       $where['status'] = 0;
       $result = model('goodslist')->getlist($where);
       return $this->return_msg($result);
   }

   //获取用户信息
   public function userinfo()
   {
       $uid = $this->uid();//Session('user.id');
       $where['id'] = $uid;
       $field = ['id','username','mobile','balance','status'];
       $result = model('User')->getuserinfo($where,$field);
       $result['auth'] = Db::name('authentication')->where($where)->field('status')->find();
       if ($result) return $this->return_msg($result);
       return $this->return_msg('',400,'获取失败');
   }

   //激活账号
    public function activateaccount()
    {
        $uid = $this->uid();
        $price = 10.00;
        //检查账号是否已激活
        $checkactivate = $this->checkactivate($uid,$price);
        return $checkactivate;
    }

    public function checkactivate($uid,$price)
    {
        //先检查是否已经激活
        $where['id'] = $uid;
        $status = Db::name('User')->where($where)->value('status');
        if($status == 1)  return $this->return_msg([],401,'请不要重复激活！');
        //扣除钻石
        Db::startTrans();
           //$where['status'] = 0;
           $res = $this->reduction($uid, $price);
           if ($res == false){
               Db::rollback();
               return $this->return_msg([],401,'钻石不足，激活失败！');
           }
           //改变激活状态
           $statusactivate = Db::name('User')->where($where)->setField('status',1);
           if ($statusactivate == false){
                Db::rollback();
                return $this->return_msg([],401,'激活失败！');
           }
           //生成记录
           $content = '激活账号-'.$price.'钻石';
           $this->savediamondrecord($uid,$price,$content);
           //获取当前余额
           $balance = $this->getbalance();
           Db::commit();
           return $this->return_msg($balance,200,'激活成功!');

    }
    //获取当前余额
    public function getbalance()
    {
        $uid = $this->uid();
        $where['id'] = $uid;
        $field = ['balance'];
        $balance = model('User')->getuserinfo($where,$field);//Db::name('User')->where($where)->field('balance')->find();
        //dump($balance);
        return $balance;
    }
    //扣除用户钻石余额
    public function reduction(int $uid,float $price)
    {
        $where['id'] = $uid;
        //$where['balance'] = $balance;
        $result = Db::name('User')->where($where)->where('balance','>=',$price)->setDec('balance',$price);
        return $result;
    }

    //生成钻石消费记录
    public function savediamondrecord(int $uid,float $price,string $content,$charge=0.00)
    {
        $uid = $this->uid();
        $save['uid']     = $uid;
        $save['content'] = $content;
        $save['price'] = $price;
        $save['charge'] = $charge;
        $save['create_time'] = time();
        $res = Db::name('diamond_record')->insert($save);
    }
    //购买钻石
    public function buyjewel()
    {
        //
        if ($this->request->isPost()) {
            $data            = $this->request->post();
                $uid = $this->uid();
                //先检查是否已经激活
                $activatestatus = model('User')->checkactivate($uid);
                if (!$activatestatus) return $this->return_msg('',400,'账户未激活');
                //绑定钱包
                $walletstatus   = model('Wallet')->checkwallet($uid);
                if (!$walletstatus) return $this->return_msg('',400,'请先绑定USDT钱包地址!');
                //锁定钻石订单
                $order_id = $data['order_id'];
                $result = model('jewelOrder')->lockorder($order_id);
                if (!$result) return $this->return_msg('',400,'订单锁定失败');
                //修改买家id和订单状态
                $result = model('jewelOrder')->buyorder($order_id,$uid);
                if (!$result) return $this->return_msg('',400,'下单失败!');
                return $this->return_msg('data',200,'订单已为您锁定成功,请尽快支付并上传付款凭证！');
        }
    }

    //出售钻石
    public function selljewel()
    {
        if ($this->request->isPost()) {

            $data            = $this->request->post();
            $uid = $this->uid();
            //先检查是否已经激活
            $activatestatus = model('User')->checkactivate($uid);
            if (!$activatestatus) return $this->return_msg('',400,'账户未激活');
            //绑定钱包
            $walletstatus   = model('Wallet')->checkwallet($uid);
            if (!$walletstatus) return $this->return_msg('',400,'请先绑定USDT钱包地址!');
            //
            $amount = $data['amount'];
            //扣除钻石
            Db::startTrans();
            try{
            //$where['status'] = 0;
            $res = $this->reduction($uid, $amount);
            if ($res == false){
                Db::rollback();
                return $this->return_msg([],401,'钻石不足，出售失败！');
            }
            //获取系统手续费率
            $where['name'] = 'price_setting';
            $name          = $this->getsystem($where);//$severinfo;\
            $interest      = $name['interest'];
            $charge        =$amount*$interest/100;
            $newamount     = $amount - $charge;
            //生成新的钻石订单
            $createorder   = $this->createorder($uid,$newamount,$charge);
            if ($createorder == false){
                Db::rollback();
                return $this->return_msg([],401,'出售失败!');
            }
            //生成记录
            $content = '出售-'.intval($amount).'钻石';
            $this->savediamondrecord($uid,$amount,$content,$charge);
                Db::commit();
            //获取当前余额
            $balance = $this->getbalance();

            return $this->return_msg($balance,200,'出售成功!');
            } catch (\Exception $e) {
                // 回滚事务
                Db::rollback();
                return $this->return_msg($e,401,'系统出错!');
            }
        }

    }

    //转账
    public function transfer()
    {
        if ($this->request->isPost()) {

            $data            = $this->request->post();
            $uid = $this->uid();
            $amount = $data['amount'];
            //先检查账户是否可用
            $where['mobile'] = $data['mobile'];
            $where['is_lock'] = 0;
            $field           =['id'];
            $receive = model('User')->getuserinfo($where,$field);
            //dump($receive);
            if(!$receive['id']) return $this->return_msg('',401,'账户不存在或已被冻结!');

            //检查是否是当前用户的上级
            $relation = model('UserRelation')->checkrelation($uid,$receive['id']);

            //检查是否是当前用户的下级
            $branch = model('UserRelation')->checkbranch($uid,$receive['id']);
//            $ids = [];
//            $child_ids = $this->getChildIds($uid,$ids);
//            halt($child_ids);
            if ($branch == false) return $this->return_msg('',401,'转账条件不满足,请核对账户!');

            //当关系存在时
            if ($relation == true || $branch == true) {
                //进入交易流程
                $result = $this->$this->reduction($uid, $amount);
            }

        }
    }
    //创建订单信息
    public function createorder(int $uid,float $amount,float $charge)
    {
        //获取系统设置的当前钻石价格
        $where['name']    = 'price_setting';
        $name             = $this->getsystem($where);//$severinfo;\
        $price            = $name['price'];
        $save['order_id'] = $this->create_trade_no();
        $save['uid']      = $uid;
        $save['amount']   = $amount;
        $save['price']    = $price;
        $save['charge']   = $charge;
        $save['create_time'] = time();
        $result = Db::name('jewel_order')->insertGetId($save);
        if ($result > 0) return true;
        return false;
    }

    //生成唯一订单号
    function create_trade_no($prefix='OB')
    {
        return $prefix .substr(microtime(), 2, 6) . sprintf('%03d', rand(0, 9999)).sprintf('%03d', rand(0, 9999));
    }

}