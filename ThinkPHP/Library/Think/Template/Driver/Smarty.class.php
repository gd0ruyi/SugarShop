<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Template\Driver;

/**
 * Smarty模板引擎驱动
 */
class Smarty {
	
	/**
	 * 渲染模板输出
	 *
	 * @access public
	 * @param string $templateFile
	 *        	模板文件名
	 * @param array $var
	 *        	模板变量
	 * @return void
	 */
	public function fetch($templateFile, $var) {
		$templateFile = substr ( $templateFile, strlen ( THEME_PATH ) );
		vendor ( 'Smarty.Smarty#class' );
		$tpl = new \Smarty ();
		$tpl->setCaching ( C ( 'TMPL_CACHE_ON' ) );
		$tpl->setTemplateDir ( THEME_PATH );		
		
		$tpl->setLeftDelimiter ( C ( 'TMPL_L_DELIM' ) );
		$tpl->setRightDelimiter ( C ( 'TMPL_R_DELIM' ) );
		
		$tpl->setPluginsDir ( C ( 'TMPL_ENGINE_CONFIG.plugins_dir' ) );
		$tpl->setDebugging ( C ( 'TMPL_ENGINE_CONFIG.DEBUGGING' ) );
		
		$tpl->force_compile = ! C ( 'TMPL_CACHE_ON' );
		$tpl->setCompileDir ( CACHE_PATH );
		$tpl->setCacheDir ( CACHE_PATH );
		$tpl->setCacheLifetime ( C ( 'TMPL_ENGINE_CONFIG.CACHE_LIFETIME' ) );
		
		$tpl->php_handling = \Smarty::PHP_ALLOW;
		\Smarty::$_DATE_FORMAT = 'Y-m-d H:i:s';
		// printR ( $tpl->getPluginsDir () );
		
		// if (C ( 'TMPL_ENGINE_CONFIG' )) {
		// $config = C ( 'TMPL_ENGINE_CONFIG' );
		// foreach ( $config as $key => $val ) {
		// $tpl->{$key} = $val;
		// }
		// }
		$tpl->assign ( $var );
		$tpl->display ( $templateFile );
	}
}