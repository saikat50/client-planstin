<?php 
namespace App\Http\Controllers;

class Main extends Controller {

    public function initialize(){

    }
    public function home(){
        return $this->view('main.home');
    }
}