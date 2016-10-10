<?php

namespace Yeo\Core;
use Yeo\Core\YeoDb;
/**
 * Todo database framework
 * Version 1.0.0
 * Copyright 2016.09.29, Yeoman-chen
 * 
 */
class YeoCore
{
	public $yeoDb;
	public function __construct()
	{

	}
	/**
	 * 启动应用
	 */
	public function run()
	{
		$pathinfo = $this->route();
		$controller = 'Yeo\Controller\\'.ucfirst($pathinfo['controller']).'Controller';
		$actionName = ucfirst($pathinfo['action']).'Action';

		if(method_exists($controller, $actionName)){

			try {
	                $ycf = new $controller();
	                $ycf->$actionName();
	        } catch (Exception $e) {
	                var_dump($e);
	            }
	        //$this->yeoDb = YeoDb::getInstance($array = []);
        } else {
            echo ("action not find");
        }
		
		//print_r($pathinfo);
	}
	/**
	 * 路由解析
	 */
	public function route()
	{
		$route = ['controller' => 'index','action' => 'index'];
		if(!empty($_GET['yeo'])){
			$route['controller'] = $_GET['yeo'];
		}
		if(!empty($_GET['act'])){
			$route['action'] = $_GET['act'];
		}
		$uri = parse_url($_SERVER['REQUEST_URI']);
		if(empty($uri['path']) || $uri['path'] == '/' || $uri['path'] == '/index.php'){
			return $route;
		}
		$pathinfo = ltrim($uri['path'],'/');

		$pathinfo = explode('/', $pathinfo);
		$pathinfo[0] && $route['controller'] = array_shift($pathinfo);
		$pathinfo[0] && $route['action'] = array_shift($pathinfo);

		return $route;
	}
} 