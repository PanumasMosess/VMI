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
				<h1><i class="fa fa-caret-right"></i>&nbsp;User Master Data</h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">User Master Data</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-address-card"></i>&nbsp;Users List</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<div class="row">
									&nbsp;&nbsp;<button type="button" class="btn btn-success btn-md" onclick="addUserModel();"><i class="fa fa-user-plus fa-lg"></i>&nbsp;Create User</button>&nbsp;|&nbsp;<button type="button" class="btn btn-info btn-md" onclick="reload_table();"><i class="fa fa-refresh fa-lg"></i>&nbsp;Refresh</button>
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
			//load pallet no
			func_load_user_list();
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


		function func_load_user_list() {
			//Load data
			setTimeout(function() {
				$("#spn_load_data_main").load("<?= $CFG->src_user_mst; ?>/load_user_mst.php");
			}, 500);

		}

		function reload_table() {
			setTimeout(function() {
				$("#spn_load_data_main").load("<?= $CFG->src_user_mst; ?>/load_user_mst.php");
			}, 500);

		}
		//function open modal update driver
		function openUpdateUser(driver_id, driver_code, driver_name_th, driver_name_en, driver_company,
			driver_section, driver_truck_head_no, driver_truck_head_no, driver_truck_tail_no, driver_truck_type
		) {

			$('#txt_user_name_th').val(driver_name_th);
			$('#txt_user_name_en').val(driver_name_en);
			$('#txt_head_vehicle').val(driver_truck_head_no);
			$('#txt_tail_vehicle').val(driver_truck_tail_no);
			$('#txt_dri_code').val(driver_id);

			//dialog open
			$('#modal-update-user').modal({});
		}

		//Confirm Update User
		function confirmUpdateUser() {
			var pattern = /^[0-9]{2}-+[0-9]{4}$/;

			var txt_dri_code = $('#txt_dri_code').val();
			var txt_dri_name_th = $('#txt_dri_name_th').val();
			var txt_dri_name_en = $('#txt_dri_name_en').val();
			var txt_head_vehicle = $('#txt_head_vehicle').val();
			var txt_tail_vehicle = $('#txt_tail_vehicle').val();

			if (!(pattern.test(txt_head_vehicle))) {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Check Format Head Vehicle Number.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_head_vehicle.focus();
				}, 2500);

			} else if (!(pattern.test(txt_tail_vehicle))) {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Check Format Tail Vehicle Number.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_tail_vehicle.focus();
				}, 2500);
			} else if (txt_dri_name_th == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Driver Name (TH). </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_tail_vehicle.focus();
				}, 2500);

			} else if (txt_dri_name_en == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Driver Name (EH). </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_name_en.focus();
				}, 2500);
			} else if (txt_head_vehicle == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Head vehicle Number. </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_head_vehicle.focus();
				}, 2500);
			} else if (txt_tail_vehicle == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Tail vehicle Number. </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_tail_vehicle.focus();
				}, 2500);
			} else {
				$.ajax({
					type: 'POST',
					async: false,
					url: '<?= $CFG->src_driver; ?>/update_driver_mst.php',
					data: {
						txt_dri_code_: txt_dri_code,
						txt_dri_name_th_: txt_dri_name_th,
						txt_dri_name_en_: txt_dri_name_en,
						txt_head_vehicle_: txt_head_vehicle,
						txt_tail_vehicle_: txt_tail_vehicle
					},
					success: function(response) {
						if (response == "UPDATE_OK") {
							$("#modal-update-driver").modal("hide");
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Update Driver !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Update Driver Data is OK</span>",
								type: "success",
								timer: 2000,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							setTimeout(function() {
								$('#txt_dri_name_th').val('');
								$('#txt_dri_name_en').val('');
								$('#txt_head_vehicle').val('');
								$('#txt_tail_vehicle').val('');
								$('#txt_dri_code').val('');
								$('#modal-update-driver').modal("hide");
								reload_table();
							}, 2500);

						} else if (response == "ERR") {
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Check ERR !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Check Data</span>",
								type: "success",
								timer: 2500,
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

		}

		//function delete User
		function inActiveUser(dri_id) {
			swal({
					html: true,
					title: "<span style='font-size: 17px;'>[C003] --- Do you want to  delete customer ?</span>",
					text: "",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes',
					closeOnConfirm: false
				},
				function(isConfirm) {

					if (isConfirm) {


						$.ajax({
							type: 'POST',
							async: false,
							url: '<?= $CFG->src_driver; ?>/update_driver_status.php',
							data: {
								dri_id_: dri_id
							},
							success: function(response) {

								swal({
									html: true,
									title: "<span style='font-size: 30px; font-weight: bold;'>Update OK !!!</span>",
									text: "<span style='font-size: 15px; color: #000;'>[C001] --- Update Driver Status is OK</span>",
									type: "success",
									timer: 2500,
									showConfirmButton: false,
									allowOutsideClick: false
								});
								setTimeout(function() {
									reload_table();
								}, 2500);

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
			);
		}

		function closeModal() {

			$('#individual_driver_chg').val("");
			$('#individual_pwd_driver_chg').val("");
			$('#individual_new_pwd_driver_chg').val("");
			$('#individual_re_new_pwd_driver_chg').val("");
			//dialog close
			$('#modal-change-driver-password').modal("hide");


			$('#txt_dri_name_th').val('');
			$('#txt_dri_name_en').val('');
			$('#txt_head_vehicle').val('');
			$('#txt_tail_vehicle').val('');
			$('#txt_dri_code').val('');

			//dialog close
			$('#modal-update-user').modal("hide");



			$('#txt_dri_user_en_add').val("");
			$('#txt_dri_name_th_add').val("");
			$('#txt_dri_name_en_add').val("");
			$('#txt_dri_company_add').val("");
			$("#txt_sel_dri_shift_add").prop('selectedIndex', 0);
			$('#txt_dri_head_add').val("");
			$('#txt_dri_tail_add').val("");
			//dialog close
			$('#modal-create-user').modal("hide");
		}

		function openChangePassDriver(dri_id) {
			$('#individual_driver_chg').val(dri_id);
			//dialog open
			$('#modal-change-driver-password').modal({});
		}

		// //function change password
		// function changePasswordDriver() {
		// 	var individual_driver_chg = $('#individual_driver_chg').val();
		// 	var individual_pwd_driver_chg = $('#individual_pwd_driver_chg').val();
		// 	var individual_new_pwd_driver_chg = $('#individual_new_pwd_driver_chg').val();
		// 	var individual_re_new_pwd_driver_chg = $('#individual_re_new_pwd_driver_chg').val();

		// 	if (individual_pwd_driver_chg == "") {
		// 		swal({
		// 			html: true,
		// 			title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
		// 			text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Current Password </span>",
		// 			type: "warning",
		// 			timer: 2500,
		// 			showConfirmButton: false,
		// 			allowOutsideClick: false
		// 		});

		// 		setTimeout(function() {
		// 			document.form.individual_pwd_driver_chg.focus();
		// 		}, 2500);
		// 	} else if (individual_new_pwd_driver_chg == "") {
		// 		swal({
		// 			html: true,
		// 			title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
		// 			text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input New Password </span>",
		// 			type: "warning",
		// 			timer: 2500,
		// 			showConfirmButton: false,
		// 			allowOutsideClick: false
		// 		});

		// 		setTimeout(function() {
		// 			document.form.individual_new_pwd_driver_chg.focus();
		// 		}, 2500);
		// 	} else if (individual_re_new_pwd_driver_chg == "") {
		// 		swal({
		// 			html: true,
		// 			title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
		// 			text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Confirm New Password </span>",
		// 			type: "warning",
		// 			timer: 2500,
		// 			showConfirmButton: false,
		// 			allowOutsideClick: false
		// 		});

		// 		setTimeout(function() {
		// 			document.form.individual_re_new_pwd_driver_chg.focus();
		// 		}, 2500);
		// 	} else if (individual_new_pwd_driver_chg != individual_re_new_pwd_driver_chg) {
		// 		swal({
		// 			html: true,
		// 			title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
		// 			text: "<span style='font-size: 15px; color: #000;'>[C001] ---New Password and Confirm New Password Not Match</span>",
		// 			type: "warning",
		// 			timer: 2500,
		// 			showConfirmButton: false,
		// 			allowOutsideClick: false
		// 		});

		// 		setTimeout(function() {
		// 			document.form.individual_re_new_pwd_driver_chg.focus();
		// 		}, 2500);

		// 	} else {

		// 		$.ajax({
		// 			type: 'POST',
		// 			async: false,
		// 			url: '<?= $CFG->src_driver; ?>/update_driver_change_password.php',
		// 			data: {
		// 				individual_driver_chg_: individual_driver_chg,
		// 				individual_pwd_driver_chg_: individual_pwd_driver_chg,
		// 				individual_new_pwd_driver_chg_: individual_new_pwd_driver_chg,
		// 				individual_re_new_pwd_driver_chg_: individual_re_new_pwd_driver_chg,
		// 			},
		// 			success: function(response) {
		// 				if (response == "UPDATE_OK") {
		// 					swal({
		// 						html: true,
		// 						title: "<span style='font-size: 30px; font-weight: bold;'>Change Driver Password !!!</span>",
		// 						text: "<span style='font-size: 15px; color: #000;'>[C001] --- Change Password Driver is OK</span>",
		// 						type: "success",
		// 						timer: 2500,
		// 						showConfirmButton: false,
		// 						allowOutsideClick: false
		// 					});
		// 					$('#modal-change-driver-password').modal("hide");
		// 					setTimeout(function() {
		// 						$('#individual_driver_chg').val("");
		// 						$('#individual_pwd_driver_chg').val("");
		// 						$('#individual_new_pwd_driver_chg').val("");
		// 						$('#individual_re_new_pwd_driver_chg').val("");
		// 						reload_table();
		// 					}, 2000);

		// 				} else if (response == "PASSWORD_NOT_MATCH") {
		// 					swal({
		// 						html: true,
		// 						title: "<span style='font-size: 30px; font-weight: bold;'>Check Password !!!</span>",
		// 						text: "<span style='font-size: 15px; color: #000;'>[C001] --- Current Password Not Match</span>",
		// 						type: "warning",
		// 						timer: 2500,
		// 						showConfirmButton: false,
		// 						allowOutsideClick: false
		// 					});
		// 				}
		// 			},
		// 			error: function() {
		// 				//dialog ctrl
		// 				swal({
		// 					html: true,
		// 					title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		// 					text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
		// 					type: "warning",
		// 					timer: 3000,
		// 					showConfirmButton: false,
		// 					allowOutsideClick: false
		// 				});
		// 			}
		// 		});
		// 	}

		// }

		//function add open driver modal
		function addUserModel() {
			//dialog open
			$('#modal-create-user').modal({});
		}

		//function add User
		function createUser() {
			var pattern = /^[0-9]{2}-+[0-9]{4}$/;

			var txt_user_code = $('#txt_user_code').val();
			var txt_dri_name_th_add = $('#txt_dri_name_th_add').val();
			var txt_dri_name_en_add = $('#txt_dri_name_en_add').val();
			var txt_dri_company_add = $('#txt_dri_company_add').val();
			var txt_sel_dri_shift_add = $('#txt_sel_dri_shift_add').val();
			var txt_dri_head_add = $('#txt_dri_head_add').val();
			var txt_dri_tail_add = $('#txt_dri_tail_add').val();

			if (!(pattern.test(txt_dri_head_add))) {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Check Format Head Vehicle Number.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_head_add.focus();
				}, 2500);

			} else if (!(pattern.test(txt_dri_tail_add))) {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Check Format Tail Vehicle Number.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_tail_add.focus();
				}, 2500);
			} else if (txt_dri_tail_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Tail Vehicle Number.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_tail_add.focus();
				}, 2500);
			} else if (txt_dri_head_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Head Vehicle Number.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_head_add.focus();
				}, 2500);
			} else if (txt_dri_user_en_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input User Name (EN).</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_user_en_add.focus();
				}, 2500);
			} else if (txt_dri_name_th_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Driver Name (TH).</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_name_th_add.focus();
				}, 2500);
			} else if (txt_dri_name_en_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Driver Name (EN).</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_name_en_add.focus();
				}, 2500);
			} else if (txt_dri_company_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Company Name.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_company_add.focus();
				}, 2500);
			} else if (txt_sel_dri_shift_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Select Shift.</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_dri_company_add.focus();
				}, 2500);
			} else {

				$.ajax({
					type: 'POST',
					async: false,
					url: '<?= $CFG->src_driver; ?>/insert_driver_mst.php',
					data: {
						txt_dri_user_en_add_ : txt_dri_user_en_add,
						txt_dri_name_th_add_ : txt_dri_name_th_add,
						txt_dri_name_en_add_ : txt_dri_name_en_add,
						txt_dri_company_add_ : txt_dri_company_add,
						txt_sel_dri_shift_add_ : txt_sel_dri_shift_add,
						txt_dri_head_add_: txt_dri_head_add,
						txt_dri_tail_add_: txt_dri_tail_add
					},
					success: function(response) {						
						if (response == "INSERT_OK") {
							$('#modal-create-driver').modal("hide");
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Create Customer !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Create Customer is OK</span>",
								type: "success",
								timer: 2000,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							setTimeout(function() {
								$('#txt_dri_user_en_add').val("");
								$('#txt_dri_name_th_add').val("");
								$('#txt_dri_name_en_add').val("");
								$('#txt_dri_company_add').val("");
								$("#txt_sel_dri_shift_add").prop('selectedIndex', 0);
								$('#txt_dri_head_add').val("");
								$('#txt_dri_tail_add').val("");
								reload_table();
							}, 2000);

						} else if (response == "ERR") {
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Check Data !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Check Data</span>",
								type: "error",
								timer: 2500,
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
		}
	</script>

</body>

</html>


<!--------------------dlg update driver-------------------->
<div class="modal fade" id="modal-update-driver" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-address-card-o"></i> Update Driver Detail</h4>
			</div>
			<form name="frmUploadorder">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_name_th">Driver Name (TH)</label>
								<input type="text" class="form-control" name="txt_dri_name_th" id="txt_dri_name_th" />
								<input type="hidden" class="form-control" name="txt_dri_code" id="txt_dri_code" disabled />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_name_en">Driver Name (EN)</label>
								<input type="text" class="form-control" name="txt_dri_name_en" id="txt_dri_name_en" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_name_en">Head Vehicle Registration Number</label>
								<input type="text" class="form-control" name="txt_head_vehicle" id="txt_head_vehicle" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_name_en">Tail Vehicle Registration Number</label>
								<input type="text" class="form-control" name="txt_tail_vehicle" id="txt_tail_vehicle" />
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div align="right">
						<button type="button" class="btn btn-primary btn-sm" onclick="confirmUpdateUser();">Update User</button>
						<button type="button" class="btn btn-default btn-sm" onclick="closeModal();">Close</button>
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


<!-------------dlg alert change Dri password------------>
<!-------------dlg change password------------>
<div class="modal fade" id="modal-change-driver-password" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Change Driver password !!!</h4>
			</div>
			<div class="modal-body">
				<!-- /.login-logo -->
				<div class="login-box-body">
					<p class="login-box-msg"><i class="fa fa-lock"></i> Change password</p>
					<div class="form-group has-feedback">
						<input type="hidden" name="individual_driver_chg" id="individual_driver_chg">
						<label for="individual_pwd_driver_chg">Current password</label>
						<input type="password" name="individual_pwd_driver_chg" id="individual_pwd_driver_chg" class="form-control" placeholder="Enter current password">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="individual_new_pwd_driver_chg">New password</label>
						<input type="password" name="individual_new_pwd_driver_chg" id="individual_new_pwd_driver_chg" maxlength="10" class="form-control" placeholder="Enter new password (5-10 character)">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="individual_re_new_pwd_driver_chg">Confirm password</label>
						<input type="password" name="individual_re_new_pwd_driver_chg" id="individual_re_new_pwd_driver_chg" maxlength="10" class="form-control" placeholder="Enter confirm password (5-10 character)">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<p>
						<b>How to create a strong new password.</b><br>
						&nbsp;<i class="fa fa-angle-double-right"></i> Passwords must be at least 8 characters long.<br>
						&nbsp;<i class="fa fa-angle-double-right"></i> Passwords must contain: (A-Z,a-z,0-9).<br>
						&nbsp;<i class="fa fa-angle-double-right"></i> Do not use real words.<br>
						&nbsp;<i class="fa fa-angle-double-right"></i> The password must not contain the login of the account or a part of its name.
					</p>
				</div>
				<!-- /.login-box-body -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btn-sm" onclick="changePasswordDriver();">Change password</button>
				<button type="button" class="btn btn-default btn-sm" onclick="closeModal();">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<!--------------------dlg create driver-------------------->
<div class="modal fade" id="modal-create-user" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-address-card-o"></i> Create User VMI</h4>
			</div>
			<form name="frmUploadorder">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_user_code">User Code</label>
								<input type="text" placeholder="Enter user code (user login)" class="form-control" name="txt_user_code" id="txt_user_code" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_user_name_en_add">User Name (EN)</label>
								<input type="text" placeholder="Enter user name (EN)" class="form-control" name="txt_user_name_en_add" id="txt_user_name_en_add" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_user_name_th_add">User Name (TH)</label>
								<input type="text" placeholder="Enter user name (TH)" class="form-control" name="txt_user_name_th_add" id="txt_user_name_en_add" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_user_company_add">User Company</label>
								<input type="text" placeholder="Enter user company" class="form-control" name="txt_user_company_add" id="txt_user_company_add" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_user_section_add">User Section</label>
								<input type="text" placeholder="Enter user section" class="form-control" name="txt_user_section_add" id="txt_user_section_add" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_user_email_add">User Email</label>
								<input type="email"  placeholder="Enter user email" class="form-control" name="txt_user_email_add" id="txt_user_email_add" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_sel_user_type_add">User Type</label>
								<select class="form-control" name="txt_sel_user_type_add" id="txt_sel_user_type_add" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<option value="Administrator">Administrator</option>
									<option value="Customer">Customer</option>
								</select>
							</div>
						</div>
						<!-- <div class="col-md-6">
							<div class="form-group">
								<label for="txt_dri_head_add">Head Vehicle Registration Number</label>
								<input type="text" class="form-control" name="txt_dri_head_add" id="txt_dri_head_add" />
							</div>
						</div> -->
					</div>
				</div>
				<div class="modal-footer">
					<div align="right">
						<button type="button" class="btn btn-success btn-sm" onclick="createUser();"><i class="fa fa-plus"></i> Create User</button>
						<button type="button" class="btn btn-default btn-sm" onclick="closeModal();">Close</button>
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