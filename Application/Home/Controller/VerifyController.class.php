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
		$config = C ( 'VERIFY_CONFIG.IMG_CONFIG' );
		if ($config ['allow_get_set']) {
			$config = array_merge ( $config, $_GET );
		}
		$verify = new \Think\Verify ( $config );
		$verify->entry ();
	}
}