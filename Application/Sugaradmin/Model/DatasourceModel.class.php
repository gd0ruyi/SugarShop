<?php

namespace Sugaradmin\Model;

/**
 * 数据源管理模型
 *
 * @author gd0ruyi@163.com 2020-01-06
 *        
 */
class DatasourceModel extends BaseModel
{
	// 使用D实例化时，需要设置模型的表名称
	protected $tableName = 'data_source';
	protected $pk = 'source_id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	// 使用public用于BaseController处理
	public $fields = array(
		'_id',
        'source_id',
        'db_cname',
        'db_connect',
		'db_name',
        'db_password',
        'db_encoded',
		'add_time',
		'upd_time',
		'las_time',
		'status',
		'use_type',
		'_type' => array(
			'source_id' => 'int',
			'db_cname' => 'string',
			'db_connect' => 'string',
            'db_name' => 'string',
            'db_password' => 'string',
            'db_encoded' => 'string',
			'add_time' => 'int',
			'upd_time' => 'int',
			'las_time' => 'int',
			'status' => 'int',
			'use_type' => 'int'
		)
	);

	// thinkphp自动验证
	protected $_validate = array(
		array('source_id', '', '数据源ID错误，该ID已存在！', self::EXISTS_VALIDATE, 'unique', self::MODEL_INSERT),
		array('db_cname', 'require', '数据源名称不能为空！', self::EXISTS_VALIDATE),
		array('db_connect', 'require', '数据源连接不能为空！', self::EXISTS_VALIDATE)
	);

	// 自动完成
	protected $_auto = array(
		array('add_time', 'time', self::MODEL_INSERT, 'function'),
		array('upd_time', 'time', self::MODEL_BOTH, 'function')
	);
}
