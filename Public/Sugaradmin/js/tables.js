// 自定义动态表单加载

var SugarTables = {
	// 内置对象栈，用于多控件
	sts: {},
	// 公共函数对象，必须要加载自定义的commons.js文件
	comms: SugarCommons.setCommonsName('SugarTables'),
	version: '1.0',

	// 表格默认配置
	table_thead: '<thead></thead>',
	table_tbody: '<tbody></tbody>',
	table_tr: '<tr></tr>',
	table_th: '<th></th>',
	table_td: '<td></td>',

	// 表格工具头模版
	table_toolbar_tpl_id: '#table-toolbar-tpl-id',
	// 默认的联合排序按钮名称
	sort_multiply_btn: 'sort-multiply',
	// 设置字段下拉的名称
	table_setting_btn: 'table-setting',

	// 转义的标点符号
	template_symbol_pre: "{",
	template_symbol_suf: "}",

	// 排序元素
	sort_icon: '<span class="sort-icon" type="submit"><font class="glyphicon glyphicon-triangle-top"></font><font class="glyphicon glyphicon-triangle-bottom"></font></span>',
	// 是否复合排序的默认值
	sort_multiply: false,
	sort_input_name: 'sort',

	// 操作按钮元素
	button_html: '<button type="button" class="btn"><span class="glyphicon"></span></button>',

	// 分页配置
	// 是否自动创建分页，默认自动创建
	pager_auto_create: true,
	// 分页模版的ID
	pager_tpl_id: '#pager-default-tpl',
	pager_size: 10,

	// 继承默认的tab的配置，也可重新赋值
	loading_waiting_speed: SugarTabs.loading_waiting_speed,
	loading_waiting_style: 'inner',

	// ajax默认提交方式
	ajax_type: "POST",

	/**
	 * 创建控件主方法
	 * @param {string} form_id 表单ID
	 * @param {string} table_id 表单内的表格ID
	 * @param {string} url 表格控件请求加载jeson数据的地址
	 * @param {object} columns 表格控件的列配置
	 * @param {string} title 表格的标题名称，用于显示loading的标题
	 * @param {boolean} toolbar 表格头部工具自定义的传参{toolbar-name:{ btnClass,btnIconClass,btnClick}}
	 * @returns SugarTables 返回整个对象
	 */
	create: function (form_id, table_id, url, columns, title, toolbar) {
		// 清理无效对象
		SugarTables.destructor();

		// 验证部份
		SugarTables.comms.checkValue('form_id', form_id, 'string', 'create');
		SugarTables.comms.checkValue('table_id', table_id, 'string', 'create');
		SugarTables.comms.checkValue('url', url, 'string', 'create');
		SugarTables.comms.checkValue('columns', columns, 'object', 'create');

		// 表单初始化
		SugarTables.sts[form_id] = {};
		SugarTables.sts[form_id].form = {};
		SugarTables.sts[form_id].form.form_id = form_id;
		// 访问地址
		SugarTables.sts[form_id].form.url = url;
		SugarTables.sts[form_id].form.form = $(form_id);
		SugarTables.createForm(form_id, url);
		//是否执行ajax中
		SugarTables.sts[form_id].form.ajax_is_loading = false;

		// 表格初始化
		SugarTables.sts[form_id].table = {};
		SugarTables.sts[form_id].table.table_id = table_id;
		SugarTables.sts[form_id].table.table = $(table_id);
		SugarTables.sts[form_id].table.table_thead = {};
		SugarTables.sts[form_id].table.table_tbody = {};
		// 表格列的配置
		SugarTables.sts[form_id].table.columns = columns;
		SugarTables.sts[form_id].table.columns_count = $.count(columns);
		// 表格获取的数据
		SugarTables.sts[form_id].table.table_data = {};
		// 表格标题
		var title = title === undefined ? $(table_id).attr("title") : title;
		title = title ? title : table_id + '(Table is not set title)';
		SugarTables.sts[form_id].table.title = title;
		// 表格排序信息
		SugarTables.sts[form_id].table.sort_multiply = SugarTables.sort_multiply;
		SugarTables.sts[form_id].table.sort = {};

		// 表格自定义工具栏赋值
		toolbar = toolbar ? toolbar : {};
		SugarTables.sts[form_id].table.toolbar = toolbar;

		// 分页信息初始化
		var pager_id = form_id + '-pager';
		var pager_tpl_id = SugarTables.pager_tpl_id;

		// 分页信息初始化
		SugarTables.sts[form_id].pager = {};
		SugarTables.sts[form_id].pager.pager_auto_create = SugarTables.pager_auto_create;
		SugarTables.sts[form_id].pager.pager_id = pager_id;

		SugarTables.sts[form_id].pager.pager_tpl_id = pager_tpl_id;
		SugarTables.sts[form_id].pager.pager = {};

		// 判断是否自动创建分页
		if (SugarTables.sts[form_id].pager.pager_auto_create == true) {
			SugarTables.createPager(form_id, pager_id, pager_tpl_id);
		}

		// 创建表头
		SugarTables.createTableThead(form_id, table_id, columns);

		// ajax请求产生表体
		SugarTables.createTableTbody(form_id, table_id, url, columns);

		// 创建表格头部工具栏按钮
		SugarTables.createTableToolbar(form_id, table_id, toolbar);

		// console.log(SugarTables.sts);

		return SugarTables;
	},

	/**
	 * 创建表单提交的方法
	 * @param {string} form_id 表单ID
	 * @param {string|object} url 表单、表格内容ajax访问的地址，或直接为数据对象
	 * @returns $(form_id) 表单句柄对象
	 */
	createForm: function (form_id, url) {
		// 验证部份
		SugarTables.comms.checkValue('form_id', form_id, 'string', 'createForm');
		SugarTables.comms.checkValue('url', url, 'string', 'createForm');

		// 表单查询和分页提交方法
		$(form_id).submit(function (e) {
			e.preventDefault();
			SugarTables.ajaxRequest(form_id);
			return false;
		});

		// 重新赋值
		SugarTables.sts[form_id].form.form_id = form_id;
		SugarTables.sts[form_id].form.url = url;
		SugarTables.sts[form_id].form.form = $(form_id);

		return $(form_id);
	},

	/**
	 * 创建表头
	 * @param {string} form_id 表单ID
	 * @param {string} table_id 表单内的表格ID
	 * @param {object} columns 表格控件的列配置
	 * @returns table_thead 表格头部句柄对象
	 */
	createTableThead: function (form_id, table_id, columns) {
		// 验证部份
		SugarTables.comms.checkValue('form_id', form_id, 'string', 'createTableThead');
		SugarTables.comms.checkValue('table_id', table_id, 'string', 'createTableThead');
		SugarTables.comms.checkValue('columns', columns, 'object', 'createTableThead');

		// 表头定义	
		var $table_thead = $(SugarTables.table_thead);
		var $table_tr = $(SugarTables.table_tr);
		var columns = columns ? columns : SugarTables.sts[form_id].table.columns;

		// 遍历配置
		$.each(columns, function (index, column) {

			var $table_th = $(SugarTables.table_th);

			// th配置验证
			if (column.th == undefined) {
				SugarTables.comms.errorMsg("SugarTables plus is error: columns[" + index + "] is empty!");
			}
			// 自定义列名赋值
			column.th.column_name = index;
			// id赋值处理
			column.th.id = index;
			// 默认不使用title属性时，将为index的名称
			column.th.title = column.th.title == undefined ? index : column.th.title;

			// 标题长度默认值
			column.th.title_length = column.th.title_length == undefined ? column.th.title.length : parseInt(column.th.title_length);
			if (column.th.title_length <= 0) {
				SugarTables.comms.errorMsg("SugarTables plus is error: columns[" + index + "].th.title.length is <=0!");
			}

			// 标题过长处理
			if (column.th.title.length > column.th.title_length) {
				$table_th.html(column.th.title.substring(0, column.th.title_length) + '...');
			} else {
				$table_th.html(column.th.title);
			}

			// 加入checkbox按钮
			if (column.th.checkbox == true) {
				var $checkbox = $('<label><input type="checkbox" name="' + index + '-all" /> ' + $table_th.html() + '</label>');
				$checkbox.find('input').on('click', function () {
					if ($(this).is(':checked')) {
						$(form_id).find('[name="' + index + '"]').each(function () {
							$(this).prop('checked', true);
						});
					} else {
						$(form_id).find('[name="' + index + '"]').each(function () {
							$(this).prop('checked', false);
						});
					}
				});
				$table_th.html($checkbox);
			}

			// 加入排序按钮（排序处理）
			if (column.th.sort_icon == true) {
				var sort_icon = $(SugarTables.sort_icon);
				var sort_attr_key = index + '-sort';

				// 样式初始化处理及赋值处理，默认为倒序，当多个sort_default字段默认为最后一个
				if (column.th.sort_default != undefined) {
					if ((column.th.sort_default).toLowerCase() == 'desc') {
						$(sort_icon).find(".glyphicon-triangle-bottom").addClass("on");
						$(sort_icon).attr(sort_attr_key, "desc");
						SugarTables.sts[form_id].table.sort[index] = 'desc';
					} else {
						$(sort_icon).find(".glyphicon-triangle-top").addClass("on");
						$(sort_icon).attr(sort_attr_key, "asc");
						SugarTables.sts[form_id].table.sort[index] = 'asc';
					}
				}

				// 排序点击事件处理
				$(sort_icon).click(function () {
					// 判断是否重复提交
					if (SugarTables.sts[form_id].form.ajax_is_loading == true) {
						return false;
					}

					// 赋值，单字段排序时去掉其他排序字段，最终用于传参
					if (SugarTables.sts[form_id].table.sort_multiply == false) {
						SugarTables.sts[form_id].table.sort = {};
						// 移除全部排序on样式
						$(table_id + ' th').find(".glyphicon").removeClass("on");
					}

					if ($(this).attr(sort_attr_key) == 'desc') {
						$(this).find(".glyphicon-triangle-top").addClass("on");
						$(this).find(".glyphicon-triangle-bottom").removeClass("on");
						$(sort_icon).attr(sort_attr_key, "asc");
						SugarTables.sts[form_id].table.sort[index] = 'asc';
					} else {
						$(sort_icon).find(".glyphicon-triangle-bottom").addClass("on");
						$(this).find(".glyphicon-triangle-top").removeClass("on");
						$(sort_icon).attr(sort_attr_key, "desc");
						SugarTables.sts[form_id].table.sort[index] = 'desc';
					}
					// 使用表单提交进行请求
					$(form_id).submit();
					// console.log(SugarTables.sts[form_id].table.sort);
				});


				$table_th.append(sort_icon);
			}

			// 显示赋值处理
			column.th.display = column.th.display != undefined ? column.th.display : true;

			// th通用属性赋值处理
			$.each(column.th, function (key, value) {
				$table_th.attr(key, value);
			});

			// 显示处理
			if (column.th.display == false) {
				$table_th.addClass('hidden');
			}

			// tr添加th
			$table_tr.append($table_th);
			// thead添加tr
			$table_thead.append($table_tr);

			// 处理后重新赋值
			columns[index] = column;
		});

		// table添加thead		
		$(table_id).html($table_thead);

		// 重新赋值
		SugarTables.sts[form_id].table.table = $(table_id);
		SugarTables.sts[form_id].table.columns = columns;
		SugarTables.sts[form_id].table.table_thead = $table_thead;

		// 返回表格头部句柄对象
		return $table_thead;
	},

	/**
	 * 创建表体
	 * @param {string} form_id 表单的ID
	 * @param {string} table_id 表格的ID
	 * @param {string|object} url 表单、表格内容ajax访问的地址，或直接为数据对象
	 * @param {object} columns 表格控件的列配置
	 * @returns table_tbody 表格句柄对象
	 */
	createTableTbody: function (form_id, table_id, url, columns) {
		// 初始化验证
		SugarTables.comms.checkValue('form_id', form_id, 'string', 'createTableTbody');
		SugarTables.comms.checkValue('table_id', table_id, 'string', 'createTableTbody');
		SugarTables.comms.checkValue('columns', columns, 'object', 'createTableTbody');

		// 判断是否为路径
		if (typeof (url) == 'string') {
			var title = $(table_id).attr('title');
			SugarTables.ajaxRequest(form_id, table_id, url, columns, title);

		} else {
			table_data = url;
			SugarTables.makeTbody(form_id, table_id, table_data, columns);
		}
	},

	/**
	 * ajax请求
	 * @param {string} form_id 表单的ID
	 * @param {string} table_id 表格的ID
	 * @param {string} url 表格内容ajax访问的地址
	 * @param {object} columns 表格控件的列配置
	 */
	ajaxRequest: function (form_id, table_id, url, columns, title) {
		// 初始化验证
		SugarTables.comms.checkValue('form_id', form_id, 'string', 'ajaxRequest');
		// 默认使用原有form_id对应的table_id
		var table_id = table_id == undefined ? SugarTables.sts[form_id].table.table_id : table_id;
		SugarTables.comms.checkValue('table_id', table_id, 'string', 'ajaxRequest');
		// 默认使用原有form_id对应的url
		var url = url == undefined ? SugarTables.sts[form_id].form.url : url;
		SugarTables.comms.checkValue('url', url, 'string', 'string', 'ajaxRequest');
		// 默认使用原有form_id对应的columns

		var columns = columns == undefined ? SugarTables.sts[form_id].table.columns : columns;
		// 默认使用原有form_id对应的title
		var title = title == undefined ? SugarTables.sts[form_id].table.title : title;

		// 判断是否重复提交
		if (SugarTables.sts[form_id].form.ajax_is_loading == true) {
			return false;
		}
		// 禁用提交处理
		SugarTables.sts[form_id].form.ajax_is_loading = true;
		// $(form_id).find('[type="submit"]').attr("disabled", "true");		

		// 排序按钮禁用样式
		$(form_id).find('[type="submit"]').addClass("disabled");
		// return false;


		// 创建表格的loading的tbody
		var td_loading_id = table_id.substring(1) + '-tbody-td';
		var tbody_td = SugarTables.table_td;
		tbody_td = $(tbody_td).attr("id", td_loading_id);
		tbody_td = $(tbody_td).attr("colspan", SugarTables.sts[form_id].table.columns_count);

		var tbody_tr = SugarTables.table_tr;
		tbody_tr = $(tbody_tr).append(tbody_td);

		var tbody = SugarTables.table_tbody;
		tbody = $(tbody).append(tbody_tr);

		// 替换加载条td的id
		td_loading_id = '#' + td_loading_id;

		// 移除所有的tbody（层级）内容
		$(td_loading_id).parent().parent().remove();

		// 加载前移除所有的tbody内容
		$(table_id).find("tbody").remove();

		// 创建表格的loading的tbody
		$(table_id).prepend(tbody);

		// 当为表格内嵌加载等待条时处理
		var loading_waiting_style = SugarTables.loading_waiting_style;
		// 显示加载对应的信息
		title = title ? title : SugarTables.sts[form_id].table.title;
		TimeKeeper.loadingWaitingStart(td_loading_id, title, SugarTables.loading_waiting_speed, loading_waiting_style);

		// console.log($(form_id).serialize());

		// 表提交数据
		var form_data = $(form_id).serializeJson();
		// 获取排序字段的参数
		form_data.sort = JSON.stringify(SugarTables.sts[form_id].table.sort);
		// debug参数处理
		form_data.debug = SugarCommons.debug;
		// console.log(form_data);

		// 获取值后方可禁用处理
		SugarCommons.setFromDisabled(form_id, true);

		// ajax请求处理
		$.ajax({
			url: url,
			type: SugarTables.ajax_type,
			dataType: SugarCommons.getAjaxDataType(),
			data: form_data,
			cache: SugarCommons.ajaxCache,
			success: function (res, status, xhr) {

				// 当为debug模式时
				if (SugarCommons.debug) {
					var debugHtml = (res + '').split('<!--Source Code End-->');
					res = $.parseJSON(debugHtml[0]);
					// console.log(debugHtml);
					// 输出printDbueg信息
					$(form_id).after(debugHtml[1]);
				}

				// 当自定义状态码为成功是处理
				if (res.status == 0) {
					// 关闭加载
					TimeKeeper.loadingWaitingEnd(td_loading_id);

					// 产生动态表格
					SugarTables.makeTbody(form_id, table_id, res.data, columns);

					// 分页信息处理
					if (SugarTables.pager_auto_create == true) {
						SugarTables.makePager(form_id, res.pager);
					}

				} else {
					// 关闭加载
					TimeKeeper.loadingWaitingEnd(td_loading_id);
					SugarTables.comms.errorMsg("SugarTables plus is error: ajax error in function ajaxRequest(), info(" + res.msg + ")");
				}
				// 关闭加载状态
				SugarTables.sts[form_id].form.ajax_is_loading = false;
				// $(form_id).find('[type="submit"]').removeAttr("disabled");
				SugarCommons.setFromDisabled(form_id, false);
				$(form_id).find('[type="submit"]').removeClass("disabled");
			},
			error: function (xhr, status, error) {
				// 关闭加载状态
				SugarTables.sts[form_id].form.ajax_is_loading = false;
				$(form_id).find('[type="submit"]').removeAttr("disabled");
				$(form_id).find('[type="submit"]').removeClass("disabled");
				SugarTables.comms.errorMsg("SugarTables plus is error: ajax error in function ajaxRequest(), info(" + status + ":" + error + ")");
			}
		});

		// 重新赋值
		SugarTables.sts[form_id].form.url = url;
		SugarTables.sts[form_id].table.table_id = table_id;
		SugarTables.sts[form_id].table.table = $(table_id);
		SugarTables.sts[form_id].table.columns = columns;
		SugarTables.sts[form_id].table.title = title;
	},

	/**
	 * 创建表体
	 * @param {string} form_id 表单的ID
	 * @param {string} table_id 表格的ID
	 * @param {object} table_data 表格数据对象
	 * @param {object} columns 表格控件的列配置
	 * @returns table_tbody 表格句柄对象
	 */
	makeTbody: function (form_id, table_id, table_data, columns) {
		// 初始化验证
		SugarTables.comms.checkValue('form_id', form_id, 'string', 'makeTbody');
		SugarTables.comms.checkValue('table_id', table_id, 'string', 'makeTbody');
		SugarTables.comms.checkValue('columns', columns, 'object', 'makeTbody');

		// 定义赋值
		var $table_tbody = $(SugarTables.table_tbody);
		// 定义表行
		var $table_tr = $(SugarTables.table_tr);
		//定义单元格
		var $table_td = $(SugarTables.table_td);
		var td_content = "暂无数据";

		// 空数据处理
		if ($.isEmptyObject(table_data)) {
			// 对象赋值
			SugarTables.sts[form_id].table.table_data = table_data;
			// td内容添加			
			$table_td.html(td_content);
			$table_td.attr("colspan", SugarTables.sts[form_id].table.columns_count);
			$table_td.addClass("text-center");
			// tr添加td
			$table_tr.append($table_td);
			// tbody添加tr
			$table_tbody.append($table_tr);

			// 移除原有tbody
			$(table_id).find('tbody').remove();
			// 表格添加tbody
			$(table_id).append($table_tbody);


			// 重新赋值
			SugarTables.sts[form_id].table.table = $(table_id);
			// SugarTables.sts[form_id].table.columns = columns;
			SugarTables.sts[form_id].table.table_tbody = $table_tbody;

			// 返回表格表体句柄对象
			return $table_tbody;
		}
		SugarTables.comms.checkValue('table_data', table_data, 'object', 'makeTbody');

		// 对象赋值
		SugarTables.sts[form_id].table.table_data = table_data;

		// 遍历数据（每行）
		$.each(table_data, function (i, row) {
			// 定义表行
			var $table_tr = $(SugarTables.table_tr);

			// 遍历配置（每列），用于处理TD
			$.each(columns, function (index, column) {
				//重新定义单元格
				$table_td = $(SugarTables.table_td);
				td_content = row[index] == undefined ? index + '-undefined' : row[index];

				// 默认初始化处理
				// 如果没有td赋值,将重新赋值
				if (column.td === undefined || !column.td) {
					column.td = {};
					column.td.title = table_id;
				}
				// 自定义列名赋值
				column.td.column_name = index;
				// 当为特殊字符operation，表示为操作处理，因此td标题为空
				column.td.title = index == 'operation' ? '' : td_content;

				// td模版配置替换处理
				if (column.td.template !== undefined) {
					if (typeof (column.td.template) == 'string') {
						// 保留原有模版信息，并用于替换
						td_content = column.td.template

						// 再次全部遍历替换，用于支持全部字段替换
						$.each(row, function (rk, rc) {
							//创建正则RegExp对象
							var reg = new RegExp(SugarTables.template_symbol_pre + rk + SugarTables.template_symbol_suf, "g");
							td_content = String(td_content).replace(reg, row[rk]);
						});

						// 替换后的赋值title
						column.td.title = td_content;

						// td默认内容长度处理
						column.td.content_length = column.td.content_length == undefined ? 0 : parseInt(column.td.content_length);

						// td内容过长截取处理
						if (column.td.content_length > 0) {
							td_content = td_content.substring(0, column.td.content_length) + '...';
						}
					}

					// 通过方法回调处理
					if (typeof (column.td.template) == 'function') {
						// 传入当前表格的索引以及当前行的值，需要返回为html内容
						td_content = column.td.template(index, row);
						// 清除td中的title
						column.td.title = '';
					}
				}

				// 加入checkbox按钮
				if (column.th.checkbox == true) {
					var $checkbox = $('<label><input type="checkbox" name="' + index + '" value="' + row[index] + '"/> </label>');
					$checkbox.append(td_content);
					td_content = $checkbox;
				}

				// td内容添加
				$table_td.html(td_content);

				// 操作按钮处理
				if (column.td.btnOptions !== undefined && typeof (column.td.btnOptions) == 'object') {
					// 单元格清空处理
					$table_td.html('');

					// 遍历操作配置项并进行处理
					$.each(column.td.btnOptions, function (optIndex, optValue) {

						var btnHtml = '';
						// 判断是否存在关键字并且为函数方法
						if (optIndex.indexOf('CustomBtn') > -1 && typeof (optValue) == 'function') {
							btnHtml = optValue(index, row);
						} else {
							// 按钮默认的处理方式
							btnHtml = SugarTables._createTdBtnDefalut(optIndex, optValue, row);
						}
						// 添加入单元格内
						$table_td.append(btnHtml);
					});
				}

				// 显示赋值处理
				column.th.display = column.th.display != undefined ? column.th.display : true;

				// td通用属性赋值处理
				$.each(column.td, function (key, value) {
					// 过滤处理,操作按钮设置和模版设置参数不加入内
					if (key != 'btnOptions' && key !== 'template') {
						$table_td.attr(key, value);
					}
				});

				// 显示处理
				if (column.th.display == false) {
					$table_td.addClass('hidden');
				}

				// tr添加td
				$table_tr.append($table_td);
			});

			// tbody添加tr
			$table_tbody.append($table_tr);
		});

		// 移除原有tbody
		$(table_id).find('tbody').remove();
		// 表格添加tbody
		$(table_id).append($table_tbody);


		// 重新赋值
		SugarTables.sts[form_id].table.table = $(table_id);
		// SugarTables.sts[form_id].table.columns = columns;
		SugarTables.sts[form_id].table.table_tbody = $table_tbody;

		// 返回表格表体句柄对象
		return $table_tbody;
	},

	/**
	 * 创建单元格操作按钮（内置用）
	 * 
	 * @param {string} optIndex 每个单元格按钮配置的索引
	 * @param {array|object} optValue 每个单元格配置参数
	 * @param {array|object} row 传入每行的值
	 */
	_createTdBtnDefalut: function (optIndex, optValue, row) {
		// 默认初始化处理
		var btnTitle = optValue.title !== undefined ? optValue.title : 'undefined';
		var btnClass = optValue.btnClass !== undefined && optValue.btnClass ? optValue.btnClass : '';
		var btnIconClass = optValue.btnIconClass !== undefined && optValue.btnIconClass ? optValue.btnIconClass : '';

		// 后续需移出html代码，作为模版属性赋值
		// var btnHtml = '<button type="button" name="' + optIndex + '" title="' + btnTitle + '" class="' + btnClass + '" ></button>';		
		// var btnIconHtml = btnIconClass ? '<span class="' + btnIconClass + '"></span>' : btnTitle;
		// 加入icon图标
		// btnHtml = $(btnHtml).html(btnIconHtml);
		var $btnHtml = $(SugarTables.button_html);
		$btnHtml.attr('name', optIndex);
		$btnHtml.attr('title', btnTitle);
		$btnHtml.addClass(btnClass);

		if (btnIconClass) {
			$btnHtml.find('span').addClass(btnIconClass);
		} else {
			$btnHtml.html(btnTitle);
		}

		// 处理操作按钮所需的数据
		var optData = optValue.data !== undefined ? optValue.data : '';

		// 判断格式
		if (typeof (optData) == 'object') {
			// 清空赋值
			optData = {};
			// 多值处理
			$.each(optValue.data, function (odi, odv) {
				optData[odi] = row[odv];
				optData[odv] = row[odv];
			});

		} else {
			SugarTables.comms.errorMsg("SugarTables plus is error: btnOptions[" + optIndex + "] is not array!");
		}

		// 处理点击调用
		if (optValue.btnClick !== undefined && typeof (optValue.btnClick) == 'function') {
			$btnHtml.click(function (e) {
				optValue.btnClick(e, optData);
			});
		}
		return $btnHtml;
	},

	/**
	 * 创建表格工具
	 * 注：自定义按钮请放到table_toolbar_tpl_id对应ID的html模版内即可
	 * @param {string} form_id 表单ID
	 * @param {string} table_id 表单内的表格ID
	 * @param {object} toolbar 表格头部工具自定义的传参{toolbar-name:{ btnClass,btnIconClass,btnClick}}
	 * @returns $(toolbar) 表格工具句柄
	 */
	createTableToolbar: function (form_id, table_id, toolbar) {
		// 默认的toolbar处理
		var $toolbarDf = $($(SugarTables.table_toolbar_tpl_id).html());

		// 默认组合排序按钮处理
		$toolbarDf.find('[toolbar-name="sort-multiply"]').click(function () {
			if (SugarTables.sts[form_id].table.sort_multiply == true) {
				$(this).removeClass("btn-info active");
				$(this).addClass("btn-default");
				SugarTables.sts[form_id].table.sort_multiply = false;
			} else {
				$(this).removeClass("btn-default");
				$(this).addClass("btn-info active");
				SugarTables.sts[form_id].table.sort_multiply = true;
			}
		});

		// 自定义显示表格列处理
		var columns = SugarTables.sts[form_id].table.columns;

		// 遍历columns配置
		$.each(columns, function (index, column) {
			var title = column.th.title ? column.th.title : index;
			var $li = $($(SugarTables.table_toolbar_tpl_id).find('[toolbar-name="table-setting-li"]').html());

			$li.find('input').attr('value', index);
			// 判断是否有隐藏的字段
			if (column.th.display != undefined) {
				$li.find('input').prop('checked', column.th.display);
			}
			$li.find('font').html(title);

			// 添加入li
			$toolbarDf.find('[toolbar-name="table-setting-li"]').after($li);
		});

		// 模版已无用，因此移除加入的模版
		$toolbarDf.find('[toolbar-name="table-setting-li"]').remove();

		// 全选按钮操作
		$toolbarDf.find('[toolbar-name="table-setting-all"]').click(function () {
			var ckav = $(this).is(':checked');
			$toolbarDf.find('[toolbar-name="table-setting-menu"] input').each(function () {
				$(this).prop('checked', ckav);
			});
		});

		// 不使用Bootstrap自带的下拉菜单处理事件，因此通过自定义处理
		$toolbarDf.find('[toolbar-name="table-setting"]').click(function () {
			$(this).parent().addClass('open');
		});

		// 点击确定显示列处理
		$toolbarDf.find('[toolbar-name="table-setting-sure"]').click(function () {
			// 处理赋值
			$toolbarDf.find('[toolbar-name="table-setting-menu"] input:not([toolbar-name="table-setting-all"])').each(function (k, v) {
				var key = $(v).val();
				if ($(v).is(':checked')) {
					$(table_id).find('[column_name="' + key + '"]').each(function () {
						$(this).removeClass('hidden');
					});
				} else {
					$(table_id).find('[column_name="' + key + '"]').each(function () {
						$(this).addClass('hidden');
					});
				}
			});

			// 隐藏下拉
			$toolbarDf.find('[toolbar-name="table-setting-menu"]').parent().removeClass('open');
		});

		// 在表格之前创建
		$(table_id).parent().before($toolbarDf);
		// return $toolbar;
	},

	makeTableToolbar: function (form_id, table_id, toolbar) { },

	/**
	 * 创建表格的分页项
	 * @param {string} form_id 表单ID
	 * @param {string} pager_id 分页ID
	 * @param {string} pager_tpl_id 分页模版ID
	 * @return pager 分页对象
	 */
	createPager: function (form_id, pager_id, pager_tpl_id) {
		// 初始化验证
		SugarTables.comms.checkValue('form_id', form_id, 'string', 'createPager');
		SugarTables.comms.checkValue('pager_id', pager_id, 'string', 'createPager');
		SugarTables.comms.checkValue('pager_tpl_id', pager_tpl_id, 'string', 'createPager');

		// 定义
		var pager = $(pager_id);

		// pager模版复制
		pager = $(pager_tpl_id).html();
		pager = $(pager).attr('id', pager_id);

		// 移除原有存在的
		$(pager_id).remove();
		// 添加分页
		$(form_id).append(pager);

		// 分页信息重新赋值
		SugarTables.sts[form_id].pager.pager_id = pager_id;
		SugarTables.sts[form_id].pager.pager_tpl_id = pager_tpl_id;
		SugarTables.sts[form_id].pager.pager = $(pager);
		// 分页数据
		SugarTables.sts[form_id].pager.page_now = 1;
		SugarTables.sts[form_id].pager.page_total = 0;
		SugarTables.sts[form_id].pager.pager_size = SugarTables.pager_size;
		SugarTables.sts[form_id].pager.pager_rows = 0;
		SugarTables.sts[form_id].pager.page_pre = 1;
		SugarTables.sts[form_id].pager.page_next = 1;

		// 构建事件
		// 第一页
		$(form_id).find('.pager-first').click(function () {
			$(form_id).find('.page-now').val(1);
			$(form_id).submit();
			return false;
		});

		// 上一页
		$(form_id).find('.pager-pre').click(function () {
			$(form_id).find('.page-now').val(SugarTables.sts[form_id].pager.page_pre);
			$(form_id).submit();
			return false;
		});

		// 下一页
		$(form_id).find('.pager-next').click(function () {
			$(form_id).find('.page-now').val(SugarTables.sts[form_id].pager.page_next);
			$(form_id).submit();
			return false;
		});

		// 最后页
		$(form_id).find('.pager-last').click(function () {
			$(form_id).find('.page-now').val(SugarTables.sts[form_id].pager.page_total);
			$(form_id).submit();
			return false;
		});

		// 设置分页尺寸
		$(form_id).find('.pager-size').change(function () {
			$(form_id).submit();
			return false;
		});

		SugarTables.resetPager(form_id);

		return SugarTables.sts[form_id].pager;
	},

	/**
	 * 执行分页，主要用于ajax获取分页信息后调用
	 * @param {string} form_id 表单ID
	 * @param {object} pager_data 分页的数据
	 */
	makePager: function (form_id, pager_data) {
		form_id = form_id == undefined ? SugarTables.sts[form_id].form_id : form_id;
		// 当前页
		var page_now = parseInt(pager_data.nowPage);
		page_now = page_now <= 0 ? 1 : page_now;
		// 总页数
		var page_total = parseInt(pager_data.totalPages);
		// 每页条数
		var pager_size = parseInt(pager_data.listRows);
		// 共计条数
		var pager_rows = parseInt(pager_data.totalRows);
		// 上一页
		var page_pre = page_now - 1;
		page_pre = page_pre <= 0 ? 1 : page_pre;
		// 下一页
		var page_next = page_now + 1;
		page_next = page_next >= page_total ? page_total : page_next;

		// 重新赋值
		SugarTables.sts[form_id].pager.page_now = page_now;
		SugarTables.sts[form_id].pager.page_total = page_total;
		SugarTables.sts[form_id].pager.pager_size = pager_size;
		SugarTables.sts[form_id].pager.pager_rows = pager_rows;
		SugarTables.sts[form_id].pager.page_pre = page_pre;
		SugarTables.sts[form_id].pager.page_next = page_next;

		SugarTables.resetPager(form_id);

		return SugarTables.sts[form_id].pager;
	},

	/**
	 * 重置分页显示信息
	 * @param {string} form_id 表单ID
	 */
	resetPager: function (form_id) {
		// 当前页
		$(form_id).find('.page-now').val(SugarTables.sts[form_id].pager.page_now);

		// 共计页数
		$(form_id).find('.page-total').html(SugarTables.sts[form_id].pager.page_total);

		// 每页条数
		$(form_id).find('.pager-size').val(SugarTables.sts[form_id].pager.pager_size);

		// 每页尺寸
		$(form_id).find('.pager-size').val(SugarTables.sts[form_id].pager.pager_size);

		// 共计条数
		$(form_id).find('.pager-rows').html(SugarTables.sts[form_id].pager.pager_rows);

		return SugarTables.sts[form_id].pager;
	},

	/**
	 * 析构，用于自动释放无效的栈
	 */
	destructor: function () {
		$.each(SugarTables.sts, function (form_id, form) {
			// 删除无效的表单对象
			if ($(form_id).index() == -1) {
				delete SugarTables.sts[form_id];
				// console.log("SugarTables.sts remove form by index:" + form_id);
			}
		});
	}
}