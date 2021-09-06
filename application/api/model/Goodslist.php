<?php

namespace app\api\model;
use think\Model;
class Goodslist extends Model
{
    //获取商品列表
    public function  getlist($where='')
    {
        $result = $this->where($where)->select();
        return $result;
    }
}