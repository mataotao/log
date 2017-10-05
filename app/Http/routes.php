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
Route::get('/cstock', ['uses'=>'CronController@stock','as'=>'cron.stock']);
Route::get('/viewLog', ['uses'=>'IndexController@viewLog','as'=>'home.viewLog']);
/**
 * 日志
 */
Route::group(['prefix' => "logView",'middleware' => []], function()
{
    Route::get('/index', ['uses'=>'LogViewController@index','as'=>'log.index']);
    Route::get('/view', ['uses'=>'LogViewController@view','as'=>'log.view']);
    Route::get('/load', ['uses'=>'LogViewController@load','as'=>'log.load']);
    Route::get('/search', ['uses'=>'LogViewController@search','as'=>'log.search']);
    Route::get('/listenLog', ['uses'=>'LogViewController@listenLog','as'=>'log.listenLog']);
    
});



Route::get('', function () {
    return redirect()->action('LogViewController@index');
});


