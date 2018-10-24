<?php 
namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as LaravelController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

//custom
use Illuminate\Http\Request;
use App\App;

class MvcController extends LaravelController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $template;
    public $request;

    public function receive(Request $request, $controller = null, $method = null, $id = null, $uri = ''){
        $this->request = $request;
    	
        $this->route = (object) [
            'controller' => ucfirst($controller ?: 'main'),
            'method' => $method ?: 'home',
            'id' => $id,
            'segments' => array_filter(explode('/', $uri)),
            'uri' => $uri,
        ];

        return $this->makeResponse($this->route->controller . '.' . $this->route->method);
    }
    
    public function makeResponse(){
        try {
            $controllerClass = __NAMESPACE__ . '\\' . $this->route->controller;

            if( !class_exists( $controllerClass ) ){
                return $this->error('Controller {'. $this->route->controller .'} not found.');
            }
            if(  !method_exists($controllerClass, $this->route->method) ){
                return $this->error('{'. $this->route->controller .'} Controller has no method {'. $this->route->method .'}');
            }

            $controller = new $controllerClass($this);
            return $controller->{$this->route->method}($this->route->id, $this->route->segments);

        }catch(\Exception $e){
            abort(404);
        }
    }


}