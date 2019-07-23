<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $req = $request->all();
        //链接redis
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        //if($redis->exists('ticket_info')){}
        //判断redis里面有没有ticket_key
        if(!$redis->get('ticket_info')){
            //判断搜索条件是否存在
            if(!empty($req['start_place']) ||  !empty($req['end_place'])){
                //记录搜索次数
                $redis->incr('ticket_num');
                $list = \DB::connection('mysql_cart')->table('plan')
                    ->where('place','like',"%{$req['start_place']}%")
                    ->where('ofplace','like',"%{$req['end_place']}%")
                    ->get();

            }else{
                //没有搜索条件返回全部数据
                $list = \DB::connection('mysql_cart')->table('plan')->get();
            }
            //redis获取访问次数
            $ticket_num = $redis->get('ticket_num');
            //判断访问次数
            if($ticket_num > 5){
                $redis_info = json_encode($list);
                $redis->set('ticket_info',$redis_info,3 * 60);
            }
            //echo "<pre>";
            $list = json_decode(json_encode($list),1);

        }else{
            $list = json_decode($redis->get('ticket_info'),1);
            //echo "<pre>";

        }

        echo "访问次数:".$redis->get('ticket_num');



        return view('Ticket.index',['start_place'=>$req['start_place'],'end_place'=>$req['end_place'],'list'=>$list]);
    }
}
