<?php /* Smarty version 3.1.24, created on 2015-11-20 11:51:50
         compiled from "./Application/Home/View/Index/index.tpl" */ ?>
<?php
/*%%SmartyHeaderCode:29824564e98d6a867d4_00569736%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bba07ccd2ef3f6b4b834a6ef9d8bdf52481343a6' => 
    array (
      0 => './Application/Home/View/Index/index.tpl',
      1 => 1447388456,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '29824564e98d6a867d4_00569736',
  'has_nocache_code' => false,
  'version' => '3.1.24',
  'unifunc' => 'content_564e98d6aea5e8_55525679',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_564e98d6aea5e8_55525679')) {
function content_564e98d6aea5e8_55525679 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '29824564e98d6a867d4_00569736';
?>
<style type="text/css">
* {
	padding: 0;
	margin: 0;
}
div {
	padding: 4px 48px;
}
body {
	background: #fff;
	font-family: "微软雅黑";
	color: #333;
	font-size: 24px
}
h1 {
	font-size: 100px;
	font-weight: normal;
	margin-bottom: 12px;
}
p {
	line-height: 1.8em;
	font-size: 36px
}
a, a:hover {
	color: blue;
}
</style>
<div style="padding: 24px 48px;">
  <h1>:)</h1>
  <p> 欢迎使用 <b>ThinkPHP</b> ！ </p>
  <br />
  版本 V<?php echo @constant('THINK_VERSION');?>
</div>
<?php echo '<script'; ?>
 type="text/javascript"
	src="http://ad.topthink.com/Public/static/client.js"><?php echo '</script'; ?>
>
<thinkad id="ad_55e75dfae343f5a1"></thinkad>
<?php echo '<script'; ?>
 type="text/javascript"
	src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"><?php echo '</script'; ?>
><?php }
}
?>