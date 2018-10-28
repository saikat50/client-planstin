<?php

namespace App\Services;

class HttpService {

	public static function GET($url, $data = [], $params = []){
		$params = array_merge(['post' => 0], $params);
		if($data){
			if(strpos($url, '?') === false){
				$url .= '?';
			}
			$url .= http_build_query($params);
		}

		return self::curl($url, $data, $params);
	}
	public static function post($url, $data, $params = []){	
		$params = array_merge(['post' => 1], $params);
		return self::curl($url, $data, $params);
	}

	public static function curl($url, $data, $params = []){
		$defaults = [
			'post' => 0, //receive from self::post method
			'return' => 1,
			'user_agent' => false, //"Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13",
			'timeout' => 20,
			'options' => [
				CURLOPT_HTTPHEADER => [
					'Content-type: application/x-www-form-urlencoded',
				],
			],
		];
		$params = array_merge($defaults, $params);

		// dd($url, $params);
		
		$ch = curl_init();

	    // set url
	    curl_setopt($ch, CURLOPT_URL, $url);

	    //return the transfer as a string
	    curl_setopt($ch, CURLOPT_POST, $params['post']);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, $params['return']);
	    curl_setopt($ch,CURLOPT_TIMEOUT, $params['timeout']);

	    if($params['user_agent']){
	    	curl_setopt($ch,CURLOPT_USERAGENT, $params['user_agent']);
	    }

	    if($params['post']){
	    	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		
		if(!empty($params['options'])){
			curl_setopt_array($ch, $params['options']);
		}
	    

	    // $output contains the output string
	    $output = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

	    curl_close($ch);
	    return [@json_decode($output, 1) ?: $output, $http_code];
	}


}