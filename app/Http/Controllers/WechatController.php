<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Tools\Wechat;

class WechatController extends Controller
{
    public $request;
    public $wechat;
    public function __construct(Request $request,Wechat $wechat)
    {
        $this->request = $request;
        $this->wechat = $wechat;
    }

    /**
     * 上传素材
     */
    public function upload_source()
    {
        return view('wechat.uploadSource');
    }

    public function do_upload(Request $request)
    {
        if($request->hasFile('image')){
            $path = $request->file('image')->store('wechat/image');
        }elseif($request->hasFile('voice')){
            $path = $request->file('voice')->store('wechat/voice');
        }elseif($request->hasFile('video')){
            $path = $request->file('video')->store('wechat/video');
        }elseif($request->hasFile('thumb')){
            $path = $request->file('thumb')->store('wechat/thumb');
        }

    }

    function posturl($url,$data){
        //$data  = json_encode($data);
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        son_decode($output,1);
    }

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
        $openid = DB::connection('mysql_cart')->table('wechat_openid')->where(['id'=>$this->request->all()['id']])->value('openid');;
        $user_info = $this->wechat->wechat_user_info($openid);
        dd($user_info);
    }

    public function wechat_user_info($openid){
        $access_token = $this->get_access_token();
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
        $user_info = json_decode($wechat_user,1);
        return $user_info;
    }

    /**
     * 粉丝列表
     */
    public function user_list()
    {
        $openid_info = DB::connection('mysql_cart')->table('wechat_openid')->get();
        return view('wechat.userList',['openid_info'=>$openid_info]);
    }

    public function get_user_list()
    {
        $access_token = $this->get_access_token();
        //拉取关注用户列表
        $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/get?access_token={$access_token}&next_openid=");
        $user_info = json_decode($wechat_user,1);
        foreach($user_info['data']['openid'] as $v){
            $subscribe = DB::connection('mysql_cart')->table('wechat_openid')->where(['openid'=>$v])->value('subscribe');
            if(empty($subscribe)){
                //获取用户详细信息
                $user = $this->wechat_user_info($v);

                DB::connection('mysql_cart')->table('wechat_openid')->insert([
                    'openid' => $v,
                    'add_time' => time(),
                    'subscribe' => $user['subscribe']
                ]);
            }else{
                //获取用户详细信息
                $access_token = $this->get_access_token();
                $openid = $v;
                $wechat_user = file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$openid."&lang=zh_CN");
                $user = json_decode($wechat_user,1);
                if($subscribe != $user['subscribe']){
                    DB::connection('mysql_cart')->table('wechat_openid')->where(['openid'=>$v])->update([
                        'subscribe' => $user['subscribe'],
                    ]);
                }
            }
        }
        echo "<script>history.go(-1);</script>";
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
