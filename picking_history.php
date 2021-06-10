<?
require_once("application.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Picking History</h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Picking History</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">

				<div class="row">
					<div class="col-xs-12">
						<div class="box box-warning">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-qrcode"></i> Picking List (All History)</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<div class="row">
									<div class="form-group col-md-3">
										<label>From Date:</label>
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="min_pick" name="min_pick">
										</div>
										<!-- /.input group -->
									</div>
									<div class="form-group col-md-3">
										<label>To Date:</label>
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="max_pick" name="max_pick">
										</div>
									</div>
									<div class="form-group col-md-2">
										<label>Refresh Data:</label>
										<button type="button" class="btn btn-info btn-md" onclick="_load_picking_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
									</div>
								</div>			
						<div style="padding-left: 8px;">
							<i class="fa fa-filter" style="color: #00F;"></i>
							<font style="color: #00F;">SQL >_ SELECT Data By Date</font>
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
		$(document).ready(function() {
			//load tags
			_load_picking_details();

		});


		function openRePrintTag(id) {
			window.open("<?=$CFG->src_mPDF;?>/print_all_tags_on_picking_sheet?picking_sheet_no="+ id +"","_blank");
		}

		function _load_picking_details() {
			$("#max_pick").val('');
			$("#min_pick").val('');
			//Load data
			setTimeout(function() {
				//$("#spn_load_tags_details").html(""); //clear span
				$("#spn_load_picking_details").load("<?= $CFG->src_report; ?>/load_picking_history.php", {
					date_start_: '',
					date_end_: ''
				});
			}, 300);
		}

		function openRePrintPickingSheet(id) {
			setTimeout(function() {
				window.open("<?= $CFG->src_mPDF; ?>/print_picking_sheet?tag=" + id + "", "_blank");
			}, 500);
		}

		function openRePrintTagWithOrderNum(id){
			window.open("<?=$CFG->src_mPDF;?>/print_all_tags_on_picking_sheet_order_num?picking_sheet_no="+ id +"","_blank");
		}

		$('#min_pick').datepicker({
			autoclose: true,
			yearRange: '1990:+0',
			format: 'yyyy-mm-dd',
			onSelect: function(date) {
				alert(date);
			},
			changeMonth: true,
			changeYear: true,
		});

		$('#max_pick').datepicker({
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

		$('#min_pick').change(function() {
			$("#max_pick").val('');
			max = '';
		});

		$('#max_pick').change(function() {
			max = $('#max_pick').datepicker({
				dateFormat: 'yyyy-mm-dd'
			}).val();
			min = $('#min_pick').datepicker({
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

				$("#spn_load_picking_details").load("<?= $CFG->src_report; ?>/load_picking_history.php", {
					date_start_: min,
					date_end_: max
				});

			}, 500);
		});
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