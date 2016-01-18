<?php
/**
 * 自定义数组打印输出
 *
 * @author ruyi@amnice.cn
 * @param expression $value 输出值
 * @param string $is_exit 是否退出
 */
function printR($value, $is_exit = false) {
	echo "<pre>\n";
	echo "<hr />\n";
	print_r ( $value );
	echo "<hr />\n";
	echo "</pre>\n";
	$is_exit ? exit () : "";
}

/**
 * 自定义数组打印输出，可带标题，内容等。
 *
 * @author ruyi@amnice.cn
 * @param string $title
 *        	标题
 * @param string $content
 *        	内容
 * @param expression $value
 *        	输出值
 * @param string $is_exit
 *        	是否退出
 */
function printRT($title, $content, $value, $is_exit = false) {
	echo "<pre>";
	echo "<h1>{$title}</h1>\n";
	echo "<h2>{$content}</h2>\n";
	printR ( $value, $is_exit );
	echo "</pre>\n";
}

/**
 * 验证检测
 * 
 * @param unknown $code        	
 * @param string $id        	
 * @return boolean
 */
function check_verify($code, $id = '') {
	if (C ( 'VERIFY_CONFIG.IS_OPEN' )) {
		$verify = new \Think\Verify ();
		return $verify->check ( $code, $id );
	}
	return true;
}
?>