<?php

namespace App\Http\Controllers;

use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use DB;

class LiuYanController extends Controller
{
    public  $wechat;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }

    public function wechat_login()
    {
        $redirect_uri = env('APP_URL').'/liuyan/wechat_code';  //接受code [微信客户端帮助用户自动跳转]
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
        header('Location:'.$url);
    }

    public function wechat_code(Request $request)
    {
        $req = $request->all();
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_APPSECRET').'&code='.$req['code'].'&grant_type=authorization_code';
        $re = file_get_contents($url);
        $result = json_decode($re,1);
        //登陆网站
        $user_wechat = DB::connection('mysql_cart')->table('user_wechat')->where(['openid'=>$result['openid']])->first();
        //用户基本信息
        $wechat_info = $this->wechat->wechat_user_info($result['openid']);
        if(!empty($user_wechat)){
            //已注册，组要登陆操作
            $request->session()->put(['uid'=>$user_wechat['uid']]);
        }else{
            //未注册，需要注册，然后登陆
            //开启事务
            DB::connection('mysql_cart')->beginTransaction();
            $uid = DB::connection('mysql_cart')->table('user')->insertGetId([
                'name'=>$wechat_info['nickname'],
                'password'=>'',
                'req_time'=>time()
            ]);
            $wechat_insert = DB::connection('mysql_cart')->table('user_wechat')->insert([
                'uid'=>$uid,
                'openid'=>$user_wechat['uid']
            ]);
            //登陆
            $request->session()->put(['uid'=>$user_wechat['uid']]);
        }
    }



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
        //echo "<pre>";print_r($se);
        if(!empty($se['username'])){
            //已经登陆
            echo "已经登陆";
        }
        $info = DB::connection('mysql_cart')->table('user_wechat')->get();
        foreach($info as $v){
            $user_info = $this->wechat->wechat_user_info($v->openid);
            $v->nick_name = $user_info['nickname'];
        }
       /* $redis = new \Redis();
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
        }*/
        //dd($info);
        return view('LiuYan/index',['info'=>$info]);
    }

    public function send(Request $request)
    {
        $req = $request->all();
        //发送模板消息
        return view('LiuYan/send',['uid'=>$req['uid']]);
    }

    public function do_send(Request $request)
    {
        $req = $request->all();
        $openid = DB::connection('mysql_cart')->table('user_wechat')->where(['uid'=>$req['uid']])->value('openid');
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->wechat->get_access_token();
        $data = [
            'touser'=>$openid,
            'template_id'=>'YBn0oqTBcJpzXNc8x9FUXfW9xCABnuc48SIC80w133g',
            'url'=>env('APP_URL').'/liuyan/index',
            'data' => [
                'first' => [
                    'value' => '留言消息',
                    'color' => ''
                ],
                'keyword1' => [
                    'value' => $this->wechat->wechat_user_info($openid)['nickname'],
                    'color' => ''
                ],
                'keyword2' => [
                    'value' => $req['send_info'],
                    'color' => ''
                ]
            ]
        ];
        $re = $this->wechat->post($url,json_encode($data));
        //我的留言

        return $re;
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
