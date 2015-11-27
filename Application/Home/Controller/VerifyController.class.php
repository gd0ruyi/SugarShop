<?php

namespace Home\Controller;

use Think\Controller;

/**
 * 验证码处理
 *
 * @author gd0ruyi@163.com 2015-11-25
 *        
 */
class VerifyController extends Controller {
	public function index() {
		$verify = new \Think\Verify ();
		$verify->fontSize = intval ( $_GET ['size'] ) ? intval ( $_GET ['size'] ) : 25;
		$verify->imageW = intval ( $_GET ['w'] ) ? intval ( $_GET ['w'] ) : 0;
		$verify->imageH = intval ( $_GET ['h'] ) ? intval ( $_GET ['h'] ) : 0;
		$verify->length = intval ( $_GET ['length'] ) ? intval ( $_GET ['length'] ) : 4;
		$Verify->fontttf = '6.ttf';
		$verify->entry ();
	}
}