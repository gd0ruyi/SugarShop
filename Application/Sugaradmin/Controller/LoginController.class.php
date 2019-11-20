<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;
use Sugaradmin\Model\UserModel;

class LoginController extends BaseController
{
	/**
	 * 登录界面
	 *
	 * @author gd0ruyi@163.com 2015-11-18
	 */
	public function index()
	{
		$this->display();
	}

	/**
	 * 登录处理
	 *
	 * @author gd0ruyi@163.com 2015-11-18
	 */
	public function login()
	{
		// 校验验证码
		if (!check_verify($_POST['verification'])) {
			$this->error("验证码不正确！请重新尝试！");
		}
		// $user_model = D ( "User" );
		$user_model = new UserModel();
		$query = array();
		$query['username'] = $_POST['username'];
		$query['password'] = makePassword($_POST['usename'], $_POST['password']);

		// 查询时自动创建索引（示例）
		$options = array();
		$options['_auto_create_index'] = true;

		$sort = array();
		$sort['username'] = 1;
		$rs = $user_model->where($query)->order($sort)->find($options);
		// 用于调试输出
		// printR($rs, false);

		if ($rs['username'] && $rs['username'] == $_POST['username']) {
			$sess_data = $rs;
			unset($sess_data['password']);
			session_save_values($sess_data);

			// 查询条件
			$query = array();
			$query['user_id'] = $sess_data['user_id'];

			// 保存登录更新时间
			$data = array();
			$data['las_time'] = time();

			$rs = $user_model->where($query)->save($data);
			if (!$rs) {
				$this->error($user_model->getError(), '../Login/index');
			}

			// 用于调试输出
			// printR($sess_data, true);

			$this->success('登录成功！', '../Index/index');
		}
		$this->error('用户名密码不正确！', '../Login/index');
	}

	/**
	 * 退出登录处理
	 *
	 * @return void
	 */
	public function logout()
	{
		session_destroy();
		$this->success('退出成功！', '../Login/index');
	}
}
