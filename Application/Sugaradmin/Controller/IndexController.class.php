<?php

namespace Sugaradmin\Controller;

class IndexController extends BaseController {
	public function index() {
	}
	
	/**
	 * 创建ADMIN账户
	 *
	 * @author gd0ruyi@163.com 2015-11-12
	 */
	public function install() {
		$manager_model = D ( "Manager" );
		$data = array ();
		//$data ['id'] = 1;
		$data ['username'] = 'admin';
		$data ['password'] = 'admin';
		$data ['password'] = md5 ( $data ['usename'] . $data ['password'] );
		$time = time ();
		$data ['add_time'] = $time;
		$data ['upd_time'] = $time;
		$data ['las_time'] = $time;
		
		$options = array ();
		// $options ['id'] = 1;
		$options ['_options'] = array (
				'fsync' => true,
				'safe' => true 
		);
		
		if (! $manager_model->add ( $data, $options, true )) {
			E ( '初始化创建管理员账户失败！' );
		}
		echo "创建账户成功！";
	}
}