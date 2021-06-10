<?
require_once("application.php");
require_once("js_css_header.php");


?>
<!DOCTYPE html>
<html>
<style>
	/** SPINNER CREATION **/
	.modal-dialog-load {
		padding-top: 15%;
		padding-left: 10%;
	}

	.loader {
		position: relative;
		text-align: center;
		margin: 15px auto 25px auto;
		z-index: 9999;
		display: block;
		width: 80px;
		height: 80px;
		border: 10px solid rgba(0, 0, 0, .3);
		border-radius: 50%;
		border-top-color: #000;
		animation: spin 1s ease-in-out infinite;
		-webkit-animation: spin 1s ease-in-out infinite;
	}

	@keyframes spin {
		to {
			-webkit-transform: rotate(360deg);
		}
	}

	@-webkit-keyframes spin {
		to {
			-webkit-transform: rotate(360deg);
		}
	}
</style>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?
  	
	require_once("menu.php");
	update_status_b2c($db_con);
  ?>
  <!--------------------------->
  <!-- body  -->
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-caret-right"></i>&nbsp;DTN Status B2C</h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">DTN Status B2C</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
					  <h3 class="box-title"><img src="<?=$CFG->iconsdir;?>/truck.png" height="18px"> Delivery Transfer Note B2C Status List</h3>
                    </div>
					<!-- /.box-header -->
                    <div class="box-header with-border">
                    <div class="row">
						<div class="form-group col-md-3">
							<label>From Date:</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="min_b2c" name="min_b2c">
								<input type="hidden" class="form-control pull-right" id="update_status_b2c" name="update_status_b2c">
							</div>
							<!-- /.input group -->
						</div>
						<div class="form-group col-md-3">
							<label>To Date:</label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="max_b2c" name="max_b2c">
							</div>
							<!-- /.input group -->
						</div>
					</div>
                    </div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT TOP 1000 ROWS</font>
					</div>
					<!-- /.box-header -->
					<span id="spn_load_dtn_sheet_details"></span>
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
$(document).ready(function(){
	
	_load_dtn_sheet_details();

    $('#min_b2c').datepicker({
			autoclose: true,
			yearRange: '1990:+0',
			format: 'yyyy-mm-dd',
			onSelect: function(date) {
				alert(date);
			},
			changeMonth: true,
			changeYear: true,
	});

	$('#max_b2c').datepicker({
			autoclose: true,
			yearRange: '1990:+0',
			format: 'yyyy-mm-dd',
			onSelect: function(date) {
				alert(date);
			},
			changeMonth: true,
			changeYear: true,
	});

		var min = '';
		var max = '';
		var value_project = '';

	$('#max_b2c').change(function() {
			max = $('#max_b2c').datepicker({
				dateFormat: 'yyyy-mm-dd'
			}).val();
			min = $('#min_b2c').datepicker({
				dateFormat: 'yyyy-mm-dd'
			}).val();

			//Load data
			setTimeout(function() {
				// <!--datatable search paging-->
				$("#loadding").modal({
					backdrop: "static", //remove ability to close modal with click
					keyboard: false, //remove option to close with keyboard
					show: true //Display loader!
				});
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_dtn_sheet_details").load("<?= $CFG->src_dtn_order;?>/load_dtn_status_details_b2c.php", {				
					date_start_: min,
					date_end_: max
				});

			}, 500);

	});

	$('#min_b2c').change(function() {
			$('#max_b2c').val('');
			max = '';
	});
	
});




function openFuncDTNWatingDetails(id)
{
	$('#modal-DTNDetails').modal('show');
	_load_openFuncDTNWaitingSheetDetails(id);
}

function _load_openFuncDTNWaitingSheetDetails(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_ps_h_picking_code = str_split_result[0];
	var t_ps_h_cus_code = str_split_result[1];
	var t_ps_h_cus_name = str_split_result[2];
	var t_ps_t_pj_name = str_split_result[3];	
	var t_ps_h_status = str_split_result[4];
	var t_ps_h_issue_date = str_split_result[5];
	
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_dtn_details").html(""); //clear span
		$("#spn_load_dtn_details").load("<?=$CFG->src_dtn_order;?>/load_waiting_conf_dtn_popup.php", { t_ps_h_picking_code: t_ps_h_picking_code, t_ps_h_cus_code: t_ps_h_cus_code, t_ps_h_cus_name: t_ps_h_cus_name, t_ps_t_pj_name: t_ps_t_pj_name, t_ps_h_status: t_ps_h_status, t_ps_h_issue_date: t_ps_h_issue_date });
	},300);
}

function _load_dtn_sheet_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_dtn_sheet_details").html(""); //clear span
		$("#spn_load_dtn_sheet_details").load("<?=$CFG->src_dtn_order;?>/load_dtn_status_details_b2c.php");
	},300);
}



function openRePrintDTNSheet(id)
{
	setTimeout(function(){
		window.open("<?=$CFG->src_mPDF;?>/print_dtn_sheet?tag="+ id +"","_blank");
	},500);
}

function openFuncDTNSheetDetails(id)
{
	$('#modal-DTNDetails').modal('show');
	_load_dtn_details(id);
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
function exportExcelB2C(id){
	setTimeout(function(){
		//href
		window.open("<?=$CFG->src_dtn_order;?>/excel_export_order_dtn_b2c?dtn_code="+ id +"","_blank");
	},500);
}
</script>
</body>
</html>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
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


<!-- Model loading -->
<div class="modal fade" id="loadding" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-load  modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="loader"></div>
			</div>
		</div>
	</div>
</div>