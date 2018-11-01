<?php 
namespace App\Http\Controllers;

class Login extends Controller {

    public function initialize(){

        $this->view->errorMessage = session('error');
        $this->view->successMessage = session('success');

        $this->includes->body = 'includes.body-login';
        $this->includes->footer = 'includes.footer-login';
    }
    public function member(){
        if(!session('token') || !session('user.level')){
            session()->put('user.level', 'member');
        }
        $this->authorizeToken();
        return $this->view('login.member');
    }
    public function account(){
        if(!session('token') || !session('user.level')){
            session()->put('user.level', 'account');
        }
        $this->authorizeToken();
        return $this->view('login.account');
    }
    public function logout(){
        session()->forget('token');
        return \App\App::redirectToLogin()->with(['success' => 'Logout successful']);
    }

}