<?php

namespace app\api\controller;

class SystemConfig  extends Common
{
    //获取系统配置
    public function getsysteminfo()
    {
//        $where['name']  = 'price_setting';
//        $arr = ['price'=>'6.5','interest'=>'30'];
//        $where['value'] = serialize($arr);
//        Db::name('system')->insert($where);
        if ($this->request->isPost()) {

            $data            = $this->request->post();
            $name = $data['name'];
            $result = $this->getsystem($name);
            //dump($result);die;
            if ($result) return $this->return_msg($result);
            return  $this->return_msg('',401,'读取系统配置错误!');
        }
    }
}