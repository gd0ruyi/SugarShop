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

	// alert提示框模版ID（用于内嵌的alert消息提示）
	alert_tpl_id: '#alert-tmp',

	// confirm提示框模版ID（用于弹出的消息提示）
	msg_dialog_tpl: '#msg-dialog-tpl',

	// 全局ajax是否使用缓存（暂时无用，后续需加入到方法）
	ajaxCache: false,

	// 是否开启debug信息
	debug: false,

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
	 * @param {boolean} currentAjax 是否进行ajax暂停处理
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
	 * 注：取消该方法，使用为makeConfirm
	 * @param {string} title 对话框标题
	 * @param {string} content 对话框内容
	 * @param {function} callback 对话框确定回调方法
	 */
	/* createConfirmDialog: function (title, content, callback) {
		var target_id = SugarCommons.msg_dialog_tpl_id;
		$(target_id + ' #msg-title').html(title);
		$(target_id + ' #msg-content').html(content);
		$(target_id).modal('show');

		$(target_id + ' .modal-footer .btn-primary').click(callback);
	}, */

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

		// 判断原有模版是否存在，不存在重新绑定ID
		if ($(target).length <= 0) {
			$(modal_target_id + ' .modal-body').attr('id', target_id);
		}

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
		}

		// 显示加载对应的信息
		// var loading_waiting_id = TimeKeeper.loadingWaitingStart(target, SugarTabs.loading_waiting_speed, 'inner');
		TimeKeeper.loadingWaitingStart(target, SugarTabs.loading_waiting_speed, 'inner');
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

	/**
	 * 通用插件加载创建
	 */
	createPlugin: function () {
		SugarCommons.createSelectInput();
		SugarCommons.createEditDialogByAjax();
	},

	/**
	 * 设置inptu框loading加载样式，用于表单input异步验证使用。
	 * @param {sting} target input的目标标识
	 * @param {boolean} show 是否显示读取动画
	 */
	makeInputLoadingCss: function (target, show) {
		if (show) {
			$(target).show();
			$(target).addClass("glyphicon-refresh");
			$(target).addClass("animation");
		} else {
			$(target).removeClass("glyphicon-refresh");
			$(target).removeClass("animation");
		}
	},

	/**
	 * 自动强制校正数字输入,使用自定义样式进行处理
	 */
	createInputForceToNumber: function () {
		$(".forceToNumber").keyup(function () {
			$(this).val($(this).val().replace(/[^\d]/g, ''));
			//CTR+V事件处理
		}).bind("paste", function () {
			$(this).val($(this).val().replace(/[^\d]/g, ''));
			//CSS设置输入法不可用
		}).css("ime-mode", "disabled");
	},

	/**
	 * 获取ajax的处理数据类型
	 */
	getAjaxDataType: function () {
		// 定义传输类型
		var data_type = 'json';

		// 判断debug是否开启
		if (SugarCommons.debug == true) {
			data_type = 'html';
		}
		return data_type;
	},

	/**
	 * bootstrapValidator表单验证提交方式
	 * @param {bvObject} e bootstrapValidator的bv方式使用的e对象
	 */
	bvAjaxSubmit: function (e) {
		// 取消提交
		e.preventDefault();

		// 获取表单元素
		var $form = $(e.target);

		//表单数据
		var $data = $form.serializeJson();

		// 获取BootstrapValidator对象
		// var bv = $form.data('bootstrapValidator');

		// 禁用提交按钮
		// $(e.target).bootstrapValidator('disableSubmitButtons', true);
		// 开启禁用
		SugarCommons.setFromDisabled(e.target, true);

		// ajax提交处理
		$.ajax({
			url: $form.attr('action'),
			type: 'POST',
			dataType: SugarCommons.getAjaxDataType(),
			data: $data,
			cache: SugarCommons.ajaxCache,
			success: function (res, status, xhr) {
				// 解除禁用
				SugarCommons.setFromDisabled(e.target, false);
				// 重新启用提交按钮
				// $($form).bootstrapValidator('disableSubmitButtons', false);

				// 当为debug时处理
				if (SugarCommons.debug == true) {
					SugarCommons.makeInnerAlert(e.target, 'alert-info', 'Debug Info:', res);
					return true;
				}
				
				// 返回成功时处理
				if (res.status == 0) {
					res.title = res.title ? res.title : 'success';
					res.msg = res.msg ? res.msg : 'is ok';
					SugarCommons.makeInnerAlert(e.target, 'alert-success', res.title, res.msg);
					// 重置表单内容
					$form[0].reset();
					// 表单验证重置
					$form.data('bootstrapValidator').resetForm();
				} else {
					// 业务逻辑处理错误抛出
					res.title = res.title ? res.title : 'danger';
					res.msg = res.msg ? res.msg : 'is error';
					SugarCommons.makeInnerAlert(e.target, 'alert-danger', res.title, res.msg, 'keep');
				}
			},
			error: function (xhr, status, error) {
				// 关闭加载状态
				SugarCommons.errorMsg("SugarCommons plus is error: ajax error in function bvAjaxSubmit(), info(" + status + ":" + error + ")");
			}
		});
	},

	/**
	 * 创建alert提示框，为植入式提示
	 * @param {string} target 目标容器
	 * @param {sting} alertClass Bootstrap的aler样式
	 * @param {sting} title 提示框的标题
	 * @param {sting} msg 提示框的内容
	 * @param {int||string} closeTime 自动关闭时间，若为keep则不进行关闭
	 */
	makeInnerAlert: function (target, alertClass, title, msg, closeTime) {
		// 模版内容赋值
		var tpl = $(SugarCommons.alert_tpl_id).html();

		// 添加入目标容器
		$(target).prepend(tpl);

		// 添加样式
		$(target).find('[role="alert"]').addClass(alertClass);
		// 赋值标题和消息内容
		$(target).find('[alert-id="title"]').html(title);
		$(target).find('[alert-id="msg"]').html(msg);

		// 判断是否自动关闭
		if (closeTime != 'keep') {
			// 默认关闭窗口时间
			closeTime = parseInt(closeTime) ? parseInt(closeTime) : 6000;
			// 定时自动关闭
			setTimeout(function () {
				$('[alert-id="close"]').click();
			}, closeTime);
		}
	},

	/**
	 * 弹窗确认提示对话框
	 * @param {Object} options 构造参数{title:string, msg:sting, sureClick:callback function(), cancelClick:callback function()}
	 */
	makeConfirm: function (options) {
		var target = SugarCommons.msg_dialog_tpl;
		options.title = options.title ? options.title : 'confirm title';
		options.msg = options.msg ? options.msg : 'confirm content';
		// 赋值标题和消息内容
		$(target).find('#msg-title').html(options.title);
		$(target).find('#msg-content').html(options.msg);
		// 显示弹窗
		$(target).modal('show');

		// 执行调用
		// 覆盖原有点击确定时的调用
		$(target).find('#msg-btn-sure').unbind('click');
		$(target).find('#msg-btn-sure').click(function (e) {
			if (typeof (options.sureClick) == 'function') {
				options.sureClick(e);
				// alert('sureClick is ok');
				$(target).modal('hide');
			}
		});

		// 覆盖原有点击取消时的调用
		$(target).find('#msg-btn-cancel').unbind('click');
		$(target).find('#msg-btn-cancel').click(function (e) {
			if (typeof (options.cancelClick) == 'function') {
				options.cancelClick(e);
				// alert('cancelClick is ok');
				$(target).modal('hide');
			}
		});
	},

	/**
	 * 创建表单重置按钮
	 * @param {string} target 表单的标识
	 */
	createRestFormButton: function (target) {
		// 取消重复绑定
		$(target).unbind('click');

		// 重置按钮处理
		$(target).find('[type="reset"]').click(function (e) {
			// 取消重置事件
			e.preventDefault();
			// 弹出对话框处理
			SugarCommons.makeConfirm({
				title: '提示',
				msg: '请确认是否重置？',
				sureClick: function (e) {
					// 表单内容重置
					$(target)[0].reset();
					// 表单验证重置
					$(target).data('bootstrapValidator').resetForm();
				},
				cancelClick: function (e) {
					// alert('cancle is ok');
				}
			});
		});
	},

	/**
	 * 对表单元素内的填写项进行禁用
	 * @param {string} target 表单标识
	 * @param {boolean} disabled 是否进行禁用
	 */
	setFromDisabled: function (target, disabled) {
		if (disabled) {
			$(target).find('input').attr('disabled', true);
			$(target).find('select').attr('disabled', true);
			$(target).find('button').attr('disabled', true);
		} else {
			$(target).find('input').removeAttr('disabled');
			$(target).find('select').removeAttr('disabled');
			$(target).find('button').removeAttr('disabled');
		}
	}
}

// jquery通用扩展
$.extend({
	// 用于计数
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

// 自定义表单序列化对象提交方法
$.fn.serializeJson = function () {
	var sObj = {};
	var sArry = this.serializeArray();
	$(sArry).each(function () {
		//如果属性已经存在
		if (sObj[this.name]) {
			if ($.isArray(sObj[this.name])) {
				//已经有此数组，添加数据即可
				sObj[this.name].push(this.value);
			} else {
				//变成数组，并添加当前遍历的value
				sObj[this.name] = [sObj[this.name], this.value];
			}
		} else {
			//普通赋值。
			sObj[this.name] = this.value;
		}
	});
	return sObj;
};