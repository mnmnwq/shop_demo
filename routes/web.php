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
Route::get('/wechat/code','WechatController@code');
Route::get('/wechat/get_user_info','WechatController@get_user_info');
Route::get('/wechat/get_user_list','WechatController@get_user_list');

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

