<?php 
namespace App\Http\Controllers;

class Employee extends Controller {
    public function initialize(){
    
    }

    public function dashboard(){
        return $this->view('employee.dashboard');
    }
}