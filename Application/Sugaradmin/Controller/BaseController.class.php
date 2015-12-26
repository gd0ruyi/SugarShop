<?php

namespace Sugaradmin\Controller;

use Think\Controller;

class BaseController extends Controller {
	public function _initialize(){
		
	}
	
	public function testBaseFun(){
	}
	
	public function checkLogin(){
		if($_SESSION[''])
		// 默认访问跳转到登录页
		$this->redirect ( 'Login/index' );
	}
}