/**
 * 公共JS方法加载
 **/
var SugarCommons = {
	coms: {},
	commons_name: 'SugarCommons',

	/**
	 * 公用函数库名称设置
	 * @param {string} commons_name 
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