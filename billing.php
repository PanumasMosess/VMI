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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Billing<small>Storage Location</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?=$CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
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
											<option selected="selected" value="">Select Project Name</option>
											<option value="ALL">ALL Project</option>
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
									</div>&nbsp;<button type="button" class="btn btn-info btn-md" onclick="reload_table();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
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
			func_load_billing("");
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
			window.open("<?=$CFG->src_mPDF; ?>/print_tag_on_tag?tag=" + id + "", "_blank");
		}

		function func_load_billing(value) {
			//Load data
			setTimeout(function() {
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_data_main_bill").load("<?= $CFG->src_report; ?>/load_billing.php", {
					sel_fj_name: value
				});
			}, 500);


		}

		function reload_table(){
			setTimeout(function() {
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_data_main_bill").load("<?= $CFG->src_report; ?>/load_billing.php", {
					sel_fj_name: ""
				});
			}, 500);

			$('#sel_fj_name').val("");
		}

		function _export_billing_by_tags() {
			//href
			var value = $("#sel_fj_name").val();

			var mapForm = document.createElement("form");
			mapForm.target = "_blank";
			mapForm.method = "POST";
			mapForm.action = '<?=$CFG->src_report; ?>/excel_billing_by_tags';

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