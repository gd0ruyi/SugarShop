// 动态选项卡处理JS
// 自定义Tabs构建
var SugarTabs = {
	// 公共函数对象，必须要加载自定义的commons.js文件
	comms: SugarCommons.setCommonsName('SugarTabs'),

	// 选项卡的ID
	target: '',
	// 选项卡自增索引
	tab_index: 0,
	// 选项卡历史路径
	history: {},
	// 选项卡标题长度
	title_length: 12,
	// 选项卡标题缩写后省略符号
	title_sort_suff: '...',
	// 选项卡模版
	tabs_tab_tpl_id: '#tabs-tab-tpl',
	// 选项卡内容模版
	tabs_tab_content_tpl_id: '#target',

	// 选项卡按钮监听按钮延时
	tab_button_time_delay: 500,

	// loading加载条默认样式,分为inner和dialog两种
	loading_waiting_style: 'inner',
	// 加载条速度,为毫秒
	loading_waiting_speed: 100,

	// 标签栏自动固定顶部
	autoTabsFiex: function () {
		$(window).scroll(function (event) {
			if ($(document).scrollTop() > $(".main-body nav").height()) {
				$("#top-tabs-bar").addClass("navbar-fixed-top");
			}
			if ($(document).scrollTop() == 0) {
				$("#top-tabs-bar").removeClass("navbar-fixed-top");
			}
		});
	},

	// 点击菜单事件处理
	clickManuEvent: function () {
		var that = this;
		// 触发ajax事件
		$("#top-manu-bar li [sgtab-target]").on('click', function (e) {
			// 不使用跳转
			e.preventDefault();
			var target = $(this).attr("sgtab-target");
			var title = $(this).html();
			var href = $(this).attr("href");

			// 菜单栏点击Active效果处理
			$("#top-manu-bar li").removeClass("active");
			$(this).parent("li").addClass("active");
			$(this).parent("li").parent().parent().addClass("active");


			// 若不存在访问历史中，则进行创建
			if (that.getHistory(target) === undefined) {
				// 添加访问记录
				that.setHistory(target, href, title);
				// 添加选项卡
				that.addTabs(target, title, href);
				// 加载ajax
				that.loadAjax(target, href, title);
			}

			// 联动触发选中选项卡
			$("[href='" + target + "']").trigger("click");
			return false;
		});
	},

	// 点击选项卡事件
	clickTabEvent: function () {
		var that = this;
		// 触发选项卡处理，使用bootstrap
		$("#top-tabs-bar-list li a").on("click", function (e) {
			// 不使用跳转
			e.preventDefault();
			var target = $(this).parent().attr('sgtab-target');

			// bootstrap内置标签切换
			$(this).tab('show');
		});

		// 监听按住Tab的鼠标事件
		$("#top-tabs-bar-list").on("mousedown", function (e) {
			// 不使用跳转
			e.preventDefault();
			var tps_id = '#top-tabs-bar-list';
			var tab_button_time_delay = that.tab_button_time_delay;
			var times = 0;

			// 避免重复处理
			if (TimeKeeper.tps[tps_id] == undefined) {
				TimeKeeper.start(tps_id, function () {
					times++;
					// 默认500毫秒调用一次，大于1秒后停止执行
					if (times > 1) {
						TimeKeeper.clear(tps_id);
						return false;
					}
					// 小于1秒时，将进行显示删除按钮（执行显示隐藏）
					$("#top-tabs-bar-list li a .glyphicon-remove").toggle("fast", function () {
						if ($(this).hasClass("hidden")) {
							$(this).removeClass("hidden");
							$(this).css({ "display": "inline-block" });
						} else {
							$(this).addClass("hidden");
							$(this).css({ "display": "none" });
						}
						return false;
					});
					// 默认每500毫秒调用一次
				}, tab_button_time_delay);
			}
		});

		// 监听松开Tab的鼠标时，将清理计时器
		$("#top-tabs-bar-list").on("mouseup", function (e) {
			// 不使用跳转
			e.preventDefault();
			var tps_id = '#top-tabs-bar-list';
			TimeKeeper.clear(tps_id);
		});

		// 监听点击移除关闭选项卡按钮
		$("#top-tabs-bar-list li a .glyphicon-remove").on("click", function (e) {
			// 不使用跳转
			e.preventDefault();
			var tab = $(this).parent().parent();
			var target = '#' + $(this).parent().attr('aria-controls');

			// 移除访问历史记录
			that.removeHistory(target);

			// 移除标签页按钮及内容
			$(tab).remove();
			$(target).remove();
		});

		// clickTabEvent方法默认返回true
		return true;
	},

	loadAjax: function (target, href, title) {
		var that = this;
		var target = that.comms.checkValue('target', target, 'string', 'loadAjax');
		var href = href;
		var title = title;
		// loadingWaiting等待加载
		var loading_waiting_id = TimeKeeper.loadingWaitingStart(target, that.loading_waiting_speed, that.loading_waiting_style);

		// 显示加载对应的信息
		$(loading_waiting_id).find('.loading-title').html(title);

		var currentAjax = $.ajax({
			url: href,
			type: "GET",
			dataType: "html",
			data: {},
			cache: false,
			xhrFields: {
				onprogress: function (event) {
					if (event.lengthComputable) {
						TimeKeeper.loadingWaitingProgress(target, event, currentAjax);
					}
				}
			},
			success: function (res) {
				// 加载条结束
				TimeKeeper.loadingWaitingEnd(target);
				$(target).html(res);
			},
			error: function (res) {
				// 加载条结束
				TimeKeeper.destructor(target);
				error = "SugarTabs plus is error by loadAjax()! state : " + res.readyState + ", status : " + res.status + ", statusText : " + res.statusText + ", responseText : " + res.responseText + "";
				$(target).html(error);
				that.comms.errorMsg(error);
			}
		});
	},

	// 保存历史访问href数组
	setHistory: function (target, href, title) {
		var target = this.comms.checkValue('target', target, 'string', 'setHistory');

		// 初始化赋值
		this.history[target] = {};
		this.history[target].target = target;
		this.history[target].href = href;
		this.history[target].title = title;
		return this.history[target];
	},

	// 按key获取记录的history信息
	getHistory: function (target) {
		var target = this.comms.checkValue('target', target, 'string', 'getHistory');
		return this.history[target] !== undefined ? this.history[target] : undefined;
	},

	// 删除历史访问href
	removeHistory: function (target) {
		var target = this.comms.checkValue('target', target, 'string', 'removeHistory');
		// 存在删除
		if (this.history[target] !== undefined) {
			delete this.history[target];
		}
		return true;
	},

	// 创建顶部导航tab选项卡
	addTabs: function (target, title) {
		this.comms.checkValue('target', target, 'string', 'addTabs');
		var tab_tpl = $(this.tabs_tab_tpl_id).clone(true);
		var content_tpl = $(this.tabs_tab_content_tpl_id).clone(true);
		// 选项卡标题长度处理
		var sort_title = title.length > this.title_length ? title.substring(0, this.title_length) + this.title_sort_suff : title;

		var target_id = target.substring(1);

		// tab的处理
		$(tab_tpl).removeAttr('id');
		$(tab_tpl).children('a').prop('href', target);
		$(tab_tpl).children('a').prop('id', target_id + '-tab');
		$(tab_tpl).children('a').attr('aria-controls', target_id);
		$(tab_tpl).children('a').children(".top-tab-name").prop("title", title);
		$(tab_tpl).children('a').children(".top-tab-name").html(sort_title);
		$(tab_tpl).removeClass("hidden");
		// 添加tab按钮
		$("#top-tabs-bar-list").append(tab_tpl);

		// tab显示内容的处理
		$(content_tpl).prop('id', target_id);
		$(content_tpl).attr('aria-labelledby', target_id + '-tab');
		// 添加tab内容
		$("#top-tabs-contents").append(content_tpl);

		// 添加锚链
		// $("#top-tabs-bar-list").append('<a name="'+target+'"></a>');
		// $("#top-manu-bar").append('<a name="'+target+'"></a>');

		// 创建完毕后点击显示
		$(tab_tpl).children('a').trigger("click");
		return true;
	},

	// 自定义Tab执行
	run: function () {
		// 开启菜单点击事件
		this.clickManuEvent();
		// 开启选项卡点击事件
		this.clickTabEvent();
		// 开启选项卡自动固定
		this.autoTabsFiex();
	}
};