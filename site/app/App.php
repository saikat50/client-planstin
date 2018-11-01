<?php

namespace App;

use Illuminate\Foundation\Application as IlluminateFoundationApplication;
use App\Exceptions\SalesforceException;

class App extends IlluminateFoundationApplication{
	private static $request;
	private static $controller;

	private static $salesforce;

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

	public static function authorizeAndSetToken(){
		session()->forget([
			'token',
			'salesforce',
		]);

		$token = static::getSessionToken('salesforce');

		if(!$token){
			throw new \Exception('Invalid Site Credentials. Please contact support.');
		}

		$salesforce = static::salesforce($token);

		if(!$salesforce->isAuthorized()){
			throw new \Exception('Invalid Site Credentials.');
		}

		try {
			$resources = $salesforce->getResources();
		}catch(SalesforceException $e){
			$refreshToken = $salesforce->refreshAccessToken($token->refresh);
			$token->setAndSave(['token' => $refreshToken['access_token']]);
		}

		session()->put(['token' => $token, 'salesforce.resources' => $resources]);
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

	public static function checkSessionToken(){
		$token = session('token');
		if(!$token){
			return false;
		}

		$latest = static::getSessionToken($token->type);

		if( strtotime($latest->updated_at) > strtotime($token->updated_at) ){
			return false;
		}

		return true;
	}

	public static function salesforce($token = null){
		if(static::$salesforce){
			return static::$salesforce;
		}
		return new \App\Services\Salesforce( $token ?: session('token') );
	}

	public static function redirectToLogin(){
        return redirect('/login/' . (session('user.level') ?: 'member') );
	}
	
	public static function redirectToDashboard(){
		return redirect(('/' . session('user.level') ?: 'member') . '/dashboard');
	}
	
}