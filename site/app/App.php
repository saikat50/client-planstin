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


	public static function getSessionToken($type){
		return \App\Models\Token::where([
				'type' => $type,
				'env' => \Config::get('app.env')
			])
			->orderBy('updated_at', 'desc')
			->orderBy('created_at', 'dec')
			->first();
	}
	public static function setSessionToken($type, $token = null){
		
		if(is_object($token) && !is_subclass_of($token, '\App\Models\Token')){
			$token = (array) $token;
		}

		if(is_array($token)){
			$existing = static::getSessionToken($type);

			
			if($existing){
				$token = $existing->setForCreate([
					'type' => $type,
					'data' => $token,
				]);
				$token->save();

			}else{
				list($token, $message) = \App\Models\Token::create([
					'type' => $type,
					'data' => $token,
				]);

				if(!$token){
					throw new \Exception($message);
				}
			}
		}

		if(!$token){		
			$token = static::getSessionToken($type);
		}


		if($token && is_subclass_of($token, '\App\Models\BaseModel')){
			session(['token' => $token]);
		}else{
			throw new \Exception('Session token not found');
		}

		return $token;
	}

	public static function checkSessionToken($replace = false){
		$token = session('token');
		if(!$token){
			return false;
		}

		$latest = static::getSessionToken($token->type);

		if( strtotime($latest->updated_at) > strtotime($token->updated_at) && !$replace){
			return false;
		}else if($latest){
			session(['token' => $token]);
		}

		return true;
	}
}