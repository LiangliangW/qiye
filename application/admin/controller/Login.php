<?php

namespace app\admin\controller;

use app\admin\common\Base;
use app\admin\model\Admin;
use think\Request;
use think\Session;

class Login extends Base
{
    //渲染登录界面
    public function index()
    {
        $this->alreadyLogin();
        return $this -> view -> fetch('login');
    }

    //验证用户身份
    public function check(Request $request)
    {
        //设置初始status
        $status = 0;

        //获取表单数据并保存在变量中
        $data = $request -> param();
        $userName = $data['username'];
        $password = md5($data['password']);

        //在admin表中查询
        $map = ['username' => $userName];
        $admin = Admin::get($map);

        //将用户名和密码分开验证

        //如果没有查询到该用户
        if (is_null($admin)) {
            $message = '用户名不存在';
        } elseif ($admin -> password != $password) {
            $message = '密码错误';
        } else {
            //修改返回信息
            $status = 1;
            $message = '验证通过，请点击确定进入后台';

            //更新表中登陆次数与最后登录时间
            $admin -> setInc('login_count');
            $admin -> save(['last_time' => time()]);

            //将用户登录信息保存在session中，供其他控制器进行登录判断
            Session::set('user_id', $userName);
            Session::set('user_inf', $data);
        }

        return ['status' => $status, 'message' => $message];
    }

    //退出登录
    public function logout(Request $request)
    {
        //删除当前用户session
        Session::delete('user_id');
        Session::delete('user_inf');

        //执行成功并返回登录界面
        $this -> success('注销成功，正在返回...', 'login/index');
    }
}
