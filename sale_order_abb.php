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
  ?>
  <!--------------------------->
  <!-- body  -->
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-caret-right"></i>&nbsp;Report B2C Order By Date for ABB</h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Report B2C Order By Date for ABB</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
					  <h3 class="box-title">Report B2C Order</h3>
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
				$("#spn_load_dtn_sheet_details").load("<?= $CFG->src_report;?>/load_sale_report_abb.php", {				
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

function _load_dtn_sheet_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_dtn_sheet_details").html(""); //clear span
		$("#spn_load_dtn_sheet_details").load("<?=$CFG->src_report;?>/load_sale_report_abb.php");
	},300);
}



function exportExcelSaleB2C_ABB(){
	var date_start = $('#min_b2c').datepicker({
				dateFormat: 'yyyy-mm-dd'
			}).val();
	var date_end = $('#max_b2c').datepicker({
				dateFormat: 'yyyy-mm-dd'
			}).val();

	setTimeout(function(){
		//href
		window.open("<?=$CFG->src_report;?>/excel_export_sale_abb?date_start="+ date_start +"&date_end="+ date_end +"","_blank");
	},500);
   
}

</script>
</body>
</html>

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