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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Replenishment Order<small>Normal Order / VMI Order / Special Order (Alternate order, Express order)</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Replenishment Order</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title">Replenishment List</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<button type="button" class="btn btn-primary btn-sm" onclick="OpenFrmUploadOrder();"><i class="fa fa-cloud-upload"></i> Upload Order</button>&nbsp;|&nbsp;<button type="button" class="btn btn-success btn-sm" onclick="confSelOrder();"><i class="fa fa-check-square-o"></i> Confirm Order</button>&nbsp;/&nbsp;<button type="button" class="btn btn-danger btn-sm" onclick="_delete_normal_order();"><i class="fa fa-trash fa-lg"></i> Delete</button>&nbsp;|&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="reload_table();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>&nbsp;|<b class="btn" id="excel_export"></b>
							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT * ROWS</font> | <font style="color: #F00;">Refill Type: VMI Order, Special Order Can't delete.</font>
							</div>

							<!-- /.box-header -->
							<div class="box-body table-responsive padding">
								<table id="tbl_replenishment_order" class="table table-bordered table-hover table-striped nowrap">
									<thead>
										<tr style="font-size: 13px;">
											<th style="width: 30px;">No.</th>
											<th><input type="checkbox" class="largerRadio" onClick="toggle_repn(this)" data-placement="top" data-toggle="tooltip" data-original-title="Select all" /></th>
											<th style="text-align: center;">Actions/Details</th>
											<th>Refill Type/Unit Type</th>
											<th>Ref No.</th>
											<th>Delivery Date</th>
											<th>FG Code Set</th>
											<th>Component Code</th>
											<th>Part Customer</th>
											<th style="color: #00F;">FG Code GDJ</th>
											<th style="color: #00F;">Quantity (Pcs.)</th>
											<th style="color: orange;">FIFO Picking (Pcs.)</th>
											<th style="color: indigo;">WMS Stock On Hand (Pcs.)</th>
											<th>Terminal Name</th>
											<th>Customer Code</th>
											<th>Project Name</th>
											<th>Issue By</th>
											<th>Issue Datetime</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
								<!--alert no item-->
								<input type="hidden" name="hdn_row_replenish" id="hdn_row_replenish" value="" />
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

	<!--------------------dlg update order-------------------->
	<div class="modal fade" id="modal-update-order" data-keyboard="false" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title"><i class="fa fa-pencil-square-o"></i> Update Replnishment Order</h3>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_order">Order Ref: </label>
								<input type="hidden" class="form-control" id="text_id"></input>
								<input type="text" class="form-control" id="text_order"></input>
							</div>
							<!-- /.form-group -->
						</div>
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_fg_code_set_abt">Fg Code Set ABT: </label>
								<input type="text" class="form-control" id="text_fg_code_set_abt"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_component_abt">Component Code ABT: </label>
								<input type="text" class="form-control" id="text_component_abt"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_fg_code_gdj">FG Code GDJ: </label>
								<input type="text" class="form-control" id="text_fg_code_gdj"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_project_name">Project Name: </label>
								<input type="text" class="form-control" id="text_project_name"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_ship_type">Ship Type: </label>
								<input type="text" class="form-control" id="text_ship_type"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_part_customer">Part Customer: </label>
								<input type="text" class="form-control" id="text_part_customer"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label for="text_qty">QTY: </label>
								<input type="number" class="form-control" id="text_qty"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_unit_type">Unit Type: </label>
								<input type="text" class="form-control" id="text_unit_type"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="text_teminal_name">Terminal Name: </label>
								<input type="text" class="form-control" id="text_teminal_name"></input>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
					<div class="row">
						<div class="col-md-4">
							<label for="text_deliver_date">Delivery Date: </label>
							<div class="input-group date">
								<div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								</div>
								<input type="text" class="form-control pull-right" id="text_deliver_date" name="text_deliver_date" />
							</div>
						</div>
						<!-- /.col -->
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="update_repleinish()" class="btn btn-success btn-sm">Update</button>
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<?
	require_once("js_css_footer.php");
	?>
	<script language="javascript">
		//Onload this page
		$(document).ready(function() {

			// var table = $('#tbl_replenishment_order').DataTable({
			// 	rowReorder: true,
			// 	"aLengthMenu": [
			// 		[10, 25, 50, 75, 100, -1],
			// 		[10, 25, 50, 75, 100, "All"]
			// 	],
			// 	"iDisplayLength": 10,
			// 	columnDefs: [{
			// 			orderable: true,
			// 			className: 'reorder',
			// 			targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
			// 		},
			// 		{
			// 			orderable: false,
			// 			targets: '_all'
			// 		}
			// 	],
			// 	orderCellsTop: true,
			// 	fixedHeader: true
			// });

			//load data json
			$.ajax({
				type: 'POST',
				url: "<?= $CFG->src_replenishment; ?>/load_replenishment_detail.php",
				success: function(respone) {
					console.log(respone);
					var result = JSON.parse(respone);
					callinTableJobsList(result);
				}
			});

			//plot data
			function callinTableJobsList(data) {

				// Setup - add a text input to each footer cell
				$('#tbl_replenishment_order thead tr').clone(true).appendTo('#tbl_replenishment_order thead');
				$('#tbl_replenishment_order thead tr:eq(1) th').each(function(i) {
					var title = $(this).text();

					//sel columnDefs
					if (i != 0 && i != 1 && i != 2 && i != 17) {
						$(this).html('<input type="text" placeholder="Search ' + title + '" />');

						$('input', this).on('keyup change', function() {
							if (table.column(i).search() !== this.value) {
								table
									.column(i)
									.search(this.value)
									.draw();
							}
						});
					} else {
						$(this).html('');
					}
				});

				var table = $("#tbl_replenishment_order").DataTable({
					"bDestroy": true,
					rowReorder: true,
					"aLengthMenu": [
						[10, 25, 50, 75, 100, -1],
						[10, 25, 50, 75, 100, "All"]
					],
					"iDisplayLength": 10,
					columnDefs: [{
							orderable: true,
							className: 'reorder',
							targets: [0, 2, 3, 4, 5, 6, 7, 8, 9]
						},
						{
							orderable: false,
							targets: '_all'
						}
					],
					orderCellsTop: true,
					fixedHeader: true,
					pagingType: "full_numbers",
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
							data: 'row_id'
						},
						{
							"data": null,
							render: function(data, type, row) {
								if (data["str_stock"] < data["repn_qty"]) {
									return "";
								} else {
									return "<input type='checkbox' name='_chk_repn_order[]' class='largerRadio' onclick='checkSelect(this.checked, " + data["row_id"] + ")' value='" + data["row_id"] + "' /> \
								    <input type='hidden' name='hdn_repn_id" + data["row_id"] + "' id='hdn_repn_id" + data["row_id"] + "' value='" + data["repn_id"] + "' /> \
                                    <input type='hidden' name='hdn_repn_order_type" + data["row_id"] + "' id='hdn_repn_order_type" + data["row_id"] + "' value='" + data["repn_order_type"] + "' /> \
                                    <input type='hidden' name='hdn_repn_order_ref" + data["row_id"] + "' id='hdn_repn_order_ref" + data["row_id"] + "' value='" + data["repn_order_ref"] + "' /> \
                                    <input type='hidden' name='hdn_fifo_picking_pack" + data["row_id"] + "' id='hdn_fifo_picking_pack" + data["row_id"] + "' value='" + data["str_fifo_picking_pack"] + "' /> \
                                    <input type='hidden' name='hdn_repn_qty" + data["row_id"] + "' id='hdn_repn_qty" + data["row_id"] + "' value='" + data["repn_qty"] + "' /> "
								}
							},
							"targets": -1
						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<button type'button' class='btn btn-info btn-sm custom_tooltip' id='" + data["repn_id"] + "###" + data["repn_order_ref"] + "###" + data["repn_fg_code_set_abt"] + "###" + data["repn_sku_code_abt"] + "###" + data["bom_fg_code_gdj"] + "###" + data["bom_pj_name"] + "###" + data["bom_ship_type"] + "###" + data["bom_part_customer"] + "###" + data["repn_qty"] + "###" + data["repn_unit_type"] + "###" + data["repn_terminal_name"] + "###" + data["repn_delivery_date"] + "' onclick='openFuncUpdate(this.id);'><i class='glyphicon glyphicon-edit'></i><span class='custom_tooltiptext'>Update Plan Detail</span></button>&nbsp;&nbsp;<button type'button' class='btn btn-warning btn-sm custom_tooltip' id='" + data["repn_id"] + "' onclick='openFuncSplitPlan(this.id);'><i class='glyphicon glyphicon-resize-small'></i><span class='custom_tooltiptext'>Split Plan</span></button>&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm custom_tooltip' id='" + data["repn_id"] + "#####" + data["repn_order_type"] + "#####" + data["repn_order_ref"] + "#####" + data["str_fifo_picking_pack"] + "#####" + data["repn_qty"] + "' onclick='openFuncConfirm(this.id);' ><i class='fa fa-check-square-o fa-lg'></i><span class='custom_tooltiptext'>Confirm</span></button>&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm custom_tooltip' id='" + data["repn_id"] + "' onclick='openFuncReject(this.id);''><i class='fa fa-times fa-lg'></i><span class='custom_tooltiptext'>Reject</span></button>"
							}
						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: " + data["str_order_color"] + "'>" + data["repn_order_type"] + "</font>/" + data["repn_unit_type"] + ""
							}
						},
						{
							data: 'repn_order_ref',
						},
						{
							"data": null,
							render: function(data, type, row) {

								return "<font style='font-weight: bold'>" + data["repn_delivery_date"] + "</font> "
							}
						},
						{
							data: 'repn_fg_code_set_abt'
						},
						{
							data: 'repn_sku_code_abt',
						},
						{
							data: 'bom_part_customer',
						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: #00F;'>" + data["bom_fg_code_gdj"] + "</font>"
							}

						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: #00F;'>" + data["repn_qty"] + "(" + data["remark_pack_piece"] + ")</font>"
							}

						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: orange;'>" + data["bom_packing"] + "(" + data["str_fifo_picking_pack"] + " Pack)</font>"
							}

						},
						{
							"data": null,
							render: function(data, type, row) {
								if(data["repn_order_type"] == "Special Order"){

									return "<font style='color: red;'>" + data["str_stock"] + "(" + data["str_stock_conv_pack"] + " Pack)</font>"

								}else{
									return "<font style='color: black;'>" + data["str_stock"] + "(" + data["str_stock_conv_pack"] + " Pack)</font>"
								}
							
							}

						},
						{
							data: 'repn_terminal_name'
						},
						{
							data: 'bom_cus_code'
						},
						{
							data: 'bom_pj_name'
						},
						{
							data: 'repn_by'
						},
						{
							data: 'repn_datetime_cut'
						},
					]
				});

				var buttons = new $.fn.dataTable.Buttons(table, {
					buttons: [{
						extend: 'excel',
						text: '<i class="fa fa-file-excel-o"></i> Export Replenishment Order',
						titleAttr: 'Excel Replenishment Order Report',
						title: 'Excel Replenishment Order Report',
						exportOptions: {
							modifier: {
								page: 'all'
							},
							columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
							format: {
								body: function(data, row, column, node) {
									if ((column >= 0 && column <= 6) || (column >= 8 && column <= 17)) {
										var data_ = data.replace(/<.*?>/ig, '');
										return data_;
									} else if (column == 7) {
										var data_ = data.replace(/<.*?>/ig, '');
										data_.split('(');
										return data_.split('(')[0];
									}
									return data;
								}
							}
						}
					}],
					dom: {
						button: {
							tag: 'button',
							className: 'btn btn-default btn-sm'
						}
					},
				}).container().appendTo($('#excel_export'));

			}

			//clear step
			$("#progress_upload_order").hide();
			$("#divresult_upload_order").hide();

			//file type validation
			//Support file type .pdf only	
			$("#order_file").change(function() {
				var selection = document.getElementById('order_file');
				for (var i = 0; i < selection.files.length; i++) {
					//var ext = selection.files[i].name.substr(-3);
					var ext = selection.files[i].name.substr((selection.files[i].name.lastIndexOf('.') + 1)); //check type
					//const size = (selection.files[i].size / 1024 / 1024).toFixed(2); //check size

					//check type
					if (ext !== "xls" && ext !== "xlsx") {
						//dialog ctrl
						alert("[C002] --- Support file type .pdf only");
						$("#order_file").replaceWith($("#order_file").val('').clone(true));
						$("#order_file").focus();

						return false;
					}
				}
			});

		});

		$('#text_deliver_date').datepicker({
			autoclose: true,
			yearRange: '1990:+0',
			format: 'yyyy-mm-dd',
			changeMonth: true,
			changeYear: true,
		});

		function openFuncConfirm(id) {
			//split id
			var str_split = id;
			var str_split_result = str_split.split("#####");

			var t_repn_id = str_split_result[0];
			var t_order_type = str_split_result[1];
			var t_ref_no = str_split_result[2];
			var t_fifo_picking_pack = str_split_result[3];
			var t_repn_qty = str_split_result[4];

			//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>Are you <b>confirm</b> replenishment order ?</span>",
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

						//accepted order
						$.ajax({
							type: 'POST',
							url: '<?= $CFG->src_replenishment; ?>/confirm_replenishment_order.php',
							data: {
								iden_t_repn_id: t_repn_id,
								iden_t_order_type: t_order_type,
								iden_t_ref_no: t_ref_no,
								iden_t_fifo_picking_pack: t_fifo_picking_pack,
								iden_hdn_repn_qty: t_repn_qty
							},
							success: function(response) {

								//refresh
								location.reload();

							},
							error: function() {
								//dialog ctrl
								swal({
									html: true,
									title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
									text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
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

		function reload_table() {
			$.ajax({
				type: 'POST',
				url: "<?= $CFG->src_replenishment; ?>/load_replenishment_detail.php",
				success: function(respone) {
					//console.log(respone);
					var result = JSON.parse(respone);
					callinTableJobsList(result);
				}
			});


			//plot data
			function callinTableJobsList(data) {

				var table = $("#tbl_replenishment_order").DataTable({
					"bDestroy": true,
					rowReorder: true,
					"aLengthMenu": [
						[10, 25, 50, 75, 100, -1],
						[10, 25, 50, 75, 100, "All"]
					],
					"iDisplayLength": 10,
					columnDefs: [{
							orderable: true,
							className: 'reorder',
							targets: [0, 2, 3, 4, 5, 6, 7, 8, 9]
						},
						{
							orderable: false,
							targets: '_all'
						}
					],
					orderCellsTop: true,
					fixedHeader: true,
					pagingType: "full_numbers",
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
							data: 'row_id'
						},
						{
							"data": null,
							render: function(data, type, row) {
								if (data["str_stock"] < data["repn_qty"]) {
									return "";
								} else {
									return "<input type='checkbox' name='_chk_repn_order[]' class='largerRadio' onclick='checkSelect(this.checked, " + data["row_id"] + ")' value='" + data["row_id"] + "' /> \
									<input type='hidden' name='hdn_repn_id" + data["row_id"] + "' id='hdn_repn_id" + data["row_id"] + "' value='" + data["repn_id"] + "' /> \
									<input type='hidden' name='hdn_repn_order_type" + data["row_id"] + "' id='hdn_repn_order_type" + data["row_id"] + "' value='" + data["repn_order_type"] + "' /> \
									<input type='hidden' name='hdn_repn_order_ref" + data["row_id"] + "' id='hdn_repn_order_ref" + data["row_id"] + "' value='" + data["repn_order_ref"] + "' /> \
									<input type='hidden' name='hdn_fifo_picking_pack" + data["row_id"] + "' id='hdn_fifo_picking_pack" + data["row_id"] + "' value='" + data["str_fifo_picking_pack"] + "' /> \
									<input type='hidden' name='hdn_repn_qty" + data["row_id"] + "' id='hdn_repn_qty" + data["row_id"] + "' value='" + data["repn_qty"] + "' /> "
								}
							},
							"targets": -1
						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<button type'button' class='btn btn-info btn-sm custom_tooltip' id='" + data["repn_id"] + "###" + data["repn_order_ref"] + "###" + data["repn_fg_code_set_abt"] + "###" + data["repn_sku_code_abt"] + "###" + data["bom_fg_code_gdj"] + "###" + data["bom_pj_name"] + "###" + data["bom_ship_type"] + "###" + data["bom_part_customer"] + "###" + data["repn_qty"] + "###" + data["repn_unit_type"] + "###" + data["repn_terminal_name"] + "###" + data["repn_delivery_date"] + "' onclick='openFuncUpdate(this.id);'><i class='glyphicon glyphicon-edit'></i><span class='custom_tooltiptext'>Update Plan Detail</span></button>&nbsp;&nbsp;<button type'button' class='btn btn-warning btn-sm custom_tooltip' id='" + data["repn_id"] + "' onclick='openFuncSplitPlan(this.id);'><i class='glyphicon glyphicon-resize-small'></i><span class='custom_tooltiptext'>Split Plan</span></button>&nbsp;&nbsp;<button type='button' class='btn btn-primary btn-sm custom_tooltip' id='" + data["repn_id"] + "#####" + data["repn_order_type"] + "#####" + data["repn_order_ref"] + "#####" + data["str_fifo_picking_pack"] + "#####" + data["repn_qty"] + "' onclick='openFuncConfirm(this.id);' ><i class='fa fa-check-square-o fa-lg'></i><span class='custom_tooltiptext'>Confirm</span></button>&nbsp;&nbsp;<button type='button' class='btn btn-danger btn-sm custom_tooltip' id='" + data["repn_id"] + "' onclick='openFuncReject(this.id);''><i class='fa fa-times fa-lg'></i><span class='custom_tooltiptext'>Reject</span></button>"
							}
						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='background-color: " + data["str_order_color"] + "'>" + data["repn_order_type"] + "</font>/" + data["repn_unit_type"] + ""
							}
						},
						{
							data: 'repn_order_ref',
						},
						{
							"data": null,
							render: function(data, type, row) {

								return "<font style='font-weight: bold'>" + data["repn_delivery_date"] + "</font> "
							}
						},
						{
							data: 'repn_fg_code_set_abt'
						},
						{
							data: 'repn_sku_code_abt'
						},
						{
							data: 'bom_part_customer'
						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: #00F;'>" + data["bom_fg_code_gdj"] + "</font>"
							}

						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: #00F;'>" + data["repn_qty"] + "(" + data["remark_pack_piece"] + ")</font> "
							}

						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: orange;'>" + data["bom_packing"] + "(" + data["str_fifo_picking_pack"] + ")</font> "
							}

						},
						{
							"data": null,
							render: function(data, type, row) {
								return "<font style='color: indigo;'>" + data["str_stock"] + "(" + data["str_stock_conv_pack"] + ")</font> "
							}

						},
						{
							data: 'repn_terminal_name'
						},
						{
							data: 'bom_cus_code'
						},
						{
							data: 'bom_pj_name'
						},
						{
							data: 'repn_by'
						},
						{
							data: 'repn_datetime_cut'
						},
					]
				});

				var buttons = new $.fn.dataTable.Buttons(table, {
					buttons: [{
						extend: 'excel',
						text: '<i class="fa fa-file-excel-o"></i> Export Replenishment Order',
						titleAttr: 'Excel Replenishment Order Report',
						title: 'Excel Replenishment Order Report',
						exportOptions: {
							modifier: {
								page: 'all'
							},
							columns: [3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17],
							format: {
								body: function(data, row, column, node) {
									if (column >= 0 && column <= 17) {
										return data.replace(/<.*?>/ig, '');
									} else if (column >= 3 && column <= 17) {
										return data.split(' ')[0];
									}
									return data;
								}
							}
						}
					}],
					dom: {
						button: {
							tag: 'button',
							className: 'btn btn-default btn-sm'
						}
					},
				}).container().appendTo($('#excel_export'));

			}
		}


		//check all
		function toggle_repn(source) {
			checkboxes = document.getElementsByName('_chk_repn_order[]');
			for (var i = 0, n = checkboxes.length; i < n; i++) {
				checkboxes[i].checked = source.checked;
				//var qty = "txtQtyDI"+(i+1);
				//var weight = "txtWeightDI"+(i+1);
				//document.getElementById(qty).disabled = !source.checked;
				//document.getElementById(weight).disabled = !source.checked;
			}
		}

		function confSelOrder() {

			let table = $('#tbl_replenishment_order').DataTable();
			let arr_list = [];
			let arr_length = 0;
			let checkedvalues = table.$("input[name='_chk_repn_order[]']:checked").each(function() {
				arr_list.push($(this).attr('id')),
					arr_length = arr_list.length
			});
			arr_list = arr_list.toString();


			//check No data available in table
			if (arr_length == 0) {
				//dialog ctrl
				swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[I004] --- No data available in table !!!</span>",
					type: "error",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
			} else {
				//dialog ctrl
				swal({
						html: true,
						title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						text: "<span style='font-size: 15px; color: #000;'>You want to <b>confirm</b> replenishment order all selected ?</span>",
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

							var cbChecked = table.$("input[name='_chk_repn_order[]']:checked").length;

							//conf
							var tmp = 0;
							$("input[name='_chk_repn_order[]']:checked").each(function() {

								//count for alert not select item
								tmp = tmp + 1;

								var iden_hdn_repn_id = "#hdn_repn_id" + $(this).val();
								console.log(iden_hdn_repn_id);
								var iden_hdn_repn_id = $(iden_hdn_repn_id).val();


								var iden_hdn_repn_order_type = "#hdn_repn_order_type" + $(this).val();
								var iden_hdn_repn_order_type = $(iden_hdn_repn_order_type).val();
								//	console.log(iden_hdn_repn_order_type);

								var iden_hdn_repn_order_ref = "#hdn_repn_order_ref" + $(this).val();
								var iden_hdn_repn_order_ref = $(iden_hdn_repn_order_ref).val();
								//	console.log(iden_hdn_repn_order_ref);

								var iden_hdn_fifo_picking_pack = "#hdn_fifo_picking_pack" + $(this).val();
								var iden_hdn_fifo_picking_pack = $(iden_hdn_fifo_picking_pack).val();
								//	console.log(iden_hdn_fifo_picking_pack);

								var iden_hdn_repn_qty = "#hdn_repn_qty" + $(this).val();
								var iden_hdn_repn_qty = $(iden_hdn_repn_qty).val();
								//	console.log(iden_hdn_repn_qty);

								//post
								$.ajax({
									type: 'POST',
									url: '<?= $CFG->src_replenishment; ?>/confirm_replenishment_order.php',
									data: {
										iden_t_repn_id: iden_hdn_repn_id,
										iden_t_order_type: iden_hdn_repn_order_type,
										iden_t_ref_no: iden_hdn_repn_order_ref,
										iden_t_fifo_picking_pack: iden_hdn_fifo_picking_pack,
										iden_hdn_repn_qty: iden_hdn_repn_qty
									},
									success: function(response) {
										//refresh
										location.reload();
									},
									error: function() {
										//dialog ctrl
										swal({
											html: true,
											title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
											text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
											type: "warning",
											timer: 3000,
											showConfirmButton: false,
											allowOutsideClick: false
										});
									}
								});
							});

							////select at least 1 item
							if (tmp == 0) {
								//dialog ctrl
								$("#modal-default").modal("show");
								$("#al_results").html("[C001] --- Please select at least 1 item !!!");

								//hide
								setTimeout(function() {
									$("#modal-default").modal("hide");
								}, 3000);
							}

							/*
							else
							{
								//refresh
								if(cbChecked == tmp)
								{
									location.reload();
								}
							}
							*/

						}
					});
			}
		}

		function openFuncReject(id) {
			$("#modal-reject-order").modal("show");
			$("#hdn_reject_id").val(id);
		}

		function ConfFuncReject() {
			if ($("#txt_reject_remark").val() == "") {
				$("#txt_reject_remark").focus();

				return false;
			} else {
				//accepted order
				$.ajax({
					type: 'POST',
					url: '<?= $CFG->src_replenishment; ?>/reject_replenishment_order.php',
					data: {
						iden_repn_id: $("#hdn_reject_id").val(),
						iden_repn_reject_remark: $("#txt_reject_remark").val()
					},
					success: function(response) {

						//refresh
						location.reload();

					},
					error: function() {
						//dialog ctrl
						swal({
							html: true,
							title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
							text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
							type: "warning",
							timer: 3000,
							showConfirmButton: false,
							allowOutsideClick: false
						});
					}
				});
			}
		}

		function _delete_normal_order() {
			//check No data available in table
			if ($("#hdn_row_replenish").val() == 0) {
				//dialog ctrl
				swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[I004] --- No data available in table !!!</span>",
					type: "error",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
			} else {
				//dialog ctrl
				swal({
						html: true,
						title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						text: "<span style='font-size: 15px; color: #000;'>You want to <b>delete</b> replenishment order all selected ?</span>",
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

							var cbChecked = $("input[name='_chk_repn_order[]']:checked").length;

							//conf
							var tmp = 0;

							$("input[name='_chk_repn_order[]']:checked").each(function() {
								//count for alert not select item
								tmp = tmp + 1;

								var iden_hdn_repn_id = "#hdn_repn_id" + $(this).val();
								var iden_hdn_repn_id = $(iden_hdn_repn_id).val();

								var iden_hdn_repn_order_type = "#hdn_repn_order_type" + $(this).val();
								var iden_hdn_repn_order_type = $(iden_hdn_repn_order_type).val();

								var iden_hdn_repn_order_ref = "#hdn_repn_order_ref" + $(this).val();
								var iden_hdn_repn_order_ref = $(iden_hdn_repn_order_ref).val();

								//Normal Order only
								if (iden_hdn_repn_order_type == "Normal Order") {
									//post
									$.ajax({
										type: 'POST',
										url: '<?= $CFG->src_replenishment; ?>/del_replenishment_order.php',
										data: {
											iden_t_repn_id: iden_hdn_repn_id,
											iden_t_order_type: iden_hdn_repn_order_type,
											iden_t_ref_no: iden_hdn_repn_order_ref
										},
										success: function(response) {
											//
										},
										error: function() {
											//dialog ctrl
											swal({
												html: true,
												title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
												text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
												type: "warning",
												timer: 3000,
												showConfirmButton: false,
												allowOutsideClick: false
											});
										}
									});
								}

							});

							////select at least 1 item
							if (tmp == 0) {
								//dialog ctrl
								$("#modal-default").modal("show");
								$("#al_results").html("[C001] --- Please select at least 1 item !!!");

								//hide
								setTimeout(function() {
									$("#modal-default").modal("hide");
								}, 3000);
							} else {
								//refresh
								if (cbChecked == tmp) {
									location.reload();
								}
							}

						}
					});
			}
		}

		// <!--OpenFrmUploadOrder-->
		function OpenFrmUploadOrder() {
			//dialog waiting ctrl
			$("#modal-upload-order").modal("show");

			//clear step
			$("#progress_upload_order").hide();
			$("#divresult_upload_order").hide();

		}

		// <!--ChkSubmit_frmUploadorder-->
		function ChkSubmit_frmUploadorder(result) {
			if ($("#txt_sel_cus").val() == "") {
				//dialog ctrl
				alert("[C001] --- Select Customer");

				//hide
				setTimeout(function() {
					$("#txt_sel_cus").focus();
				}, 500);

				return false;
			} else if ($("#order_file").val() == "") {
				//dialog ctrl
				alert("[C001] --- Select file");

				//hide
				setTimeout(function() {
					$("#order_file").replaceWith($("#order_file").val('').clone(true));
					$("#order_file").focus();
				}, 500);

				return false;
			}

			$("#divFrm_upload_order").hide("slide", {
				direction: "right"
			}, 400);
			$("#progress_upload_order").show("slide", {
				direction: "left"
			}, 600);
			$("#divresult_upload_order").html("<font style='font-size:14px; color: #00F; font-weight:bold;'>[I002] --- Please wait for a while, system is running....</font>");
			$("#divresult_upload_order").show("slide", {
				direction: "left"
			}, 600);

			return true;
		}

		// <!--showResult_frmUploadOrder-->
		function showResult_frmUploadOrder(result) {
			console.log(result);
			$("#progress_upload_order").hide("slide", {
				direction: "right"
			}, 600);
			$("#divFrm_upload_order").show("slide", {
				direction: "left"
			}, 400);

			if (result == 1) {
				$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! (ตรวจสอบ Column ไม่ตรง หรือ ไม่มี).</font>");

				// <!--Clear value type file-->
				//$("#order_file").replaceWith($("#order_file").clone());
				$("#order_file").replaceWith($("#order_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_order").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_order").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 2) {
				$("#divresult_upload_order").html("<font style='font-size:14px; color: green; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/Check_icon.svg.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D003] --- Upload order file success.</font>");

				// <!--Clear value type file-->
				//$("#order_file").replaceWith($("#order_file").clone());
				$("#order_file").replaceWith($("#order_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_order").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_order").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);

				//refresh
				setTimeout(function() {
					location.reload();
				}, 5200);
			} else if (result == 3) {
				//show result
				$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! BOM ไม่ตรง ตรวจสอบด่วน</font>");

				// <!--Clear value type file-->
				//$("#order_file").replaceWith($("#order_file").clone());
				$("#order_file").replaceWith($("#order_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_order").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_order").show("slide", {
						direction: "left"
					}, 400);

				}, 4000);
			} else if (result == 4) {
				//show result
				$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C002] --- Error !! (.xls, .xlsx) only. (อัพเฉพาะ Excel file)</font>");

				// <!--Clear value type file-->
				//$("#order_file").replaceWith($("#order_file").clone());
				$("#order_file").replaceWith($("#order_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_order").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_order").show("slide", {
						direction: "left"
					}, 400);
				}, 4000);
			} else if (result == 5) {
				//show result
				$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C005] ---(ไม่พบข้อมูล Customer )</font>");

				// <!--Clear value type file-->
				//$("#order_file").replaceWith($("#order_file").clone());
				$("#order_file").replaceWith($("#order_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_order").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_order").show("slide", {
						direction: "left"
					}, 400);
				}, 4000);
			} else {
				//show result
				$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?= $CFG->imagedir; ?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C005] ---(ข้อมูลไม่ตรง BOM เช็ดค่า " + result + ")</font>");

				// <!--Clear value type file-->
				//$("#order_file").replaceWith($("#order_file").clone());
				$("#order_file").replaceWith($("#order_file").val('').clone(true));

				setTimeout(function() {
					$("#divresult_upload_order").hide("slide", {
						direction: "right"
					}, 1000);
					$("#divFrm_upload_order").show("slide", {
						direction: "left"
					}, 400);
				}, 4000);
			}
		}

		function openFuncSplitPlan(id) {

			//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 25px; font-weight: bold;'>Split Plan Repenishment</span>",
					type: 'input',
					inputType: "number",
					animation: "slide-from-top",
					inputPlaceholder: "กรอกจำนวน Split แผน",
					showCancelButton: true,
					confirmButtonClass: "btn-success",
					confirmButtonText: "Confirm",
					cancelButtonText: "Cancel",
					closeOnConfirm: false,
					closeOnCancel: true,
				},
				function(inputValue) {
					if (inputValue === "") {
						swal.showInputError(" You need to insert number Split !");
						return false
					} else if (inputValue != "") {
						$.ajax({
							type: 'POST',
							url: '<?= $CFG->src_replenishment; ?>/confirm_split_order.php',
							data: {
								iden_t_repn_id: id,
								iden_t_split_number: inputValue
							},
							success: function(response) {
								if (response == "not enough") {
									swal({
										html: true,
										title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
										text: "<span style='font-size: 15px; color: #000;'>[D001] --- Split Qty > Plan </span>",
										type: "warning",
										timer: 3000,
										showConfirmButton: false,
										allowOutsideClick: false
									});

								} else {
									swal({
										html: true,
										title: "<span style='font-size: 15px; font-weight: bold;'>Wating !!!</span>",
										text: "<span style='font-size: 15px; color: #000;'>Wating for Load new plan....... </span>",
										type: "success",
										timer: 30000,
										showConfirmButton: false,
										allowOutsideClick: false
									});
									//refresh
									location.reload();
								}
							},
							error: function() {
								//dialog ctrl
								swal({
									html: true,
									title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
									text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
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

		function openFuncUpdate(id) {
			$("#modal-update-order").modal("show");
			var str_split = id;
			var str_split_result = str_split.split("###");

			var repn_id = str_split_result[0];
			var repn_order_ref = str_split_result[1];
			var repn_fg_code_set_abt = str_split_result[2];
			var repn_sku_code_abt = str_split_result[3];
			var bom_fg_code_gdj = str_split_result[4];
			var bom_pj_name = str_split_result[5];
			var bom_ship_type = str_split_result[6];
			var bom_part_customer = str_split_result[7];
			var repn_qty = str_split_result[8];
			var repn_unit_type = str_split_result[9];
			var repn_terminal_name = str_split_result[10];
			var repn_delivery_date = str_split_result[11];

			$('#text_id').val(repn_id);
			$('#text_order').val(repn_order_ref);
			$('#text_fg_code_set_abt').val(repn_fg_code_set_abt);
			$('#text_component_abt').val(repn_sku_code_abt);
			$('#text_fg_code_gdj').val(bom_fg_code_gdj);
			$('#text_project_name').val(bom_pj_name);
			$('#text_ship_type').val(bom_ship_type);
			$('#text_part_customer').val(bom_part_customer);
			$('#text_qty').val(repn_qty);
			$('#text_unit_type').val(repn_unit_type);
			$('#text_teminal_name').val(repn_terminal_name);
			$('#text_deliver_date').val(repn_delivery_date);

		}

		function update_repleinish() {
			
			//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>Are you <b>confirm</b> replenishment update ?</span>",
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
						//accepted order
						$.ajax({
							type: 'POST',
							url: '<?= $CFG->src_replenishment; ?>/confrim_update_replenishment.php',
							data: {
								repn_id: $('#text_id').val(),
								repn_order_ref: $('#text_order').val(),
								repn_code_set_abt: $('#text_fg_code_set_abt').val(),
								repn_code_set_abt: $('#text_fg_code_set_abt').val(),
								repn_component_abt: $('#text_component_abt').val(),
								repn_bom_fg_code_gdj: $('#text_fg_code_gdj').val(),
								repn_bom_pj_name: $('#text_project_name').val(),
								repn_bom_ship_type: $('#text_ship_type').val(),
								repn_bom_part_customer: $('#text_part_customer').val(),
								repn_qty :$('#text_qty').val(),
								repn_unit_type: $('#text_unit_type').val(),
								repn_terminal_name: $('#text_teminal_name').val(),
								repn_delivery_date: $('#text_deliver_date').val()
							},
							success: function(response) {
								
								if(response == "UPDATE_SUCCESS"){
									$("#modal-update-order").modal("hide");
									//refresh
									location.reload();
									
								}else{
									$("#modal-update-order").modal("hide");
									swal({
									html: true,
									title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
									text: "<span style='font-size: 15px; color: #000;'>Please Check Data Update</span>",
									type: "warning",
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
									title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
									text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
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
<!--------------------dlg replenishment order-------------------->
<div class="modal fade" id="modal-reject-order" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Reasons for reject</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label>Reasons:<input type="hidden" name="hdn_reject_id" id="hdn_reject_id"></label>
							<textarea class="form-control" name="txt_reject_remark" id="txt_reject_remark" rows="3" placeholder="Remark"></textarea>
						</div>
						<!-- /.form-group -->
					</div>
					<!-- /.col -->
				</div>
				<!-- /.row -->
			</div>
			<div class="modal-footer">
				<button type="button" onclick="ConfFuncReject()" class="btn btn-danger btn-sm">Reject</button>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!--------------------dlg upload order-------------------->
<div class="modal fade" id="modal-upload-order" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-cloud-upload"></i> Upload order by excel file</h4>
			</div>
			<form name="frmUploadorder" action="<?= $CFG->src_replenishment; ?>/upload_order_exe_2.php" method="post" enctype="multipart/form-data" target="iframe_target_uploadorder" onSubmit="return ChkSubmit_frmUploadorder();">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<a href="<?= $CFG->src_template_master_file; ?>/template_upload_order_mst.xlsx" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="Excel file"><i class="fa fa-cloud-download"></i>&nbsp;Template order master <i class="fa fa-file-excel-o"></i></a><br>

							</div>
							<!-- /.form-group -->
						</div>
						<!-- /.col -->
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="form-group">
								<label for="txt_sel_cus">Select Customer</label>
								<select class="form-control" name="txt_sel_cus" id="txt_sel_cus" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<option value="Split">ขายแบบตามลูกค้า</option>
									<option value="Pack">ขายแบบ Pack</option>
									<!-- <?
									$strSQL = " SELECT bom_cus_name FROM tbl_bom_mst where bom_status = 'Active' group by bom_cus_name order by bom_cus_name  asc ";
									$objQuery = sqlsrv_query($db_con, $strSQL) or die("Error Query [" . $strSQL . "]");
									while ($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC)) {
									?>
										<option value="<?= $objResult["bom_cus_name"]; ?>"><?= $objResult["bom_cus_name"]; ?></option>
									<?
									}
									sqlsrv_close($db_con);
									?> -->
								</select>
							</div>
							<!-- /.form-group -->
						</div>
						<!-- /.col -->
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="order_file"><i class="fa fa-folder-open-o"></i> Attachment file -- Support file type .xls,.xlsx only</label>
								<input type="file" name="order_file" id="order_file">
							</div>
							<!-- /.form-group -->
						</div>
						<!-- /.col -->
					</div>
				</div>
				<div class="modal-footer">
					<div align="left">
						<div id="divresult_upload_order" name="divresult_upload_order"></div>
						<div id="progress_upload_order" name="progress_upload_order"><img src="<?= $CFG->imagedir; ?>/Fountain.gif"></div><iframe id="iframe_target_uploadorder" name="iframe_target_uploadorder" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
						<div id="divFrm_upload_order" name="divFrm_upload_order">
							<button type="submit" class="btn btn-primary btn-sm">Upload order</button>
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