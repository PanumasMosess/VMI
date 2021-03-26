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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Billing<small>Storage Location</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Billing</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-cubes"></i> Tags List</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<div class="row">
									<div class="col-md-3">
										<select id="sel_fj_name" name="sel_fj_name" class="form-control select2" style="width: 100%;" onchange="func_load_billing(this.value)">
											<?
											if(($objResult_authorized['user_type'] == "Administrator" && $objResult_authorized['user_section'] == "IT") || ($objResult_authorized['user_type'] == "Administrator" && $objResult_authorized['user_section'] == "GDJ")){
												$strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
											?>
											<option value="ALL" selected="selected">All Project</option>
											<?
											}else{
												$cus_code = $objResult_authorized['user_section'];
												if($cus_code == "IT"){
													$strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
												}else{
													$strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst where bom_cus_code = '$cus_code' group by bom_pj_name";
												}
												
												?>
											<option selected="selected" value="ALL">All Project</option>
											<?
											}
											?>
											<?
					                              //  $strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
					                                $objQuery_fj_name = sqlsrv_query($db_con, $strSQL_fj_name) or die ("Error Query [".$strSQL_fj_name."]");
					                                while($objResult_fj_name = sqlsrv_fetch_array($objQuery_fj_name, SQLSRV_FETCH_ASSOC))
					                            {
				                                ?>
											<option value="<?= $objResult_fj_name["bom_pj_name"]; ?>"><?= $objResult_fj_name["bom_pj_name"]; ?></option>
											<?
					                            }
				                                 ?>
										</select>
									</div>&nbsp;<div class="col-md-1"><button type="button" class="btn btn-info btn-md" onclick="reload_table();"><i class="fa fa-refresh fa-lg"></i> Refresh</button></div>
								</div><br />
								<div class="row">
									<div class="form-group col-md-3">
										<label>From Date:</label>
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="min" name="min">
										</div>
										<!-- /.input group -->
									</div>
									<div class="form-group col-md-3">
										<label>To Date:</label>
										<div class="input-group date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control pull-right" id="max" name="max">
										</div>
										<!-- /.input group -->
									</div>
								</div>
							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT * ROWS</font>
							</div>
							<span id="spn_load_data_main_bill"></span>
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
			//toUpperCase
			//$('#txt_scn_put_tag_id').keyup(function() { this.value = this.value.toUpperCase(); });
			//$('#txt_scn_put_pallet').keyup(function() { this.value = this.value.toUpperCase(); });

			//load pallet no
			reload_table();
		});

		//check eng only
		function isEnglishchar(str) {
			var orgi_text = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-";
			var str_length = str.length;
			var isEnglish = true;
			var Char_At = "";
			for (i = 0; i < str_length; i++) {
				Char_At = str.charAt(i);
				if (orgi_text.indexOf(Char_At) == -1) {
					isEnglish = false;
					break;
				}
			}
			return isEnglish;
		}


		function openRePrintTag(id) {
			window.open("<?= $CFG->src_mPDF; ?>/print_tag_on_tag?tag=" + id + "", "_blank");
		}

		function func_load_billing() {
			max = '';
			min ='';
			$('#max').val('');
			$('#min').val('');
		}

		function reload_table() {
			// // <!--datatable search paging-->
			// $("#loadding").modal({
			// 	backdrop: "static", //remove ability to close modal with click
			// 	keyboard: false, //remove option to close with keyboard
			// 	show: true //Display loader!
			// });
			setTimeout(function() {
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_data_main_bill").load("<?= $CFG->src_report; ?>/load_billing.php", {
					sel_fj_name: $('#sel_fj_name').val(),
					date_start_: '',
					date_end_: ''
				});
			}, 500);


		}

		function _export_billing_by_tags() {
			//href
			var value = $("#sel_fj_name").val();

			var mapForm = document.createElement("form");
			mapForm.target = "_blank";
			mapForm.method = "POST";
			mapForm.action = '<?= $CFG->src_report; ?>/excel_billing_by_tags';

			// Create an input
			var mapInput = document.createElement("input");
			mapInput.type = "text";
			mapInput.name = "pj_name";
			mapInput.value = value;

			// Add the input to the form
			mapForm.appendChild(mapInput);

			// Add the form to dom
			document.body.appendChild(mapForm);

			// Just submit
			mapForm.submit();
		}

		$('#min').datepicker({
			autoclose: true,
			yearRange: '1990:+0',
			format: 'yyyy-mm-dd',
			onSelect: function(date) {
				alert(date);
			},
			changeMonth: true,
			changeYear: true,
		});
		$('#max').datepicker({
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

		$('#min').change(function() {
			$("#max").val('');
			max = '';
		});

		$('#max').change(function() {
			max = $('#max').datepicker({
				dateFormat: 'yyyy-mm-dd'
			}).val();
			min = $('#min').datepicker({
				dateFormat: 'yyyy-mm-dd'
			}).val();

			value_project = $('#sel_fj_name').val();

			//Load data
			setTimeout(function() {
				// <!--datatable search paging-->
				$("#loadding").modal({
					backdrop: "static", //remove ability to close modal with click
					keyboard: false, //remove option to close with keyboard
					show: true //Display loader!
				});
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_data_main_bill").load("<?= $CFG->src_report; ?>/load_billing.php", {
					sel_fj_name: value_project,
					date_start_: min,
					date_end_: max
				});

			}, 500);


		});
	</script>
</body>

</html>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------dlg putaway-------------------->
<!--$('#modal-refill-putaway').modal('show');-->
<div class="modal fade" id="modal-refill-putaway" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> xxxx !!!</h4>
			</div>
			<div class="modal-body">
				<p>5555</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm">xxxx</button>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!--------------------dlg pallet-details-------------------->
<div class="modal fade" id="modal-pallet-details" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-search"></i> Pallet Tags Details</h4>
			</div>
			<div class="modal-body">
				<div class="box-body table-responsive padding">
					<span id="spn_load_open_pallet_details"></span>
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
<div class="modal fade" id="loadding" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
	<div class="modal-dialog modal-dialog-load  modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="loader"></div>
			</div>
		</div>
	</div>
</div>