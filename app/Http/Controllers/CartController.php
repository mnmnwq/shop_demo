<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class CartController extends Controller
{
    public $request;
    protected $redis;
    protected $db;
    protected $cart_total_key; //车位总数key
    protected $cart_use_key;  //已使用车位key
    public function __construct(Request $request){
        $this->request = $request;
        $this->redis = $this->get_redis();
        $this->db = DB::connection('mysql_cart');
        $this->cart_use_key = 'cart:cart_use_key';
    }

    public function get_redis(){
        $redis = new \Redis();
        $redis->connect('127.0.0.1','6379');
        return $redis;
    }

    public function index(){
        $cart_use_num = $this->redis->get($this->cart_use_key);
        if(empty($cart_use_num)){
            $cart_use_num = 0;
        }
        $cart_left_num = 400 - $cart_use_num;
        return view('cart.index',['cart_left_num'=>$cart_left_num]);
    }

    public function add_cart(){
        return view("cart.addCart");
    }

    public function do_add_cart(){
        $req = $this->request->all();
        $re = $this->db->table('cart')->where(['cart_num'=>$req['cart_num'],'state'=>1])->value('cart_num');
        if(!empty($re)){
            echo "车辆已入库";die();
        }
        $result = $this->db->table('cart')->insert([
            'cart_num'=>$req['cart_num'],
            'add_time'=>time(),
            'state'=>1
        ]);
        if(!$result){
            echo "操作失败!";die();
        }else{
            $this->redis->incr($this->cart_use_key);
            return redirect("cart/index");
        }
        
    }

    public function del_cart(){
        return view('cart.delCart');
    }

    public function do_del_cart(){
        $req = $this->request->all();
        $re = $this->db->table('cart')->where(['cart_num'=>$req['cart_num'],'state'=>1])->select(['id','add_time'])->first();
        if(empty($re)){
            echo "车辆不存在";die();
        }
        $stop_time = time() - $re->add_time; //停车时间间隔
        $pay_amount = 0;  //计费
        //dd($stop_time);
        if($stop_time < 15 * 60){
            $pay_amount = 0;
        }else if($stop_time >= 15 * 60 && $stop_time <= 6 * 3600){
            $pay_amount = ceil($stop_time/1800) * 2;
        }else{
            $pay_amount = 12 * 2;
            $pay_amount += ceil(($stop_time-6*3600)/3600) * 1;
        }
        if($stop_time >= 3600){
            $time_info = floor($stop_time / 3600).'时'.floor(($stop_time % 3600)/60).'分';
        }else{
            $time_info ='0时'.floor($stop_time/60).'分';
        }
        //echo date('Y-m-d H:i',$re->add_time);
        //dd($time_info);
        
        //dd((int)$pay_amount);
        return view('cart.cartPrice',['pay_amount'=>(int)$pay_amount,'cart_num'=>$req['cart_num'],'time_info'=>$time_info,'cart_id'=>$re->id]);
    }

    public function del_price(){
        $req = $this->request->all();
        $cart_use_num = $this->redis->get($this->cart_use_key);
        if(empty($cart_use_num)){
            $cart_use_num = 0;
        }
        if($cart_use_num == 0){
            echo "信息错误";
        }
        $result = $this->db->table('cart')->where(['id'=>$req['id']])->update([
            'state'=>2,
            'del_time'=>time(),
            'price'=>$req['price']
        ]);
        if(!$result){
            echo "操作失败!";die();
        }else{

            $this->redis->set($this->cart_use_key,$cart_use_num - 1);
            return redirect("cart/index");
        }
        
    }
}
