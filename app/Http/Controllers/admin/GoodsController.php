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
        $path = $request->file('goods_pic')->store('goods');
        echo asset('storage').'/'.$path;
    }
}
