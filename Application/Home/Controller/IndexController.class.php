<?php

namespace Home\Controller;

use Think\Controller;
use Think\Model\MongoModel;

class IndexController extends Controller {
	public function index() {
		// C('TMPL_ENGINE_TYPE', 'Think');
		//printR(get_defined_constants());
		$this->show ();
	}
	public function hello() {
		echo "OK";
	}
	public function test() {
		$data = array (
				'name' => 'ruyi',
				'sex' => 'men',
				'age' => 1 
		);
		echo serialize($data);
		// 批量保存session
		session_save_values ( $data );
		printR ( $_SESSION );
		
		// $Model = M ( "User" );
		// $Model->create ();
		// $Model->name = '流年2';
		// $Model->add ();
		// $a = $Model->select ();
		// var_dump ( $a );
		
		//$Model = M ( "User" );
		//$Model->find ();
		
		// $data = array ();
		// $data ['name'] = 'ruyi';
		// $data ['sex'] = 'boy';
		// $id = $Model->add ( $data );
		// print_r ( $id );
	}
}