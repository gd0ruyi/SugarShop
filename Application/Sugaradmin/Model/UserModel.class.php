<?php

namespace Sugaradmin\Model;

/**
 * 管理员用户模型
 *
 * @author gd0ruyi@163.com 2015-11-12
 *        
 */
class UserModel extends BaseModel
{
	protected $pk = 'user_id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	protected $fields = array(
		'_id',
		'user_id',
		'username',
		'password',
		'truename',
		'email',
		'mobile',
		'add_time',
		'upd_time',
		'las_time',
		'status',
		'use_type',
		'_type' => array(
			'user_id' => 'int',
			'username' => 'string',
			'password' => 'string',
			'truename' => 'string',
			'email' => 'string',
			'mobile' => 'string',
			'add_time' => 'int',
			'upd_time' => 'int',
			'las_time' => 'int',
			'status' => 'int',
			'use_type' => 'int'
		)
	);
	protected $_validate = array(
		array(
			'username',
			'require',
			'管理员用户名不能为空！',
			self::MUST_VALIDATE
		),
		array(
			'pwd',
			'require',
			'管理员密码不能为空！',
			self::MUST_VALIDATE
		)
	);
}
