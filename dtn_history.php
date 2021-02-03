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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Delivery Transfer Note History</h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Delivery Transfer Note History</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
	  
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-warning">
					<div class="box-header with-border">
					  <h3 class="box-title"><img src="<?=$CFG->iconsdir;?>/truck.png" height="18px"> Delivery Transfer Note List (All History)</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-info btn-sm" onclick="_load_picking_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT Date - 14 Days</font>
					</div>
					<!-- /.box-header -->
					<span id="spn_load_picking_details"></span>
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
	//load tags
	_load_dtn_history_details();
	
});



function openFuncDTNSheetDetails(id)
{
	$('#modal-DTNDetails').modal('show');
	_load_dtn_details(id);
}

function openRePrintDTNSheet(id)
{
	setTimeout(function(){
		window.open("<?=$CFG->src_mPDF;?>/print_dtn_sheet?tag="+ id +"","_blank");
	},500);
}

function _load_dtn_history_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_tags_details").html(""); //clear span
		$("#spn_load_picking_details").load("<?=$CFG->src_report;?>/load_dtn_history.php");
	},300);
}

function _load_dtn_details(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_dn_h_dtn_code = str_split_result[0];
	var t_dn_h_cus_code = str_split_result[1];
	var t_dn_h_cus_name = str_split_result[2];
	var t_ps_t_pj_name = str_split_result[3];	
	var t_dn_h_status = str_split_result[4];
	var t_dn_h_delivery_date = str_split_result[5];
	
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_dtn_details").html(""); //clear span
		$("#spn_load_dtn_details").load("<?=$CFG->src_dtn_order;?>/load_dtn_sheet_details_popup.php", { t_dn_h_dtn_code: t_dn_h_dtn_code, t_dn_h_cus_code: t_dn_h_cus_code, t_dn_h_cus_name: t_dn_h_cus_name, t_ps_t_pj_name: t_ps_t_pj_name, t_dn_h_status: t_dn_h_status, t_dn_h_delivery_date: t_dn_h_delivery_date });
	},300);
}
</script>
</body>
</html>



<!--------------------dlg DTNDetails-------------------->
<div class="modal fade" id="modal-DTNDetails" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-search"></i> Confirm Order Details</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body table-responsive padding">
		  <span id="spn_load_dtn_details"></span>
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		</div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->