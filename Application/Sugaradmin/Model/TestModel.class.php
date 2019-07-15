<?php

namespace Sugaradmin\Model;

/**
 * 测试用的模型
 * 
 * @author gd0ruyi@163.com 2019-1-16
 *        
 */
class TestModel extends BaseModel {
	protected $pk = 'test_id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	protected $fields = array (
			'_id',
			'test_id',
			'name',
			'cname',
			'status',
			'_type' => array (
					'test_id' => 'int',
					'name' => 'string',
					'cname' => 'string',
					'status' => 'int'
			) 
	);
}