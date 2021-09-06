<?php
namespace app\api\controller;
//use think\Request;
use think\Controller;
use think\Db;

/**
 *
 */
class Common extends Controller
{
      //protected $systeminfo;// = session('user.id');
//    protected $request;//处理参数
//    protected $middleware = ['CheckLogin'];
    protected function _initialize()
    {

    }
    protected function uid()
    {
          $uid = session('user.id');
          return $uid;
//        parent::_initialize();//继承父类构造方法
//        $this->request=Request::instance();
//        $this->check_time($this->request->only(['time']));
//        $this->check_token($this->request->param());
    }

    //返回信息
    public function return_msg($data=[],$code=200,$msg='获取成功！')
    {
        $return_data['code'] = $code;
        $return_data['msg'] = $msg;
        $return_data['data'] = $data;

        return json($return_data);//返回
    }
    //获取系统配置
    public function getsystem($name)
    {
        $where['name'] = $name;
        $result = Db::name('system')->where($where)->value('value');
        $res    = unserialize($result);
        return $res;
    }

    //验证登入密码
    public function checkpassword(int $mobile,string $password)
    {
        //获取密码盐
        $where['mobile']     = $mobile;
        $field = ['salt','password','id','is_lock'];
        $userinfo = model('User')->getuserinfo($where,$field);
        return $userinfo;
//        if (!$userinfo) return false;
//        $result = md5($password.$userinfo['salt']);
//        $result == $userinfo['password'];

    }
}