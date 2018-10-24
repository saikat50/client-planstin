<?php

namespace App;

use Illuminate\Foundation\Application as IlluminateFoundationApplication;

class App extends IlluminateFoundationApplication{
	private static $request;
	private static $controller;

	public function publicPath(){
        return $_SERVER['DOCUMENT_ROOT'];
	}
	public static function getDb($connection = null){
		return \Illuminate\Database\Capsule\Manager::connection($connection);
	}
	public static function setRequest($request){
		self::$request = $request;
	}
	public static function setController($controller){
		self::$controller = $controller;
	}
	public static function request(){
		return self::$request;
	}
	public static function controller(){
		return self::$controller;
	}
	public static function notFoundClass(){
		$namespace = "\\" . \Config::get('controller.service_provider')->getNamespace();
		$controller_action = \Config::get('controller.404');
		if($namespace && $controller_action){
			return explode('@', $namespace . "\\" . $controller_action);
		}
	}
}