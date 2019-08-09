<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

////////////////////////////////////////////////////////////////////////////////////////////
Route::any('/wechat/event','WechatController@event'); //接收公众号事件
//用户标签相关
Route::get('/wechat/update_tag','WechatController@update_tag'); //修改标签
Route::post('/wechat/do_update_tag','WechatController@do_update_tag'); //执行修改标签
Route::get('/wechat/tag_list','WechatController@tag_list'); //标签列表
Route::get('/wechat/add_tag','WechatController@add_tag'); //添加标签
Route::get('/wechat/do_add_tag','WechatController@do_add_tag'); //执行添加标签
Route::post('/wechat/add_user_tag','WechatController@add_user_tag'); //为用户打标签
Route::get('/wechat/del_tag','WechatController@del_tag'); //删除标签
Route::get('/wechat/tag_user','WechatController@tag_user'); //标签下用户列表
Route::get('/wechat/get_user_tag','WechatController@get_user_tag'); //获取用户标签
Route::get('/wechat/del_user_tag','WechatController@del_user_tag'); //删除用户标签
Route::get('/wechat/push_tag_message','WechatController@push_tag_message'); //根据标签推送消息
Route::post('/wechat/do_push_tag_message','WechatController@do_push_tag_message'); //执行根据标签推送消息

Route::get('/wechat/code','WechatController@code');
Route::get('/wechat/login','WechatController@login');

Route::get('/wechat/template_list','WechatController@template_list');
Route::get('/wechat/del_template','WechatController@del_template');
Route::get('/wechat/push_template','WechatController@push_template');

//上传素材
Route::get('/wechat/upload_source','WechatController@upload_source');
Route::get('/wechat/get_source','WechatController@get_source');
Route::get('/wechat/get_video_source','WechatController@get_video_source');
Route::get('/wechat/get_voice_source','WechatController@get_voice_source');
Route::post('wechat/do_upload','WechatController@do_upload');

//第一周作业
Route::get('/wechat/get_user_info','WechatController@get_user_info');
Route::get('/wechat/get_user_list','WechatController@get_user_list');
Route::get('/wechat/user_list','WechatController@user_list');

/// //////////////////////////////////////////////////////////////////////////////////////////

//试题
Route::get('question/add','QuestionController@add');
Route::post('question/do_add','QuestionController@do_add');
Route::get('question/list','QuestionController@index');
Route::get('question/add_papers','QuestionController@add_papers');
Route::post('question/do_add_papers','QuestionController@do_add_papers');
Route::post('question/insert_papers','QuestionController@insert_papers');
Route::get('question/test_list','QuestionController@test_list');
Route::get('question/test_detail','QuestionController@test_detail');
//火车票
Route::get('ticket/index','TicketController@index');

//车库管理
Route::get("cart/index",'CartController@index');
Route::get("cart/add_cart",'CArtController@add_cart');
Route::post("cart/do_add_cart",'CArtController@do_add_cart');
Route::get("cart/del_cart",'CArtController@del_cart');
Route::post("cart/do_del_cart",'CArtController@do_del_cart');
Route::get("cart/del_price",'CArtController@del_price');


Route::get('pay','PayController@do_pay');

Route::get('return_url','PayController@return_url'); //同步
Route::post('notify_url','PayController@notify_url'); //异步

Route::get('/admin/add_goods','admin\GoodsController@add_goods');
Route::post('/admin/do_add_goods','admin\GoodsController@do_add_goods');
Route::get('/admin','admin\indexController@index');

Route::get('/student/login','StudentController@login');
Route::post('/student/do_login','StudentController@do_login');

Route::get('/liuyan/index','LiuYanController@index');
Route::get('/liuyan/do_del','LiuYanController@do_del'); //删除留言

Route::post('liuyan_info','LiuYanController@info');

//浏览学生信息
Route::get('/student/index', 'StudentController@index');

//
Route::post('/student/do_add','StudentController@do_add');
//修改
//Route::get('/student/update','StudentController@update');

Route::post('/student/do_update','StudentController@do_update');
//删除
Route::get('/student/delete','StudentController@delete');



//调用中间件
Route::group(['middleware' => ['login']], function () {
    //添加学生信息
    Route::get('/student/add','StudentController@add');     
});


Route::group(['middleware'=>['update']],function(){
    Route::get('/student/update','StudentController@update');
});

