<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;

/**
 * 默认管理后台入口
 * @author gd0ruyi@163.com
 *
 */
class IndexController extends BaseController
{
	public function index()
	{
		// 用于js的debug赋值
		$this->assign('IS_DEBUG', isDebug());
		$this->display();
	}

	// 默认首页显示
	public function home()
	{
		$this->display();
	}
}
