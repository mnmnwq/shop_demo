<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Tools\Wechat;
use EasyWeChat\Factory;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;

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
        $config = [
            'app_id' => env("WECHAT_APPID"),
            'secret' => env("WECHAT_APPSECRET"),
            'token' => 'token',
            'response_type' => 'array',
        ];
        $app = Factory::officialAccount($config);
        $path='./storage/wechat/image/a1witFOK3QkaLia01o5BkD0o72W5GX3WEZGviIfg.jpeg';
        //realpath($path)
//        $re = $app->media->uploadImage(realpath($path));
        $client = new Client();
        $body = fopen(realpath($path), 'r');
        $r = $client->request('POST', 'http://httpbin.org/post', ['media' => $body]);
        dd($r->getBody());
        return view('wechat.uploadSource');
    }

    public function do_upload(Request $request)
    {
        if($request->hasFile('image')){
            $file = $request->file('image');
            //$path = $request->file('image')->store('wechat'.$fenge.'image');
            $image_name = 'a1witFOK3QkaLia01o5BkD0o72W5GX3WEZGviIfg.jpeg';
            $type = 'image';
            $path='./storage/wechat/image/a1witFOK3QkaLia01o5BkD0o72W5GX3WEZGviIfg.jpeg';
            $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->get_access_token() .'&type='.$type;
            $data = ['media' => new \CURLFile(realpath($path))];
            dd($data);
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL,$url);
            curl_setopt($curl, CURLOPT_POST, 1 );
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_USERAGENT,"TEST");
            $result = curl_exec($curl);
            curl_close($curl);
            $res=json_decode($result,true);
            dd($res);
        }elseif($request->hasFile('voice')){
            $path = $request->file('voice')->store('wechat/voice');
        }elseif($request->hasFile('video')){
            $path = $request->file('video')->store('wechat/video');
        }elseif($request->hasFile('thumb')){
            $path = $request->file('thumb')->store('wechat/thumb');
        }

    }

    function posturl($url,$data){
        $data  = json_encode($data);
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
        return json_decode($output,1);
    }

    public function login(){
        $redirect_uri = 'http://www.shopdemo.com/wechat/code';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect ';
        header('Location:'.$url);
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
        //去user_openid 表查 是否有数据 openid = $openid
        //有数据 在网站有用户 user表有数据[ 登陆 ]
        //没有数据 注册信息  insert user  user_openid   生成新用户
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
