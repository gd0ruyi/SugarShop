<?php
/**
 * 自定义U方法的使用
 * @author gd0ruyi@163.com 2015-11-18
 * @param array $params
 * @param object $template
 */
function smarty_function_U($params, $template) {
	$url = $params ['url'];	
	$suffix = isset ( $params ['suffix'] ) ? $params ['suffix'] : true;
	$domain = isset ( $params ['domain'] ) ? $params ['domain'] : false;
	
	unset($params ['url']);
	unset($params ['suffix']);
	unset($params ['domain']);
	$vars = $params;
	return U ( $url, $vars, $suffix, $domain );
}
?>