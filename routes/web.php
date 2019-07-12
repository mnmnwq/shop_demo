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
Route::get('/admin/add_goods','admin\GoodsController@add_goods');
Route::post('/admin/do_add_goods','admin\GoodsController@do_add_goods');
Route::get('/admin','admin\indexController@index');

Route::get('/student/login','StudentController@login');
Route::post('/student/do_login','StudentController@do_login');
//浏览学生信息
Route::get('/student/index', 'StudentController@index');

//
Route::post('/student/do_add','StudentController@do_add');
//修改
Route::get('/student/update','StudentController@update');

Route::post('/student/do_update','StudentController@do_update');
//删除
Route::get('/student/delete','StudentController@delete');

//调用中间件
Route::group(['middleware' => ['login']], function () {
    //添加学生信息
    Route::get('/student/add','StudentController@add');     
});

