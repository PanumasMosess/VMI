<?
require_once("application.php");
require_once("get_authorized.php");
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
				<h1><i class="fa fa-caret-right"></i>&nbsp;FG (ft^2)<small>ค่าตารางฟุต</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">FG (ft^2)</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">FG (ft^2) List</h3><small>&nbsp;<font style="color: red">* เพิ่ม | แก้ไขข้อมูลด้วยการ อัพโหลด excel file</font></small>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<button type="button" class="btn btn-primary btn-sm" onclick="OpenFrmUploadFGft2();"><i class="fa fa-cloud-upload"></i> Upload FG (ft^2)</button>&nbsp;|&nbsp;<button type="button" class="btn btn-success btn-sm" onclick="openFuncDownloadFGft2();"><i class="fa fa-cloud-download fa-lg"></i> Download FG (ft^2)</button>&nbsp;&nbsp;/&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="javascript:location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT * ROWS</font>
							</div>

							<!-- /.box-header -->
							<div class="box-body table-responsive padding">
								<table id="tbl_fg_ft2_mst" class="table table-bordered table-hover table-striped nowrap">
									<thead>
										<tr style="font-size: 13px;">
											<th style="width: 30px;">No.</th>
											<!--<th style="text-align: center;">Actions/Details</th>-->
											<th>FG Code</th>
											<th>ft^2</th>
											<th>Issue By</th>
											<th>Issue Datetime</th>
										</tr>
									</thead>
									<tbody style="font-size: 13px;">
									</tbody>
								</table>
							</div>
							<!-- /.box-body -->

							<!--alert no item-->
							<!-- <input type="hidden" name="hdn_row" id="hdn_row" value="<?= $row_id; ?>" /> -->
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
		//Onload this page
		$(document).ready(function() {

			$.ajax({
                url: "<?= $CFG->src_fg_ft2; ?>/load_fg_ft2_mst.php",
                success: function(data) {
                   //console.log(data);
                    var result = JSON.parse(data);
                    callinTable(result);
                }
            });

		function callinTable(data) {
			// <!--datatable search paging-->
			$('#tbl_fg_ft2_mst').DataTable({
				bDestroy: true,
				rowReorder: true,
				columnDefs: [{
						orderable: true,
						className: 'reorder',
						targets: [0, 1, 2, 3, 4]
					},
					{
						orderable: false,
						targets: '_all'
					}
				],
				pagingType: "full_numbers",
				rowCallback: function(row, data, index) {
					//status
					/*
					if (data["bom_status"] == "Active") {
						$(row).find('td:eq(1)').css('color', 'indigo');
					} else if (data["bom_status"] == "InActive") {
						$(row).find('td:eq(1)').css('color', 'red');
					}

					//cost/pcs
					if (data["bom_cost_per_pcs"] == ".00") {
						$('td:eq(26)', row).html('0');
					}

					//price sale/pcs
					if (data["bom_price_sale_per_pcs"] == ".00") {
						$('td:eq(27)', row).html('0');
					}
					*/

				},
				responsive: true,
				autoFill: true,
				colReorder: true,
				keys: true,
				rowReorder: true,
				select: true,
				processing: true,
				serverside: true,
				data: data,
				columns: [{
						data: 'no'
					},
					{
						data: 'ft2_fg_code'
					},
					{
						data: 'ft2_value'
					},
					{
						data: 'ft2_issue_by'
					},
					{
						data: 'ft2_issue_datetime'
					},

				]
	
			});
		}

			// <!--Uppercase-->
			//$("#txt_line_vp_code").keyup(function(){ $(this).val( $(this).val().toUpperCase() ); });

			//clear step
			$("#progress_upload_fg_ft2").hide();
			$("#divresult_upload_fg_ft2").hide();

			//file type validation
			//Support file type .pdf only	
			$("#fg_ft2_file").change(function() {
				var selection = document.getElementById('fg_ft2_file');
				for (var i = 0; i < selection.files.length; i++) {
					//var ext = selection.files[i].name.substr(-3);
					var ext = selection.files[i].name.substr((selection.files[i].name.lastIndexOf('.') + 1)); //check type
					//const size = (selection.files[i].size / 1024 / 1024).toFixed(2); //check size

					//check type
					if (ext !== "xls" && ext !== "xlsx") {
						//dialog ctrl
						alert("[C002] --- Support file type .xls, .xlsx only");
						$("#fg_ft2_file").replaceWith($("#fg_ft2_file").val('').clone(true));
						$("#fg_ft2_file").focus();

						return false;
					}
				}
			});

		});

		// <!--OpenFrmUploadFGft2-->
		function OpenFrmUploadFGft2() {
			//dialog waiting ctrl
			$("#modal-upload-fg-ft2").modal("show");

			//clear step
			$("#progress_upload_fg_ft2").hide();
			$("#divresult_upload_fg_ft2").hide();

		}

		
		// !--ChkSubmit_frmUploadFGft2-- >
		function ChkSubmit_frmUploadFGft2(result) {
			if ($("#fg_ft2_file").val() == "") {
				//dialog ctrl
				alert("[C001] --- Select file");

				//hide
				setTimeout(function() {
					$("#fg_ft2_file").replaceWith($("#fg_ft2_file").val('').clone(true));
					$("#fg_ft2_file").focus();
				}, 500);

				return false;
			}

			$("#divFrm_upload_fgft2").hide("slide", {
				direction: "right"
			}, 400);
			$("#progress_upload_fg_ft2").show("slide", {
				direction: "left"
			}, 600);
			$("#divresult_upload_fg_ft2").html("<font style='font-size:14px; color: #00F; font-weight:bold;'>[I002] --- Please wait for a while, system is running....</font>");
			$("#divresult_upload_fg_ft2").show("slide", {
				direction: "left"
			}, 600);

			return true;
		}

		// <
		// !--showResult_frmUploadFGft2-- >
		function showResult_frmUploadFGft2(result) {
			$("#progress_upload_fg_ft2").hide("slide", {
				direction: "right"
			}, 600);
			$("#divFrm_upload_fgft2").show("slide", {
				direction: "left"
			}, 400);

			if (result == 1) {
				$("#divresult_upload_fg_ft2").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Column in excel file does not match the database.</font>");

				// <
				// !--Clear value type file-- >
				//$("#fg_ft2_file").replaceWith($("#fg_ft2_file").clone());
				$("#fg_ft2_file").replaceWith($("#fg_ft2_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_fg_ft2").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_fgft2").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 2) {
				$("#divresult_upload_fg_ft2").html("<font style='font-size:14px; color: green; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/Check_icon.svg.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D003] --- Upload FG (ft^2) file success.</font>");

				// <
				// !--Clear value type file-- >
				//$("#fg_ft2_file").replaceWith($("#fg_ft2_file").clone());
				$("#fg_ft2_file").replaceWith($("#fg_ft2_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_fg_ft2").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_fgft2").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);

				//refresh
				setTimeout(function() {
					location.reload();
				}, 5200);
			} else if (result == 3) {
				//show result
				$("#divresult_upload_fg_ft2").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Unable to upload FG (ft^2) file.</font>");

				// <
				// !--Clear value type file-- >
				//$("#fg_ft2_file").replaceWith($("#fg_ft2_file").clone());
				$("#fg_ft2_file").replaceWith($("#fg_ft2_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_fg_ft2").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_fgft2").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 4) {
				//show result
				$("#divresult_upload_fg_ft2").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C002] --- Error !! Wrong file type (Must be a file (.xls, .xlsx) only.</font>");

				// <
				// !--Clear value type file-- >
				//$("#fg_ft2_file").replaceWith($("#fg_ft2_file").clone());
				$("#fg_ft2_file").replaceWith($("#fg_ft2_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_fg_ft2").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_fgft2").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			}
		}

		function openFuncDownloadFGft2() {
			//href
			window.open('<?= $CFG->src_fg_ft2; ?>/excel_fg_ft2_mst', '_blank');
		}
	</script>
</body>

</html>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------dlg upload FG (ft^2)-------------------->
<div class="modal fade" id="modal-upload-fg-ft2" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Upload FG (ft^2) excel file</h4>
			</div>
			<form name="frmUploadFGft2" action="<?= $CFG->src_fg_ft2; ?>/fg_ft2_upload_exe.php" method="post" enctype="multipart/form-data" target="iframe_target_uploadFGft2" onSubmit="return ChkSubmit_frmUploadFGft2();">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<p><font style="color: red; font-size:12px;">* ข้อมูลใน FG (ft^2) ไม่ควรใช้เครื่องหมาย เว้นวรรค ควรใช้ - แทน มีหัวข้อได้แก่  FG Code GDJ</font></p>
								<a href="<?= $CFG->src_template_master_file; ?>/template_fg_ft2_mst.xlsx" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="Excel file"><i class="fa fa-cloud-download"></i>&nbsp;Template FG (ft^2) Master <i class="fa fa-file-excel-o"></i></a><br>
								<label for="fg_ft2_file"><i class="fa fa-folder-open-o"></i> Attachment file -- Support file type .xls,.xlsx only</label>
								<input type="file" name="fg_ft2_file" id="fg_ft2_file">
							</div>
							<!-- /.form-group -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div>
				<div class="modal-footer">
					<div align="left">
						<div id="divresult_upload_fg_ft2" name="divresult_upload_fg_ft2"></div>
						<div id="progress_upload_fg_ft2" name="progress_upload_fg_ft2"><img src="<?= $CFG->imagedir; ?>/Fountain.gif"></div><iframe id="iframe_target_uploadFGft2" name="iframe_target_uploadFGft2" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>

						<div id="divFrm_upload_fgft2" name="divFrm_upload_fgft2">
							<button type="submit" class="btn btn-primary btn-sm">Upload FG (ft^2)</button>
							<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
			<!-- /.form -->
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->