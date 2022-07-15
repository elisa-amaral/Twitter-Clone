<?php

namespace Miniframework\Init;

abstract class Bootstrap 
{

	private $routes;

	abstract protected function initRoutes(); 

	public function getRoutes() 
	{
		return $this->routes;
	}

	public function setRoutes(array $routes) 
	{
	    $this->routes = $routes;
	}
	protected function getURL() 
	{
		return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); 
	}

	public function __construct() 
	{
	    $this->initRoutes();
	    $this->run($this->getURL());
	}

	    protected function run($url) 
		{ 

	    foreach($this->getRoutes() as $routes => $route) 
		{
	    	if($url == $route['route']) 
			{
				$class = "App\\Controllers\\".ucfirst($route['controller']);
				$controller = new $class;
				$action = $route['action']; 
				$controller->$action();

	    	}
	    }
    }
}

?>