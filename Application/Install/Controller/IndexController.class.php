<?php

namespace Install\Controller;

use Sugaradmin\Model\UserModel;
use Think\Controller;

/**
 * 初始化安装
 *
 * @author gd0ruyi@163.com 2016-01-04
 *        
 */
class IndexController extends Controller
{
	public function index()
	{

		// 以下用于保存验证
		// $user_model = D("Sugaradmin/User");
		$user_model = new UserModel();

		$data = array();
		// $data['username'] = 'admin';
		$data['password'] = 'admin';
		$data['password'] = md5($data['usename'] . $data['password']);
		$data['truename'] = "超级管理员";
		$data['email'] = "gd0ruyi@163.com";
		$data['mobile'] = "13661123476";
		$data['status'] = 0;
		$data['use_type'] = 0;
		$time = time();
		$data['add_time'] = $time;
		$data['upd_time'] = $time;
		$data['las_time'] = 0;

		$options = array();
		// $options ['id'] = 1;
		$options['_options'] = array(
			'fsync' => true,
			'safe' => true
		);

		$flag = $user_model->create($data);
		var_dump($flag);
		exit($user_model->getError());
		echo "this is index";
		// $this->install();
	}

	/**
	 * 创建ADMIN账户
	 *
	 * @author gd0ruyi@163.com 2015-11-12
	 */
	public function install()
	{
		// $mongo = new Mongo ();
		// $mongo->switchCollection ( 'system.js', C ( 'DB_NAME' ) );

		// $options = array ();
		// // $options ['id'] = 1;
		// $options ['_options'] = array (
		// 'fsync' => true,
		// 'safe' => true
		// );
		// // $rs = $system_model->add ( $data, $options, true );
		// printR ( $mongo );
		// return false;
		$user_model = D("Sugaradmin/User");

		$data = array();
		// $data ['user_id'] = 1;
		$data['username'] = 'admin';
		$data['password'] = 'admin';
		$data['password'] = md5($data['usename'] . $data['password']);
		$data['truename'] = "超级管理员";
		$data['email'] = "gd0ruyi@163.com";
		$data['mobile'] = "13661123476";
		$data['status'] = 0;
		$data['use_type'] = 0;
		$time = time();
		$data['add_time'] = $time;
		$data['upd_time'] = $time;
		$data['las_time'] = 0;

		$options = array();
		// $options ['id'] = 1;
		$options['_options'] = array(
			'fsync' => true,
			'safe' => true
		);

		if (!$lastInfo = $user_model->add($data, $options, true)) {
			E('初始化创建管理员账户失败！');
		}
		$lastInfo = json_encode($lastInfo);
		// printR($user_model->getLastInsID());
		echo "创建账户成功,last info ({$lastInfo}, )！";
	}
}
