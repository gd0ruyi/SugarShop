/**
 * 公共JS方法加载
 **/
var SugarCommons = {
	coms: {},
	commons_name: 'SugarCommons',
	// 消息对话框ID
	msg_dialog_tpl_id: '#msg-dialog-tpl',
	// 编辑对话框ID:
	edit_dialog_tpl_id: '#edit-dialog-tpl',

	// 全局ajax是否使用缓存（暂时无用，后续需加入到方法）
	ajaxCache: false,

	// 是否开启debug信息
	debug: false,

	// 是否开启debug后的信息强制输出
	printDebug: false,

	/**
	 * 公用函数库名称设置
	 * @param {string} commons_namess
	 */
	setCommonsName: function (commons_name) {
		// 初始化验证
		var commons_name = this.checkValue('commons_name', commons_name, 'string', 'setCommonsName');

		// 克隆赋值
		this.coms[commons_name] = Object.assign({}, this);;
		this.coms[commons_name].commons_name = commons_name;
		return this.coms[commons_name];
	},

	/**
	 * 常规检查值
	 * @param {string} key 检查的键名
	 * @param {string} value 检查的键值
	 * @param {string} type 检查的类型
	 * @param {string|object|function} function_name 当前方法的名称
	 * @param {boolean} is_throw 校验发现错误时，是否进行退出，默认为true
	 * @param {ajaxObject} currentAjax ajax的当前句柄，若需要停止时，可传入处理
	 * @returns {any|boolean} 返回检查通过值；当is_throw为false时，检查不通过将返回false；
	 */
	checkValue: function (key, value, check_type, function_name, is_throw, currentAjax) {
		var check_type = check_type ? check_type : 'string';
		var error = '';
		var function_name = function_name;
		var is_throw = is_throw === undefined ? true : is_throw;

		// 自动将方法或者对象转成名称
		switch (typeof (function_name)) {
			case 'function':
				function_name = this.substringBetween(function_name, 'function ', '(');
				break;
			case 'object':
				function_name = object;
			default:
				function_name = function_name + '()';
				break;
		}

		switch (check_type.toLocaleLowerCase()) {
			// 检查值是否为字符串
			case 'string':
				if (typeof (value) != 'string' || value == '' || value == '#') {
					error = this.commons_name + " is error by " + function_name + ": the type of '" + key + "' must be string, input value is '" + value + "'";
				}
				break;
			case 'number':
				if (typeof (value) != 'number') {
					error = this.commons_name + " is error by " + function_name + ": the type of '" + key + "' must be number, input value is '" + value + "'";
				}
				break;
			case 'object':
				if (typeof (value) != 'object') {
					error = this.commons_name + " is error by " + function_name + ": the type of '" + key + "' must be object, input value is '" + value + "'";
				}
				// 检查是否为空
				if ($.isEmptyObject(value)) {
					error = this.commons_name + " is error by " + function_name + ": '" + key + "' is empty, input value is '" + value + "'";
				}
				break;
			case 'function':
				if (typeof (value) != 'function') {
					error = this.commons_name + " is error by " + function_name + ": the type of '" + key + "' must be function, input value is '" + value + "'";
				}
				break;
			default:
				// 默认为字符串检查
				if (typeof (value) != 'string' || value == '' || value == '#') {
					error = this.commons_name + " is error by " + function_name + ": the type of '" + key + "' must be string, input value is '" + value + "'";
				}
				break;
		}

		// 如果存在错误
		if (error != '') {
			this.errorMsg(error, is_throw, currentAjax);
			return false;
		}

		return value;
	},

	/**
	 * 自定义错误提示
	 * @param {string} error 错误信息
	 * @param {boolean} is_throw 是否进行throw退出
	 * @param
	 */
	errorMsg: function (error, is_throw, currentAjax) {
		error = 'Error info >> ' + error;
		console.log(error);

		// 如果存在ajax句柄，则先暂停处理
		if (currentAjax !== undefined) {
			currentAjax.abort();
		}

		if (is_throw) {
			alert(error);
			return error;
		}
		throw alert(error);
	},

	/**
	 * 常用自定义截取开始结束字符间的值
	 * @param {any} str 
	 * @param {string} start_str 
	 * @param {string} end_str 
	 * @returns string
	 */
	substringBetween: function (str, start_str, end_str) {
		var str = str.toString();
		var start_index = str.indexOf(start_str) + start_str.length;
		var end_index = str.indexOf(end_str);
		return str.substring(start_index, end_index);
	},

	/**
	 * 创建select下拉，用于表单提交。
	 * @param {string} target_id Bootstrap下拉元素ID
	 */
	createSelectInput: function () {
		var target_selector = '[sugar-selector="true"]';

		$(target_selector).each(function (index) {
			var that = this;

			// 下拉点击事件
			$(that).find('ul.dropdown-menu li a').on('click', function (event) {
				var sval = $(this).attr('value');
				var stitle = $(this).html();

				// 禁用点击跳转
				event.preventDefault();

				// 判断value值是否存在
				if (typeof ($(this).attr('value')) == 'undefined') {
					SugarCommons.errorMsg($(this).html() + ' value is not find');
					return false;
				}

				// 判断是否存在input隐藏输入框
				if ($(that).is('input[type="hidden"]')) {
					SugarCommons.errorMsg($(that).html() + ' input[type="hidden"] is not find');
					return false;
				}

				// 赋值
				$(that).find('input[type="hidden"]').val(sval);
				// 显示选中
				$(that).find('button[data-toggle="dropdown"] font').html(stitle);
			});
		});
	},

	/**
	 * 确定对话框弹窗处理
	 * @param {string} title 对话框标题
	 * @param {string} content 对话框内容
	 * @param {function} callback 对话框确定回调方法
	 */
	createConfirmDialog: function (title, content, callback) {
		var target_id = SugarCommons.msg_dialog_tpl_id;
		$(target_id + ' #msg-title').html(title);
		$(target_id + ' #msg-content').html(content);
		$(target_id).modal('show');

		$(target_id + ' .modal-footer .btn-primary').click(callback);
	},

	/**
	 * 创建弹窗编辑对话框
	 */
	createEditDialogByAjax: function () {
		var target_selector = '[sugar-dialog]';
		// 需要保留历史记录，因此sugar-dialog需要加入ID标识
		// 遍历自定义属性处理
		$(target_selector).each(function (index) {
			var that = this;
			$(that).on('click', function (event) {
				event.preventDefault();
				var title = $(that).attr('title');
				var url = $(that).attr('sugar-url');
				var data = $(that).attr('sugar-data');
				var target_id = $(that).attr('sugar-target-id');
				SugarCommons.runEditDialogByAjax(target_id, title, url, data);
			});
		});
	},

	/**
	 * 执行弹出对话框
	 * @param {string} target_id 动态的targetID
	 * @param {string} title 弹窗标题
	 * @param {string} url 弹窗加载地址
	 * @param {string} data 字符串的对象"{}"
	 */
	runEditDialogByAjax: function (target_id, title, url, sugar_data) {
		// 动态的targetID
		var target = "#" + target_id;
		// 弹出对话框的默认模版ID
		var modal_target_id = SugarCommons.edit_dialog_tpl_id;
		$(modal_target_id).modal('show');
		$(modal_target_id + ' .modal-body').attr('id', target_id);

		// loading的选择器名称
		var loading_target_title = modal_target_id + ' .edit-title';
		$(loading_target_title).html(title);

		// 判断地址是否为空
		if (url == "") {
			SugarCommons.errorMsg("SugarCommons plus is error:: ajax error in function runEditDialogByAjax(), sugar-url is empty !");
		}

		sugar_data = sugar_data ? sugar_data : {};
		// 判断debug是否开启
		if (SugarCommons.debug == true) {
			sugar_data.debug = true;
			sugar_data.printDebug = SugarCommons.printDebug;
		}

		// 显示加载对应的信息
		var loading_waiting_id = TimeKeeper.loadingWaitingStart(target, SugarTabs.loading_waiting_speed, 'inner');
		$(target).find('.loading-title').html(title);

		// ajax请求处理
		$.ajax({
			url: url,
			type: "GET",
			dataType: "html",
			data: sugar_data,
			cache: SugarCommons.ajaxCache,
			success: function (res, status, xhr) {
				// 内容
				$(target).html(res);
				// 关闭加载
				TimeKeeper.loadingWaitingEnd(target);
			},
			error: function (xhr, status, error) {
				// 关闭加载
				TimeKeeper.loadingWaitingEnd(target);
				// 关闭加载状态
				SugarCommons.errorMsg("SugarCommons plus is error: ajax error in function runEditDialogByAjax(), info(" + status + ":" + error + ")");
			}
		});
	},

	createPlugin: function () {
		SugarCommons.createSelectInput();
		SugarCommons.createEditDialogByAjax();
	}

}

// jquery通用扩展
$.extend({
	"count": function (obj) {
		var count = 0;
		if (typeof (obj) != "object" && typeof (obj) != "function") {
			return count;
		}
		$.each(obj, function (k, v) {
			count++;
		});
		return count;
	}
});