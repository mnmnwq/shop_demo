<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WechatController extends Controller
{

    /**
     * 通过code获得access_token
     * @param Request $request
     */
    public function code(Request $request){
        $req = $request->all();
        $code = $req['code'];
        //获取access_token
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env("WECHAT_APPID")."&secret=".env("WECHAT_APPSECRET")."&code=".$code."&grant_type=authorization_code";
        $re = file_get_contents($url);
        $result = json_decode($re,1);
        $access_token = $result['access_token'];
        $openid = $result['openid'];
        dd($openid);
    }

    public function get_user_info()
    {
        $access_token = $this->get_access_token();
        $openid = 'otAUQ1Src8fq9rMwHD4NQKL0npUE';
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $user_info = json_decode($wechat_user,1);
        dd($user_info);
    }

    public function get_user_list()
    {
        $access_token = $this->get_access_token();
        //拉取关注用户列表
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=");
        $user_info = json_decode($wechat_user,1);
    }

    /**
     * 获取access_token
     */
    public function get_access_token(){
        //获取access_token
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        $access_token_key = 'wechat_access_token';
        if($redis->exists($access_token_key)){
            //去缓存拿
            $access_token = $redis->get($access_token_key);
        }else{
            //去微信接口拿
            $access_re = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WECHAT_APPID')."&secret=".env('WECHAT_APPSECRET'));
            $access_result = json_decode($access_re,1);
            $access_token = $access_result['access_token'];
            $expire_time = $access_result['expires_in'];
            //加入缓存
            $redis->set($access_token_key,$access_token,$expire_time);
        }
        return $access_token;
    }
}
