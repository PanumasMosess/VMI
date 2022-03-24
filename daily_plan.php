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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Daily Plan<small>ตั้งค่าแผนการผลิตรายวัน</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Daily Plan</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Daily Plan List</h3><small>&nbsp;<font style="color: red">* เพิ่ม | แก้ไขข้อมูลด้วยการ อัพโหลด excel file</font></small>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<button type="button" class="btn btn-primary btn-sm" onclick="OpenFrmUploadDailyPlan();"><i class="fa fa-cloud-upload"></i> Upload Daily Plan</button>&nbsp;|&nbsp;<button type="button" class="btn btn-success btn-sm" onclick="openFuncDownloadDailyPlan();"><i class="fa fa-cloud-download fa-lg"></i> Download Daily Plan</button>&nbsp;&nbsp;/&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="javascript:location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT * ROWS</font>
							</div>

							<!-- /.box-header -->
							<div class="box-body table-responsive padding">
								<table id="tbl_daily_plan_mst" class="table table-bordered table-hover table-striped nowrap">
									<thead>
										<tr style="font-size: 13px;">
											<th style="width: 30px;">No.</th>
											<!--<th style="text-align: center;">Actions/Details</th>-->
											<th>Plan Date</th>
											<th>Plan (ft^2)</th>
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
                url: "<?= $CFG->src_daily_plan; ?>/load_daily_plan.php",
                success: function(data) {
                   //console.log(data);
                    var result = JSON.parse(data);
                    callinTable(result);
                }
            });

		function callinTable(data) {
			// <!--datatable search paging-->
			$('#tbl_daily_plan_mst').DataTable({
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
						data: 'plan_date'
					},
					{
						data: 'plan_ft2_value'
					},
					{
						data: 'plan_issue_by'
					},
					{
						data: 'plan_issue_datetime'
					},

				]
	
			});
		}

			// <!--Uppercase-->
			//$("#txt_line_vp_code").keyup(function(){ $(this).val( $(this).val().toUpperCase() ); });

			//clear step
			$("#progress_upload_daily_plan").hide();
			$("#divresult_upload_daily_plan").hide();

			//file type validation
			//Support file type .pdf only	
			$("#daily_plan_file").change(function() {
				var selection = document.getElementById('daily_plan_file');
				for (var i = 0; i < selection.files.length; i++) {
					//var ext = selection.files[i].name.substr(-3);
					var ext = selection.files[i].name.substr((selection.files[i].name.lastIndexOf('.') + 1)); //check type
					//const size = (selection.files[i].size / 1024 / 1024).toFixed(2); //check size

					//check type
					if (ext !== "xls" && ext !== "xlsx") {
						//dialog ctrl
						alert("[C002] --- Support file type .xls, .xlsx only");
						$("#daily_plan_file").replaceWith($("#daily_plan_file").val('').clone(true));
						$("#daily_plan_file").focus();

						return false;
					}
				}
			});

		});

		// <!--OpenFrmUploadDailyPlan-->
		function OpenFrmUploadDailyPlan() {
			//dialog waiting ctrl
			$("#modal-upload-daily-plan").modal("show");

			//clear step
			$("#progress_upload_daily_plan").hide();
			$("#divresult_upload_daily_plan").hide();

		}

		
		// !--ChkSubmit_frmUploadDailyPlan-- >
		function ChkSubmit_frmUploadDailyPlan(result) {
			if ($("#daily_plan_file").val() == "") {
				//dialog ctrl
				alert("[C001] --- Select file");

				//hide
				setTimeout(function() {
					$("#daily_plan_file").replaceWith($("#daily_plan_file").val('').clone(true));
					$("#daily_plan_file").focus();
				}, 500);

				return false;
			}

			$("#divFrm_upload_daily_plan").hide("slide", {
				direction: "right"
			}, 400);
			$("#progress_upload_daily_plan").show("slide", {
				direction: "left"
			}, 600);
			$("#divresult_upload_daily_plan").html("<font style='font-size:14px; color: #00F; font-weight:bold;'>[I002] --- Please wait for a while, system is running....</font>");
			$("#divresult_upload_daily_plan").show("slide", {
				direction: "left"
			}, 600);

			return true;
		}

		// <
		// !--showResult_frmUploadDailyPlan-- >
		function showResult_frmUploadDailyPlan(result) {
			$("#progress_upload_daily_plan").hide("slide", {
				direction: "right"
			}, 600);
			$("#divFrm_upload_daily_plan").show("slide", {
				direction: "left"
			}, 400);

			if (result == 1) {
				$("#divresult_upload_daily_plan").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Column in excel file does not match the database.</font>");

				// <
				// !--Clear value type file-- >
				//$("#daily_plan_file").replaceWith($("#daily_plan_file").clone());
				$("#daily_plan_file").replaceWith($("#daily_plan_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_daily_plan").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_daily_plan").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 2) {
				$("#divresult_upload_daily_plan").html("<font style='font-size:14px; color: green; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/Check_icon.svg.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D003] --- Upload Daily Plan file success.</font>");

				// <
				// !--Clear value type file-- >
				//$("#daily_plan_file").replaceWith($("#daily_plan_file").clone());
				$("#daily_plan_file").replaceWith($("#daily_plan_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_daily_plan").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_daily_plan").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);

				//refresh
				setTimeout(function() {
					location.reload();
				}, 5200);
			} else if (result == 3) {
				//show result
				$("#divresult_upload_daily_plan").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Unable to upload Daily Plan file.</font>");

				// <
				// !--Clear value type file-- >
				//$("#daily_plan_file").replaceWith($("#daily_plan_file").clone());
				$("#daily_plan_file").replaceWith($("#daily_plan_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_daily_plan").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_daily_plan").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 4) {
				//show result
				$("#divresult_upload_daily_plan").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C002] --- Error !! Wrong file type (Must be a file (.xls, .xlsx) only.</font>");

				// <
				// !--Clear value type file-- >
				//$("#daily_plan_file").replaceWith($("#daily_plan_file").clone());
				$("#daily_plan_file").replaceWith($("#daily_plan_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_daily_plan").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_daily_plan").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			}
		}

		function openFuncDownloadDailyPlan() {
			//href
			window.open('<?= $CFG->src_daily_plan; ?>/excel_daily_plan', '_blank');
		}
	</script>
</body>

</html>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------dlg upload Daily Plan-------------------->
<div class="modal fade" id="modal-upload-daily-plan" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Upload Daily Plan excel file</h4>
			</div>
			<form name="frmUploadDailyPlan" action="<?= $CFG->src_daily_plan; ?>/daily_plan_upload_exe.php" method="post" enctype="multipart/form-data" target="iframe_target_uploadDailyPlan" onSubmit="return ChkSubmit_frmUploadDailyPlan();">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<p><font style="color: red; font-size:12px;">* ข้อมูลใน Daily Plan รูปแบบวันที่ที่ใช้ได้คือ YYYY-mm-dd หรือ <?=date('Y-m-d');?></font></p>
								<a href="<?= $CFG->src_template_master_file; ?>/template_daily_plan.xlsx" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="Excel file"><i class="fa fa-cloud-download"></i>&nbsp;Template Daily Plan <i class="fa fa-file-excel-o"></i></a><br>
								<label for="daily_plan_file"><i class="fa fa-folder-open-o"></i> Attachment file -- Support file type .xls,.xlsx only</label>
								<input type="file" name="daily_plan_file" id="daily_plan_file">
							</div>
							<!-- /.form-group -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div>
				<div class="modal-footer">
					<div align="left">
						<div id="divresult_upload_daily_plan" name="divresult_upload_daily_plan"></div>
						<div id="progress_upload_daily_plan" name="progress_upload_daily_plan"><img src="<?= $CFG->imagedir; ?>/Fountain.gif"></div><iframe id="iframe_target_uploadDailyPlan" name="iframe_target_uploadDailyPlan" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>

						<div id="divFrm_upload_daily_plan" name="divFrm_upload_daily_plan">
							<button type="submit" class="btn btn-primary btn-sm">Upload Daily Plan</button>
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