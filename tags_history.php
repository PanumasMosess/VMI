<?
require_once("application.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
<html>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?
	require_once("menu.php");
  ?>
  <!--------------------------->
  <!-- body  -->
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-caret-right"></i>&nbsp;Master Tags History</h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Master Tags History</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
	  
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-warning">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-qrcode"></i> Master Tags List (All History)</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-info btn-sm" onclick="_load_tags_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT Date - 7 Days</font>
					</div>
					<!-- /.box-header -->
					<span id="spn_load_tags_details"></span>
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->

	</section>
	<!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!--------------------------->
  <!-- /.body -->
  <!--------------------------->
  <?
	require_once("footer.php");
  ?>

  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<? 
require_once("js_css_footer.php"); 
?>
<script language="javascript">
$(document).ready(function()
{
	//search
    /*
	$('#tbl_print_tags').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });
	*/
	
	/*
	<!--datatable search paging-->
	$('#tbl_print_tags').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9,10 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			//if(data[2] == "Active"){
			//	$(row).find('td:eq(2)').css('color', 'indigo');
			//}
			//else if(data[2] == "InActive"){
			//	$(row).find('td:eq(2)').css('color', 'red');
			//}		
		},
    });
	*/
	
	/*
	//valid number only
	$("#txt_prod_plan,#txt_packing_std").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [48, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	*/
	
	
	//load tags
	_load_tags_details();
	
});


function openRePrintIndividual(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?tag="+ id +"","_blank");
}

function openRePrintSet(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?token="+ id +"","_blank");
}

function _load_tags_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_tags_details").html(""); //clear span
		$("#spn_load_tags_details").load("<?=$CFG->src_print_tags;?>/load_tags_history.php");
	},300);
}
</script>
</body>
</html>