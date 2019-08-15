<?php

namespace App\Http\Controllers;

use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use DB;

class AgentController extends Controller
{
    public $wechat;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }

    public function signature(Request $request)
    {
        //随机字符串
        $str = time() . rand(111111,999999). 'suibian';
        $nonce_str = substr( md5($str),5,8 );
        $timestamp = time(); //当前时间戳
        $url = 'http://'.$request['url'];  //需要前端传过来

        $data = [
            'jsapi_ticket'  => $this->wechat->jsapi_ticket(),
            'noncestr'      => $nonce_str,
            'timestamp'     => $timestamp,
            'url'           => $url
        ];

        $param = "";
        foreach($data as $k=>$v){
            $param .= $k.'='.$v.'&';
        }
        $p = rtrim($param,'&');
        //计算签名
        $signature = sha1($p);
        $response['nonce_str'] = $nonce_str;
        $response['timestamp'] = $timestamp;
        $response['signature'] = $signature;
        $response['url'] = $url;
        echo json_encode($response);
    }
    
    /**
     * 用户列表
     */
    public function user_list()
    {
        $user_info = DB::connection('mysql_cart')->table('user')->get();
        //计算签名
        $jsconfig = [
            'appid' => env('WECHAT_APPID'),        //APPID
            'timestamp' => time(),
            'noncestr'    => time() . rand(111111,999999). 'suibian',
        ];
        $sign = $this->wxJsConfigSign($jsconfig);
        $jsconfig['sign'] = $sign;
        $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        return view('Agent.userList',['user_info'=>$user_info,'url'=>$url,'jsconfig'=>$jsconfig]);
    }

    /**
     * 计算JSSDK sign
     */
    public function wxJsConfigSign($param)
    {
        $current_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];     //当前调用 jsapi的 url
        $ticket = $this->wechat->jsapi_ticket();
        $str =  'jsapi_ticket='.$ticket.'&noncestr='.$param['noncestr']. '&timestamp='. $param['timestamp']. '&url='.$current_url;
        $signature=sha1($str);
        return $signature;
    }

    /**
     * 生成专属二维码
     */
    public function create_qrcode(Request $request)
    {
        //生成带参数的二维码
        $uid = $request->all()['uid']; //用户uid
        //用户uid就是专属推广码
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->wechat->get_access_token();
        $data = [
            'expire_seconds' => 24 * 3600 * 30,
            'action_name' => 'QR_STR_SCENE',
            'action_info' => [
                'scene' => [
                    'scene_str' => $uid
                ]
            ]
        ];
        $re = $this->wechat->post($url,json_encode($data));
        $qrcode_result = json_decode($re);
        //二维码存入larvel
        $qr_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$qrcode_result->ticket;
        $client = new Client();
        $response = $client->get($qr_url);
        //获取文件名
        $h = $response->getHeaders();
        //echo '<pre>';print_r($h);echo '</pre>';die;
        $ext = explode('/',$h['Content-Type'][0])[1];
        $file_name = time().rand(1000,9999).'.'.$ext;
        //$wx_image_path = 'wx/images/'.$file_name;
        //保存图片
        $path = 'qrcode/'.$file_name;
        $re = Storage::disk('local')->put($path, $response->getBody());
        $qrcode_url = env('APP_URL').'/storage/'.$path;
        //存入数据库
        DB::connection('mysql_cart')->table('user')->where(['id'=>$uid])->update([
            'qrcode_url' => $qrcode_url,
            'agent_code' => $uid
        ]);
        //返回二维码链接
        return redirect('agent/user_list');
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
