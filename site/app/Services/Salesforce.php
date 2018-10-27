<?php 
namespace App\Services;

class Salesforce { 
    var $client_id;
    var $client_secret;
    var $redirect_uri;
    var $endpoint;
    var $env;

    var $debug = false;

    public function __construct(){
        $this->env = \Config::get('app.salesforce.env');

        $this->client_id = \Config::get('app.salesforce.client_id');
        $this->client_secret = \Config::get('app.salesforce.client_secret');
        $this->redirect_uri = \Config::get('app.salesforce.redirect_uri');
        $this->endpoint = \Config::get("app.salesforce.endpoints.{$this->env}");
    }
    public function getAuthUrl(){
        /**
         * https://login.salesforce.com/services/oauth2/authorize
         *      ?response_type=code
         *      &client_id=3MVG9lKcPoNINVBIPJjdw1J9LLM82HnFVVX19KY1uA5mu0QqEWhqKpoW3svG3XHrXDiCQjK1mdgAvhCscA9GE
         *      &redirect_uri=https%3A%2F%2Fwww.mysite.com%2Fcode_callback.jsp&state=mystate
         */

        return $this->auth_usrl = "{$this->endpoint}/services/oauth2/authorize".
            "?response_type=code".
            "&client_id={$this->client_id}".
            "&redirect_uri=". urlencode($this->redirect_uri);
    }

    public function requestAccessToken($code){
        /**
         *  POST /services/oauth2/token HTTP/1.1
         *   Host: login.salesforce.com 
         *   grant_type=authorization_code
         *   &code=aPrxsmIEeqM9PiQroGEWx1UiMQd95_5JUZVEhsOFhS8EVvbfYBBJli2W5fn3zbo.8hojaNW_1g%3D%3D
         *   &client_id=3MVG9lKcPoNINVBIPJjdw1J9LLM82HnFVVX19KY1uA5mu0QqEWhqKpoW3svG3XHrXDiCQjK1mdgAvhCscA9GE
         *   &client_secret=1955279925675241571&
         *   redirect_uri=https%3A%2F%2Fwww.mysite.com%2Fcode_callback.jsp
         */

        $post = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirect_uri,
        ];
        $headers = [
            'options' => [
                CURLOPT_HTTPHEADER => [
                    'Content-type: application/x-www-form-urlencoded',
                    'Authorization: Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
                ],
            ],
        ];

        list($response, $http_code) =  HttpService::CURL("{$this->endpoint}/services/oauth2/token", $post, $headers);
        return $response;
    }
}