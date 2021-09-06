<?php

namespace app\api\model;


use think\Model;

class UserRelation extends Model
{
    //检查是否是当前用户的直线下级
    public function checkrelation(int $uid,int $pid)
    {
        $where['uid']    = $uid;
        $where['pid'] = $pid;
        $result = $this->where($where)->find();
        //dump($result);
        if (!$result) return false;
        return true;
    }

    //检查是否是当前用户的上级
    public function checkbranch(int $uid,int $branch)
    {
        //$result = $this->
    }

    public function getChildIds($user_id,&$ids = [])
    {
        $result = $this->where('pid',$user_id)->column('id');
        if ($result) {
            $ids  = array_merge($result,$ids);
            foreach ($result as $v) {
                $this->getChildIds($v,$ids);
            }
        }
    }
}