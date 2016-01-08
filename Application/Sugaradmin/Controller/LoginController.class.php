<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;

class LoginController extends BaseController {
	/**
	 * 登录界面
	 *
	 * @author gd0ruyi@163.com 2015-11-18
	 */
	public function index() {
		$this->display ();
	}
	
	/**
	 * 登录处理
	 *
	 * @author gd0ruyi@163.com 2015-11-18
	 */
	public function login() {
		//$this->ajaxReturn ( $data, 'JSON' );
		//$this->ajaxReturn($data,'info',1);
		//printR ( $_SESSION );
		$this->_login();
	}
	
	protected function _login(){
		$manager_model = D ( "Manager" );
		$pwd = md5 ( $_POST ['username'] . $_POST ['password'] );
		$query = array ();
		$query ['username'] = $_POST ['username'];
		$query ['password'] = md5 ( $_POST ['usename'] . $_POST ['password'] );
		
		$options = array ();
		// 查询时自动创建索引（示例）
		$options ['_auto_create_index'] = true;
		
		$sort = array ();
		$sort ['username'] = 1;
		$rs = $manager_model->where ( $query )->order ( $sort )->find ( $options );
		printR($rs, 1);
		
		$data ['error'] = 1;
		$data ['tit'] = 'info';
		$data ['size'] = 9;
		$data ['url'] = $url;
		
		if ($rs ['username']) {
			$sess_data = $rs;
			unset ( $sess_data ['password'] );
			session_save_values ( $sess_data );
			
		} else {
			
		}
	}
}