<!--<link rel="stylesheet" type="text/css" media="screen" href="/Public/jqGrid-5.0.2/css/ui.jqgrid.css" />-->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="/Public/jqGrid-5.0.2/css/ui.jqgrid-bootstrap.css" /> -->
<!-- <link rel="stylesheet" type="text/css" media="screen" href="/Public/jqGrid-5.0.2/css/ui.jqgrid-bootstrap-ui.css" /> -->
<!-- 加载控件皮肤 -->
<!--<link rel="stylesheet" type="text/css" media="screen" href="/Public/theames/jquery-ui-1.11.4.custom-flick/jquery-ui.css" />-->
<style>
.ui-jqgrid .ui-jqgrid-view {
	font-size: 16px;
	line-height: 16px;
}
.ui-jqgrid tr.jqgrow td{
	padding:10px;
}
table .sort-icon{
	position:relative;
	margin-left:16px;
}
table .sort-icon .glyphicon{
	position:absolute;
	font-size:1px;
	color:#CCC;
}
table .sort-icon .glyphicon-triangle-top{
	top:-2px;
}
table .sort-icon .glyphicon-triangle-bottom{
	top:6px;
}
table .sort-icon .on{
	color:#00F;
	display:inline-block;
}
</style>
<div class="panel panel-primary">
  <div class="panel-heading">
    <h3 class="panel-title">管理员列表</h3>
  </div>
  <div class="panel-body" id="panel-body-content">
    <table class="table table-striped table-bordered table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>账户 <span class="sort-icon"><font class="glyphicon glyphicon-triangle-top"></font><font class="glyphicon glyphicon-triangle-bottom on"></font></span></th>
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
      <tr>
        <td colspan="6" align="center">
        	<nav>
              <ul class="pagination pagination-sm">
                <li>
                  <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li>
                  <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
              <ul class="pager">
                <li class="previous"><a href="#"><span aria-hidden="true">&larr;</span> Older</a></li>
                <li class="next"><a href="#">Newer <span aria-hidden="true">&rarr;</span></a></li>
              </ul>
            </nav>
        </td>
      </tr>
      </tbody>
      
    </table>
    <div>
      <table id="jqGrid">
      </table>
      <div id="jqGridPager"></div>
    </div>
  </div>
</div>
<!--<script src="/Public/jqGrid-5.0.2/js/i18n/grid.locale-cn.js" type="text/javascript"></script>--> 
<!--<script src="/Public/jqGrid-5.0.2/js/jquery.jqGrid.min.js" type="text/javascript"></script> -->
<!--<script type="text/javascript"> 
$(document).ready(function () {
	//$.jgrid.defaults.width = 800;
	//$.jgrid.defaults.width.height=600;
	//$.jgrid.defaults.styleUI = 'Bootstrap';
	
	/*$("#jqGrid").jqGrid({
    	url: '/Sugaradmin/Manager/showJsonData',
        mtype: "GET",
		//styleUI : 'Bootstrap',
		height : 'auto',
		autowidth : true,
        datatype: "jsonp",
        colNames : [ 'Inv No', 'Date', 'Client', 'Amount', 'Tax','Total', 'Notes' ], 
		colModel : [ 
			{name : 'id',index : 'id',width : 100}, 
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
		//viewrecords : true, 
		sortorder : "desc", 
		caption : "JSON 实例"
    });
	
	jQuery("#jqGrid").jqGrid('navGrid', '#jqGridPager', {edit : false,add : false,del : false});
	$("#jqGrid").setGridWidth($("#panel-body-content").width()-15);
	
	$(window).resize(function(){
		$("#jqGrid").setGridWidth($("#panel-body-content").width());
	});*/　　
});
 
</script> -->
