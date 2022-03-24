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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Picking Order B2C<small>FIFO Picking</small></h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Picking Order</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-qrcode"></i> Generate Picking Sheet</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Next Picking Sheet ID:<span id="spn_load_picking_no"></span></label>
									<input type="text" id="txt_picking_running" name="txt_picking_running" class="form-control input-sm" placeholder="Auto load Picking Sheet ID" disabled>
								</div>
								<!-- /.form-group -->
							</div>
							<!-- /.col -->
						</div>
						<!-- /.row -->

					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button id="btn_gen_picking_code" type="button" class="btn btn-primary btn-sm" onclick="gen_picking_code()"><i class="fa fa-qrcode"></i> Generate Picking Sheet</button>
					</div>
				</div>
				<!-- /.box -->

				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-check-square-o"></i> Confirm Picking Sheet B2C</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Current Picking Sheet ID:<span id="spn_load_curr_picking_no"></span><span id="spn_load_encode_curr_picking_no"></span></label>
									<input type="text" id="txt_curr_picking_no" name="txt_curr_picking_no" class="form-control input-sm" placeholder="Auto Receiving Picking Sheet ID" disabled>
									<input type="hidden" id="hdn_curr_picking_no" name="hdn_curr_picking_no">
								</div>
								<!-- /.form-group -->
							</div>
						</div>
						<!-- /.row -->


						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-cart-plus"></i> Confirmed Replenishment Order B2C List</h3> | <font style="color: #F00;">* Please select by Project or Customer</font>
						</div>
						<!-- /.box-header -->

						<div class="box-header">
							<button type="button" class="btn btn-success btn-sm" onclick="confirmPicking();"><i class="fa fa-check-square-o"></i> Confirm Picking</button>&nbsp;/&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="_load_waiting_conf_picking();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
						</div>
						<div style="padding-left: 8px;">
							<i class="fa fa-filter" style="color: #00F;"></i>
							<font style="color: #00F;">SQL >_ SELECT * ROWS</font>
						</div>
						<span id="spn_load_waiting_conf_picking"></span>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->

				<div class="row">
					<div class="col-xs-12">
						<div class="box box-primary">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-files-o"></i> Picking Sheet B2C List (Waiting Picking QC)</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<button type="button" class="btn btn-info btn-sm" onclick="_load_picking_sheet_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT * ROWS</font>
							</div>
							<!-- /.box-header -->
							<span id="spn_load_picking_sheet_details"></span>
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
			//clear
			_load_picking_no();
			_load_waiting_conf_picking();
			_clear_step();
			_load_picking_sheet_details();

		});

		function openFuncPickingSheetDetails(id) {
			$('#modal-pickingDetails').modal('show');
			_load_openFuncPickingSheetDetails(id);
		}

		function _load_openFuncPickingSheetDetails(id) {
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
			setTimeout(function() {
				//$("#spn_load_picking_details").html(""); //clear span
				$("#spn_load_picking_details").load("<?= $CFG->src_picking_order; ?>/load_PickingSheetDetails.php", {
					t_ps_h_picking_code: t_ps_h_picking_code,
					t_ps_h_cus_code: t_ps_h_cus_code,
					t_ps_h_cus_name: t_ps_h_cus_name,
					t_ps_t_pj_name: t_ps_t_pj_name,
					t_ps_h_status: t_ps_h_status,
					t_ps_h_issue_date: t_ps_h_issue_date
				});
			}, 300);
		}

		function _load_picking_sheet_details() {
			//Load data
			setTimeout(function() {
				//$("#spn_load_picking_sheet_details").html(""); //clear span
				$("#spn_load_picking_sheet_details").load("<?= $CFG->src_picking_order; ?>/load_picking_sheet_details_b2c.php");
			}, 300);
		}

		function _clear_step() {
			//clear
			$('#txt_curr_picking_no').val('');
		}

		function gen_picking_code() {
			//load picking no last updated
			_load_picking_no();

			if ($("#txt_picking_running").val() != "") {
				//dialog ctrl
				swal({
						html: true,
						title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						text: "<span style='font-size: 15px; color: #000;'>Confirm create Picking Sheet ID: <b>" + $("#txt_picking_running").val() + "</b> </span>",
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
								url: '<?= $CFG->src_picking_order; ?>/insert_picking_no.php',
								data: {
									iden_txt_picking_running: $("#txt_picking_running").val()
								},
								success: function(response) {

									if (response == "new") {
										//load picking no
										_load_picking_no();

										//clear step
										$('#txt_curr_picking_no').val($('#txt_picking_running').val());
										_load_hdn_encode_curr_picking_no();
									} else if (response == "dup") {
										//load tags
										_load_picking_no();
										_load_curr_picking_no();
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
		}

		function _load_hdn_encode_curr_picking_no() {
			//load pallet no
			setTimeout(function() {
				//$("#spn_load_encode_curr_picking_no").html(""); //clear span
				$("#spn_load_encode_curr_picking_no").load("<?= $CFG->src_picking_order; ?>/get_encode_picking.php", {
					curr_picking_no: $("#txt_curr_picking_no").val()
				});
			}, 300);
		}

		function _load_picking_no() {
			//load pallet no
			setTimeout(function() {
				//$("#spn_load_picking_no").html(""); //clear span
				$("#spn_load_picking_no").load("<?= $CFG->src_picking_order; ?>/generate_picking.php");
			}, 300);
		}

		function _load_curr_picking_no() {
			//load pallet no
			setTimeout(function() {
				//$("#spn_load_curr_picking_no").html(""); //clear span
				$("#spn_load_curr_picking_no").load("<?= $CFG->src_picking_order; ?>/get_curr_picking.php");
			}, 300);
		}

		function _load_waiting_conf_picking() {
			//Load data
			setTimeout(function() {
				//$("#spn_load_waiting_conf_picking").html(""); //clear span
				$("#spn_load_waiting_conf_picking").load("<?= $CFG->src_picking_order; ?>/load_waiting_conf_picking_b2c.php");
			}, 300);
		}

		//check all
		function toggle_chk_picking(source) {
			checkboxes = document.getElementsByName('_chk_picking[]');
			for (var i = 0, n = checkboxes.length; i < n; i++) {
				checkboxes[i].checked = source.checked;
				//var qty = "txtQtyDI"+(i+1);
				//var weight = "txtWeightDI"+(i+1);
				//document.getElementById(qty).disabled = !source.checked;
				//document.getElementById(weight).disabled = !source.checked;
			}
		}

		//remove duplicates values
		function remove_duplicates_es6(arr) {
			let s = new Set(arr);
			let it = s.values();
			return Array.from(it);
		}

		function confirmPicking() {
			var cbChecked = $("input[name='_chk_picking[]']:checked").length;
			var arr_pj = [];

			//read project
			$("input[name='_chk_picking[]']:checked").each(function() {
				var iden_hdn_bom_pj_name = "#hdn_bom_pj_name" + $(this).val();
				var iden_hdn_bom_pj_name = $(iden_hdn_bom_pj_name).val();

				arr_pj.push(iden_hdn_bom_pj_name);
			});

			uniq = remove_duplicates_es6(arr_pj);
			var chk_len = uniq.length;

			//check project > 1
			if (chk_len > 1) {
				//dialog ctrl
				swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please select one project name at a time</span>",
					type: "warning",
					timer: 2000,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				return false;
			} else {
				//check No data available in table
				if ($("#hdn_row_waiting_conf_picking").val() == 0) {
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
					if ($("#txt_curr_picking_no").val() == "") {
						//dialog ctrl
						swal({
							html: true,
							title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
							text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Generate Picking Sheet</span>",
							type: "warning",
							timer: 2000,
							showConfirmButton: false,
							allowOutsideClick: false
						});

						//hide
						setTimeout(function() {
							$('#btn_gen_picking_code').focus();
						}, 3000);

						return false;
					} else {
						//dialog ctrl
						swal({
								html: true,
								title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>Confirm create <b>New Picking Sheet</b></span>",
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

									var cbChecked = $("input[name='_chk_picking[]']:checked").length;

									//conf del
									var tmp = 0;
									$("input[name='_chk_picking[]']:checked").each(function() {
										// <
										// !--Alert not select item-- >
										tmp = tmp + 1;

										var iden_hdn_repn_id = "#hdn_repn_id" + $(this).val();
										var iden_hdn_repn_id = $(iden_hdn_repn_id).val();

										var iden_hdn_repn_fg_code_set_abt = "#hdn_repn_fg_code_set_abt" + $(this).val();
										var iden_hdn_repn_fg_code_set_abt = $(iden_hdn_repn_fg_code_set_abt).val();

										var iden_hdn_repn_sku_code_abt = "#hdn_repn_sku_code_abt" + $(this).val();
										var iden_hdn_repn_sku_code_abt = $(iden_hdn_repn_sku_code_abt).val();

										var iden_hdn_bom_fg_code_gdj = "#hdn_bom_fg_code_gdj" + $(this).val();
										var iden_hdn_bom_fg_code_gdj = $(iden_hdn_bom_fg_code_gdj).val();

										var iden_hdn_bom_cus_code = "#hdn_bom_cus_code" + $(this).val();
										var iden_hdn_bom_cus_code = $(iden_hdn_bom_cus_code).val();

										var iden_hdn_bom_cus_name = "#hdn_bom_cus_name" + $(this).val();
										var iden_hdn_bom_cus_name = $(iden_hdn_bom_cus_name).val();

										var iden_hdn_bom_pj_name = "#hdn_bom_pj_name" + $(this).val();
										var iden_hdn_bom_pj_name = $(iden_hdn_bom_pj_name).val();

										var iden_hdn_bom_ship_type = "#hdn_bom_ship_type" + $(this).val();
										var iden_hdn_bom_ship_type = $(iden_hdn_bom_ship_type).val();

										var iden_hdn_bom_part_customer = "#hdn_bom_part_customer" + $(this).val();
										var iden_hdn_bom_part_customer = $(iden_hdn_bom_part_customer).val();

										var iden_hdn_str_conv_pack = "#hdn_str_conv_pack" + $(this).val();
										var iden_hdn_str_conv_pack = $(iden_hdn_str_conv_pack).val();

										$.ajax({
											type: 'POST',
											url: '<?= $CFG->src_picking_order; ?>/create_picking_sheet.php',
											data: {
												iden_hdn_repn_id: iden_hdn_repn_id,
												iden_txt_curr_picking_no: $("#txt_curr_picking_no").val(),
												iden_hdn_repn_fg_code_set_abt: iden_hdn_repn_fg_code_set_abt,
												iden_hdn_repn_sku_code_abt: iden_hdn_repn_sku_code_abt,
												iden_hdn_bom_fg_code_gdj: iden_hdn_bom_fg_code_gdj,
												iden_hdn_bom_cus_code: iden_hdn_bom_cus_code,
												iden_hdn_bom_cus_name: iden_hdn_bom_cus_name,
												iden_hdn_bom_pj_name: iden_hdn_bom_pj_name,
												iden_hdn_bom_ship_type: iden_hdn_bom_ship_type,
												iden_hdn_bom_part_customer: iden_hdn_bom_part_customer,
												iden_hdn_str_conv_pack: iden_hdn_str_conv_pack,
												tmp_sel: tmp
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
									});

									//select at least 1 item
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
											openRePrintPickingSheet($("#hdn_curr_picking_no").val());
											_load_waiting_conf_picking();
											_load_picking_sheet_details();
											_clear_step();;
										}
									}
								}
							});
					}
				}
			}
		}

		function openRePrintPickingSheet(id) {
			setTimeout(function() {
				window.open("<?= $CFG->src_mPDF; ?>/print_picking_sheet_b2c?tag=" + id + "", "_blank");
			}, 500);
		}

		function openRePrintSet_on_picking_sheet(id) {
			window.open("<?= $CFG->src_mPDF; ?>/print_all_tags_on_picking_sheet?picking_sheet_no=" + id + "", "_blank");
		}

		function openFuncDetails(id) {
			$('#modal-pickingDetails').modal('show');
			_load_picking_details(id);
		}

		function _load_picking_details(id) {
			//split id
			var str_split = id;
			var str_split_result = str_split.split("#####");

			var t_fg_code_set_abt = str_split_result[0];
			var t_sku_code_abt = str_split_result[1];
			var t_fg_code_gdj = str_split_result[2];
			var t_pj_name = str_split_result[3];
			var t_ship_type = str_split_result[4];
			var t_part_customer = str_split_result[5];
			var t_conv_pack_qty = str_split_result[6];
			var t_repn_id = str_split_result[7];
			var t_repn_qty = str_split_result[8];

			//load pallet no
			setTimeout(function() {
				//$("#spn_load_picking_details").html(""); //clear span
				$("#spn_load_picking_details").load("<?= $CFG->src_picking_order; ?>/load_picking_order_details.php", {
					t_fg_code_set_abt: t_fg_code_set_abt,
					t_sku_code_abt: t_sku_code_abt,
					t_fg_code_gdj: t_fg_code_gdj,
					t_pj_name: t_pj_name,
					t_ship_type: t_ship_type,
					t_part_customer: t_part_customer,
					t_conv_pack_qty: t_conv_pack_qty,
					t_repn_id: t_repn_id,
					t_repn_qty: t_repn_qty
				});
			}, 300);
		}

		function openFuncSplitTags(id) {
			$('#modal-splitTags').modal('show');
			_load_SplitTags(id);
		}

		function _load_SplitTags(id) {
			//split id
			var str_split = id;
			var str_split_result = str_split.split("#####");

			var t_fg_code_set_abt = str_split_result[0];
			var t_sku_code_abt = str_split_result[1];
			var t_fg_code_gdj = str_split_result[2];
			var t_pj_name = str_split_result[3];
			var t_ship_type = str_split_result[4];
			var t_part_customer = str_split_result[5];
			var t_conv_pack_qty = str_split_result[6];
			var t_repn_qty = str_split_result[7];
			var t_repn_id = str_split_result[8];

			//load pallet no
			setTimeout(function() {
				//$("#spn_load_splitTags").html(""); //clear span
				$("#spn_load_splitTags").load("<?= $CFG->src_picking_order; ?>/load_picking_SplitTags.php", {
					t_fg_code_set_abt: t_fg_code_set_abt,
					t_sku_code_abt: t_sku_code_abt,
					t_fg_code_gdj: t_fg_code_gdj,
					t_pj_name: t_pj_name,
					t_ship_type: t_ship_type,
					t_part_customer: t_part_customer,
					t_conv_pack_qty: t_conv_pack_qty,
					t_repn_qty: t_repn_qty,
					t_repn_id: t_repn_id
				});
			}, 300);

		}

		function openReturnReplenish(id) {
			//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>Confirm Return To <b>Replenishment Order</b></span>",
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
							url: '<?= $CFG->src_picking_order; ?>/return_to_replenishment.php',
							data: {
								iden_repn_id: id
							},
							success: function(response) {

								//refresh
								_load_waiting_conf_picking();

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

		function _conf_split_tags() {
			//check No data available in table
			if ($("#hdn_row_split_tags").val() == 0) {
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
				//hide
				$('#modal-splitTags').modal('hide');

				//dialog ctrl
				swal({
						html: true,
						title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						text: "<span style='font-size: 15px; color: #000;'>Confirm <b>Split</b> Tags ???</span>",
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
								url: '<?= $CFG->src_picking_order; ?>/conf_split_tags.php',
								data: {
									iden_t_repn_id: $("#hdn_t_repn_id").val(),
									iden_t_split_qty: $("#hdn_split_tags_qty").val()
								},
								success: function(response) {

									//print tags
									openRePrintIndividual(response);

									//refresh
									_load_waiting_conf_picking();

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

						} else {
							$('#modal-splitTags').modal('show');
						}
					});
			}
		}

		function openRePrintIndividual(id) {
			window.open("<?= $CFG->src_mPDF; ?>/print_tags?tag=" + id + "", "_blank");
		}

		function openFuncReturnSplitTags(id) {
			//split id
			var str_split = id;
			var str_split_result = str_split.split("#####");

			var t_fg_code_set_abt = str_split_result[0];
			var t_sku_code_abt = str_split_result[1];
			var t_fg_code_gdj = str_split_result[2];
			var t_pj_name = str_split_result[3];
			var t_ship_type = str_split_result[4];
			var t_part_customer = str_split_result[5];
			var t_conv_pack_qty = str_split_result[6];
			var t_repn_qty = str_split_result[7];
			var t_repn_id = str_split_result[8];

			//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>Confirm <b>Return Split</b> Tags ???</span>",
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
							url: '<?= $CFG->src_picking_order; ?>/conf_return_split_tags.php',
							data: {
								iden_t_repn_id: t_repn_id
							},
							success: function(response) {

								//print tags
								openRePrintIndividual(response);

								//refresh
								_load_waiting_conf_picking();

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

		function restoreToConfirmedReplenishment(id) {
			//split id
			var str_split = id;
			var str_split_result = str_split.split("#####");

			var t_ps_h_picking_code = str_split_result[0];
			var t_ps_h_cus_code = str_split_result[1];
			var t_ps_h_cus_name = str_split_result[2];
			var t_ps_t_pj_name = str_split_result[3];
			var t_ps_h_status = str_split_result[4];
			var t_ps_h_issue_date = str_split_result[5];

			//dialog ctrl
			swal({
					html: true,
					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>Confirm Return <b> Picking Sheet</b></span>",
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
						$("#loadding").modal({
							backdrop: "static", //remove ability to close modal with click
							keyboard: false, //remove option to close with keyboard
							show: true //Display loader!
						});
						$.ajax({
							type: 'POST',
							url: '<?= $CFG->src_picking_order; ?>/return_to_confirm_picking_sheet.php',
							data: {
								t_ps_h_picking_code: t_ps_h_picking_code,
								t_ps_h_cus_code: t_ps_h_cus_code,
								t_ps_h_cus_name: t_ps_h_cus_name,
								t_ps_t_pj_name: t_ps_t_pj_name,
								t_ps_h_status: t_ps_h_status,
								t_ps_h_issue_date: t_ps_h_issue_date
							},
							success: function(response) {
								$("#loadding").modal("hide");
								//refresh
								_load_waiting_conf_picking();
								_load_picking_sheet_details();

							},
							error: function() {
								$("#loadding").modal("hide");
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
<!--------------------dlg pickingDetails-------------------->
<div class="modal fade" id="modal-pickingDetails" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-search"></i> Picking Order B2C Details</h4>
			</div>
			<div class="modal-body">
				<div class="box-body table-responsive padding">
					<span id="spn_load_picking_details"></span>
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

<!--------------------dlg splitTags-------------------->
<div class="modal fade" id="modal-splitTags" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-search"></i> Picking Order B2C Details (Split Tags)</h4>
			</div>
			<div class="modal-body">
				<div class="box-body table-responsive padding">
					<span id="spn_load_splitTags"></span>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="_conf_split_tags();" class="btn btn-primary btn-sm"><i class="fa fa-check-circle"></i> Comfirm Split Tag</button>
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