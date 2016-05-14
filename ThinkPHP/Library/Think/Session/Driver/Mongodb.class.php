<?php

namespace Think\Session\Driver;

/**
 * MongoDB的SESSION处理扩展类
 *
 * @author gd0ruyi@163.com 2015-11-8
 *        
 */
class Mongodb {
	protected $lifeTime = 3600;
	protected $collection = 'session';
	protected $mongo_handle = null;
	protected $db_handle = null;
	protected $coll_handle = null;
	
	/**
	 * 打开Session
	 *
	 * @access public
	 * @param string $savePath        	
	 * @param mixed $sessName        	
	 */
	public function open($savePath, $sessName) {
		// 初始化配置
		$this->lifeTime = C ( 'SESSION_EXPIRE' ) ? C ( 'SESSION_EXPIRE' ) : $this->lifeTime;
		$config = array (
				'hostname' => C ( 'DB_HOST' ),
				'database' => C ( 'DB_NAME' ),
				'username' => C ( 'DB_USER' ),
				'password' => C ( 'DB_PWD' ),
				'hostport' => C ( 'DB_PORT' ),
				'dsn' => C ( 'DB_DNS' ),
				'params' => C ( 'DB_PARAMS' ),
				'charset' => C ( 'DB_CHARSET' ),
				'DB_PREFIX' => C ( 'DB_PREFIX' ),
				'debug' => C ( 'DB_DEBUG' ) 
		);
		$server = 'mongodb://' . ($config ['username'] ? "{$config['username']}" : '') . ($config ['password'] ? ":{$config['password']}@" : '') . $config ['hostname'] . ($config ['hostport'] ? ":{$config['hostport']}" : '') . '/' . ($config ['database'] ? "{$config['database']}" : '');
		$server = $config ['dsn'] ? $config ['dsn'] : $server;
		try {
			$this->mongo_handle = new \MongoClient ( $server, $config ['params'] );
			// \MongoCursor::$timeout = - 1;
			
			$db = $config ['database'] ? $config ['database'] : $config ['params'] ['database'];
			$this->db_handle = $this->mongo_handle->selectDB ( $db );
			$this->coll_handle = $this->db_handle->selectCollection ( $this->collection );
			
			// 自动创建索引
			$index_info = $this->coll_handle->getIndexInfo ();
			$had_index = false;
			foreach ( $index_info as $value ) {
				if ($value ['name'] == 'sessID_1') {
					$had_index = true;
				}
			}
			if (! $had_index) {
				$keys = array (
						'sessID' => 1 
				);
				$options = array (
						'background' => true 
				);
				$this->coll_handle->ensureIndex ( $keys, $options );
			}
		} catch ( \MongoConnectionException $e ) {
			E ( $e->getmessage () );
		}
		return true;
	}
	
	/**
	 * 关闭Session
	 *
	 * @access public
	 */
	public function close() {
		$this->gc ( $this->lifeTime );
		$this->mongo_handle->close ();
		$this->mongo_handle = null;
		$this->db_handle = null;
		$this->coll_handle = null;
		return true;
	}
	
	/**
	 * 读取Session
	 *
	 * @access public
	 * @param string $sessID        	
	 */
	public function read($sessID) {
		$query = array ();
		$query ['sessID'] = $sessID;
		$rs = $this->coll_handle->findOne ( $query );
<<<<<<< HEAD
		$_SESSION = $rs ['sessData'];
		return session_encode ();
=======
		return serialize($rs['sessData']);
>>>>>>> d5d777e3b10f4aba2a979ce8426f1a4d257e70e8
	}
	
	/**
	 * 写入Session
	 *
	 * @access public
	 * @param string $sessID        	
	 * @param String $sessData        	
	 */
	public function write($sessID, $sessData) {
		$data = array ();
		$query = array ();
		$field = array ();
		$_id = null;
		
		$query ['sessID'] = $sessID;
		$field ['_id'] = 1;
		
		$rs = $this->coll_handle->findOne ( $query, $field );
		$options = array ();
		// $options ['fsync'] = true;
		// $options ['safe'] = true;
		
		if (empty ( $_SESSION ) || empty ( $sessData )) {
			return true;
		}
		
<<<<<<< HEAD
		// natsort ( $_SESSION );
=======
		if (empty ( $_SESSION )) {
			return true;
		}
		
		natsort ( $_SESSION );
>>>>>>> d5d777e3b10f4aba2a979ce8426f1a4d257e70e8
		try {
			if (isset ( $rs ['_id'] ) && $rs ['_id']) {
				$data ['$set'] ['sessID'] = $sessID;
				$data ['$set'] ['sessData'] = $_SESSION;
				$data ['$set'] ['last_ip'] = get_client_ip ();
				$data ['$set'] ['lifeTime'] = time () + $this->lifeTime;
				$options ['multiple'] = false;
				return $this->coll_handle->update ( $query, $data, $options );
			} else {
				$data ['sessID'] = $sessID;
				$data ['sessData'] = $_SESSION;
				$data ['last_ip'] = get_client_ip ();
				$data ['lifeTime'] = time () + $this->lifeTime;
				return $this->coll_handle->insert ( $data, $options );
			}
		} catch ( \MongoCursorException $e ) {
			E ( $e->getMessage () );
		}
	}
	
	/**
	 * 删除Session
	 *
	 * @access public
	 * @param string $sessID        	
	 */
	public function destroy($sessID) {
		$query = array ();
		$query ['_id'] = $sessID;
		$options = array ();
		$options ['justOne'] = true;
		return $this->coll_handle->remove ( $query, $options );
	}
	
	/**
	 * Session 垃圾回收
	 *
	 * @access public
	 * @param string $sessMaxLifeTime        	
	 */
	public function gc($sessMaxLifeTime) {
		$query = array ();
		$query ['lifeTime'] = array ();
		$query ['lifeTime'] ['$lt'] = time ();
		$options = array ();
		$options ['justOne'] = false;
		return $this->coll_handle->remove ( $query, $options );
	}
}
