<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get("/","MailController@showMails");
Route::get("/printable","MailController@showPrintableMails");
Route::get("/login","AuthController@showLogin");
Route::post("/login",array("before"=>"csrf","uses"=>"AuthController@processLogin"));

Route::group(array("before"=>"auth"),function()
{
	Route::get("/logout","AuthController@logout");
	Route::get("/admin","MailController@showAdmin");
	Route::get("/ajax/get","AjaxController@getUsers");
	Route::post("/ajax/add","AjaxController@addMail");
	Route::post("/ajax/delete","AjaxController@deleteMail");
});