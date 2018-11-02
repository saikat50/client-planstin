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


Route::get('/login', function(){

    return view('login.login');

});

Route::get('/forgot-password', function (){
    return view('login.forgot-password');
});

Route::get('/recovery', function (){
    return view('login.recovery-code');
});

Route::get('/register', function (){
    return view('login.register');
});




Route::get('{controller?}/{method?}/{id?}', 'MvcController@receive');

