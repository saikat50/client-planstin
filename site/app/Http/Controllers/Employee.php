<?php 
namespace App\Http\Controllers;

class Employee extends Controller {
    public function initialize(){
        $this->authorize();
    }

    public function dashboard(){
        $salesforce = new \App\Services\Salesforce(session('token'));

        $this->view->user = $salesforce->getUser();

        return $this->view('employee.dashboard');
    }
}