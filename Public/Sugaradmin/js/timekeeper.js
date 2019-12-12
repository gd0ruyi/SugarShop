/**
 * 公用定时器
 * 注：
 * 1、当使用loadingWaiting时，一个target目标只允许一个出现，重复的将会被覆盖
 */
var TimeKeeper = {
    // 定时器栈多对象
    tps: {},
    // 公共函数对象，必须要加载自定义的commons.js文件
    comms: SugarCommons.setCommonsName('TimeKeeper'),

    // 定时器的计数默认值
    times: 0,
    // 定时器默认执行周期的时间值，为毫秒
    ms: 500,

    // 加载条多对象
    lws: {},
    // lws的对象个数
    lws_count: 0,
    // loading加载条使用的默认ID
    loading_waiting_id: '#loading-waiting',
    // loading加载条默认模版ID
    loading_waiting_tpl: '#loading-waiting-tpl',
    // 弹出窗体容器的模版ID
    dialog_tpl_id: '#task-dialog-tpl',
    // loading加载条默认样式,分为inner和dialog两种
    loading_waiting_style: 'inner',
    // 加载条速度,为毫秒
    loading_waiting_speed: 100,
    // 超时时间，为秒
    loading_waiting_timeout: 300,

    // 等待进度条默认最小值，用于初始化（只有使用dialog时有效）
    loading_waiting_dialog_percent_min_value: 0,


    /**
     * 计时器执行开始
     * @param {string} tps_id 当前计时器的唯一ID
     * @param {function} fc 当前执行回调函数 
     * @param {number} ms 定时的毫秒
     * @param {*} fc_type 
     */
    start: function (tps_id, fc, ms, fc_type) {
        // 验证
        tps_id = TimeKeeper.comms.checkValue('tps_id', tps_id, 'string', 'start');
        fc = TimeKeeper.comms.checkValue('fc', fc, 'function', 'start');
        // 定时器默认执行周期的时间值
        ms = ms == undefined ? TimeKeeper.ms : ms;
        ms = TimeKeeper.comms.checkValue('ms', ms, 'number', 'start');
        ms = parseInt(ms);
        if (ms < 1) {
            error = "TimeKeeper is error by start():ms, input value must be > 1";
            TimeKeeper.comms.errorMsg(error);
        }
        // 验证js计时的两种方式
        fc_type = fc_type == undefined ? 'setInterval' : fc_type;
        if (fc_type != 'setInterval' && fc_type != 'setTimeout') {
            error = "TimeKeeper is error by start():fc_type, the value must be 'setInterval' or 'setTimeout', input value is '" + fc_type + "'";
            TimeKeeper.comms.errorMsg(error);
        }

        // 避免计时器同一ID重复调用出现错误
        if (tps_id !== undefined && TimeKeeper.tps[tps_id] !== undefined) {
            TimeKeeper.clear(tps_id);
        }

        // 初始化
        TimeKeeper.tps[tps_id] = {};
        TimeKeeper.tps[tps_id].tps_id = tps_id;
        TimeKeeper.tps[tps_id].fc = fc;
        TimeKeeper.tps[tps_id].ms = ms;
        TimeKeeper.tps[tps_id].fc_type = fc_type;

        // 定时器的计数值，主要用于++处理
        TimeKeeper.tps[tps_id].times = TimeKeeper.times;

        // 定时器的唯一ID值处理
        if (fc_type == 'setInterval') {
            TimeKeeper.tps[tps_id].interval = setInterval(fc, ms);
        } else {
            TimeKeeper.tps[tps_id].interval = setTimeout(fc, ms);
        }

        return TimeKeeper.tps[tps_id];
    },

    /**
     * 按计时器唯一ID清除
     * @param {string} tps_id 当前计时器的唯一ID
     * @returns boolean 成功移除后返回true，失败返回false
     */
    clear: function (tps_id) {
        if (tps_id !== undefined && TimeKeeper.tps[tps_id] !== undefined) {
            tps_id = TimeKeeper.comms.checkValue('tps_id', tps_id, 'string', 'clear');
            //console.log(TimeKeeper.tps[tps_id]);
            // 判断类型
            if (TimeKeeper.tps[tps_id].fc_type == 'setInterval') {
                TimeKeeper.tps[tps_id].interval = clearInterval(TimeKeeper.tps[tps_id].interval);
            } else {
                TimeKeeper.tps[tps_id].interval = clearTimeout(TimeKeeper.tps[tps_id].interval);
            }
            delete TimeKeeper.tps[tps_id];
            return true;
        } else {
            // 遍历清除
            $.each(TimeKeeper.tps, function (key, value) {
                TimeKeeper.clear(key);
            });
        }
        return false;
    },

    /**
     * 按计时器唯一ID清除(为了方便使用，同clear名方法)
     * @param {string} tps_id 当前计时器的唯一ID
     * @returns boolean 成功移除后返回true，失败返回false
     */
    end: function (tps_id) {
        return this.clear(tps_id);
    },

    /**
     * 加载条开始
     * @param {string} target 加载条植入的元素ID
     * @param {string} title 加载条内的显示标题
     * @param {int} speed 加载条移动的速度，为秒
     * @param {*} style 加载条样式
     * @returns string loading_waiting_id 返回加载条的（任务）ID
     */
    loadingWaitingStart: function (target, title, speed, style) {
        // 校验
        var target = TimeKeeper.comms.checkValue('target', target, 'string', 'loadingWaitingStart');
        var title = title !== undefined ? title : 'title is undefined';
        var speed = speed == undefined ? TimeKeeper.loading_waiting_speed : parseInt(speed);
        speed = TimeKeeper.comms.checkValue('speed', speed, 'number', 'loadingWaitingStart');

        // 默认样式赋值
        var style = style == undefined ? TimeKeeper.loading_waiting_style : style;

        // 加载条对象初始化赋值
        TimeKeeper.lws[target] = {};
        TimeKeeper.lws[target].target = target;
        TimeKeeper.lws[target].speed = speed;
        TimeKeeper.lws[target].style = style;
        TimeKeeper.lws[target].loading_waiting_tpl = '';
        TimeKeeper.lws[target].loading_waiting_id = '';

        // 获取lws的对象个数
        TimeKeeper.lws_count = Object.getOwnPropertyNames(TimeKeeper.lws).length;
        TimeKeeper.lws_count = parseInt(TimeKeeper.lws_count);

        // 开始效果处理
        // 加载条ID定义
        var loading_waiting_id = TimeKeeper._getLoadingWaitingIdByTarget(target);
        // 复制loading的tpl并形成新的loading内容
        var $loading_waiting_tpl = $(TimeKeeper.loading_waiting_tpl).html();
        $loading_waiting_tpl = $($loading_waiting_tpl);

        // 赋值处理
        $loading_waiting_tpl.attr('id', loading_waiting_id.substring(1));
        $loading_waiting_tpl.find('.loading-title').html(title);

        // 初始化隐藏progress和进度
        $loading_waiting_tpl.find('.loading-loaded-span').hide();
        $loading_waiting_tpl.find('.progress').hide();

        //赋值        
        TimeKeeper.lws[target].loading_waiting_id = loading_waiting_id;

        // 处理不同的样式效果（inner or dialog）
        if (!style) {
            TimeKeeper.destructor(target);
            error = "TimeKeeper is error by loadingWaitingStart(): not found style, input value must be (inner or dialog)!";
            this.comms.errorMsg(error);
        }

        // dialog弹窗任务数显示
        $(TimeKeeper.dialog_tpl_id).find('.loading-tasks-count').html(TimeKeeper.lws_count);

        // 如果存在重复的历史的显示则移除
        if ($(TimeKeeper.dialog_tpl_id).find(loading_waiting_id).index() > -1) {
            // 移除加载内容
            $(TimeKeeper.dialog_tpl_id).find(TimeKeeper.lws[target].loading_waiting_id).remove();
        }

        // dialog弹窗内容堆叠新增
        $(TimeKeeper.dialog_tpl_id).find('.modal-body').prepend($loading_waiting_tpl);


        // 单项历史点击关闭删除时进行重新计数
        $(TimeKeeper.dialog_tpl_id).find(loading_waiting_id).on('closed.bs.alert', function () {
            delete TimeKeeper.lws[target];
            TimeKeeper._resetDialogCount();
        });

        // 弹窗隐藏设置（当关闭时停止计时）
        // $(TimeKeeper.dialog_tpl_id).on('hidden.bs.modal', function (e) {
        //     TimeKeeper.end(target);
        // });

        // 显示弹窗
        $(TimeKeeper.dialog_tpl_id).modal('show');

        // TimeKeeper.tps[target].times = 0;
        // TimeKeeper.destructor(target);

        // 如果为嵌入方式则进行克隆处理
        if (style == 'inner') {
            // 目标容器植入内容并显示加载
            $(target).html($loading_waiting_tpl.clone(true));
        }

        // 赋值到存储对象处理
        TimeKeeper.lws[target].loading_waiting_tpl = $loading_waiting_tpl;

        // 定时执行效果
        TimeKeeper.start(target, function () {
            // 此处target=tps_id
            TimeKeeper.tps[target].times++;

            // 毫秒转换成秒
            var second = TimeKeeper.tps[target].times * speed / 1000;
            second = second.toFixed(1);
            // 秒显示处理
            $('[id=' + loading_waiting_id.substring(1) + ']').find(".loading-second").html(second);
        }, speed);

        return loading_waiting_id;
    },

    /**
     * 用于百分比显示
     * @param {string} target 加载条植入的元素ID
     * @param {object} event ajax支持百分比响应时的句柄
     * @param {ajaxObject} currentAjax ajax对象，用于错误终止
     */
    loadingWaitingProgress: function (target, event, currentAjax) {
        // 校验target的值
        var target = this.comms.checkValue('target', target, 'string', 'loadingWaitingProgress', false, currentAjax);
        // 判断target错误将终止ajax请求
        if (target == false) {
            // progress特殊异常将清理全部
            throw this.destructor();
        }

        var style = this.lws[target].style;
        var loading_waiting_id = this.lws[target].loading_waiting_id;

        var second = $(loading_waiting_id).find(".loading-second").html();
        var kbs = event.loaded / 1024 / second;
        var loaded = event.loaded / 1024;
        var loaded_unit = 'kb';
        var total = event.total / 1024;
        var total_unit = 'kb';

        percent = (loaded / total) * 100;
        // percent = percent.toFixed(2);
        percent = parseInt(percent);
        // console.log(percent);

        // 转换单位
        if (loaded > 1024) {
            loaded = loaded / 1024;
            loaded_unit = 'MB';
        }
        if (total > 1024) {
            total = total / 1024;
            total_unit = 'MB';
        }

        kbs = kbs.toFixed(2);
        loaded = loaded.toFixed(2);
        total = total.toFixed(2);

        // 若style为dialog的时候，将target切换为对应的loading_waiting_id
        if (style == 'dialog') {
            target = loading_waiting_id;
        }

        // 显示隐藏的progress
        $(target).find('.loading-loaded-span').show();
        $(target).find('.progress').show();

        // 进度条显示加载
        $(target + ' [role="progressbar"]').attr('aria-valuenow', percent);
        $(target + ' [role="progressbar"]').width(percent + '%');

        // 显示百分比
        $(target).find(".loading-percent").html(percent);
        // 显示百分比速率
        $(target).find(".loading-kbs").html(kbs);
        // 显示已加载量
        $(target).find(".loading-loaded").html(loaded);
        $(target).find(".loading-loaded-unit").html(loaded_unit);
        // 显示总加载量
        $(target).find(".loading-total").html(total);
        $(target).find(".loading-total-unit").html(total_unit);
        if (percent == 100) {
            console.log("Timekeeper loadingWaitingProgress(): loaded progress is complete!");
            console.log("Loaded total: " + total + " " + total_unit);
            console.log("Use time: " + second + " s");
            console.log("Link Speed: " + kbs + " kb/s");
        }
    },

    /**
     * 加载条结束处理
     * @param {string} target 加载条植入的元素ID，默认不传值时为全部清理
     */
    loadingWaitingEnd: function (target) {
        // 当target有值时，并且能在lws数组对象中找到
        if (target !== undefined && TimeKeeper.lws[target] !== undefined) {
            // 清理定时器
            TimeKeeper.clear(target);

            // 移除历史任务弹窗内loading的旋转动画效果
            $(TimeKeeper.dialog_tpl_id).find(TimeKeeper.lws[target].loading_waiting_id).find('.glyphicon-refresh').removeClass('animation');

            if (!TimeKeeper.lws[target].style) {
                TimeKeeper.destructor(target);
                error = "TimeKeeper is error by loadingWaitingEnd(): not found style, input value must be (inner or dialog)!";
                this.comms.errorMsg(error);
            }

            // 当为inner时移除
            if (TimeKeeper.lws[target].style == 'inner') {
                // 移除嵌入的内容
                $(target).find(TimeKeeper.lws[target].loading_waiting_id).remove();
            }

            // 关闭任务显示dialog
            $(TimeKeeper.dialog_tpl_id).modal('hide');
            // 因bostrap的modal使用动画效果存在延时情况，会出现多个背景的情况，因此将强制将其背景移除
            // $('.modal-backdrop').remove();

            return true;
        } else {
            // 默认清理全部loading
            $.each(TimeKeeper.lws, function (target, value) {
                // 回调
                TimeKeeper.loadingWaitingEnd(target);
            });
            return true;
        }
    },

    /**
     * 重新计算弹窗任务数
     */
    _resetDialogCount() {
        // 重新计数
        TimeKeeper.lws_count = Object.getOwnPropertyNames(TimeKeeper.lws).length;
        TimeKeeper.lws_count = parseInt(TimeKeeper.lws_count);

        // dialog弹窗任务数显示
        $(TimeKeeper.dialog_tpl_id).find('.loading-tasks-count').html(TimeKeeper.lws_count);
    },

    /**
     * 自定义获取loading加载条的ID（统一命名）
     * @param {string} target 
     * @returns {string} loading_waiting_id
     */
    _getLoadingWaitingIdByTarget: function (target) {
        // var loading_waiting_id = '#' + target.substring(1) + '-loading';
        var loading_waiting_id = target + '-loading';
        return loading_waiting_id;
    },

    /**
     * 按target获取当前loading的方式
     * @param {string} target 
     * @returns {string} loading_waiting_id
     */
    _getLoadingWaitingStyleByTarget: function (target) {
        return this.lws[target].style;
    },

    /**
     * 析构函数，用于清理计时器的内置对象栈
     * （暂时无用）
     * @param {string} 堆栈中的目标ID，默认为全部
     */
    destructor: function (target) {

        // 清理单个目标
        if (target !== undefined && TimeKeeper.lws[target] !== undefined || TimeKeeper.tps[target] !== undefined) {
            TimeKeeper.clear(target);
            TimeKeeper.loadingWaitingEnd(target);
            console.log('Timekeeper is destructor : ' + target);
        } else {
            // 全部清理
            $.each(TimeKeeper.tps, function (target, value) {
                TimeKeeper.destructor(target);
            });
            console.log('Timekeeper is destructor : all');
        }
    }
};

// 用于测试
/* TimeKeeper.start('test', function () {
    TimeKeeper.tps['test'].times++;
    alert("start:" + TimeKeeper.tps['test'].times);
    alert(TimeKeeper.tps['test'].interval);
    if (TimeKeeper.tps['test'].times > 2) {
        alert("end:" + TimeKeeper.tps['test'].times);
        TimeKeeper.end('test');
        alert(TimeKeeper.tps['test'].interval);
    }
}, 50); */