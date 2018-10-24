<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//minifier
use MatthiasMullie\Minify;

//custom
use Illuminate\Http\Request;
use App\App;

class Controller extends LaravelController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $template;
    public $request;

    public function __construct($mvc){
        $this->mvc = $mvc;
        $this->request = $mvc->request;
        $this->route = $mvc->route;

    	App::setRequest($mvc->request);
        App::setController($this);

        $this->setupParameters();
        $this->setupView();

        $this->initialize();
    }
    
    public function initialize(){ }

    public function setupParameters(){
        $this->params = (object) ($this->request->route() !== null ? $this->request->route()->parameters() : []);

        $all = $this->request->all();

        $get = (array) $_GET;
        $post = (array) $_POST;

        foreach($all as $key => $val){
            if($this->request->isMethod('post') && !isset($get[$key])){
                $post[$key] = $val;
            }
        }

        $this->get = (object) $get;
        $this->post = (object) $post;
    }
    public function setupView(){
        $this->view = (object) [];
    }
    public function view($path, $args = []){
        return view($path, $this->makeView($args));
    }
    public function makeView($args = []){
        return array_merge( ['template' => $this->template, 'controller' => $this], (array) $this->view, $args);
    }

}
