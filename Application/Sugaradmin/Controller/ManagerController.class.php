<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;
use Sugaradmin\Model\ManagerModel;

/**
 * 管理员管理
 *
 * @author gd0ruyi@163.com
 *        
 */
class ManagerController extends BaseController
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
		$manager_model = D("Sugaradmin/Manager");
		// $manager_model = new ManagerModel();
		$manager_list = $manager_model->getList(array(), '', 1, 10);
		$this->assign('manager_list', $manager_list);
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
		$data = array();
		$rs = $this->getList(array());
		$this->setRes($rs);
		$this->suc($data, true);
	}

	public function edit()
	{
		$this->display();
	}
}
