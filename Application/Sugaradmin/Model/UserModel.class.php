<?php

namespace Sugaradmin\Model;

/**
 * 管理员用户模型
 * 注：需要使用D方法声明时才可以使用自动校验，新版本的M方法会先找D，然后再找M，M方法可以不写对应的M类。
 *
 * @author gd0ruyi@163.com 2015-11-12
 *        
 */
class UserModel extends BaseModel
{
	// 使用D实例化时，需要设置模型的表名称
	protected $tableName = 'user';
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

	// thinkphp自动验证
	protected $_validate = array(
		array('user_id', '', '用户ID错误，该ID已存在！', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
		array('username', 'require', '管理员用户名不能为空！', self::MUST_VALIDATE),
		array('username', '', '用户名称已存在！', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
		array('password', 'require', '管理员密码不能为空！', self::MUST_VALIDATE)
	);

	// 自动完成
	protected $_auto = array(
		array('add_time', 'time', self::MODEL_INSERT, 'function'),
		array('upd_time', 'time', self::MODEL_BOTH, 'function')
	);
}
