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
	// 是否开启表格工具
	table_toolbar_open: true,
	// 表格工具头模版
	table_toolbar_tpl_id: '#table-toolbar-tpl-id',

	// 转义的标点符号
	template_symbol_pre: "{",
	template_symbol_suf: "}",

	// 排序元素
	sort_icon: '<span class="sort-icon" type="submit"><font class="glyphicon glyphicon-triangle-top"></font><font class="glyphicon glyphicon-triangle-bottom"></font></span>',
	// 是否复合排序的默认值
	sort_multiply: false,
	sort_input_name: 'sort',

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
	 * @returns SugarTables 返回整个对象
	 */
	create: function (form_id, table_id, url, columns, title) {
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
		// 表格工具
		SugarTables.sts[form_id].table.table_toolbar_open = SugarTables.table_toolbar_open;
		SugarTables.sts[form_id].table.table_toolbar_tpl_id = SugarTables.table_toolbar_tpl_id;

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

		// 判断是否创建表格工具
		if (SugarTables.sts[form_id].table.table_toolbar_open == true) {
			SugarTables.createTableToolbar(form_id, table_id);
		}

		// 创建表头
		SugarTables.createTableThead(form_id, table_id, columns);

		// ajax请求产生表体
		SugarTables.createTableTbody(form_id, table_id, url, columns);

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
	 * 创建表格工具
	 * @param {string} form_id 表单ID
	 * @param {string} table_id 表单内的表格ID
	 */
	createTableToolbar: function (form_id, table_id) {
		var toolbar = $(SugarTables.sts[form_id].table.table_toolbar_tpl_id).html();
		toolbar = $(toolbar);

		// 组合排序按钮绑定事件
		$(toolbar).find(".glyphicon-magnet").parent().click(function () {
			if (SugarTables.sts[form_id].table.sort_multiply == true) {
				$(this).removeClass("btn-primary active");
				$(this).addClass("btn-default");
				SugarTables.sts[form_id].table.sort_multiply = false;
			} else {
				$(this).removeClass("btn-default");
				$(this).addClass("btn-primary active");
				SugarTables.sts[form_id].table.sort_multiply = true;
			}

		});

		$(table_id).parent().before(toolbar);
		// SugarTables.sts[form_id].form.table_toolbar = toolbar;
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
		var table_thead = SugarTables.table_thead;
		var table_tr = SugarTables.table_tr;
		var columns = SugarTables.sts[form_id].table.columns;

		// 遍历配置
		$.each(columns, function (index, column) {
			var table_th = SugarTables.table_th;

			// th配置验证
			if (column.th == undefined) {
				SugarTables.comms.errorMsg("SugarTables plus is error: columns[" + index + "] is empty!");
			}
			// 默认不使用title属性时，将为index的名称
			column.th.title = column.th.title == undefined ? index : column.th.title;

			// 标题长度默认值
			column.th.title_length = column.th.title_length == undefined ? column.th.title.length : parseInt(column.th.title_length);
			if (column.th.title_length <= 0) {
				SugarTables.comms.errorMsg("SugarTables plus is error: columns[" + index + "].th.title.length is <=0!");
			}

			// 标题过长处理
			if (column.th.title.length > column.th.title_length) {
				table_th = $(table_th).html(column.th.title.substring(0, column.th.title_length) + '...');
			} else {
				table_th = $(table_th).html(column.th.title);
			}

			// th通用属性赋值处理
			$.each(column.th, function (key, value) {
				table_th = $(table_th).attr(key, value);
			});

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


				table_th = $(table_th).append(sort_icon);
			}

			$(table_th).attr('id', index);

			// tr添加th
			table_tr = $(table_tr).append(table_th);
			// thead添加tr
			table_thead = $(table_thead).append(table_tr);

			// 处理后重新赋值
			columns[index] = column;
		});
		//console.log(SugarTables.table_thead);
		//console.log(SugarTables.columns);

		// table添加thead		
		$(table_id).html(table_thead);

		// 重新赋值
		SugarTables.sts[form_id].table.table = $(table_id);
		SugarTables.sts[form_id].table.columns = columns;
		SugarTables.sts[form_id].table.table_thead = $(table_thead);

		// 返回表格头部句柄对象
		return $(table_thead);
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
		$(form_id).find('[type="submit"]').attr("disabled", "true");
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

		// 创建表格的loading的tbody（坑，简写，不使用对象生成）
		// $(table_id).prepend('<tbody><tr><td id="' + td_loading_id + '" colspan="' + SugarTables.sts[form_id].table.columns_count + '"></td></tr></tbody>');


		// 当为表格内嵌加载等待条时处理
		var loading_waiting_style = SugarTables.loading_waiting_style;

		// 显示加载对应的信息
		var loading_waiting_id = TimeKeeper.loadingWaitingStart(td_loading_id, SugarTables.loading_waiting_speed, loading_waiting_style);
		title = title ? title : SugarTables.sts[form_id].table.title;
		$(loading_waiting_id).find('.loading-title').html(title);
		// console.log(loading_waiting_id);

		// console.log($(form_id).serialize());

		// 表提交数据
		var form_data = $(form_id).serializeJson();
		form_data.sort = JSON.stringify(SugarTables.sts[form_id].table.sort);

		// 定义传输类型
		var data_type = 'json';

		// 判断debug是否开启
		if (SugarCommons.debug == true) {
			form_data.debug = true;
			data_type = 'html';
			// console.log('debug is open!');
		}
		// console.log(form_data);

		// ajax请求处理
		$.ajax({
			url: url,
			type: SugarTables.ajax_type,
			dataType: data_type,
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
				$(form_id).find('[type="submit"]').removeAttr("disabled");
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

		// 空数据处理
		if ($.isEmptyObject(table_data)) {
			// 定义赋值
			var table_tbody = SugarTables.table_tbody;
			// 对象赋值
			SugarTables.sts[form_id].table.table_data = table_data;
			// 定义表行
			var table_tr = SugarTables.table_tr;
			//定义单元格
			var table_td = SugarTables.table_td;
			var td_content = "暂无数据";
			// td内容添加			
			table_td = $(table_td).html(td_content);
			$(table_td).attr("colspan", SugarTables.sts[form_id].table.columns_count);
			$(table_td).addClass("text-center");
			// tr添加td
			table_tr = $(table_tr).append(table_td);
			// tbody添加tr
			table_tbody = $(table_tbody).append(table_tr);

			// 移除原有tbody
			$(table_id).find('tbody').remove();
			// 表格添加tbody
			$(table_id).append(table_tbody);


			// 重新赋值
			SugarTables.sts[form_id].table.table = $(table_id);
			// SugarTables.sts[form_id].table.columns = columns;
			SugarTables.sts[form_id].table.table_tbody = $(table_tbody);

			// 返回表格表体句柄对象
			return $(table_tbody);
		}
		SugarTables.comms.checkValue('table_data', table_data, 'object', 'makeTbody');

		// 定义赋值
		var table_tbody = SugarTables.table_tbody;

		// 对象赋值
		SugarTables.sts[form_id].table.table_data = table_data;

		// 遍历数据（每行）
		$.each(table_data, function (i, row) {
			// 定义表行
			var table_tr = SugarTables.table_tr;

			// 遍历配置（每列），用于处理TD
			$.each(columns, function (index, column) {
				//定义单元格
				var table_td = SugarTables.table_td;
				var td_content = row[index] == undefined ? index + '-undefined' : row[index];

				// 默认初始化处理
				// column.td = column.td == undefined ? {} : column.td;
				// column.td.title = column.td.title == undefined ? td_content : column.td.title;
				// 如果没有td赋值,将重新赋值
				if (column.td === undefined || !column.td) {
					column.td = {};
					column.td.title = table_id;
				}
				column.td.title = td_content;

				// td模版配置替换处理
				if (column.td.template !== undefined && typeof (column.td.template) == 'string') {
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
				}

				// td默认内容长度处理
				column.td.content_length = column.td.content_length == undefined ? td_content.length : parseInt(column.td.content_length);

				// td内容过长截取处理
				if (td_content.length > column.td.content_length) {
					td_content = td_content.substring(0, column.td.content_length) + '...';
				}

				// td内容添加
				table_td = $(table_td).html(td_content);

				// td通用属性处理
				$.each(column.td, function (key, value) {
					table_td = $(table_td).attr(key, value);
				});

				// 处理后重新赋值
				// columns[index] = column;

				// tr添加td
				table_tr = $(table_tr).append(table_td);
			});

			// tbody添加tr
			table_tbody = $(table_tbody).append(table_tr);
		});

		// 移除原有tbody
		$(table_id).find('tbody').remove();
		// 表格添加tbody
		$(table_id).append(table_tbody);


		// 重新赋值
		SugarTables.sts[form_id].table.table = $(table_id);
		// SugarTables.sts[form_id].table.columns = columns;
		SugarTables.sts[form_id].table.table_tbody = $(table_tbody);

		// 返回表格表体句柄对象
		return $(table_tbody);
	},

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
		page_next = page_next >= page_total ? 1 : page_next;

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