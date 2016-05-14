<link rel="stylesheet" type="text/css" media="screen" href="/Public/jqGrid-5.0.2/css/ui.jqgrid.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/Public/jqGrid-5.0.2/css/ui.jqgrid-bootstrap.css" />
<link rel="stylesheet" type="text/css" media="screen" href="/Public/jqGrid-5.0.2/css/ui.jqgrid-bootstrap-ui.css" />
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">管理员列表</h3>
  </div>
  <div class="panel-body">
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>账户</th>
          <th>创建时间</th>
          <th>更新时间</th>
          <th>登录时间</th>
        </tr>
      </thead>
      <tbody>
      <{foreach from=$manager_list item=list}>
      <tr>
        <td><{$list.manager_id}></td>
        <td><{$list.username}></td>
        <td><{$list.add_time}></td>
        <td><{$list.upd_time}></td>
        <td><{$list.las_time}></td>
      </tr>
      <{/foreach}>
        </tbody>
      
    </table>
    <div style="margin-left:20px">
      <table id="jqGrid">
      </table>
      <div id="jqGridPager"></div>
    </div>
  </div>
</div>
<script src="/Public/jqGrid-5.0.2/js/i18n/grid.locale-cn.js" type="text/javascript"></script> 
<script src="/Public/jqGrid-5.0.2/js/jquery.jqGrid.min.js" type="text/javascript"></script> 
<script type="text/javascript"> 
$(document).ready(function () {
	$.jgrid.defaults.width = 780;
	$.jgrid.defaults.styleUI = 'Bootstrap';
	
	$("#jqGrid").jqGrid({
    	url: '/Sugaradmin/Manager/showJsonData',
        mtype: "GET",
		styleUI : 'Bootstrap',
        datatype: "jsonp",
        colNames : [ 'Inv No', 'Date', 'Client', 'Amount', 'Tax','Total', 'Notes' ], 
		colModel : [ 
			{name : 'id',index : 'id',width : 55}, 
			{name : 'invdate',index : 'invdate',width : 90}, 
			{name : 'name',index : 'name asc, invdate',width : 100}, 
			{name : 'amount',index : 'amount',width : 80,align : "right"}, 
			{name : 'tax',index : 'tax',width : 80,align : "right"}, 
			{name : 'total',index : 'total',width : 80,align : "right"}, 
			{name : 'note',index : 'note',width : 150,sortable : false} 
		], 
		rowNum : 10, 
		rowList : [ 10, 20, 30 ], 
		pager : '#jqGridPager', 
		sortname : 'id', 
		mtype : "post", 
		viewrecords : true, 
		sortorder : "desc", 
		caption : "JSON 实例"
    });
	
	jQuery("#jqGrid").jqGrid('navGrid', '#jqGridPager', {edit : false,add : false,del : false});
});
 
</script> 
