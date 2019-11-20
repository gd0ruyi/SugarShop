<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Model;

use Think\Model;

/**
 * MongoModel模型类
 * 实现了ODM和ActiveRecords模式
 */
class MongoModel extends Model
{
	// 主键类型
	const TYPE_OBJECT = 1;
	const TYPE_INT = 2;
	const TYPE_STRING = 3;

	// 主键名称
	protected $pk = '_id';
	// _id 类型 1 Object 采用MongoId对象 2 Int 整形 支持自动增长 3 String 字符串Hash
	protected $_idType = self::TYPE_OBJECT;
	// 主键是否自增
	protected $_autoinc = true;
	// Mongo默认关闭字段检测 可以动态追加字段
	protected $autoCheckFields = false;
	// 链操作方法列表
	protected $methods = array(
		'table',
		'order',
		'auto',
		'filter',
		'validate'
	);

	/**
	 * 最终插入的自增ID
	 *
	 * @var int
	 */
	protected $lastAutoIncId = null;

	/**
	 * mongodb当前指针执行的情况
	 *
	 * @var array()
	 */
	protected $_explain = array();

	/**
	 * 是否开启自动创建索引
	 *
	 * @author gd0ruyi@163.com 2015-11-19
	 * @var boolean
	 */
	protected $_auto_create_index = false;

	/**
	 * 初始化
	 *
	 * @param string $name        	
	 * @param string $tablePrefix        	
	 * @param string $connection        	
	 */
	public function __construct($name = '', $tablePrefix = '', $connection = '')
	{
		parent::__construct($name, $tablePrefix, $connection);
	}

	/**
	 * 利用__call方法实现一些特殊的Model方法
	 *
	 * @access public
	 * @param string $method 方法名称
	 * @param array $args 调用参数
	 * @return mixed
	 */
	public function __call($method, $args)
	{
		if (in_array(strtolower($method), $this->methods, true)) {
			// 连贯操作的实现
			$this->options[strtolower($method)] = $args[0];
			return $this;
		} elseif (strtolower(substr($method, 0, 5)) == 'getby') {
			// 根据某个字段获取记录
			$field = parse_name(substr($method, 5));
			$where[$field] = $args[0];
			return $this->where($where)->find();
		} elseif (strtolower(substr($method, 0, 10)) == 'getfieldby') {
			// 根据某个字段获取记录的某个值
			$name = parse_name(substr($method, 10));
			$where[$name] = $args[0];
			return $this->where($where)->getField($args[1]);
		} else {
			E(__CLASS__ . ':' . $method . L('_METHOD_NOT_EXIST_'));
			return;
		}
	}

	/**
	 * 获取字段信息并缓存 主键和自增信息直接配置
	 *
	 * @access public
	 * @return void
	 */
	public function flush()
	{
		// 缓存不存在则查询数据表信息
		$fields = $this->db->getFields();
		if (!$fields) { // 暂时没有数据无法获取字段信息 下次查询
			return false;
		}
		$this->fields = array_keys($fields);
		foreach ($fields as $key => $val) {
			// 记录字段类型
			$type[$key] = $val['type'];
		}
		// 记录字段类型信息
		if (C('DB_FIELDTYPE_CHECK'))
			$this->fields['_type'] = $type;

		// 2008-3-7 增加缓存开关控制
		if (C('DB_FIELDS_CACHE')) {
			// 永久缓存数据表信息
			$db = $this->dbName ? $this->dbName : C('DB_NAME');
			F('_fields/' . $db . '.' . $this->name, $this->fields);
		}
	}

	/**
	 * 对保存到数据库的数据进行处理
	 *
	 * @access protected
	 * @param mixed $data 要操作的数据
	 * @return boolean
	 */
	protected function _facade($data)
	{
		return parent::_facade($data);
	}

	// 写入数据前的回调方法 包括新增和更新
	protected function _before_write(&$data)
	{
		$pk = $this->getPk();
		// 根据主键类型处理主键数据
		if (isset($data[$pk]) && $this->_idType == self::TYPE_OBJECT) {
			$data[$pk] = new \MongoId($data[$pk]);
		}
		// gd0ruyi@163.com 加入mongoDb的默认主键处理
		if (isset($data['_id']) && !is_object($data['_id'])) {
			$data['_id'] = new \MongoId($data[_id]);
		}
		// gdoruyi@163.com 为了保持存入mongodb的字段顺序性，这里进行重新按定义的field进行顺序排序
		$_stor_data = array();
		foreach ($this->fields as $value) {
			if (isset($data[$value])) {
				$_stor_data[$value] = $data[$value];
			}
		}
		$data = $_stor_data;
	}

	/**
	 * count统计 配合where连贯操作
	 *
	 * @access public
	 * @return integer
	 */
	public function count()
	{
		// 分析表达式
		$options = $this->_parseOptions();
		return $this->db->count($options);
	}

	/**
	 * 获取唯一值
	 *
	 * @access public
	 * @return array | false
	 */
	public function distinct($field, $where = array())
	{
		// 分析表达式
		$this->options = $this->_parseOptions();
		$this->options['where'] = array_merge((array) $this->options['where'], $where);

		$command = array(
			"distinct" => $this->options['table'],
			"key" => $field,
			"query" => $this->options['where']
		);

		$result = $this->command($command);
		return isset($result['values']) ? $result['values'] : false;
	}

	/**
	 * 获取下一ID 用于自动增长型
	 *
	 * @access public
	 * @param string $pk 字段名 默认为主键
	 * @return mixed
	 */
	public function getMongoNextId($pk = '')
	{
		if (empty($pk)) {
			$pk = $this->getPk();
		}
		return $this->db->getMongoNextId($pk);
	}

	/**
	 * 获取插入数据最后ID，用于自动增长值
	 * 注：每次调取该方法时，将会进行自动增长
	 *
	 * @author gd0ruyi@163.com 2019-01-17
	 * @see \Think\Model::getLastInsID()
	 * @param string $pk 主键名称，默认为当前集合的主键
	 * @return integer
	 */
	public function getLastInsID($pk = '')
	{
		if (empty($pk)) {
			$pk = $this->getPk();
		}
		$last_ins_id = $this->db->getLastInsID($pk);
		// $last_ins_id = $last_ins_id <= 0 ? $last_ins_id : $last_ins_id - 1;
		return $last_ins_id;
	}

	/**
	 * 新增数据
	 * 注：关于replac使用的说明，replace为mongo数据库驱动类inser方法的透传。
	 * 
	 * @author ruyi <gd0ruyi@163.com>
	 * @access public
	 * @param mixed $data 数据
	 * @param array $options 表达式
	 * @param boolean $replace 是否使用mongdb的原生方法save还是insert。
	 * @return boolean|array('_id'=>string,'last_id'=>int,data=>array())
	 */
	public function add($data = '', $options = array(), $replace = false)
	{

		if (empty($data)) {
			// 没有传递数据，获取当前数据对象的值
			if (!empty($this->data)) {
				$data = $this->data;
				// 重置数据
				$this->data = array();
			} else {
				$this->error = L('_DATA_TYPE_INVALID_');
				return false;
			}
		}
		// 数据处理
		$data = $this->_facade($data);
		// 分析表达式
		$options = $this->_parseOptions($options);

		// 插入前处理，此处data引用已赋值
		if (false === $this->_before_insert($data, $options)) {
			$this->error = "MongoModel类add方法错误，创建时无法获取自增主键";
			return false;
		}

		// 构造自定义返回结果集
		$result = array();
		// 写入数据到数据库
		$result['rs'] = $this->db->insert($data, $options, $replace);
		$result['_id'] = md5(0);
		$result['last_id'] = 0;
		$result['data'] = $data;

		// 判断结果是否正常
		if (false !== $result['rs'] && $result['rs']['ok'] == 1) {
			$pk = $this->getPk();
			$result['last_id'] = $this->getLastAutoIncId();
			$where = array($pk => $result['last_id']);
			$result['_id'] = $this->where($where)->getField('_id');

			// 增加复合主键支持(原ThinkPHP写法，作用未知)
			if (is_array($pk)) {
				return $result;
			}
		}
		// 插入出现错误
		else {
			$this->error = 'MongoModel类insert出现错误 >> ';
			$this->error .= print_r($result, true);
			return false;
		}

		// 插入后回调处理
		if (false === $this->_after_insert($data, $options)) {
			return false;
		}

		return $result;
	}

	/**
	 * 批量新增数据
	 *
	 * @author gd0ruyi@163.com 2016-01-08
	 * @access public
	 * @param mixed $dataList 数据
	 * @param array $options 表达式
	 * @return mixed
	 */
	public function addAll($dataList = array(), $options = array(), $replace = false)
	{
		if (empty($dataList)) {
			$this->error = L('_DATA_TYPE_INVALID_');
			return false;
		}

		// 分析表达式
		$options = $this->_parseOptions($options);

		// 数据处理
		foreach ($dataList as $key => $data) {
			if (false === $this->_before_insert($data, $options)) {
				return false;
			}
			$dataList[$key] = $data;
		}
		// 开始批量插入
		return $this->db->insertAll($dataList, $options, $replace);
	}

	/**
	 * 保存数据
	 * 
	 * @author ruyi <gd0ruyi@163.com>
	 * @access public
	 * @param mixed $data 数据
	 * @param array $options 表达式
	 * @return mixed|array('_id'=>string,'last_id'=>int)
	 */
	public function save($data = '', $options = array())
	{
		if (empty($data)) {
			// 没有传递数据，获取当前数据对象的值
			if (!empty($this->data)) {
				$data = $this->data;
				// 重置数据
				$this->data = array();
			} else {
				$this->error = L('_DATA_TYPE_INVALID_');
				return false;
			}
		}
		// 数据处理
		$data = $this->_facade($data);
		if (empty($data)) {
			// 没有数据则不执行
			$this->error = L('_DATA_TYPE_INVALID_');
			return false;
		}

		// 分析表达式
		$options = $this->_parseOptions($options);

		// 获取主键名称
		$pk = $this->getPk();

		// 判断查询条件内是否存在主键
		if (!isset($options['where'])) {
			// 如果存在主键数据，并且不为0时，则自动作为更新条件
			if (is_string($pk) && isset($data[$pk]) && $data[$pk] != 0) {
				$where[$pk] = $data[$pk];
				unset($data[$pk]);
			} elseif (is_array($pk)) {
				// 增加复合主键支持
				foreach ($pk as $field) {
					if (isset($data[$field])) {
						$where[$field] = $data[$field];
					} else {
						// 如果缺少复合主键数据则不执行
						$this->error = L('_OPERATION_WRONG_');
						return false;
					}
					unset($data[$field]);
				}
			}

			// 如果没有任何条件则表示创建
			if (!isset($where)) {
				return $this->add($data, $options);
			} else {
				$options['where'] = $where;
			}
		}

		// 更新前回调处理(注：考虑是否加入_id的处理;暂无需考虑)
		if (false === $this->_before_update($data, $options)) {
			return false;
		}

		// 构造自定义返回结果集
		$result = array();
		// 更新数据到数据库
		// (考虑是否需要加入findAndModify处理，findAndModify为原子级-事物处理会用到，可以返回修改之前的值，但是响应比较慢，并且findAndModify可以进行删除操作)
		$result['rs'] = $this->db->update($data, $options);
		$result['data'] = $data;

		// 判断结果是否正常
		if (false === $result['rs'] || $result['rs']['ok'] != 1) {
			$this->error = 'MongoModel类save出现错误 >> ';
			$this->error .= print_r($result, true);
			return false;
		}

		// 更新后调用处理
		if (false === $this->_after_update($data, $options)) {
			return false;
		}
		return $result;
	}

	/**
	 * 插入数据前的回调方法，用于自增处理
	 *
	 * @see \Think\Model::_before_insert()
	 */
	protected function _before_insert(&$data, $options)
	{
		// 判断是否为整型并使用为自增
		if ($this->_autoinc && $this->_idType == self::TYPE_INT) {
			// 获取主键名称
			$pk = $this->getPk();
			// 判断主键是否存在，又或者为0时
			if (!isset($data[$pk]) || $data[$pk] == 0) {
				// 获取自增ID，会更新主键表的ID
				$data[$pk] = $this->lastAutoIncId = $this->db->getMongoNextId($pk);
			}
		}
		// 其他类型自增待补充，暂时无用
	}

	/**
	 * 获取最终插入的主键自增ID
	 *
	 * @author gd0ruyi@163.com 2016-01-08
	 * @return number
	 */
	public function getLastAutoIncId()
	{
		return $this->lastAutoIncId;
	}

	/**
	 * 数据库清除
	 */
	public function clear()
	{
		return $this->db->clear();
	}

	/**
	 * 查询成功后的回调方法
	 *
	 * @see \Think\Model::_after_select()
	 *
	 * @author gd0ruyi@163.com 2019-1-17
	 * @param unknown $resultSet        	
	 * @param array $options 查询条件即设置选项
	 */
	protected function _after_select(&$resultSet, $options)
	{
		array_walk($resultSet, array(
			$this,
			'checkMongoId'
		));

		// 如果debug开启，则获取执行的情况
		if (APP_DEBUG === true && C('DB_DEBUG')) {
			trace(__FUNCTION__ . ' explain : ' . json_encode($this->getExplain()) . ';', '', 'SQL');
		}

		$this->_autoCreateIndex($options);
	}

	/**
	 * 获取MongoId
	 *
	 * @access protected
	 * @param array $result 返回数据
	 * @return array
	 */
	protected function checkMongoId(&$result)
	{
		if (is_object($result['_id'])) {
			$result['_id'] = $result['_id']->__toString();
		}
		return $result;
	}

	/**
	 * 表达式过滤回调方法
	 *
	 * @see \Think\Model::_options_filter()
	 * @return array $options
	 */
	protected function _options_filter(&$options)
	{
		$id = $this->getPk();
		if (isset($options['where'][$id]) && is_scalar($options['where'][$id]) && $this->_idType == self::TYPE_OBJECT) {
			$options['where'][$id] = new \MongoId($options['where'][$id]);
		}
		// gr0ruyi@163.com:当设置为非mongodb默认主键时，却存在_id查询时对_id进行表达式过滤
		if ($id != '_id' && isset($options['where']['_id']) && is_scalar($options['where'][$id])) {
			$options['where']['_id'] = new \MongoId($options['where']['_id']);
		}
		// 对查询条件字段进行排序，以便进行后续的自动创建索引
		isset($options['where']) && is_array($options['where']) ? natsort($options['where']) : false;
		return $options;
	}

	/**
	 * 数据类型检测
	 *
	 * @access protected
	 * @param mixed $data 数据
	 * @param string $key 字段名
	 * @return void
	 */
	protected function _parseType(&$data, $key)
	{
		if (!isset($this->options['bind'][':' . $key]) && isset($this->fields['_type'][$key])) {
			$fieldType = strtolower($this->fields['_type'][$key]);
			if (false !== strpos($fieldType, 'enum')) {
				// 支持ENUM类型优先检测
			} elseif (false === strpos($fieldType, 'bigint') && false !== strpos($fieldType, 'int')) {
				$data[$key] = intval($data[$key]);
			} elseif (false !== strpos($fieldType, 'float') || false !== strpos($fieldType, 'double')) {
				$data[$key] = floatval($data[$key]);
			} elseif (false !== strpos($fieldType, 'bool')) {
				$data[$key] = (bool) $data[$key];
			} elseif (false !== strpos($fieldType, 'mongodate')) {
				$data[$key] = new \MongoDate($data[$key]);
			}
		}
	}

	/**
	 * 查询单条数据，这里为ThinkPHP的find，所以为单条
	 *
	 * @access public
	 * @param mixed $options 表达式参数
	 * @return mixed
	 */
	public function find($options = array())
	{
		if (is_numeric($options) || is_string($options)) {
			$id = $this->getPk();
			$where[$id] = $options;
			$options = array();
			$options['where'] = $where;
		}
		// 分析表达式
		$options = $this->_parseOptions($options);
		// 这里使用的为ThinkPHP定义的find，只会返回单条
		$result = $this->db->find($options);
		if (false === $result) {
			return false;
		}
		if (empty($result)) { // 查询结果为空
			return null;
		} else {
			$this->checkMongoId($result);
		}
		$this->data = $result;
		$this->_after_find($result, $options);
		return $this->data;
	}

	/**
	 * 查询成功的回调方法（用于处理自动索引的创建）
	 *
	 * @author gd0ruyi@163.com 2015-11-19
	 * @see \Think\Model::_after_find()
	 */
	protected function _after_find(&$result, $options)
	{
		// 如果debug开启，则获取执行的情况
		if (APP_DEBUG === true && C('DB_DEBUG')) {
			trace(__FUNCTION__ . ' explain : ' . json_encode($this->getExplain()) . ';', '', 'SQL');
		}
		// 自动创建索引
		$this->_autoCreateIndex($options);
	}

	/**
	 * 字段值增长
	 *
	 * @access public
	 * @param string $field 字段名
	 * @param integer $step 增长值
	 * @return boolean
	 */
	public function setInc($field, $step = 1, $lazyTime = 0)
	{
		return $this->setField($field, array(
			'inc',
			$step
		));
	}

	/**
	 * 字段值减少
	 *
	 * @access public
	 * @param string $field 字段名
	 * @param integer $step 减少值
	 * @return boolean
	 */
	public function setDec($field, $step = 1, $lazyTime = 0)
	{
		return $this->setField($field, array(
			'inc',
			'-' . $step
		));
	}

	/**
	 * 获取一条记录的某个字段值
	 *
	 * @access public
	 * @param string $field 字段名
	 * @param string $spea 字段数据间隔符号
	 * @return mixed
	 */
	public function getField($field, $sepa = null)
	{
		$options['field'] = $field;
		$options = $this->_parseOptions($options);
		if (strpos($field, ',')) { // 多字段
			if (is_numeric($sepa)) { // 限定数量
				$options['limit'] = $sepa;
				$sepa = null; // 重置为null 返回数组
			}
			$resultSet = $this->db->select($options);
			if (!empty($resultSet)) {
				$_field = explode(',', $field);
				$field = array_keys($resultSet[0]);
				$key = array_shift($field);
				$key2 = array_shift($field);
				$cols = array();
				$count = count($_field);
				foreach ($resultSet as $result) {
					$name = $result[$key];
					if (2 == $count) {
						$cols[$name] = $result[$key2];
					} else {
						$cols[$name] = is_null($sepa) ? $result : implode($sepa, $result);
					}
				}
				return $cols;
			}
		} else {
			// 返回数据个数
			if (true !== $sepa) { // 当sepa指定为true的时候 返回所有数据
				$options['limit'] = is_numeric($sepa) ? $sepa : 1;
			} // 查找符合的记录
			$result = $this->db->select($options);
			if (!empty($result)) {
				if (1 == $options['limit']) {
					$result = reset($result);
					return $result[$field];
				}
				foreach ($result as $val) {
					$array[] = $val[$field];
				}
				return $array;
			}
		}
		return null;
	}

	/**
	 * 执行Mongo指令
	 *
	 * @access public
	 * @param array $command 指令
	 * @return mixed
	 */
	public function command($command, $options = array())
	{
		$options = $this->_parseOptions($options);
		return $this->db->command($command, $options);
	}

	/**
	 * 执行MongoCode
	 *
	 * @access public
	 * @param string $code MongoCode
	 * @param array $args 参数
	 * @return mixed
	 */
	public function mongoCode($code, $args = array())
	{
		return $this->db->execute($code, $args);
	}

	// 数据库切换后回调方法
	protected function _after_db()
	{
		// 切换Collection
		$this->db->switchCollection($this->getTableName(), $this->dbName ? $this->dbName : C('db_name'));
	}

	/**
	 * 得到完整的数据表名 Mongo表名不带dbName
	 *
	 * @access public
	 * @return string
	 */
	public function getTableName()
	{
		if (empty($this->trueTableName)) {
			$tableName = !empty($this->tablePrefix) ? $this->tablePrefix : '';
			if (!empty($this->tableName)) {
				$tableName .= $this->tableName;
			} else {
				$tableName .= parse_name($this->name);
			}
			$this->trueTableName = strtolower($tableName);
		}
		return $this->trueTableName;
	}

	/**
	 * 分组查询
	 *
	 * @access public
	 * @return string
	 */
	public function group($key, $init, $reduce, $option = array())
	{
		$option = $this->_parseOptions($option);

		// 合并查询条件
		if (isset($option['where']))
			$option['condition'] = array_merge((array) $option['condition'], $option['where']);

		return $this->db->group($key, $init, $reduce, $option);
	}

	/**
	 * 返回Mongo运行错误信息
	 *
	 * @access public
	 * @return json
	 */
	public function getLastError()
	{
		return $this->db->command(array(
			'getLastError' => 1
		));
	}

	/**
	 * 返回指定集合的统计信息，包括数据大小、已分配的存储空间和索引的大小
	 *
	 * @access public
	 * @return json
	 */
	public function status()
	{
		$option = $this->_parseOptions();
		return $this->db->command(array(
			'collStats' => $option['table']
		));
	}

	/**
	 * 取得当前数据库的对象
	 *
	 * @access public
	 * @return object
	 */
	public function getDB()
	{
		return $this->db->getDB();
	}

	/**
	 * 取得集合对象，可以进行创建索引等查询
	 *
	 * @access public
	 * @return MongoCollection
	 */
	public function getCollection()
	{
		return $this->db->getCollection();
	}

	/**
	 * 取得执行指针
	 *
	 * @author gd0ruyi@163.com 2015-11-23
	 * @return MongoCursor
	 */
	public function getCursor()
	{
		return $this->db->getCursor();
	}

	/**
	 * 获取当前查询执行的情况
	 *
	 * @author gd0ruyi@163.com 2015-11-23
	 * @return array
	 */
	public function getExplain()
	{
		return $this->_explain = $this->getCursor()->explain();
	}

	/**
	 * 自动创建索引（在查询后使用）
	 *
	 * @author gd0ruyi@163.com 2015-11-19
	 * @param array $options        	
	 */
	protected function _autoCreateIndex($options)
	{
		$this->_auto_create_index = C('MONGODB_AUTO_CREATE_INDEX');
		$this->_auto_create_index = isset($options['_auto_create_index']) ? $options['_auto_create_index'] : $this->_auto_create_index;
		if (!$this->_auto_create_index) {
			return false;
		}
		$_index_key = $this->_format_index_key($options);
		if (isset($_index_key['name']) && !empty($_index_key['name'])) {
			$_options = array();
			$_options['name'] = $_index_key['name'];
			$_options['background'] = true;
			$this->getCollection()->ensureIndex($_index_key['values'], $_options);
			if (APP_DEBUG === true && C('DB_DEBUG')) {
				trace('auto create index : ' . $this->connection . '.ensureIndex(' . json_encode($_index_key['values']) . ',' . json_encode($_options) . ');', '', 'SQL');
			}
		}
	}

	/**
	 * 格式化索引
	 *
	 * @author gd0ruyi@163.com 2015-11-20
	 * @param array $options        	
	 * @return array('name'=>string, 'values'=>array())
	 */
	protected function _format_index_key($options)
	{
		$_index_key = array();
		$_index_key['name'] = array();
		$_index_key['values'] = array();

		// 判断查询过程中索引是否生效
		if (empty($this->_explain)) {
			$this->getExplain();
		}
		$cursor = $this->_explain['cursor'];
		$cursor = explode(' ', $cursor);
		// 如果生效则无需进行再次创建索引
		if (isset($cursor[1]) && isset($this->_explain['scanAndOrder'])) {
			return $_index_key;
		}

		// 按条件整理索引
		if (isset($options['where']) && !empty($options['where']) && (is_string($options['where']) || is_array($options['where']))) {
			// 若为字符串处理
			if (is_string($options['where'])) { }

			//
			foreach ($options['where'] as $key => $value) {
				$key = strtolower($key);
				$_index_key['values'][$key] = 1;
				$_index_key['name'][$key] = $key . '_' . 1;
			}
		}

		// 按排序整理索引
		if (isset($options['order']) && !empty($options['order']) && (is_string($options['order']) || is_array($options['order']))) {
			$options['order'] = $this->db->parseOrder($options['order']);

			// 处理索引排序成MongoDB的数字
			foreach ((array) $options['order'] as $key => $value) {
				$key = strtolower($key);
				// $value = $value == 'desc' ? -1 : 1;
				$_index_key['values'][$key] = $value;
				$_index_key['name'][$key] = $key . '_' . $value;
			}
		}

		// 合并成字符串索引名称
		$_index_key['name'] = implode('_', $_index_key['name']);

		// 获取原集合中的索引进行对比，如果前缀符合，则无需进行更新，返回为空。
		$_had_index_key = $this->getIndexInfo();
		$_had_flag = false;
		foreach ($_had_index_key as $value) {
			$_had_str = stripos($value['name'], $_index_key['name']);
			if ($_had_str === 0 || $_had_str > 0) {
				$_had_flag = true;
			}
		}
		return $_had_flag ? array() : $_index_key;
	}

	/**
	 * 获取索引
	 *
	 * @author gd0ruyi@163.com 2015-11-19
	 * @return array
	 */
	public function getIndexInfo()
	{
		return $this->db->getCollection()->getIndexInfo();
	}
}
