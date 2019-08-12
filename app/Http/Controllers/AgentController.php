<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AgentController extends Controller
{
    /**
     * 用户列表
     */
    public function user_list()
    {
        $user_info = DB::connection('mysql_cart')->table('user')->get();
        return view('Agent.userList',['user_info'=>$user_info]);
    }

    /**
     * 生成专属二维码
     */
    public function creat_qrcode(Request $request)
    {
        $uid = $request->all()['uid']; //用户uid
        //用户uid就是专属推广码
        //生成带参数的二维码
        //二维码存入larvel
        //返回二维码链接
    }

    /**
     * 用户推广用户列表
     * @param Request $request
     */
    public function agent_list(Request $request)
    {
        $uid = $request->all()['uid']; //用户uid
        //user_agent 表数据 根据uid查询
    }
}
