<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;
use Sugaradmin\Model\UserModel;

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
		$user_model = D ( "User" );
		$user_model = new UserModel ();
		$pwd = md5 ( $_POST ['username'] . $_POST ['password'] );
		$query = array ();
		$query ['username'] = $_POST ['username'];
		$query ['password'] = md5 ( $_POST ['usename'] . $_POST ['password'] );
		
		$query = array ();
		// 查询时自动创建索引（示例）
		$query ['_auto_create_index'] = true;
		
		$sort = array ();
		$sort ['username'] = 1;
		$rs = $user_model->where ( $query )->order ( $sort )->find ( $query );
		
		if ($rs ['username']) {
			$sess_data = $rs;
			unset ( $sess_data ['password'] );
			session_save_values ( $sess_data );
			
			// 保存登录更新时间
			$data = array ();
			$data ['las_time'] = time ();
			$rs = $user_model->where ( $query )->save ( $data );
			
			$this->success ( '登录成功！', '../Index/index' );
		}
		$this->error ( '用户名密码不正确！', '../Login/index' );
	}
	
	/**
	 * 退出登录处理
	 *
	 * @return void
	 */
	public function logout() {
		session_destroy ();
		$this->success ( '退出成功！', '../Login/index' );
	}
}