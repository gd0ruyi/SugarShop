<?php

namespace Sugaradmin\Controller;

use Sugaradmin\Controller\BaseController;
use Sugaradmin\Model\TestModel;

/**
 * 测试使用
 *
 * @author gd0ruyi@163.com
 *        
 */
class TestController extends BaseController
{

	/**
	 * 通用初始化方法
	 */
	public function _initialize()
	{ }

	/**
	 * 默认示例
	 */
	public function index()
	{
		$manager_model = D("Sugaradmin/Manager");
		$manager_model = new ManagerModel();
		$manager_list = $manager_model->getList(array(), '', 1, 10);
		$this->assign('manager_list', $manager_list);
		$this->display();
	}

	/**
	 * 通过smarty加载列表页面示例
	 * 
	 * @author gd0ruyi@163.com 2019-2-15
	 * return_type
	 */
	public function loadSmarty()
	{
		$rs = $this->getList(array());
		// printR($rs);
		$this->assign('data', $rs['data']);
		$this->display();
	}

	/**
	 * 通过ajax加载列表
	 * @author gd0ruyi@163.com 2019-1-7
	 */
	public function loadAjax()
	{
		$this->display();
	}

	/**
	 * 进度条加载测试
	 *
	 * @return void
	 */
	public function loadProgress()
	{
		C('OPEN_AJAX_LOADING_PROGRESS', true);
		$max = 100000 / 4;
		$html = "<h1>This is loadProgress test!</h1>";
		$html .= "<pre>";
		for ($i = 0; $i < $max; $i++) {
			$html .= "<font color='#FF0000'>0123456789</font>(" . $i . ")<br />";
		}
		$html .= "</pre> ";
		$html = "content_length=" . mb_strlen($html) . "<br />" . $html;
		$this->assign('html', $html);
		$this->display();
	}

	/**
	 * 表格json加载
	 *
	 * @return void
	 */
	public function loadAjaxJson()
	{
		$data = array();
		// for ($i = 0; $i < 10; $i++) {
		// 	$data[$i]['id'] = $i;
		// 	$data[$i]['name'] = '名称-' . $i;
		// 	$data[$i]['cname'] = '中文-' . $i . '长字符中文长字符中文长字符中文';
		// 	$data[$i]['money'] = floatval('0.' . $i);
		// }
		// $m = new TestModel();
		// $rs = $m->getList(array(), 10, $page);
		// $rs = $m->getList(array(), 10);
		// $rs = $m->getList(array());
		$rs = $this->getList(array());
		$this->setRes($rs);

		// $data = null;
		// $this->ajaxReturn($data, 'JSON');
		$this->suc($data, true);
	}

	// 打印示例并添加
	public function testPrint()
	{
		// printR ( $_SESSION );
		// $test = new TestModel ();
		$test = D("Sugaradmin/Test");

		$data = array();
		for ($i = 0; $i < 99; $i++) {
			// $data [$i] ['test_id'] = $i;
			$data[$i]['name'] = '名称-' . $i;
			$data[$i]['cname'] = '中文名称-' . $i;
			$data[$i]['status'] = $i % 2;
		}
		// printR($data);

		$options = array();
		// $options['_options'] = array(
		// 	'fsync' => true,
		// 	'safe' => true
		// );

		// 单条创建
		// $test->add ( $data[0], $options );

		// 多条创建
		// $test->addAll ( $data, $options );
		// 需要补充mongod获取自增最后的ID，在db驱动类内
		printR($test->getLastInsID());

		$options = array();
		$options['_auto_create_index'] = true;
		// 配置返回数组的每行键的名称为test_id
		$options['index'] = 'test_id';
		$res = $test->order('test_id desc')->select($options);
		// printR ( $test->getLastInsID () );
		// printR ( $res );

		// ThinkPhP 的find是返回一条数据，如同mongdb的findOne
		// $res = $test->order ( 'test_id desc' )->find ( $options );
		printR($res);
	}

	// 测试退出登录
	public function logout()
	{
		session_destroy();
		printR($_SESSION);
	}

	// 用于测试
	public function testModel()
	{
		parent::getList();
	}
}
