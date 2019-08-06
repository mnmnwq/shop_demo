<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Tools\Wechat;
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
     * 推送模板消息
     */
    public function push_template()
    {
        $openid = 'otAUQ1XOd-dph7qQ_fDyDJqkUj90';
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.$this->wechat->get_access_token();
        $data = [
            'touser'=>$openid,
            'template_id'=>'erO8fzlj0BXY_71coD9DqnJxNhPj6Qx2zOO1YBp-gmI',
            'url'=>'http://www.baidu.com',
            'data' => [
                'first' => [
                    'value' => '商品名称',
                    'color' => ''
                ],
                'keyword1' => [
                    'value' => '低价',
                    'color' => ''
                ],
                'keyword2' => [
                    'value' => '是低价',
                    'color' => ''
                ],
                'remark' => [
                    'value' => '备注',
                    'color' => ''
                ]
            ]
        ];
        $re = $this->wechat->post($url,json_encode($data));
        dd($re);
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
        $client = new Client();
        if($request->hasFile('image')){
            $path = $request->file('image')->store('wechat/image');
            $path='./storage/'.$path;
            $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token=' . $this->wechat->get_access_token().'&type=image';
            $response = $client->request('POST',$url,[
                'multipart' => [
                    [
                        'name'     => 'media',
                        'contents' => fopen(realpath($path), 'r')
                    ],
                ]
            ]);
            $body = $response->getBody();
            unlink($path);
            echo $body;
           dd();
        }elseif($request->hasFile('voice')){
            //保存文件
            $img_file = $request->file('voice');
            $file_ext = $img_file->getClientOriginalExtension();          //获取文件扩展名
            //重命名
            $new_file_name = time().rand(1000,9999). '.'.$file_ext;
            //文件保存路径
            //保存文件
            $save_file_path = $img_file->storeAs('wechat/voice',$new_file_name);       //返回保存成功之后的文件路径
            $path = './storage/'.$save_file_path;
            $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->wechat->get_access_token().'&type=voice';
            $response = $client->request('POST',$url,[
                'multipart' => [
                    [
                        'name'     => 'media',
                        'contents' => fopen(realpath($path), 'r')
                    ],
                ]
            ]);
            $body = $response->getBody();
            unlink(realpath($path));
            echo $body;
            dd();
        }elseif($request->hasFile('video')){
            //保存文件
            $img_file = $request->file('video');
            $file_ext = $img_file->getClientOriginalExtension();          //获取文件扩展名
            //重命名
            $new_file_name = time().rand(1000,9999). '.'.$file_ext;
            //文件保存路径
            //保存文件
            $save_file_path = $img_file->storeAs('wechat/video',$new_file_name);       //返回保存成功之后的文件路径
            $path = './storage/'.$save_file_path;
            $url='https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$this->wechat->get_access_token().'&type=video';
            $response = $client->request('POST',$url,[
                'multipart' => [
                    [
                        'name'     => 'media',
                        'contents' => fopen(realpath($path), 'r')
                    ],
                ]
            ]);
            $body = $response->getBody();
            unlink(realpath($path));
            echo $body;
            dd();
        }elseif($request->hasFile('thumb')){
            $path = $request->file('thumb')->store('wechat/thumb');
        }

    }

    public function login(){
        $redirect_uri = 'http://www.shopdemo.com/wechat/code';
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.env('WECHAT_APPID').'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect ';
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
        $wechat_user_info = $this->wechat->wechat_user_info($openid);
        //去user_openid 表查 是否有数据 openid = $openid
        $user_openid = DB::connection('mysql_cart')->table("user_wechat")->where(['openid'=>$openid])->first();
        if(!empty($user_openid)){
            //有数据 在网站有用户 user表有数据[ 登陆 ]
            $user_info = DB::connection('mysql_cart')->table("user")->where(['id'=>$user_openid->uid])->first();
            $request->session()->put('username',$user_info['name']);
        }else{
            //没有数据 注册信息  insert user  user_openid   生成新用户
            DB::connection("mysql_cart")->beginTransaction();
            $user_result = DB::connection('mysql_cart')->table('user')->insertGetId([
                'password' => '',
                'name' => $wechat_user_info['nickname'],
                'reg_time' => time()
            ]);
            $openid_result = DB::connection('mysql_cart')->table('user_wechat')->insert([
                'uid'=>$user_result,
                'openid' => $openid,
            ]);
            DB::connection('mysql_cart')->commit();
        }


    }

    public function get_user_info()
    {
        $openid = DB::connection('mysql_cart')->table('wechat_openid')->where(['id'=>$this->request->all()['id']])->value('openid');;
        $user_info = $this->wechat->wechat_user_info($openid);
        dd($user_info);
    }

    public function wechat_user_info($openid){
        $access_token = $this->wechat->get_access_token();
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
        $access_token = $this->wechat->get_access_token();
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
                $access_token = $this->wechat->get_access_token();
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

}
