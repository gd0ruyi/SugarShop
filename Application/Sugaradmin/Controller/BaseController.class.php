<?php

namespace Sugaradmin\Controller;

use Think\Controller;
use Behavior\ShowPageTraceBehavior;
use Sugaradmin\Model\BaseModel;
use Think\Model;

/**
 * 基础控制类
 * 主要用于继承，完成基本校验等处理
 */
class BaseController extends Controller
{
	// 不进行登录验证的控制器
	public $non_checkLogin_controller = array(
		'/Login'
	);
	// 不进行登录验证的控制器方法
	public $non_checkLogin_action = array(
		'/Login/index'
	);

	// 当前用户id
	public $self_user_id = 0;

	// 返回json的格式
	protected $_res = array(
		'status' => 0,
		'title' => 'info',
		'msg' => 'success',
		'jump' => '',
		'data' => array(),
		'pager' => array(),
		'valid' => false
	);

	// 默认分页数
	public $pager_size = 10;

	// 动态的mode
	protected $_auto_model = array();

	/**
	 * 初始化方法
	 */
	public function __construct()
	{
		// 自定义初始化前方法，construct初始化前调用，因initialize是在construct内调用
		$this->_before_initialize();

		parent::__construct();

		// 设置默认头部
		header('Content-Type:text/html; charset=utf-8');

		// 合并传递参数
		$_GET = array_merge($_GET, $_POST);
		$_POST = $_GET;

		// 格式化正确的不检查登录的控制器
		foreach ($this->non_checkLogin_controller as $key => $value) {
			$this->non_checkLogin_controller[$key] = __MODULE__ . $value;
		}

		// 格式化正确的不检查登录的控制器
		foreach ($this->non_checkLogin_action as $key => $value) {
			$this->non_checkLogin_action[$key] = __MODULE__ . $value;
		}

		// 判断是否需要校验登录的控制器或者具体的方法
		if (!in_array(__CONTROLLER__, $this->non_checkLogin_controller) && !in_array(__ACTION__, $this->non_checkLogin_action)) {
			$this->checkLogin();			
		}

		$this->_after_initialize();
	}

	/**
	 * 通用初始化方法前置执行
	 */
	public function _before_initialize()
	{ }

	/**
	 * 通用初始化方法
	 */
	public function _initialize()
	{ }

	/**
	 * 通用初始化方法后置执行
	 */
	public function _after_initialize()
	{ }

	/**
	 * 校验登录
	 */
	public function checkLogin()
	{
		$this->self_user_id = intval($_SESSION['user_id']);
		if (!$this->self_user_id) {
			$this->error('您尚未登录或登录超时，请重新登录！', __MODULE__ . '/Login/index');
			// 默认访问跳转到登录页
			// $this->redirect ( __MODULE__ . '/Login/index' );
		}
	}

	/**
	 * 自动化构建对应的model
	 *
	 * @return void
	 */
	public function makeAutoModel()
	{
		// 自动化构建Model处理
		$model_name = CONTROLLER_NAME;
		$model_name = MODULE_NAME . '\\Model\\' . ucfirst($model_name) . 'Model';
		return $this->_auto_model = empty($this->_auto_model) ? new $model_name : $this->_auto_model;
	}

	/**
	 * 自定义display输出处理
	 *
	 * @author gd0ruyi@163.com 2016-6-1
	 * @param string $templateFile
	 * @param string $charset
	 * @param string $contentType
	 * @param string $content
	 * @param string $prefix
	 */
	public function displayAutoByIsAjax($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '')
	{
		// 如果为AJAX请求的情况，并且配置为进度打开，将按照流的方式输出。
		if (IS_AJAX && C('OPEN_AJAX_LOADING_PROGRESS')) {
			// 设置php执行超时时间为不超时
			set_time_limit(0);

			$content = $this->fetch($templateFile, $content, $prefix);
			header("Content-Length: " . mb_strlen($content));

			// ob_start();
			$step = 1024;
			for ($i = 0; $i < ceil(mb_strlen($content) / $step); $i++) {
				echo substr($content, ($i * $step), $step);
				ob_flush();
				flush();
			}
			ob_end_flush();
		} else {
			parent::display($templateFile, $charset, $contentType, $content, $prefix);
		}
	}

	/**
	 * 覆盖原有框架的display方法
	 * （主要用于输出时加入Content-Length）
	 *
	 * @param string $templateFile
	 * @param string $charset
	 * @param string $contentType
	 * @param string $content
	 * @param string $prefix
	 * @return void
	 */
	public function display($templateFile = '', $charset = '', $contentType = '', $content = '', $prefix = '')
	{
		$this->displayAutoByIsAjax($templateFile, $charset, $contentType, $content, $prefix);
		// 注：此处无需调用ShowPageTraceBehavior进行显示，ThinkPHP默认会加载Behavior时，会调用ShowPageTraceBehavior
		if (isDebug()) {
			$this->_printDebug(false);
		}
	}

	/**
	 * 设置返回结果集，批量，存在同样的键名将覆盖
	 * 注：新修改，复用setResKeyValue方法，当res为字符串时，可以设置key和value
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param array||string $res 传入的结果集或key
	 * @param array $value 值
	 * @return array 返回结果集
	 */
	public function setRes($res, $value = null)
	{
		if (is_string($res)) {
			$this->setResKeyValue($res, $value);
		} else {
			foreach ($res as $k => $v) {
				$this->_res[$k] = $v;
			}
		}
		return $this->_res;
	}

	/**
	 * 按键值设置返回结果集
	 *
	 * @author gd0ruyi@163.com 2016-6-11
	 * @param srting $key
	 * @param array $value
	 * @return array
	 */
	public function setResKeyValue($key, $value)
	{
		return $this->_res[$key] = $value;
	}

	/**
	 * 获取返回结果集
	 *
	 * @author gd0ruyi@163.com 2016-6-11
	 * @return multitype:number string NULL
	 */
	public function getRes()
	{
		return $this->_res;
	}

	/**
	 * 自定义返回结果集方法，用于Ajaxs
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param array $res
	 */
	public function resReturn($res = array(), $json_options = JSON_UNESCAPED_UNICODE)
	{
		$this->setRes($res);
		header('Content-Type:application/json; charset=utf-8');
		echo json_encode($this->_res, $json_options);
		//  是否输出debu打印（注：因没有使用display，所以需要加入自定义的debug输出）
		if (isDebug()) {
			$this->_printDebug(true);
		}
		exit();
	}

	/**
	 * 自定义返回结果集success方法，用于Ajaxs,使用getList时，suc则无需赋值data
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param array $data 返回数据
	 * @param string $msg 提示信息
	 * @param string $title	标题
	 */
	public function suc($data = array(), $msg = 'OK', $title = 'success')
	{
		$this->_res['status'] = 0;
		$this->_res['title'] = $title;
		$this->_res['msg'] = $msg;
		// 用于自动处理，默认
		$this->_res['data'] = empty($data) ? $this->_res['data'] : $data;
		$this->_res['valid'] = true;
		$this->resReturn($this->_res);
	}

	/**
	 * 自定义返回结果集error方法，用于Ajaxs
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param string $msg 提示信息
	 * @param string $title 标题
	 * @param string $data 返回数据
	 * @param number $status 错误状态，默认为1
	 */
	public function err($msg, $title = 'error', $data = array(), $status = 1)
	{
		$this->_res['status'] = $status;
		$this->_res['title'] = $title;
		$this->_res['msg'] = $msg;
		$this->_res['data'] = $data;
		$this->_res['valid'] = false;
		$this->resReturn($this->_res);
	}

	/**
	 * 设置结果集jump的跳转路径
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param string $url 跳转路径
	 * @return string
	 */
	public function setResJump($url)
	{
		return $this->_res['jump'] = $url;
	}


	/**
	 * 设置调试结果集
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param string $key debug的键名
	 * @param unknown $value debug的键值
	 * @return unknown
	 */
	/* public function setResDebug($key = '', $value = '')
	{
		if (isDebug() && IS_AJAX) {
			// 返回结果集赋值
			$this->_res['debug'] = array(
				'MODULE_PATH' => MODULE_PATH,
				'NOW_TIME' => date('Y-m-d H:i:s', NOW_TIME) . '(' . NOW_TIME . ")",
				'REQUEST_METHOD' => REQUEST_METHOD,
				'IS_AJAX' => IS_AJAX,
				// 'res' => $this->_res,
				'res_dump' => json_dump($this->_res),
				'debug_data' => $this->_debug,
				'debug_data_dump' => json_dump($this->_debug),
				'get' => $_GET,
				'post' => $_POST,
				'session' => $_SESSION,
				'cookie' => $_COOKIE
			);
		}
		if ($key) {
			$this->_res['debug'][$key] = $value;
		}

		return $this->_res['debug'];
	} */

	/**
	 * 打印debug信息
	 * 
	 * @author ruyi <gd0ruyi@163.com>
	 * @param boolean $is_show_trace 是否强制输出ThinkPHP内置修改过的trace（注：用于解决ThinkPHP自动加载ShowPageTraceBehavior后会重复输出trace信息）
	 * @return void
	 */
	public function _printDebug($is_show_trace = false)
	{
		if (!IS_AJAX) {
			header('Content-Type:text/html; charset=utf-8');
		}

		echo "<!--Source Code End-->\n";

		echo "<hr /><pre><h1>Debug Start</h1></pre>\n";
		echo "<hr />\n";

		echo "<pre>Tip: If you use ajax to request, you can use the browser to open and get the Trace information of the page. </pre>\n";
		echo "<hr />\n";

		echo "<pre><h1>URL: " . getLocalUrl() . "</h1></pre>\n";
		echo "<pre><h1>ACTION: " . __ACTION__ . "</h1>\n";
		// echo "<pre><h1>MODULE_PATH:" . MODULE_PATH . "</h1>\n";
		echo "NOW_TIME : " . date('Y-m-d H:i:s', NOW_TIME) . '(' . NOW_TIME . ")\n";
		echo "<hr />\n";
		echo "</pre>\n";

		echo "<pre><h2>result print</h2>\n";
		echo "<hr />\n";
		print_r($this->_res);
		echo "</pre>\n";

		echo "<pre><h2>result dump</h2>\n";
		echo "<hr />\n";
		dump($this->_res);
		echo "</pre>\n";

		// echo "<pre><h1>debug date print</h1>\n";
		// echo "<hr />\n";
		// print_r($this->_debug);
		// echo "</pre>\n";

		// echo "<pre><h1>debug date dump</h1>\n";
		// echo "<hr />\n";
		// dump($this->_debug);
		// echo "</pre>\n";

		echo "<pre><h1>request</h1>\n";
		echo "<hr />\n";
		print_r($_REQUEST);
		echo "</pre>\n";

		echo "<pre><h1>get</h1>\n";
		echo "<hr />\n";
		print_r($_GET);
		echo "</pre>\n";

		echo "<pre><h1>post</h1>\n";
		echo "<hr />\n";
		print_r($_POST);
		echo "</pre>\n";

		echo "<pre><h1>session</h1>\n";
		echo "<hr />\n";
		print_r($_SESSION);
		echo "</pre>\n";

		echo "<pre><h1>cookie</h1>\n";
		echo "<hr />\n";
		print_r($_COOKIE);
		echo "</pre>\n";

		// 输出常量
		if (DEBUG_PRINT_CONSTANTS) {
			echo "<pre><h1>constants</h1>\n";
			echo "<hr />\n";
			print_r(get_defined_constants(true));
			echo "</pre>\n";
		}

		// 输出服务器信息
		if (DEBUG_PRINT_SERVER) {
			echo "<pre><h1>server</h1>\n";
			echo "<hr />\n";
			print_r($_SERVER);
			echo "</pre>\n";
		}

		// 此处为了加载页面Tace
		// $this->display ( 'Public/index' );
		if ($is_show_trace) {
			$spt = new ShowPageTraceBehavior();
			$param = array();
			$spt->run($param);
		}
		echo "<hr />\n";
		echo "<pre><h1>Debug End</h1></pre>";
	}

	/**
	 * 自动转换类型（暂时无用）
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param unknown $res        	
	 * @return unknown|Ambigous
	 */
	/* public function resFormat($res)
	{
		if (!is_array($res) || empty($res)) {
			return $res;
		}
		foreach ($res as $key => $value) {
			if (is_array($value)) {
				$res[$key] = $this->resValueFormat($value);
			}
		}

		return $res;
	} */

	/**
	 * 结果值自动格式化转换（暂时无用）
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param unknown $values        	
	 * @return Ambigous <string, number>
	 */
	/* public function resValueFormat($values)
	{
		foreach ($values as $k => $v) {
			// 类型处理
			if (is_numeric($v) && !strstr('.', $v)) {
				$values[$k] = intval($v);
			} elseif (is_numeric($v) && strstr('.', $v)) {
				$values[$k] = floatval($v);
			} elseif ($v == 'true' || $v == 'false') {
				$values[$k] = boolval($v);
			} elseif (is_string($v)) {
				$values[$k] = trim($v);
			} else {
				$values[$k] = $v;
			}

			// 特殊处理
			if (strstr($k, 'image') || strstr($k, 'icon')) {
				$values[$k] = $v && !strstr('http://', $v) ? C('PIC_URL') . $v : '';
			}
		}

		return $values;
	} */

	/**
	 * 操作错误的跳转快捷方法
	 *
	 * @param string $message 错误信息
	 * @param string $url 页面跳转地址
	 * @param mixed $speed 跳转的等待时间秒，默认为3秒
	 * @return void
	 */
	public function error($message = '', $url = '', $speed = 3)
	{
		$this->dispatchJump($message, 1, $url, $speed);
	}

	/**
	 * 操作成功的跳转快捷方法
	 *
	 * @access protected
	 * @param string $message 错误信息
	 * @param string $url 页面跳转地址
	 * @param mixed $speed 跳转的等待时间秒，默认为3秒
	 * @return void
	 */
	public function success($message = '', $url = '', $speed = 3)
	{
		$this->dispatchJump($message, 0, $url, $speed);
	}

	/**
	 * 默认跳转操作 支持错误导向和正确跳转
	 * 调用模板显示 默认为public目录下面的success页面
	 * 提示页面为可配置 支持模板标签
	 * 注：覆盖原ThinkPHP的调用方法，原方法路径为ThinPHP/Library/Think/Controller.class.php
	 * 
	 * @param string $message 提示信息
	 * @param Boolean $status 状态
	 * @param string $url 页面跳转地址
	 * @param mixed $speed 跳转的等待时间秒，默认为3秒
	 * @access private
	 * @return void
	 */
	private function dispatchJump($message, $status = 1, $url = '', $speed = 3)
	{
		// 初始化
		$status = intval($status);
		$speed = intval($speed);
		$data = array(
			'status' => $status,
			'title' => $status ? L('_OPERATION_FAIL_') : L('_OPERATION_SUCCESS_'),
			'msg' => $message,
			'jump' => $url,
			'speed' => $speed,
			'valid' => $status ? true : false,
			// ThinkPHP默认的输出
			'info' => $message,
			'url' => $url
		);
		// 设置到自定义的结果集
		$this->setRes($data);

		// AJAX返回
		if (IS_AJAX) {
			$this->ajaxReturn($data);
		}

		// 开始页面赋值
		$this->assign($data);

		// 保证输出不受静态缓存影响
		C('HTML_CACHE_ON', false);

		// 显示处理
		$this->display(C('TMPL_ACTION_INFO'));
		if (isDebug()) {
			$this->_printDebug(true);
		}
		exit();
	}

	/**
	 * 模板变量赋值（json赋值）
	 *
	 * @param mixed $name 要显示的模板变量，可以为数组，当为数组时直接遍历赋值
	 * @param mixed $value 变量的值
	 * @param int $json_options json编码常量
	 * @return void
	 */
	public function assignToJson($name, $value = '', $json_options = JSON_UNESCAPED_UNICODE)
	{
		return $this->assign($name, json_encode($value, $json_options));
	}

	/**
	 * 通用获取分页列表信息，注意使用前务必导入对应的Model（use YournameModel）
	 *
	 * @param array $query 查询条件参数，当为空值时表示默认全部
	 * @param integer $listRows 每页条数,默认为pager_size，使用传参分页（传入为all时表示全部）;当为0时表示不分页（避免页面传参直接获取全部数据）
	 * @param integer $nowPage 当前页,默认使用ThinkPHP的P
	 * 
	 * @return array() $pager 分页信息结果集
	 */
	public function getList($query = array(), $listRows = 'pager_size', $nowPage = 0)
	{
		// 排序默认参数处理
		if (!isset($query['order']) || empty($query['order'])) {
			$query['order'] = isset($_GET['sort']) ? json_decode($_GET['sort'], true) : array();
		}

		// 判断是否使用默认的page_size分页
		if ($listRows == 'pager_size') {
			if (isset($_GET['pager_size']) && strtolower($_GET['pager_size']) == 'all') {
				$listRows = 0;
			} else {
				$listRows = isset($_GET['pager_size']) ? intval($_GET['pager_size']) : 0;
				$listRows = $listRows <= 0 ? 10 : $listRows;
			}
		} else {
			$listRows = intval($listRows);
			$listRows = $listRows < 0 ? 10 : $listRows;
		}

		// 自动化构建model重新赋值到统一输入的rs上
		return $this->setRes($this->makeAutoModel()->getList($query, $listRows, $nowPage));
	}

	/**
	 * 获取单条信息
	 * @param array $query 查询条件参数
	 *
	 * @return array() $rs 单条信息结果集
	 */
	public function getOne($query)
	{
		// 排序默认参数处理
		if (!isset($query['order']) || empty($query['order'])) {
			$query['order'] = isset($_GET['sort']) ? json_decode($_GET['sort'], true) : array();
		}
		// 自动化构建model
		$rs = $this->makeAutoModel()->getOne($query);
		// 用于判断查询出现多条值的情况处理
		if (intval($rs['status'])) {
			$this->err($rs['msg']);
		}
		// 重新赋值到统一输入的rs上
		return $this->setRes($rs);
	}

	// 通用删除方法，通过主键ID进行删除处理
	public function remove()
	{
		$pk = $this->makeAutoModel()->getPk();
		$pkv = isset($_GET[$pk]) ? $_GET[$pk] : 0;
		if (!$pkv) {
			$this->err('非法操作，没有对应的主键！');
		}
		$rs = $this->makeAutoModel()->delete();
	}

	// 通用更改字段值，需要加入允许变更字段的过滤
	public function changeFieldValue()
	{ }
}
