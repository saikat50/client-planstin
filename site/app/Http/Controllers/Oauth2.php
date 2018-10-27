<?php 
namespace App\Http\Controllers;

use App\Services\Salesforce;

class Oauth2 extends Controller {

    public function request(){
        $salesforce = new Salesforce;

        header('Location: ' . $salesforce->getAuthUrl());
        die();
    }
    public function success(){
        if(!$this->request->has('code')){
           return $this->error('No Code Parameter');
        }
        
        $salesforce = new Salesforce;

        $token = $salesforce->requestAccessToken( $this->request->get('code') );
        
        if(!empty($token['access_token'])){
            session(['token' => $token]);
            return redirect('/employee/dashboard')->with(['success' => 'Successfully logged in']);
        }

        return redirect('/login')->with(['error' => 'Error authenticating: ' . $token['error_description']]);
    }
}