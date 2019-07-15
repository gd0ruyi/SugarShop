// 动态选项卡处理JS
$(document).ready(function() {

	// 公用计时器构建
	var TimeKeeper = {
		// 初始化
		_init : function(){
			// 自动添加清除方法
			var that = this;
			$.each(this, function(index, value){				
				if(typeof(value)=="object"){
					that[index].times = 0;
					that[index].interval = -1;	
					// 重新构建清除方法				
					that[index].clear = function(){
						clearInterval(that[index].interval);
						that[index].times = 0;
						that[index].interval = -1;
					}
				}
			});
			return this;
		},
		// 添加一个新的对象
		addNewElement : function(elementName){
			if(typeof(elementName) == "string"){
				this[elementName]={};
				this._init();
				return this;
			}
			return false;
		},
		clear : function(elementName){
			if(typeof(elementName) == "string"){
				this[elementName].times = 0;
				this[elementName].interval = -1;
				return this;
			}
			$.each(this, function(index, value){
				if(typeof(value) == "object"){
					value.times = 0;
					value.interval = -1;
				}
			});
			return true;
		},
		tabClose : {
			interval : -1,
			times : 0,
			clear : function(){}
		},
		loading : {
			interval : -1,
			times : 0,
			clear : function(){}
		}
	};
	// 计时器对象初始化
	TimeKeeper._init();
	
	
	//　自定义Tabs构建
	var SugarTabs = {
		// 选项卡的ID
		target : '',
		// 选项卡自增索引
		tab_index : 0,
		// 选项卡历史路径
		history_url : {},
		// 选项卡标题长度
		title_length : 12,
		// 选项卡标题缩写后省略符号
		title_sort_suff : '...',

		// 标签栏自动固定顶部
		autoTabsFiex : function() {
			$(window).scroll(function(event) {
				if ($(document).scrollTop() > $(".main-body nav").height()) {
					$("#top-tabs-bar").addClass("navbar-fixed-top");
				}
				if ($(document).scrollTop() == 0) {
					$("#top-tabs-bar").removeClass("navbar-fixed-top");
				}
			});
		},

		//点击选项卡事件
		clickTabEvent : function() {
			// 触发选项卡处理，使用bootstrap
			$("#top-tabs-bar-list li a").on("click", function(e) {
				e.preventDefault();
				$(this).tab('show');
				location.href = $(this).prop("href");
				return true;				
			});

			// 监听按住Tab的鼠标事件
			$("#top-tabs-bar-list li a").on("mousedown", function() {
				//console.log(TimeKeeper.tabClose.interval);
				// 避免重复处理
				if(TimeKeeper.tabClose.interval < 0){
					TimeKeeper.tabClose.interval = setInterval(function(){
						TimeKeeper.tabClose.times++;
						// 判断调用次数
						if(TimeKeeper.tabClose.times > 1){
							TimeKeeper.tabClose.clear();
							return false;
						}
						// 执行显示隐藏
						$("#top-tabs-bar-list li a .glyphicon-remove").toggle("fast", function(){
							if($(this).hasClass("hidden")){
								$(this).removeClass("hidden");
								$(this).css({"display" : "inline-block"});
							}else{
								$(this).addClass("hidden");
								$(this).css({"display" : "none"});
							}
						});
					}, 500)
				}else{
					TimeKeeper.tabClose.clear();
				}
			});

			// 监听松开Tab的鼠标时间
			$("#top-tabs-bar-list li a").on("mouseup", function() {
				TimeKeeper.tabClose.clear();
			});
			
			// 监听移除关闭选项卡按钮
			$("#top-tabs-bar-list li a .glyphicon-remove").on("click", function(){
				var tab = $(this).parent().parent();
				var tab_id = $(tab).prop("id");
				var suffix = "-tab";
				var tab_content_id = tab_id.substring(0, tab_id.length- suffix.length);
				SugarTabs.removeHistoryUrl(tab_content_id);
				$(tab).remove();
				$("#" + tab_content_id).remove();
			});
			return true;
		},

		// 点击菜单事件处理
		clickManuEvent : function() {
			// 菜单栏点击Active效果处理
			$("#top-manu-bar li").click(function() {
				// 同级除样式
				$(this).siblings().removeClass("active");
				$(this).siblings().find("li").removeClass("active");
				$(this).addClass("active");				
			});

			// 触发ajax事件
			$("#top-manu-bar li [ajax-target]").click(function() {
				var target = $(this).attr("ajax-target");
				var title = $(this).html();
				var href = $(this).attr("href");

				// 防止重复创建
				if (SugarTabs.saveHistoryUrl(target, href) == false) {
					// 触发选中选项卡
					$("[href='#"+target+"']").trigger("click");
					return false;
				};
				// 添加选项卡
				if (SugarTabs.addTabs(target, title, href) == false){
					return false;
				}
				
				//加载ajax
				return !SugarTabs.loadAjax(target, href);
				//console.log("loading start");
				//SugarTabs.loadAjax(target);
				//return false;
			});

		},
		
		loadAjax : function(target, href){
			if ( typeof (target) == "string" && target !='') {
				target = target.indexOf("#") == 0 ? target : "#"+target;
			}else if ( typeof (target) != "object" || typeof (target) == "undefined") {
				return false;
			}
			
			// 开始显示loading加载
			if(SugarTabs.loadingWiating.start(target) == false){
				return false;
			}
						
			$.ajax({
					url : href,
					type : "GET",
					dataType : "html",
					data : {},
					cache : false,
					xhrFields : {
						onprogress : function(event) {
							if (event.lengthComputable) {
								// 显示百分比
								$(target).find(".percent").show();
								percent = (event.loaded / event.total) * 100;
								percent = percent.toFixed(2);
								$(target).find(".percent").parent().removeClass("hidden");
								$(target).find(".percent").html(percent);
								//console.log(percent);
							}
						}
					},
					success : function(res) {
						$(target).html(res);
						SugarTabs.loadingWiating.end();
					},
				});
			return true;
		},

		// 等待进度条，无进度
		loadingWiating : {
			// 进度条显示速度
			speed : 100,
			// 进度条开始
			start : function(target) {
				if ( typeof (target) == "string" && target !='') {
					target = target.indexOf("#") == 0 ? target : "#"+target;
				}else if ( typeof (target) != "object" || typeof (target) == "undefined") {
					return false;
				}
				
				//console.log(target);
				// loading的模版加载
				$(target).html($("#loading-waiting-tpl").html());
				
				// 清理后执行
				TimeKeeper.loading.clear();
				// 开始执行进度条
				TimeKeeper.loading.interval = setInterval(function() {
					TimeKeeper.loading.times++;
					var second = TimeKeeper.loading.times * SugarTabs.loadingWiating.speed / 1000;
					second = second.toFixed(2);
					$(target).find(".second").html(second);
				}, SugarTabs.loadingWiating.speed);
				return true;
			},
			end : function() {
				clearInterval(TimeKeeper.loading.interval);
			}
		},

		// 保存历史访问url数组
		saveHistoryUrl : function(key, url) {
			if (key == '' || typeof (key) != 'string') {
				return false;
			}
			// 不存在加入
			if ( typeof (SugarTabs.history_url[key]) == 'undefined') {
				SugarTabs.history_url[key] = url;
				return SugarTabs.history_url[key];
			}
			return false;
			//console.log(SugarTabs.history_url);
		},

		//　删除历史访问url
		removeHistoryUrl : function(key) {
			if (key == '' || typeof (key) != 'string') {
				return false;
			}
			//console.log(key);
			// 存在删除
			if ( typeof (SugarTabs.history_url[key]) != 'undefined') {
				delete SugarTabs.history_url[key];
			}
			//console.log(SugarTabs.history_url);
			return true;
		},

		// 创建顶部导航tab选项卡
		addTabs : function(target, title, href) {
			if ( typeof(target)=='undefined' || typeof (target) != 'string' ||target == '') {
				return false;
			}
			var tab_tpl = $("#top-tabs-tab-tpl").clone(true);
			var content_tpl = $("#top-tabs-content-tpl").clone(true);
			var sort_title = title.length > SugarTabs.title_length ? title.substring(0, SugarTabs.title_length) + SugarTabs.title_sort_suff : title;
			
			// tab的处理
			$(tab_tpl).prop("id", target+'-tab');
			$(tab_tpl).children("a").prop("href", "#"+target);
			$(tab_tpl).removeClass("hidden");
			$(tab_tpl).children("a").children(".top-tab-name").prop("title", title);
			$(tab_tpl).children("a").children(".top-tab-name").html(sort_title);
			
			
			// tab显示内容的处理
			$(content_tpl).prop("id", target);
			$(content_tpl).removeClass("hidden");
			//$(content_tpl).addClass("active in");
			
			// 添加元素
			$("#top-tabs-bar-list").append(tab_tpl);
			// 添加锚链
			//$("#top-tabs-bar-list").append('<a name="#'+target+'"></a>');
			//$("#top-manu-bar").append('<a name="#'+target+'"></a>');
			$("#top-tabs-contents").append(content_tpl);
			
			// 创建完毕后点击显示
			$(tab_tpl).children("a").trigger("click");
			return true;
		},
		
		// 移除选项卡
		removeTabs : function(){
			
		},

		// 自定义Tab执行
		run : function() {
			// 开启菜单点击事件
			SugarTabs.clickManuEvent();
			// 开启选项卡点击事件
			SugarTabs.clickTabEvent();
			// 开启选项卡自动固定
			SugarTabs.autoTabsFiex();
		}
	};

	//运行自定义Tabs控件
	SugarTabs.run();

	// 加载弹出等待条
	/*$("[link-target]").on("click", function() {
		var herf = $(this).attr("href");
		var layout = $(this).attr("link-target");
		var laoding_interval = 0;
		var $i = 1;

		if (herf == "#") {
			return false;
		}

		// 记录历史
		if (!SugarManu.saveHistoryUrl(herf)) {
			return false;
		};
		SugarManu.addTabs($(this).text());

		// 全局调用ajax开始时处理
		$(document).ajaxStart(function() {
			//$('#loading').modal({
			//backdrop: 'static'
			//});
			$('#loading').modal("show");
			laoding_interval = setInterval(function() {
				if ($i > 99) {
					//$i = 1;
					return false;
				}
				$('#loading .progress-bar').width($i + "%");
				$('#loading .progress-bar font').text($i);
				$i++;
			}, 50);
		});
		// 全局调用ajax成功时处理
		$(document).ajaxSuccess(function() {
			$('#loading .progress-bar').width("100%");
			clearInterval(laoding_interval);
			$('#loading').modal("hide");
		});
		//  全局调用ajax错误时处理
		$(document).ajaxError(function(event, XMLHttpRequest, ajaxOptions, thrownError) {
			$("#error_title").html("警告：请求失败！");
			$("#error_msg").html("Error(" + XMLHttpRequest.status + "):" + thrownError);
			$('#myModal').modal("show");
			$('#loading').modal("hide");
		});
		$(layout).load(herf);
		return false;
	});*/
}); 