<?php 
namespace App\Http\Controllers;

class Login extends Controller {

    public function initialize(){
        $this->includes->body = 'includes.body-login';
        $this->includes->footer = 'includes.footer-login';
    }
    public function home(){
        $this->view->errorMessage = session('error');
        $this->view->successMessage = session('success');
        return $this->view('login.login');
    }
    public function logout(){
        session()->forget('token');
        return redirect('/login')->with(['success' => 'Logout successful']);
    }
}