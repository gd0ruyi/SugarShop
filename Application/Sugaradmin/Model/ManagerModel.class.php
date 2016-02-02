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
	protected $pk = 'manager_id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	protected $fields = array (
			'_id',
			'manager_id',
			'username',
			'password',
			'add_time',
			'upd_time',
			'las_time',
			'_type' => array (
					'manager_id' => 'int',
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
	
	/**
	 * 分页对象，默认使用ThinkPHP的分页方法
	 *
	 * @var object
	 */
	protected $pager = null;
	
	/**
	 * 获取当前列表
	 *
	 * @param array||string $options
	 *        	查询条件数组
	 * @param string $order
	 *        	排序字符串
	 * @param number $page        	
	 * @param number $pice        	
	 * @return string
	 */
	public function getList($options = array(), $order = '', $page = 0, $pice = 10) {
		$page = intval ( $page );
		$pice = intval ( $pice );
		$rs = array ();
		if ($page > 0) {
			$count = $this->where ( $options )->count ();
			$rs = $this->page ( $page, $pice );
			$this->pager = new \Think\Page ( $count, $pice );
			$this->pager->show ();
			// echo $pager->totalPages . "|" . $pager->totalRows;
		}
		$rs = $rs->select ( $options );
		$formart = C ( 'DATE_FORMAT.default' );
		foreach ( $rs as $key => $value ) {
			foreach ( $value as $k => $v ) {
				if (stristr ( $k, '_time' )) {
					$rs [$key] [$k] = date ( $formart, $v );
				}
			}
		}
		return $rs;
	}
	
	/**
	 * 获取分页对象
	 *
	 * @return object
	 */
	public function getPager() {
		return $this->pager;
	}
}