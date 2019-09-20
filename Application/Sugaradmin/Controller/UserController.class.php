<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;
use Sugaradmin\Model\UserModel;

/**
 * 管理员管理
 *
 * @author gd0ruyi@163.com
 *        
 */
class UserController extends BaseController
{
	/**
	 * 通用初始化方法
	 */
	public function _initialize()
	{ }

	/**
	 * 显示管理员列表页面
	 */
	public function index()
	{
		$user_model = D("Sugaradmin/User");
		// $user_model = new UserModel();
		$user_list = $user_model->getList(array(), '', 1, 10);
		$this->assign('user_list', $user_list);
		// $this->display ();
		// $this->displayAutoAjax();
		$this->display();
	}

	/**
	 * ajax加载管理员列表数据
	 *
	 * @return void
	 */
	public function loadAjax()
	{
		// $data = array();
		// $rs = $this->getList(array());
		// $this->setRes($rs);
		// $this->suc($data, true);
		$this->getList(array());
		$this->suc();
	}

	/**
	 * 编辑页面处理
	 *
	 * @return void
	 */
	public function edit()
	{
		$options = array();
		$options['user_id'] = 1;
		$rs = $this->getOne($options);
		$this->assign('user', $rs['data']);
		$this->display();
	}

	public function save()
	{
		$user_model = D("Sugaradmin/User");
		$data = array();

		if ($data['_id']) {
			$data['_id'] = $_REQUEST['_id'];
		}
		$data['user_id'] = intval($_GET['user_id']);
		$data['username'] = trim($_GET['username']);
		$data['password'] = $_GET['password'];
		$data['password'] = md5($data['usename'] . $data['password']);
		$data['truename'] = trim($_GET['truename']);
		$data['email'] = trim($_GET['email']);
		$data['mobile'] = trim($_GET['mobile']);
		$data['status'] = intval($_GET['status']);
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

		$user_model->save($data,$options);
		
	}
}
