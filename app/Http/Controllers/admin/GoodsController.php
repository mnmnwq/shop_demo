<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GoodsController extends Controller
{
    public function add_goods(){
        
        return view('admin.add_goods');
    }

    public function do_add_goods(Request $request){
        $files = $request->file('goods_pic');
        $path = '';
        if(empty($files)){
            //未传图片
            echo "fail";
        }else{
            //已传图片
            $path = $files->store('goods');
        }
        dd($path);
        echo 'storage'.'/'.$path;
    }
}
