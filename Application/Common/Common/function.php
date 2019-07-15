<?php
/**
 * 自定义数组打印输出
 *
 * @author ruyi@amnice.cn
 * @param expression $value 输出值
 * @param string $is_exit 是否退出，默认为不退出
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
 * 将数组转成带类型返回，用于json
 *
 * @author gd0ruyi@163.com 2016-6-13
 * @param array $param        	
 * @return array
 */
function json_dump($param) {
	if (is_array ( $param )) {
		$_temp = array ();
		foreach ( $param as $key => $value ) {
			if (is_array ( $value )) {
				$_temp [$key] [_json_dump_gettype ( $value )] = json_dump ( $value );
			} else {
				$_temp [$key] = _json_dump_gettype ( $value );
			}
		}
	} else {
		
		$_temp = _json_dump_gettype ( $param );
	}
	return $_temp;
}

/**
 * 获取类型输出，用于json_dump
 *
 * @author gd0ruyi@163.com 2016-6-13
 * @param unknown $value        	
 * @return string
 */
function _json_dump_gettype($value) {
	$type = "unknown type";
	if (is_array ( $value ))
		$type = "array(" . count ( $value ) . ")";
	if (is_bool ( $value ))
		$type = "boolean(" . ($value ? 'true' : 'false') . ")";
	if (is_float ( $value ))
		$type = "float(" . $value . ")";
	if (is_int ( $value ))
		$type = "integer(" . $value . ")";
	if (is_string ( $value ))
		$type = "string(" . strlen ( $value ) . ") {$value}";
	if (is_null ( $value ))
		$type = "NULL";
	if (is_numeric ( $value )) {
		if (gettype ( $value ) == 'double') {
			$type = "float(" . $value . ")";
		} else {
			$type = gettype ( $value ) . "(" . $value . ")";
		}
	}
	if (is_object ( $value ))
		$type = "object(" . $value . ")";
	if (is_resource ( $value ))
		$type = "resource(" . $value . ")";
	return $type;
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

/**
* 多个连续空格只保留一个
*
* @param string $string 待转换的字符串
* @return string $string 转换后的字符串
*/
function merge_spaces($string){
    return preg_replace("/\s(?=\s)/","\\1",$string);
}
?>