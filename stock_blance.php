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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Stock Blance<small>Storage Location</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Stock Blance</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-cubes"></i> Stock List</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<div class="row">
									<div class="col-md-3">
										<select id="sel_fj_name" name="sel_fj_name" class="form-control select2" style="width: 100%;" onchange="func_load_pallate(this.value)">
											<option selected="selected" value="">Select Project Name</option>
											<?
					                                $strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
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
								</div>

							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT * ROWS</font>
							</div>
							<span id="spn_load_data_main"></span>
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
			reload_table("");
			$('#sel_fj_name').val("");

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

		function openRefill(id) {
			$('#modal-refill-putaway').modal('show');
		}

		function openFuncDetails(id) {
			$('#modal-pallet-details').modal('show');
			_open_pallet_details(id);
		}

		function _open_pallet_details(id) {
			//split id
			var str_split = id;
			var str_split_result = str_split.split("#####");

			var t_receive_pallet_code = str_split_result[0];
			var t_tags_fg_code_gdj = str_split_result[1];
			var t_receive_location = str_split_result[2];
			var t_receive_status = str_split_result[3];
			var t_receive_date = str_split_result[4];

			//load pallet no
			setTimeout(function() {
				//$("#spn_load_open_pallet_details").html(""); //clear span
				$("#spn_load_open_pallet_details").load("<?= $CFG->src_put_away; ?>/load_data_on_pallet_details.php", {
					t_receive_pallet_code: t_receive_pallet_code,
					t_tags_fg_code_gdj: t_tags_fg_code_gdj,
					t_receive_location: t_receive_location,
					t_receive_status: t_receive_status,
					t_receive_date: t_receive_date
				});
			}, 300);
		}

		function func_load_pallate(value) {
			// <!--datatable search paging-->
			$("#loadding").modal({
				backdrop: "static", //remove ability to close modal with click
				keyboard: false, //remove option to close with keyboard
				show: true //Display loader!
			});
			//Load data
			setTimeout(function() {
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_data_main").load("<?= $CFG->src_terminal; ?>/load_vmi_stock_blance.php", {
					sel_fj_name: value
				});
			}, 500);

		}

		function reload_table() {
			setTimeout(function() {
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_data_main").load("<?= $CFG->src_terminal; ?>/load_vmi_stock_blance.php", {
					sel_fj_name: ""
				});
			}, 500);

			$('#sel_fj_name').val("");
		}


		function _export_stock_by_pallet() {
			//href
			var value = $("#sel_fj_name").val();

			var mapForm = document.createElement("form");
			mapForm.target = "_blank";
			mapForm.method = "POST";
			mapForm.action = '<?= $CFG->src_terminal; ?>/excel_stock_blance';

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

		function _export_stock_by_tags() {
			//href
			var value = $("#sel_fj_name").val();

			var mapForm = document.createElement("form");
			mapForm.target = "_blank";
			mapForm.method = "POST";
			mapForm.action = '<?= $CFG->src_terminal; ?>/excel_stock_blance';

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