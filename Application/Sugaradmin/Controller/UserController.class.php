<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;
use Sugaradmin\Model\SessionModel;
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
		// $user_model = D("Sugaradmin/User");
		// $user_model = new UserModel();
		// $user_list = $user_model->getList(array(), '', 1, 10);
		// $this->assign('user_list', $user_list);
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

		$query = array();
		$query['where'] = array();

		// 关键字处理
		if (isset($_GET['user_keyword']) && trim($_GET['user_keyword']) != '') {
			// $query['where']['username'] = array('like', '^' . trim($_GET['user_keyword']));
			$query['where']['username'] = array('like', trim($_GET['user_keyword']));
		}

		// 用户类型条件处理
		if (isset($_GET['use_type']) && $_GET['use_type'] != 'all') {
			$query['where']['use_type'] = intval($_GET['use_type']);
		}

		// 用户状态条件处理
		if (isset($_GET['status']) && $_GET['status'] != 'all') {
			$query['where']['status'] = intval($_GET['status']);
		}

		// $rs = $this->getList($query);
		// $this->suc($rs['data']);

		$this->getList($query);
		$this->suc();
	}

	/**
	 * 编辑页面处理
	 *
	 * @return void
	 */
	public function edit()
	{
		$query = array();
		$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : false;
		$rs = array();
		// 用于传入字段，主要用于页面的userinfo错误解除抛出
		$rs['data'] = $this->makeAutoModel()->fields;
		if ($user_id) {
			$query['where'] = array();
			$query['where']['user_id'] = $user_id;
			$rs = $this->getOne($query);
		}
		// $this->assign('user', json_encode($rs['data'], JSON_UNESCAPED_UNICODE));
		// 移除密码信息
		unset($rs['data']['password']);
		// 数据赋值
		$this->assignToJson('user', $rs['data']);
		$this->display();
	}

	/**
	 * 保存用户信息
	 *
	 * @return void
	 */
	public function save()
	{
		// $user_model = D("Sugaradmin/User");
		$user_model = new UserModel();
		$data = array();

		// 校验及初始化
		$data['user_id'] = intval($_GET['user_id']);
		// $data['user_id'] = 2;
		$data['username'] = trim($_GET['username']);
		$data['password'] = $_GET['password'];
		// 密码加密规则
		$data['password'] = makePassword($data['usename'], $data['password']);
		$data['truename'] = trim($_GET['truename']);
		$data['email'] = trim($_GET['email']);
		$data['mobile'] = trim($_GET['mobile']);
		$data['status'] = intval($_GET['status']);
		$data['use_type'] = 0;
		$time = time();
		$data['add_time'] = $time;
		$data['upd_time'] = $time;
		$data['las_time'] = 0;

		// 当user_id不为0表示为保存处理
		if ($data['user_id']) {
			unset($data['username']);
			// 密码为空表示不修改
			if (!$_GET['password']) {
				unset($data['password']);
			}
			unset($data['add_time']);
			unset($data['las_time']);
		}

		$options = array();
		// $options ['id'] = 1;
		$options['_options'] = array(
			'fsync' => true,
			'safe' => true
		);

		// 判断是否符合规则
		if ($data = $user_model->create($data)) {
			$last_info = $user_model->save($data, $options);
			// 赋值到返回结果中
			$this->setRes('last_info', $last_info);
			if($user_model->getError()){
				$this->err($user_model->getError(), '警告');
			}else{
				$this->suc($data, '保存用户成功', '提示');
			}
		} else {
			$this->err($user_model->getError(), '警告');
		}
	}

	/**
	 * 校验用户名称唯一
	 *
	 * @return void
	 */
	public function checkUserUnique()
	{
		$query = array();
		$query['where'] = array();
		$query['where']['username'] = trim($_GET['username']);
		$data = $this->getOne($query);
		$data = $data['data'];
		if (!isset($data['username'])) {
			$this->suc($data);
		} else {
			$this->err('用户名重复，用户已存在！');
		}
	}

	/**
	 * 获取session列表信息
	 *
	 * @return void
	 */
	public function sessionList(){
		$sm = new SessionModel();
		$rs = $sm->select();

		// 时间配置处理
        $date_format_c = C('DATE_FORMAT');
		foreach($rs as $k => $v){
			$v['lifeTime'] = date($date_format_c['TIME'], $v['lifeTime']);
			$v['sessData']['upd_time'] = date($date_format_c['TIME'], $v['sessData']['upd_time']);
			$v['sessData']['add_time'] = date($date_format_c['TIME'], $v['sessData']['add_time']);
			$v['sessData']['las_time'] = date($date_format_c['TIME'], $v['sessData']['las_time']);
			$rs[$k] = $v;
		}
		printR($rs);
	}

	// 用于BootstrapValidator测试问题
	public function test()
	{
		$this->display();
	}
}
