<?php

namespace Sugaradmin\Controller;

use Think\Controller;

class BaseController extends Controller {
	// 允许访问的控制器
	public $allow_controller = array (
			'/Sugaradmin/Login' 
	);
	// 允许访问控制器的方法
	public $allow_action = array (
			'/Sugaradmin/Login/index' 
	);
	public $manager_id = 0;
	public $jseon_result = array (
			'error' => 0,
			'title' => 'info',
			'msg' => 'OK',
			'data' => array () 
	);
	
	/**
	 * 通用初始化方法
	 */
	public function _initialize() {
		$this->_before_initialize();
		
		//合并传递参数
		$_GET = array_merge($_GET, $_POST);
		
		// 判断是否需要校验登录的控制器或者具体的方法
		if (! in_array ( __CONTROLLER__, $this->allow_controller ) && ! in_array ( __ACTION__, $this->allow_action )) {
			$this->checkLogin ();
		}
		
		$this->_after_initialize();
	}
	
	/**
	 * 初始化前执行
	 */
	public function _before_initialize(){}
	
	/**
	 * 初始化后执行
	 */
	public function _after_initialize(){}
	
	/**
	 * 校验登录
	 */
	public function checkLogin() {
		$this->manager_id = intval ( $_SESSION ['manager_id'] );
		if (! $this->manager_id) {
			// 默认访问跳转到登录页
			$this->redirect ( 'Login/index' );
		}
	}
}