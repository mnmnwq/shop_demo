<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class LiuYanController extends Controller
{
    public function info(Request $request)
    {
        if(empty($request->all()['access_token']) || $request->all()['access_token'] != '12345'){
            return  json_encode(['errno'=>'40014']);
        }
        $info = DB::connection('mysql_cart')->table('liu')->get()->toArray();
        $info = json_decode(json_encode($info),1);
        echo json_encode($info);
    }
    public function index(Request $request){
        $se = $request->session()->all();
        echo "<pre>";print_r($se);
//        if(!empty($se['username'])){
//            //已经登陆
//            echo "已经登陆";
//        }
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $key = 'liuyyan:list';
        if($redis->exists($key)){
            $redis_info = $redis->get($key);
            $info = json_decode($redis_info,1);
            echo 11111;
        }else{
            $info = DB::connection('mysql_cart')->table('liu')->get()->toArray();
            $info = json_decode(json_encode($info),1);
            $redis->set($key,json_encode($info),30);
        }
        dd($info);
    }

    public function do_del(Request $request)
    {
        $req = $request->all();
        $se = $request->session()->all();
        $liuyan_uid = DB::connection('mysql_cart')->table('liu')->where(['id'=>$req['id']])->select('uid','add_time')->first();
        //删除一条数据
        //判断当前用户和要删除数据用户id是否相等

        if($se['uid'] != $liuyan_uid->uid){
            echo "没权限";
        }
        //相等判断创建时间
        if(time() - $liuyan_uid->add_time > 1800){
            echo "没权限";
        }
        echo "ok";
    }
}
