<?php 
namespace App\Http\Controllers;

class Account extends Controller {
    public function initialize(){
        $this->authorizeToken();
    }

    public function dashboard(){
        $salesforce = \App\App::salesforce();
        $this->view->user = $salesforce->getUser() ?: ['first_name' => '', 'last_name' => '', 'email' => ''];

        return $this->view('account.dashboard');
    }
}