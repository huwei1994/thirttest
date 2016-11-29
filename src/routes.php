<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Controllers Within The "App\Http\Controllers\scms" Namespace

Route::group(['namespace' => 'Scms', 'prefix' => 'scms'], function()
{
    Route::group(['namespace' => 'Admin','prefix' => 'admin'],function()
    {
        Route::get('scms/list','ScmsController@index');//首页访问入口
        Route::post('scms/add','ScmsController@addItem');
        Route::post('scms/del','ScmsController@delItem');
        Route::post('scms/update','ScmsController@update');
        Route::post('scms/getdata','ScmsController@getData');
        Route::post('scms/reset','ScmsController@resetItem');
        Route::post('scms/uploadbigfile','ScmsController@uploadBigFile');
        Route::post('scms/uploadsmallfile','ScmsController@uploadSmallFile');
        //分组相关
        Route::get('category/list','CategoryController@getList');
        Route::post('category/add','CategoryController@add');
        Route::post('category/edit','CategoryController@edit');
        Route::post('category/delete','CategoryController@delete');
    });
    Route::group(['namespace' => 'Api','prefix' => 'api'], function(){
        Route::post('post/list','PostController@getList');
        Route::get('post/index','PostController@index');
    });
});



// Controllers Within The "App\Http\Controllers\Lrts" Namespace
Route::group(['namespace' => 'Lrts', 'prefix' => 'lrts'], function()
{
    Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function()
    {
        Route::get('/manager/list','ManagerController@getList');
        Route::post('/manager/add','ManagerController@add');
        Route::post('/manager/edit','ManagerController@edit');
        Route::post('/manager/delete','ManagerController@delete');
    });
});