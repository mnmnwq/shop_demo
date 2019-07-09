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
//浏览学生信息
Route::get('/student/index', 'StudentController@index');
//添加学生信息
Route::get('/student/add','StudentController@add');  
//
Route::post('/student/do_add','StudentController@do_add');
//修改
Route::get('/student/update','StudentController@update');

Route::post('/student/do_update','StudentController@do_update');
//删除
Route::get('/student/delete','StudentController@delete');

