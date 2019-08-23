<?php

namespace App\Http\Controllers;

use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use DB;

class LoginController extends Controller
{
    public $redirect_uri;
    public $wechat;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
        $this->redirect_uri = env('APP_URL').'/wechat/code'; //接受code [微信客户端帮助用户自动跳转]
    }

    public function wechat_login()
    {
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($this->redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect';
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
            $request->session()->put(['uid'=>$user_wechat->uid]);
            return redirect('biaobai/index');
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
                'openid'=>$user_wechat->uid
            ]);
            //登陆
            $request->session()->put(['uid'=>$user_wechat->uid]);
        }
    }
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('student/login');
    }
}
