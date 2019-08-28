<?php

namespace App\Http\Controllers;

use App\Http\Tools\Wechat;
use Illuminate\Http\Request;
use EasyWeChat\Kernel\Messages\Text;
use DB;

class BiaoBaiController extends Controller
{
    public $wechat;
    public $redis;
    public function __construct(Wechat $wechat)
    {
        $this->wechat = $wechat;
        $this->redis = new \Redis();
        $this->redis->connect('39.105.95.102','6379');
    }
    public function index(Request $request)
    {
        /*$uid = $request->session()->get('uid');
        echo $uid.'<br/>';*/
        $openid_list = $this->wechat->app->user->list($nextOpenId = null);
//        $openid_info  = $openid_list['data']['openid'];
//        dd($openid_info);
        return view('BiaoBai.index',['info'=>$openid_list['data']['openid']]);
    }

    public function notify_url()
    {
        
    }

    public function send(Request $request)
    {
        return view('BiaoBai.send',['openid'=>$request->all()['openid']]);
    }

    public function do_send(Request $request)
    {
        $req = $request->all();
        //$uid = $request->session()->get('uid');
        $openid = 'otAUQ1XOd-dph7qQ_fDyDJqkUj90';
        $user = $this->wechat->app->user->get($openid);
        //模板消息
        $this->wechat->app->template_message->send([
            'touser' => $req['openid'],
            'template_id' => 'hy-ju5jnMvV0PWVvJ4LMlg1ky_WQ91DtOrNYRQpfoq0',
            'url' => env('APP_URL').'/biaobai/index',
            'data' => [
                'first' => $req['user_type'] == 2?'匿名用户':$user['nickname'],
                'keyword1' => $req['content'],
            ],
        ]);
        //入库
        $result = DB::connection('mysql_cart')->table('biaobai')->insert([
            'from_user'=>$openid,
            'content'=>$req['content'],
            'to_user'=>$req['openid'],
            'add_time'=>time()
        ]);
    }
}
