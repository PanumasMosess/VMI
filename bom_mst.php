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
				<h1><i class="fa fa-caret-right"></i>&nbsp;BOM<small>Bill of Material</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">BOM</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">BOM List</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<button type="button" class="btn btn-primary btn-sm" onclick="OpenFrmUploadBom();"><i class="fa fa-cloud-upload"></i> Upload BOM</button>&nbsp;|&nbsp;<button type="button" class="btn btn-success btn-sm" onclick="openFuncDownloadBom();"><i class="fa fa-cloud-download fa-lg"></i> Download BOM</button>&nbsp;&nbsp;/&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="javascript:location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT * ROWS</font>
							</div>

							<!-- /.box-header -->
							<div class="box-body table-responsive padding">
								<table id="tbl_bom_mst" class="table table-bordered table-hover table-striped nowrap">
									<thead>
										<tr style="font-size: 13px;">
											<th style="width: 30px;">No.</th>
											<th style="text-align: center;">Actions</th>
											<th>Status</th>
											<th>FG Code Set ABT</th>
											<th>Component Code ABT</th>
											<th>FG Code GDJ</th>
											<th>Description</th>
											<th>Customer Code</th>
											<th>Customer Name</th>
											<th>Project Name</th>
											<th>Carton code Normal</th>
											<th>SNP</th>
											<th>Type Code</th>
											<th>Ship Type</th>
											<th>Package Type</th>
											<th>W</th>
											<th>L</th>
											<th>H</th>
											<th>Usage</th>
											<th>Space Paper</th>
											<th>Flute</th>
											<th>Packing</th>
											<th>WMS Min</th>
											<th>WMS Max</th>
											<th>VMI Min</th>
											<th>VMI Max</th>
											<th>VMI App</th>
											<th>Part Customer</th>
											<th>Cost/Pcs.</th>
											<th>Price Sale/Pcs.</th>
											<th>Issue By</th>
											<th>Issue Datetime</th>
										</tr>
									</thead>
									<tbody style="font-size: 13px;">
									</tbody>
								</table>
							</div>
							<!-- /.box-body -->
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
		$(".select2").select2();
		//Onload this page
		$(document).ready(function() {

			$.ajax({
				url: "<?= $CFG->src_bom; ?>/load_bom_mst.php",
				success: function(data) {
					//console.log(data);
					var result = JSON.parse(data);
					callinTable(result);
				}
			});

			function callinTable(data) {
				// <!--datatable search paging-->
				$('#tbl_bom_mst').DataTable({
					bDestroy: true,
					rowReorder: true,
					columnDefs: [{
							orderable: true,
							className: 'reorder',
							targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29]
						},
						{
							orderable: false,
							targets: '_all'
						}
					],
					pagingType: "full_numbers",
					rowCallback: function(row, data, index) {
						//status
						if (data["bom_status"] == "Active") {
							$(row).find('td:eq(2)').css('color', 'indigo');
						} else if (data["bom_status"] == "InActive") {
							$(row).find('td:eq(2)').css('color', 'red');
						}

						//cost/pcs
						if (data["bom_cost_per_pcs"] == ".00") {
							$('td:eq(28)', row).html('0');
						}

						//price sale/pcs
						if (data["bom_price_sale_per_pcs"] == ".00") {
							$('td:eq(29)', row).html('0');
						}

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
							"data": null,
							render: function(data, type, row) {
								return "<button type'button' class='btn btn-info btn-sm custom_tooltip' id='" + data["bom_id"] + "###" + data["bom_fg_code_set_abt"] + "###" + data["bom_fg_sku_code_abt"] + "###" + data["bom_fg_code_gdj"] + "###" + data["bom_fg_desc"] + "###" + data["bom_cus_code"] + "###" + data["bom_cus_name"] + "###" + data["bom_pj_name"] + "###" + data["bom_ctn_code_normal"] + "###" + data["bom_snp"] + "###" + data["bom_sku_code"] + "###" + data["bom_ship_type"] + "###" + data["bom_pckg_type"] + "###" + data["bom_dims_w"] + "###" + data["bom_dims_l"] + "###" + data["bom_dims_h"] + "###" + data["bom_usage"] + "###" + data["bom_space_paper"] + "###" + data["bom_flute"] + "###" + data["bom_packing"] + "###" + data["bom_wms_min"] + "###" + data["bom_wms_max"] + "###" + data["bom_vmi_min"] + "###" + data["bom_vmi_max"] + "###" + data["bom_vmi_app"] + "###" + data["bom_part_customer"] + "###" + data["bom_cost_per_pcs"] + "###" + data["bom_price_sale_per_pcs"] + "###" + data["bom_status"] + "' onclick='openFuncBomUpdate(this.id);'><i class='glyphicon glyphicon-edit'></i><span class='custom_tooltiptext'>Update BOM Detail</span></button>"
							}
						},
						{
							data: 'bom_status'
						},
						{
							data: 'bom_fg_code_set_abt'
						},
						{
							data: 'bom_fg_sku_code_abt'
						},
						{
							data: 'bom_fg_code_gdj'
						},
						{
							data: 'bom_fg_desc'
						},
						{
							data: 'bom_cus_code',
						},
						{
							data: 'bom_cus_name'
						},
						{
							data: 'bom_pj_name'
						},
						{
							data: 'bom_ctn_code_normal'
						},
						{
							data: 'bom_snp'
						},
						{
							data: 'bom_sku_code'
						},
						{
							data: 'bom_ship_type'
						},
						{
							data: 'bom_pckg_type'
						},
						{
							data: 'bom_dims_w'
						},
						{
							data: 'bom_dims_l'
						},
						{
							data: 'bom_dims_h'
						},
						{
							data: 'bom_usage'
						},
						{
							data: 'bom_space_paper'
						},
						{
							data: 'bom_flute'
						},
						{
							data: 'bom_packing'
						},
						{
							data: 'bom_wms_min'
						},
						{
							data: 'bom_wms_max'
						},
						{
							data: 'bom_vmi_min'
						},
						{
							data: 'bom_vmi_max'
						},
						{
							"data": null,
							render: function(data, type, row) {
								if (data["bom_vmi_app"] == "Y") {
									return "Show In VMI App"

								} else {
									return "-"
								}

							}
						},
						{
							data: 'bom_part_customer'
						},
						{
							data: 'bom_cost_per_pcs'
						},
						{
							data: 'bom_price_sale_per_pcs'
						},
						{
							data: 'bom_issue_by'
						},
						{
							data: 'bom_issue_datetime'
						},

					]

				});
			}

			// <!--Uppercase-->
			//$("#txt_line_vp_code").keyup(function(){ $(this).val( $(this).val().toUpperCase() ); });

			//clear step
			$("#progress_upload_bom").hide();
			$("#divresult_upload_bom").hide();

			//file type validation
			//Support file type .pdf only	
			$("#bom_file").change(function() {
				var selection = document.getElementById('bom_file');
				for (var i = 0; i < selection.files.length; i++) {
					//var ext = selection.files[i].name.substr(-3);
					var ext = selection.files[i].name.substr((selection.files[i].name.lastIndexOf('.') + 1)); //check type
					//const size = (selection.files[i].size / 1024 / 1024).toFixed(2); //check size

					//check type
					if (ext !== "xls" && ext !== "xlsx") {
						//dialog ctrl
						alert("[C002] --- Support file type .pdf only");
						$("#bom_file").replaceWith($("#bom_file").val('').clone(true));
						$("#bom_file").focus();

						return false;
					}
				}
			});

		});

		// <!--OpenFrmUploadBom-->
		function OpenFrmUploadBom() {
			//dialog waiting ctrl
			$("#modal-upload-bom").modal("show");

			//clear step
			$("#progress_upload_bom").hide();
			$("#divresult_upload_bom").hide();

		}


		// !--ChkSubmit_frmUploadBom-- >
		function ChkSubmit_frmUploadBom(result) {
			if ($("#bom_file").val() == "") {
				//dialog ctrl
				alert("[C001] --- Select file");

				//hide
				setTimeout(function() {
					$("#bom_file").replaceWith($("#bom_file").val('').clone(true));
					$("#bom_file").focus();
				}, 500);

				return false;
			}

			$("#divFrm_upload_bom").hide("slide", {
				direction: "right"
			}, 400);
			$("#progress_upload_bom").show("slide", {
				direction: "left"
			}, 600);
			$("#divresult_upload_bom").html("<font style='font-size:14px; color: #00F; font-weight:bold;'>[I002] --- Please wait for a while, system is running....</font>");
			$("#divresult_upload_bom").show("slide", {
				direction: "left"
			}, 600);

			return true;
		}

		// <
		// !--showResult_frmUploadBom-- >
		function showResult_frmUploadBom(result) {
			$("#progress_upload_bom").hide("slide", {
				direction: "right"
			}, 600);
			$("#divFrm_upload_bom").show("slide", {
				direction: "left"
			}, 400);

			if (result == 1) {
				$("#divresult_upload_bom").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Column in excel file does not match the database.</font>");

				// <
				// !--Clear value type file-- >
				//$("#bom_file").replaceWith($("#bom_file").clone());
				$("#bom_file").replaceWith($("#bom_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_bom").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_bom").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 2) {
				$("#divresult_upload_bom").html("<font style='font-size:14px; color: green; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/Check_icon.svg.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D003] --- Upload BOM file success.</font>");

				// <
				// !--Clear value type file-- >
				//$("#bom_file").replaceWith($("#bom_file").clone());
				$("#bom_file").replaceWith($("#bom_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_bom").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_bom").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);

				//refresh
				setTimeout(function() {
					location.reload();
				}, 5200);
			} else if (result == 3) {
				//show result
				$("#divresult_upload_bom").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Unable to upload BOM file.</font>");

				// <
				// !--Clear value type file-- >
				//$("#bom_file").replaceWith($("#bom_file").clone());
				$("#bom_file").replaceWith($("#bom_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_bom").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_bom").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 4) {
				//show result
				$("#divresult_upload_bom").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C002] --- Error !! Wrong file type (Must be a file (.xls, .xlsx) only.</font>");

				// <
				// !--Clear value type file-- >
				//$("#bom_file").replaceWith($("#bom_file").clone());
				$("#bom_file").replaceWith($("#bom_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_bom").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_bom").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			}
		}

		function openFuncDownloadBom() {
			//href
			window.open('<?= $CFG->src_bom; ?>/excel_bom_mst', '_blank');
		}

		function openFuncBomUpdate(id) {
			$("#modal-update-bom").modal("show");
			var str_split = id;
			var str_split_result = str_split.split("###");

			$("#text_bom_id").val(str_split_result[0]);
			$("#text_fg_code_set_abt").val(str_split_result[1]);
			$("#text_component_abt").val(str_split_result[2]);
			$("#text_fg_code_gdj").val(str_split_result[3]);
			$("#text_des").val(str_split_result[4]);
			$("#text_customer_code").val(str_split_result[5]).trigger('change');
			$("#text_customer_name").val(str_split_result[6]).trigger('change');
			$("#text_project_name").val(str_split_result[7]).trigger('change');
			$("#text_ctn_code_normal").val(str_split_result[8]);
			$("#text_snp").val(str_split_result[9]);
			$("#text_type_code").val(str_split_result[10]);
			$("#text_ship_type").val(str_split_result[11]);
			$("#text_package_type").val(str_split_result[12]);
			$("#text_w").val(str_split_result[13]);
			$("#text_l").val(str_split_result[14]);
			$("#text_h").val(str_split_result[15]);
			$("#text_usage").val(str_split_result[16]);
			$("#text_space_paper").val(str_split_result[17]);
			$("#text_flute").val(str_split_result[18]);
			$("#text_packing").val(str_split_result[19]);
			$("#text_wms_min").val(str_split_result[20]);
			$("#text_wms_max").val(str_split_result[21]);
			$("#text_vmi_min").val(str_split_result[22]);
			$("#text_vmi_max").val(str_split_result[23]);
			$str_vmi_app = str_split_result[24];
			$("#text_partcustomer").val(str_split_result[25]);
			$("#text_cost").val(str_split_result[26]);
			$("#text_sale").val(str_split_result[27]);
			$str_vmi_bom_status = str_split_result[28];

			if ($str_vmi_app == 'Y') {

				$("#optionsRadiosAppY").prop("checked", true);

			} else {

				$("#optionsRadiosAppN").prop("checked", true);
			}

			if ($str_vmi_bom_status == 'Active') {

				$("#optionsActive").prop("checked", true);

			} else {

				$("#optionsUnActive").prop("checked", true);
			}

		}

		function updateBom_mst() {

			$text_bom_id = $("#text_bom_id").val();
			$text_fg_code_set_abt = $("#text_fg_code_set_abt").val();
			$text_component_abt = $("#text_component_abt").val();
			$text_fg_code_gdj = $("#text_fg_code_gdj").val();
			$text_des = $("#text_des").val();
			$text_customer_code = $("#text_customer_code").val();
			$text_customer_name = $("#text_customer_name").val();
			$text_project_name = $("#text_project_name").val();
			$text_ctn_code_normal = $("#text_ctn_code_normal").val();
			$text_snp = $("#text_snp").val();
			$text_type_code = $("#text_type_code").val();
			$text_ship_type = $("#text_ship_type").val();
			$text_package_type = $("#text_package_type").val();
			$text_w = $("#text_w").val();
			$text_l = $("#text_l").val();
			$text_h = $("#text_h").val();
			$text_usage = $("#text_usage").val();
			$text_space_paper = $("#text_space_paper").val();
			$text_flute = $("#text_flute").val();
			$text_packing = $("#text_packing").val();
			$text_wms_min = $("#text_wms_min").val();
			$text_wms_max = $("#text_wms_max").val();
			$text_vmi_min = $("#text_vmi_min").val();
			$text_vmi_max = $("#text_vmi_max").val();
			$text_partcustomer = $("#text_partcustomer").val();
			$text_cost = $("#text_cost").val();
			$text_sale = $("#text_sale").val();
			$str_vmi_app = $("#optionsRadiosAppY").val();

			if ($str_vmi_app == 'Y') {
				$str_vmi_app = $("#optionsRadiosAppY").val();
			} else {
				$str_vmi_app = $("#optionsRadiosAppN").val();
			}

			$str_bom_status = $("#optionsActive").val();
			if ($str_bom_status == "Active") {
				$str_bom_status = $("#optionsActive").val();
			} else {
				$str_bom_status = $("#optionsUnActive").val();
			}

			//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>Are you <b>confirm</b> bom update ?</span>",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: "btn-info",
					confirmButtonText: "Yes",
					cancelButtonText: "No",
					closeOnConfirm: true,
					closeOnCancel: true
				},
				function(isConfirm) {
					if (isConfirm) {

						$.ajax({
							type: 'POST',
							url: '<?= $CFG->src_bom; ?>/update_bom_exe',
							data: {
								ajax_bom_id : $text_bom_id,
								ajax_text_fg_code_set_abt: $text_fg_code_set_abt,
								ajax_text_component_abt: $text_component_abt,
								ajax_text_fg_code_gdj: $text_fg_code_gdj,
								ajax_text_des: $text_des,
								ajax_text_customer_code: $text_customer_code,
								ajax_text_customer_name: $text_customer_name,
								ajax_text_project_name: $text_project_name,
								ajax_text_ctn_code_normal: $text_ctn_code_normal,
								ajax_text_snp: $text_snp,
								ajax_text_type_code: $text_type_code,
								ajax_text_ship_type: $text_ship_type,
								ajax_text_package_type: $text_package_type,
								ajax_text_w: $text_w,
								ajax_text_l: $text_l,
								ajax_text_h: $text_h,
								ajax_text_usage: $text_usage,
								ajax_text_space_paper: $text_space_paper,
								ajax_text_flute: $text_flute,
								ajax_text_packing: $text_packing,
								ajax_text_wms_min: $text_wms_min,
								ajax_text_wms_max: $text_wms_max,
								ajax_text_vmi_min: $text_vmi_min,
								ajax_text_vmi_max: $text_vmi_max,
								ajax_text_partcustomer: $text_partcustomer,
								ajax_text_cost: $text_cost,
								ajax_text_sale: $text_sale,
								ajax_str_vmi_app: $str_vmi_app,
								ajax_str_bom_status: $str_bom_status,
							},
							success: function(response) {

								if (response == "UPDATE_SUCCESS") {
									$("#modal-update-bom").modal("hide");
									swal({
										html: true,
										title: "<span style='font-size: 20px; font-weight: bold;'>แก้ไข BOM สำเร็จ</span>",
										text: "<span style='font-size: 15px; color: #000;'>ข้อมูล BOM ได้ถูกแก้ไขเข้าระบบ</span>",
										type: "success",
										timer: 2000,
										showConfirmButton: false,
										allowOutsideClick: false
									});

									location.reload();

								} else {
									swal({
										html: true,
										title: "<span style='font-size: 15px; font-weight: bold;'>แก้ไขผิพลาด</span>",
										text: "<span style='font-size: 15px; color: #000;'>[D002]กรุณาตรวจสอบข้อมูล</span>",
										type: "error",
										timer: 3000,
										showConfirmButton: false,
										allowOutsideClick: false
									});
								}

							},
							error: function() {
								//dialog ctrl
								swal({
									html: true,
									title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
									text: "<span style='font-size: 15px; color: #000;'>[D002]ติดต่อ Admin</span>",
									type: "warning",
									timer: 3000,
									showConfirmButton: false,
									allowOutsideClick: false
								});
							}
						});

					}
				});


		}
	</script>
</body>

</html>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------dlg upload Bom-------------------->
<div class="modal fade" id="modal-upload-bom" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Upload BOM excel file</h4>
			</div>
			<form name="frmUploadBom" action="<?= $CFG->src_bom; ?>/bom_upload_exe.php" method="post" enctype="multipart/form-data" target="iframe_target_uploadBom" onSubmit="return ChkSubmit_frmUploadBom();">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<a href="<?= $CFG->src_template_master_file; ?>/template_bom_mst.xlsx" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="Excel file"><i class="fa fa-cloud-download"></i>&nbsp;Template BOM Master <i class="fa fa-file-excel-o"></i></a><br>
								<label for="bom_file"><i class="fa fa-folder-open-o"></i> Attachment file -- Support file type .xls,.xlsx only</label>
								<input type="file" name="bom_file" id="bom_file">
							</div>
							<!-- /.form-group -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div>
				<div class="modal-footer">
					<div align="left">
						<div id="divresult_upload_bom" name="divresult_upload_bom"></div>
						<div id="progress_upload_bom" name="progress_upload_bom"><img src="<?= $CFG->imagedir; ?>/Fountain.gif"></div><iframe id="iframe_target_uploadBom" name="iframe_target_uploadBom" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>

						<div id="divFrm_upload_bom" name="divFrm_upload_bom">
							<button type="submit" class="btn btn-primary btn-sm">Upload BOM</button>
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


<!--------------------dlg update bom-------------------->
<div class="modal fade" id="modal-update-bom" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title"><i class="fa fa-pencil-square-o"></i> Update bom VMI</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="text_fg_code_set_abt">Fg Code Set ABT: </label>
							<input type="text" class="form-control" id="text_fg_code_set_abt"></input>
							<input type="hidden" id="text_bom_id"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="text_component_abt">Component Code ABT: </label>
							<input type="text" class="form-control" id="text_component_abt"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="text_fg_code_gdj">FG Code GDJ: </label>
							<input type="text" class="form-control" id="text_fg_code_gdj"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label for="text_des">Description: </label>
							<textarea rows="3" class="form-control" id="text_des"></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="text_customer_code">Customer Code: </label>
							<select id="text_customer_code" name="text_customer_code" class="form-control select2">
								<?
								$strSQL_code = "SELECT  bom_cus_code  FROM [tbl_bom_mst] where  bom_status = 'Active' group by bom_cus_code ";
								$objQuery_code = sqlsrv_query($db_con, $strSQL_code) or die("Error Query [" . $strSQL_code . "]");
								while ($objResult_code = sqlsrv_fetch_array($objQuery_code, SQLSRV_FETCH_ASSOC)) {
								?>
									<option value="<?= $objResult_code["bom_cus_code"]; ?>"><?= $objResult_code["bom_cus_code"]; ?></option>
								<?
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label for="text_customer_name">Customer Name: </label>
							<select id="text_customer_name" name="text_customer_name" class="form-control select2">
								<?
								$strSQL_code = "SELECT  bom_cus_name  FROM [tbl_bom_mst] where  bom_status = 'Active' group by bom_cus_name ";
								$objQuery_code = sqlsrv_query($db_con, $strSQL_code) or die("Error Query [" . $strSQL_code . "]");
								while ($objResult_code = sqlsrv_fetch_array($objQuery_code, SQLSRV_FETCH_ASSOC)) {
								?>
									<option value="<?= $objResult_code["bom_cus_name"]; ?>"><?= $objResult_code["bom_cus_name"]; ?></option>
								<?
								}
								?>
							</select>
						</div>
					</div>
				</div>
				<!-- /.row -->
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="text_project_name">Project Name: </label>
							<select id="text_project_name" name="text_project_name" class="form-control select2">
								<?
								$strSQL_code = "SELECT  bom_pj_name  FROM [tbl_bom_mst] where  bom_status = 'Active' group by bom_pj_name ";
								$objQuery_code = sqlsrv_query($db_con, $strSQL_code) or die("Error Query [" . $strSQL_code . "]");
								while ($objResult_code = sqlsrv_fetch_array($objQuery_code, SQLSRV_FETCH_ASSOC)) {
								?>
									<option value="<?= $objResult_code["bom_pj_name"]; ?>"><?= $objResult_code["bom_pj_name"]; ?></option>
								<?
								}
								?>
							</select>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label for="text_ctn_code_normal">Carton code normal: </label>
							<input type="text" class="form-control" id="text_ctn_code_normal"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2">
						<div class="form-group">
							<label for="text_snp">SNP: </label>
							<input type="text" maxlength="4" class="form-control" onkeyup="this.value=this.value.replace(/[^\d]/,'')" id="text_snp"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_ship_type">Type Code: </label>
							<input type="text" class="form-control" id="text_type_code"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_ship_type">Ship Type: </label>
							<input type="text" class="form-control" id="text_ship_type"></input>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label for="text_package_type">Package Type: </label>
							<input type="text" class="form-control" id="text_package_type"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_w">W: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_w"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_l">L: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_l"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_h">H: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_h"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_usage">Usage: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_usage"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="text_space_paper">Space Paper: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_space_paper"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_flute">Flute: </label>
							<input type="text" class="form-control" id="text_flute"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_packing">Packing: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_packing"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_wms_min">WMS Min: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_wms_min"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_wms_max">WMS Max: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_wms_max"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_vmi_min">VMI Min: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_vmi_min"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_vmi_max">VMI Max: </label>
							<input type="text" maxlength="4" onkeyup="this.value=this.value.replace(/[^\d]/,'')" class="form-control" id="text_vmi_max"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label for="text_partcustomer">Part Customer: </label>
							<input type="text" class="form-control" id="text_partcustomer"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_cost">Cost/Pcs: </label>
							<input type="text" maxlength="5" onkeyup="this.value=this.value.replace(/[^\f]/,'')" class="form-control" id="text_cost"></input>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="text_sale">Price Sale/Pcs: </label>
							<input type="text" maxlength="5" onkeyup="this.value=this.value.replace(/[^\f]/,'')" class="form-control" id="text_sale"></input>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label>Bom Status </label>
							<div class="radio">
								<label>
									<input type="radio" name="options_bom_status" id="optionsActive" value="Active" checked="">
									Active
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="options_bom_status" id="optionsUnActive" value="InActive" checked="">
									Inactive
								</label>
							</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label>VMI App </label>
							<div class="radio">
								<label>
									<input type="radio" name="options_vmi_app" id="optionsRadiosAppY" value="Y" checked="">
									APP
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="options_vmi_app" id="optionsRadiosAppN" value="N" checked="">
									None
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="updateBom_mst()" class="btn btn-success btn-sm">Update</button>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->