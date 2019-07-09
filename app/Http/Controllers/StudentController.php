<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class StudentController extends Controller
{
    public function index(Request $request){
    	$req = $request->all();
    	$search = "";
    	if(!empty($req['search'])){
    		$search = $req['search'];
    		$info = DB::table("student")->where('name','like','%'.$req['search'].'%')->paginate(2);
    	}else{
    		$info = DB::table("student")->paginate(2);
    	}

    	return view('studentList',['student'=>$info,'search'=>$search]);
    }

    public function update(Request $request){
    	$req = $request->all();
    	$info = DB::table('student')->where(['id'=>$req['id']])->first();
    	return view('studentUpdate',['student_info'=>$info]);
    }

    public function do_update(Request $request){
    	$req = $request->all();
    	$result = DB::table('student')->where(['id'=>$req['id']])->update([
    		'name'=>$req['name']
    	]);
    	dd($result);
    }

    public function delete(Request $request){
    	$req = $request->all();
    	$result = DB::table("student")->where(['id'=>$req['id']])->delete();
    	dd($result);
    }

    /**
     * 添加学生信息,进入页面
     */
    public function add(){
    	return view('studentAdd',[]);
    }

    /**
     * 添加学生信息，处理数据
     */
    public function do_add(Request $request){
    	 $validatedData = $request->validate([
        	'name' => 'required'
    	],[
    		'name.required'=>'字段必填'
    	]);
    	$req = $request->all();
    	dd($req);
    	$result  = true;
    	if($result){
    		return redirect('/student/index');
    	}else{
    		echo "fail";
    	}

    }
}
