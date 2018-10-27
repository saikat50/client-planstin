<?php

namespace App\Services;

class HttpService {

	public static function GET($url, $params = null){
		$opts = array(
			'http' => array(
			    'method'  => 'GET',
		    )
		);

		if($params){
			if(strpos($url, '?') === false){
				$url .= '?';
			}
			$url .= http_build_query($params);
		}

		return self::exec($url, $opts);

	}
	public static function POST($url, $data, $headers = array()){
		$default_headers = array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => http_build_query($data),
	    );

	    $headers = array_merge($default_headers, $headers);

		$opts = array(
			'http' => $headers,
		);
		
		$res = self::exec($url, $opts);
		
		return $res;
	}

	public static function CURL($url, $data, $params = []){
		$defaults = [
			'post' => 1,
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

	public static function exec($url, $opts){

		$context  = stream_context_create($opts);

		try{
			$result = file_get_contents($url, false, $context);
			$json = \json_decode($result, 1);
		}catch(\Exception $e){
			return (object)['success' => false, 'message' => $e->getMessage()];
		}
		
		return  (object)['success' => true,'response' => ($json ? $json : $result),'message' => ''];
	}
}