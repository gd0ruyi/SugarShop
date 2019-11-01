<?php

namespace Sugaradmin\Controller;

use Think\Controller;
use Behavior\ShowPageTraceBehavior;

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
	// 真实的get传入
	protected $_isset_get_map = array();
	// 管理员ID
	public $user_id = 0;

	// 返回json的格式
	private $_res = array(
		'status' => 0,
		'title' => 'info',
		'msg' => 'success',
		'jump' => '',
		'data' => null,
		'pager' => array(),
		'valid' => false
	);

	public $pager_size = 10;

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
		$this->user_id = intval($_SESSION['user_id']);
		if (!$this->user_id) {
			$this->error('您尚未登录或登录超时，请重新登录！', __MODULE__ . '/Login/index');
			// 默认访问跳转到登录页
			// $this->redirect ( __MODULE__ . '/Login/index' );
		}
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
		// 输出模版前用于输入debug
		isDebug() ? $this->_printDebug() : '';
	}

	/**
	 * 设置返回结果集，批量，存在同样的键名将覆盖
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param array $res 传入的结果集
	 * @return array 返回结果集
	 */
	public function setRes($res)
	{
		foreach ($res as $k => $v) {
			$this->_res[$k] = $v;
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
		//  是否输出debu打印
		isDebug() ? $this->_printDebug() : '';
		exit();
	}

	/**
	 *
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
		$this->_res['data'] = empty($data) ? $this->_res['data'] : $data;
		$this->_res['valid'] = true;
		$this->resReturn($this->_res);
	}

	/**
	 * 自定义返回结果集error方法，用于Ajaxs
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param string $msg
	 *        	提示信息
	 * @param string $title
	 *        	标题
	 * @param string $data
	 *        	返回数据
	 * @param number $status
	 *        	错误状态，默认为1
	 */
	public function err($msg, $title = 'error', $data = null, $status = 1)
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
	 * @param string $url
	 *        	跳转路径
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
	 * @param string $key
	 *        	debug的键名
	 * @param unknown $value
	 *        	debug的键值
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
	 * 自定义debug输出，用于查看php打印返回结果集
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @return boolean
	 */
	public function resDebug()
	{ }

	/**
	 * 打印debug信息
	 *
	 * @return void
	 */
	public function _printDebug()
	{
		header('Content-Type:text/html; charset=utf-8');

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

		echo "<pre><h1>debug date print</h1>\n";
		echo "<hr />\n";
		print_r($this->_debug);
		echo "</pre>\n";

		echo "<pre><h1>debug date dump</h1>\n";
		echo "<hr />\n";
		dump($this->_debug);
		echo "</pre>\n";

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
		$spt = new ShowPageTraceBehavior();
		$param = array();
		$spt->run($param);
		echo "<hr />\n";
		echo "<pre><h1>Debug End</h1></pre>";
	}

	/**
	 * 自动转换类型
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param unknown $res        	
	 * @return unknown|Ambigous
	 */
	public function resFormat($res)
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
	}

	/**
	 * 结果值自动格式化转换
	 *
	 * @author gd0ruyi@163.com 2016-6-9
	 * @param unknown $values        	
	 * @return Ambigous <string, number>
	 */
	public function resValueFormat($values)
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
	}

	/**
	 * 操作错误跳转的快捷方法
	 *
	 * @access protected
	 * @param string $message
	 *        	错误信息
	 * @param string $jumpUrl
	 *        	页面跳转地址
	 * @param mixed $ajax
	 *        	是否为Ajax方式 当数字时指定跳转时间
	 * @return void
	 */
	protected function error($message = '', $jumpUrl = '', $ajax = false)
	{
		$this->dispatchJump($message, 1, $jumpUrl, $ajax);
	}

	/**
	 * 默认跳转操作 支持错误导向和正确跳转
	 * 调用模板显示 默认为public目录下面的success页面
	 * 提示页面为可配置 支持模板标签/*
	 * 
	 * @param string $message 提示信息
	 * @param Boolean $status 状态
	 * @param string $jumpUrl 页面跳转地址
	 * @param mixed $ajax 是否为Ajax方式 当数字时指定跳转时间
	 * @access private
	 * @return void
	 */
	private function dispatchJump($message, $status = 1, $jumpUrl = '', $ajax = false)
	{
		if (true === $ajax || IS_AJAX) { // AJAX提交
			$data = is_array($ajax) ? $ajax : array();
			$data['info'] = $message;
			$data['status'] = $status;
			$data['url'] = $jumpUrl;
			$this->ajaxReturn($data);
		}
		if (is_int($ajax))
			$this->assign('waitSecond', $ajax);
		if (!empty($jumpUrl))
			$this->assign('jumpUrl', $jumpUrl);
		// 提示标题
		$this->assign('msgTitle', $status ? L('_OPERATION_FAIL_') : L('_OPERATION_SUCCESS_'));
		// 如果设置了关闭窗口，则提示完毕后自动关闭窗口
		if ($this->get('closeWin')) {
			$this->assign('jumpUrl', 'javascript:window.close();');
		}
		// 状态
		$this->assign('status', $status);
		// 保证输出不受静态缓存影响
		C('HTML_CACHE_ON', false);
		// 发送成功信息
		if ($status) {
			// 提示信息
			$this->assign('error', $message);
			// 发生错误时候默认停留3秒
			if (!isset($this->waitSecond))
				$this->assign('waitSecond', '3');
			// 默认发生错误的话自动返回上页
			if (!isset($this->jumpUrl))
				$this->assign('jumpUrl', "javascript:history.back(-1);");
			$this->display(C('TMPL_ACTION_ERROR'));
			// 中止执行 避免出错后继续执行
			exit();
		} else {
			// 提示信息
			$this->assign('message', $message);
			// 成功操作后默认停留1秒
			if (!isset($this->waitSecond))
				$this->assign('waitSecond', '1');
			// 默认操作成功自动返回操作前页面
			if (!isset($this->jumpUrl))
				$this->assign("jumpUrl", $_SERVER["HTTP_REFERER"]);
			$this->display(C('TMPL_ACTION_SUCCESS'));
		}
	}

	/**
	 * 通用获取分页列表信息，注意使用前务必导入对应的Model（use YournameModel）
	 *
	 * @param array $query 查询条件参数
	 * @param integer $listRows 每页条数,默认为pager_size，使用传参分页（传入为all时表示全部）;当为0时表示不分页（避免页面传参直接获取全部数据）
	 * @param integer $nowPage 当前页,默认使用ThinkPHP的P
	 * 
	 * @return array() $pager 分页信息结果集
	 */
	public function getList($query, $listRows = 'pager_size', $nowPage = 0)
	{
		// 自动化构建Model处理
		$model_name = CONTROLLER_NAME;
		$model_name = MODULE_NAME . '\\Model\\' . ucfirst($model_name) . 'Model';
		$m = new $model_name;

		// 排序默认参数处理
		if (!isset($query['order']) || empty($query['order'])) {
			$_REQUEST['sort'] = json_decode($_REQUEST['sort'], true);
			$query['order'] = $_REQUEST['sort'];
		}

		// 判断是否使用默认的page_size分页
		if ($listRows == 'pager_size') {
			if (isset($_REQUEST['pager_size']) && strtolower($_REQUEST['pager_size']) == 'all') {
				$listRows = 0;
			} else {
				$listRows = isset($_REQUEST['pager_size']) ? intval($_REQUEST['pager_size']) : 0;
				$listRows = $listRows <= 0 ? 10 : $listRows;
			}
		} else {
			$listRows = intval($listRows);
			$listRows = $listRows < 0 ? 10 : $listRows;
		}

		$rs = $m->getList($query, $listRows, $nowPage);
		$this->setRes($rs);
		return $rs;
	}

	/**
	 * 获取单条信息
	 * @param array $query 查询条件参数
	 *
	 * @return array() $rs 单条信息结果集
	 */
	public function getOne($query)
	{
		// 自动化构建Model处理
		$model_name = CONTROLLER_NAME;
		$model_name = MODULE_NAME . '\\Model\\' . ucfirst($model_name) . 'Model';
		$m = new $model_name;

		// 排序默认参数处理
		if (!isset($query['order']) || empty($query['order'])) {
			$_REQUEST['sort'] = json_decode($_REQUEST['sort'], true);
			$query['order'] = $_REQUEST['sort'];
		}

		$rs = $m->getOne($query);
		// $this->setRes($rs);
		return $rs;
	}
}
