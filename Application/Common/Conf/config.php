<?php
return array (
		
		/**
		 * 路由配置
		 */
		// URL模式
		'URL_MODEL' => '2',
		
		// 不区分URL大小写
		'URL_CASE_INSENSITIVE' => true,
		
		// 默认入口
		'DEFAULT_MODULE' => 'Home',
		
		/**
		 * 数据库设置
		 */
		// 数据库类型
		'DB_TYPE' => 'mongo',
		
		// 服务器地址
		'DB_HOST' => 'localhost',
		
		// 数据库名
		'DB_NAME' => 'sugarShop',
		
		// 用户名
		'DB_USER' => 'sugarShop',
		
		// 密码
		'DB_PWD' => 'sugarShop',
		
		// 端口
		'DB_PORT' => '27017',
		
		// 数据库表前缀
		'DB_PREFIX' => '',
		
		// 数据库连接参数
		'DB_PARAMS' => array (),
		
		// 数据库调试模式 开启后可以记录SQL日志
		'DB_DEBUG' => TRUE,
		
		// 启用字段缓存
		'DB_FIELDS_CACHE' => false,
		
		// 数据库编码默认采用utf8
		'DB_CHARSET' => 'utf8',
		
		// 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
		'DB_DEPLOY_TYPE' => 0,
		
		// 数据库读写是否分离 主从式有效
		'DB_RW_SEPARATE' => false,
		
		// 读写分离后 主服务器数量
		'DB_MASTER_NUM' => 1,
		
		// 指定从服务器序号
		'DB_SLAVE_NO' => '',
		
		// 关闭字段缓存
		'DB_FIELDS_CACHE' => false,
		
		// MongoDB开启自动创建索引（在查询时自动创建）
		'MONGODB_AUTO_CREATE_INDEX' => false,
		
		// MongoDB自增ID的集合名称
		'MONGODB_AUTO_INC_COLL' => 'counters',
		
		/**
		 * 模版配置
		 */
		// 使用smarty
		'TMPL_ENGINE_TYPE' => 'Smarty',
		
		// smarty配置
		'TMPL_ENGINE_CONFIG' => array (
				'plugins_dir' => array (
						'./Application/Smarty/Plugins/',
						'./ThinkPHP/Vendor/Smarty/plugins/' 
				),
				'CACHE_LIFETIME' => 60,
				'DEBUGGING' => false 
		),
		'TMPL_CACHE_ON' => false,
		'TMPL_L_DELIM' => '<{',
		'TMPL_R_DELIM' => '}>',
		
		// 简化模板的目录层次
		// 'TMPL_FILE_DEPR'=>'_',
		
		// 模板后缀
		'TMPL_TEMPLATE_SUFFIX' => '.tpl',
		
		/**
		 * 其他配置
		 */
		// session配置，使用数据库。
		'SESSION_TYPE' => 'Mongodb',
		
		// 显示调试输出
		'SHOW_PAGE_TRACE' => true,
		
		// 验证码配置
		'VERIFY_CONFIG' => array (
				
				// 是否开启验证码处理
				'IS_OPEN' => true,
				
				// 验证码图片配置
				'IMG_CONFIG' => array (
						
						// 验证码字体大小
						'fontSize' => 20,
						
						// 验证码图片宽度
						'imageW' => 0,
						
						// 验证码图片高度
						'imageH' => 40,
						
						// 验证码位数
						'length' => 4,
						
						// 验证码字体
						'fontttf' => '5.ttf',
						
						// 验证码时效时长（秒）
						'expire' => 60,

						//是否允许接收GET参数进行自动生成验证
						'allow_get_set' => true
				) 
		),
		
		// 默认错误跳转对应的模板文件
		'TMPL_ACTION_ERROR' => 'Public:notice',
		
		// 默认成功跳转对应的模板文件
		'TMPL_ACTION_SUCCESS' => 'Public:notice' 
);
