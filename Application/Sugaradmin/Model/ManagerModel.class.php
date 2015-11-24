<?php

namespace Sugaradmin\Model;

use Think\Model\MongoModel;

/**
 * 管理员用户模型
 *
 * @author gd0ruyi@163.com 2015-11-12
 *        
 */
class ManagerModel extends MongoModel {
	protected $pk = 'id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	protected $fields = array (
			'_id',
			'id',
			'username',
			'password',
			'add_time',
			'upd_time',
			'las_time',
			'_type' => array (
					'id' => 'int',
					'username' => 'string',
					'password' => 'string',
					'add_time' => 'int',
					'upd_time' => 'int',
					'las_time' => 'int'
			) 
	);
	protected $_validate = array (
			array (
					'username',
					'require',
					'管理员用户名不能为空！',
					self::MUST_VALIDATE 
			),
			array (
					'pwd',
					'require',
					'管理员密码不能为空！',
					self::MUST_VALIDATE 
			) 
	);
}