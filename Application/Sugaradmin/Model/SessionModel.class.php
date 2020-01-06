<?php

namespace Sugaradmin\Model;

/**
 * 管理员用户模型
 * 注：需要使用D方法声明时才可以使用自动校验，新版本的M方法会先找D，然后再找M，M方法可以不写对应的M类。
 *
 * @author gd0ruyi@163.com 2015-11-12
 *        
 */
class SessionModel extends BaseModel
{
	// 使用D实例化时，需要设置模型的表名称
	protected $tableName = 'session';
	protected $pk = 'sessID';
	protected $_idType = self::TYPE_STRING;
}