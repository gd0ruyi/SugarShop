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
		// 校验验证码
		if (! check_verify ( $_POST ['verification'] )) {
			$this->error ( "验证码不正确！请重新尝试！" );
		}
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
		
		if ($rs ['username']) {
			$sess_data = $rs;
			unset ( $sess_data ['password'] );
			session_save_values ( $sess_data );
			$this->success ( '登录成功！', '/Index/index' );
		}
		$this->error ( '用户名密码不正确！' );
	}
}