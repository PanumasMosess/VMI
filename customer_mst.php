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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Customer Master Data</h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Customer Master Data</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box box-success">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-user"></i>&nbsp;Customer List</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<div class="row">
									&nbsp;&nbsp;<button type="button" class="btn btn-success btn-md" onclick="addCustomer();"><i class="fa fa-plus fa-lg"></i>&nbsp;Create Customer</button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-md" onclick="reload_table();"><i class="fa fa-refresh fa-lg"></i>&nbsp;Refresh</button>
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
			func_load_customer();
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


		function func_load_customer() {
			//Load data
			setTimeout(function() {
				$("#spn_load_data_main").load("<?= $CFG->src_customer; ?>/load_customer_mst.php");
			}, 500);

		}

		function reload_table() {
			setTimeout(function() {
				$("#spn_load_data_main").load("<?= $CFG->src_customer; ?>/load_customer_mst.php");
			}, 500);

		}


		//function form update 
		function openUpdateCustomer(
			cus_id, cus_code_tex, cus_name_th, cus_name_en, cus_with_bom_cus_code, cus_with_bom_pj_name,
			cus_terminal_type, cus_type_b, cus_email
		) {

			$("#txt_cus_name_th").val(cus_name_th);
			$("#txt_cus_name_en").val(cus_name_en);
			$("#txt_cus_code").val(cus_id);
			$("#txt_cus_email").val(cus_email);			
			document.getElementById('txt_cus_code').style.display = 'none';


			var cus_code = $('#txt_sel_cus_code').children('option').length
			var list = document.getElementById('txt_sel_cus_code');
			for (cus_code_i = 0; cus_code_i < cus_code; cus_code_i++) {
				var data = list.options[cus_code_i].text;
				if (data == cus_with_bom_cus_code) {
					$("#txt_sel_cus_code").prop('selectedIndex', cus_code_i);
				}
			}

			var cus_name = $('#txt_sel_cus_name').children('option').length
			var list2 = document.getElementById('txt_sel_cus_name');
			for (cus_name_i = 0; cus_name_i < cus_name; cus_name_i++) {
				var data = list2.options[cus_name_i].text;
				if (data == cus_with_bom_pj_name) {
					$("#txt_sel_cus_name").prop('selectedIndex', cus_name_i);
				}
			}

			var cus_ter = $('#txt_sel_cus_terminal_type').children('option').length
			var list = document.getElementById('txt_sel_cus_terminal_type');
			for (cus_ter_i = 0; cus_ter_i < cus_ter; cus_ter_i++) {
				var data = list.options[cus_ter_i].text;
				if (data == cus_terminal_type) {
					$("#txt_sel_cus_terminal_type").prop('selectedIndex', cus_ter_i);
				}
			}

			var cus_type = $('#txt_sel_cus_type').children('option').length
			var list = document.getElementById('txt_sel_cus_type');
			for (cus_type_i = 0; cus_type_i < cus_type; cus_type_i++) {
				var data = list.options[cus_type_i].text;
				if (data == cus_type_b) {
					$("#txt_sel_cus_type").prop('selectedIndex', cus_type_i);
				}
			}


			//dialog open
			$('#modal-update-customer').modal({});

			//clear step
			$("#progress_upload_order").hide();
		}

		//function close modal update
		function closeModal() {
			$("txt_sel_cus_code").val("");
			$("txt_sel_cus_name").val("");
			$("txt_sel_cus_terminal_type").val("");
			$("txt_sel_cus_type").val("");
			$("#modal-update-customer").modal("hide");

			$("#txt_cus_user_en_add").val("");
			$("#txt_cus_name_th_add").val("");
			$("#txt_cus_name_en_add").val("");
			$('#txt_cus_email_add').val("");
			$("#txt_sel_cus_code_add").prop('selectedIndex', 0);
			$("#txt_sel_cus_name_add").prop('selectedIndex', 0);
			$("#txt_sel_cus_terminal_type_add").prop('selectedIndex', 0);
			$("#txt_sel_cus_type_add").prop('selectedIndex', 0);
			$("#modal-create-customer").modal("hide");

			$('#individual_customer_chg').val("");
			$('#individual_pwd_customer_chg').val("");
			$('#individual_new_pwd_customer_chg').val("");
			$('#individual_re_new_pwd_customer_chg').val("");
			$("#modal-change-cus-password").modal("hide");
		}

		//function confirm update
		function confirmUpdate() {

			var txt_cus_name_th = $('#txt_cus_name_th').val();
			var txt_cus_name_en = $('#txt_cus_name_en').val();
			var txt_cus_code = $('#txt_cus_code').val();
			var txt_cus_email = $('#txt_cus_email').val();			
			var txt_sel_cus_code = document.getElementById('txt_sel_cus_code').value;
			var txt_sel_cus_name = document.getElementById('txt_sel_cus_name').value;
			var txt_sel_cus_terminal_type = document.getElementById('txt_sel_cus_terminal_type').value;
			var txt_sel_cus_type = document.getElementById('txt_sel_cus_type').value;


			if (
				txt_cus_name_th == ""
			) {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Customer Name Thai </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_cus_name_th.focus();
				}, 2500);

			} else if (txt_cus_name_en == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Customer Name Eng</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_cus_name_en.focus();
				}, 2500);
			} else if (txt_sel_cus_code == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Select Customer Code </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_code.focus();
				}, 2500);
			} else if (txt_sel_cus_name == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Customer Project Name </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_name.focus();
				}, 2500);
			} else if (txt_sel_cus_terminal_type == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Select Terminal </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_terminal_type.focus();
				}, 2500);
			} else if (txt_sel_cus_type == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Select Customer Type </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_type.focus();
				}, 2500);
			} else if(txt_cus_email == ""){
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Email </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_cus_email.focus();
				}, 2500);
			}			
			else {

				$.ajax({
					type: 'POST',
					async: false,
					url: '<?= $CFG->src_customer; ?>/update_customer_mst.php',
					data: {
						txt_cus_code_: txt_cus_code,
						txt_cus_name_th_: txt_cus_name_th,
						txt_cus_name_en_: txt_cus_name_en,
						txt_sel_cus_code_: txt_sel_cus_code,
						txt_sel_cus_name_: txt_sel_cus_name,
						txt_sel_cus_terminal_type_: txt_sel_cus_terminal_type,
						txt_sel_cus_type_: txt_sel_cus_type,
						txt_cus_email_ : txt_cus_email
					},
					success: function(response) {
						if (response == "UPDATE_OK") {
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Update Customer !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Update Customer Data is OK</span>",
								type: "success",
								timer: 2500,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							setTimeout(function() {
								$("#modal-update-customer").modal("hide");
								reload_table();
							}, 2500);

						} else if (response == "ERR") {
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Confirm Stock !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Confirm Stock Data OK</span>",
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

		//function delete customer
		function inActive(cus_id) {
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
							url: '<?= $CFG->src_customer; ?>/update_customer_mst_status.php',
							data: {
								cus_id_: cus_id
							},
							success: function(response) {

								swal({
									html: true,
									title: "<span style='font-size: 30px; font-weight: bold;'>Update OK !!!</span>",
									text: "<span style='font-size: 15px; color: #000;'>[C001] --- Update Customer is OK</span>",
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

		//function add open customer modal
		function addCustomer() {
			//dialog open
			$('#modal-create-customer').modal({});
		}

		//function confirm insert customer to DB
		function createCustomer() {
			var txt_cus_user_en_add = $('#txt_cus_user_en_add').val();
			var txt_cus_name_th_add = $('#txt_cus_name_th_add').val();
			var txt_cus_name_en_add = $('#txt_cus_name_en_add').val();
			var txt_cus_email_add = $('#txt_cus_email_add').val();
			var txt_sel_cus_code_add = document.getElementById('txt_sel_cus_code_add').value;
			var txt_sel_cus_name_add = document.getElementById('txt_sel_cus_name_add').value;
			var txt_sel_cus_terminal_type_add = document.getElementById('txt_sel_cus_terminal_type_add').value;
			var txt_sel_cus_type_add = document.getElementById('txt_sel_cus_type_add').value;


			if (
				txt_cus_name_th_add == ""
			) {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Customer Name Thai </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_cus_name_th_add.focus();
				}, 2500);

			} else if (txt_cus_user_en_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Customer User EN</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_cus_user_en_add.focus();
				}, 2500);

			} else if (txt_cus_name_en_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Customer Name Eng</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.txt_cus_name_en_add.focus();
				}, 2500);
			} else if (txt_sel_cus_code_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Select Select Customer Code </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_code_add.focus();
				}, 2500);
			} else if (txt_sel_cus_name_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Select Customer Project Name </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_name_add.focus();
				}, 2500);
			} else if (txt_sel_cus_terminal_type_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Select Terminal </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_terminal_type_add.focus();
				}, 2500);
			} else if (txt_sel_cus_type_add == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Select Customer Type </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});
				setTimeout(function() {
					document.form.txt_sel_cus_type_add.focus();
				}, 2500);
			} else {
				$.ajax({
					type: 'POST',
					async: false,
					url: '<?= $CFG->src_customer; ?>/insert_customer_mst.php',
					data: {
						txt_cus_user_en_add_: txt_cus_user_en_add,
						txt_cus_name_th_add_: txt_cus_name_th_add,
						txt_cus_name_en_add_: txt_cus_name_en_add,
						txt_sel_cus_code_add_: txt_sel_cus_code_add,
						txt_sel_cus_name_add_: txt_sel_cus_name_add,
						txt_sel_cus_terminal_type_add_: txt_sel_cus_terminal_type_add,
						txt_sel_cus_type_add_: txt_sel_cus_type_add,
						txt_cus_email_add_: txt_cus_email_add
					},
					success: function(response) {
						if (response == "INSERT_OK") {
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Create Customer !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Create Customer is OK</span>",
								type: "success",
								timer: 2500,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							setTimeout(function() {
								$("#modal-create-customer").modal("hide");
								$("#txt_cus_user_en_add").val("");
								$("#txt_cus_name_th_add").val("");
								$("#txt_cus_name_en_add").val("");
								$('#txt_cus_email_add').val("");
								$("#txt_sel_cus_code_add").prop('selectedIndex', 0);
								$("#txt_sel_cus_name_add").prop('selectedIndex', 0);
								$("#txt_sel_cus_terminal_type_add").prop('selectedIndex', 0);
								$("#txt_sel_cus_type_add").prop('selectedIndex', 0);
								reload_table();
							}, 2500);

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

		//function change password
		function changePassword() {
			var individual_customer_chg = $('#individual_customer_chg').val();
			var individual_pwd_customer_chg = $('#individual_pwd_customer_chg').val();
			var individual_new_pwd_customer_chg = $('#individual_new_pwd_customer_chg').val();
			var individual_re_new_pwd_customer_chg = $('#individual_re_new_pwd_customer_chg').val();

			if (individual_pwd_customer_chg == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Current Password </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.individual_pwd_customer_chg.focus();
				}, 2500);
			} else if (individual_new_pwd_customer_chg == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input New Password </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.individual_new_pwd_customer_chg.focus();
				}, 2500);
			} else if (individual_re_new_pwd_customer_chg == "") {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Input Confirm New Password </span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.individual_re_new_pwd_customer_chg.focus();
				}, 2500);
			} else if (individual_new_pwd_customer_chg != individual_re_new_pwd_customer_chg) {
				swal({
					html: true,
					title: "<span style='font-size: 30px; font-weight: bold;'>Warning !!!</span>",
					text: "<span style='font-size: 15px; color: #000;'>[C001] ---New Password and Confirm New Password Not Match</span>",
					type: "warning",
					timer: 2500,
					showConfirmButton: false,
					allowOutsideClick: false
				});

				setTimeout(function() {
					document.form.individual_re_new_pwd_customer_chg.focus();
				}, 2500);

			} else {

				$.ajax({
					type: 'POST',
					async: false,
					url: '<?= $CFG->src_customer; ?>/update_customer_change_password.php',
					data: {
						individual_customer_chg_: individual_customer_chg,
						individual_pwd_customer_chg_: individual_pwd_customer_chg,
						individual_new_pwd_customer_chg_: individual_new_pwd_customer_chg,
						individual_re_new_pwd_customer_chg_: individual_re_new_pwd_customer_chg,
					},
					success: function(response) {
						if (response == "UPDATE_OK") {
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Change Customer Password !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Change Password Customer is OK</span>",
								type: "success",
								timer: 2500,
								showConfirmButton: false,
								allowOutsideClick: false
							});
							setTimeout(function() {
								$('#individual_customer_chg').val("");
								$('#individual_pwd_customer_chg').val("");
								$('#individual_new_pwd_customer_chg').val("");
								$('#individual_re_new_pwd_customer_chg').val("");
								$("#modal-change-cus-password").modal("hide");
								reload_table();
							}, 2500);

						} else if (response == "PASSWORD_NOT_MATCH") {
							swal({
								html: true,
								title: "<span style='font-size: 30px; font-weight: bold;'>Check Password !!!</span>",
								text: "<span style='font-size: 15px; color: #000;'>[C001] --- Current Password Not Match</span>",
								type: "warning",
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

		//function open change pass
		function openChangePass(cus_id) {
			//dialog open
			$('#modal-change-cus-password').modal({});
			$('#individual_customer_chg').val(cus_id);
		}
	</script>
</body>

</html>

<!--------------------dlg update customer-------------------->
<div class="modal fade" id="modal-update-customer" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-address-card-o"></i> Update Customer Detail</h4>
			</div>
			<form name="frmUploadorder">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="txt_cus_name_th">Customer Name th</label>
								<input type="text" class="form-control" name="txt_cus_name_th" id="txt_cus_name_th" />
								<input type="text" class="form-control" name="txt_cus_code" id="txt_cus_code" disabled />
							</div>
							<div class="form-group">
								<label for="txt_cus_name_en">Customer Name en</label>
								<input type="text" class="form-control" name="txt_cus_name_en" id="txt_cus_name_en" />
							</div>
							<div class="form-group">
								<label for="txt_sel_cus_code">Select Customer Code</label>
								<select class="form-control" name="txt_sel_cus_code" id="txt_sel_cus_code" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<?
										$strSQL_cus_code = " SELECT [bom_cus_code] FROM tbl_bom_mst group by [bom_cus_code] order by [bom_cus_code] asc ";
										$objQuery_cus_code = sqlsrv_query($db_con, $strSQL_cus_code) or die ("Error Query [".$strSQL_cus_code."]");
										while($objResult_cus_code = sqlsrv_fetch_array($objQuery_cus_code, SQLSRV_FETCH_ASSOC))
										{
						 				?>
									<option value="<?= $objResult_cus_code["bom_cus_code"]; ?>"><?= $objResult_cus_code["bom_cus_code"]; ?></option>
									<?
										}
										// sqlsrv_close($db_con);
						  			?>
								</select>
							</div>
							<div class="form-group">
								<label for="txt_sel_cus_name">Select Project Name</label>
								<select class="form-control" name="txt_sel_cus_name" id="txt_sel_cus_name" style="width: 100%;">
									<option selected="selected" value="">Choose</option>

									<?
										$strSQL = " SELECT [bom_pj_name] FROM tbl_bom_mst group by [bom_pj_name] order by [bom_pj_name] asc ";
										$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
										while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
										{
						 				?>
									<option value="<?= $objResult["bom_pj_name"]; ?>"><?= $objResult["bom_pj_name"]; ?></option>
									<?
										}
									//	sqlsrv_close($db_con);
						  			?>
								</select>
							</div>
							<div class="form-group">
								<label for="txt_sel_cus_terminal_type">Select Terminal Type</label>
								<select class="form-control" name="txt_sel_cus_terminal_type" id="txt_sel_cus_terminal_type" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<option value="Kiosk">Kiosk</option>
									<option value="Stand">Stand</option>
								</select>
							</div>
							<div class="form-group">
								<label for="txt_cus_email">Customer Email</label>
								<input type="text" class="form-control" name="txt_cus_email" id="txt_cus_email" />
							</div>
							<div class="form-group">
								<label for="txt_sel_cus_type">Select Customer Type</label>
								<select class="form-control" name="txt_sel_cus_type" id="txt_sel_cus_type" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<option value="User">User</option>
								</select>
							</div>
							<!-- /.form-group -->
						</div>
						<!-- /.col -->
					</div>
				</div>
				<div class="modal-footer">
					<div align="right">
						<button type="button" class="btn btn-primary btn-sm" onclick="confirmUpdate();">Update Customer</button>
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


<!--------------------dlg Create customer-------------------->
<div class="modal fade" id="modal-create-customer" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-address-card-o"></i> Create Customer Detail</h4>
			</div>
			<form name="frmUploadorder">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_user_en_add">Customer User Name</label>
								<input type="text" class="form-control" name="txt_cus_user_en_add" id="txt_cus_user_en_add" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_name_th_add">Customer Name (TH)</label>
								<input type="text" class="form-control" name="txt_cus_name_th_add" id="txt_cus_name_th_add" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_name_en_add">Customer Name (EN)</label>
								<input type="text" class="form-control" name="txt_cus_name_en_add" id="txt_cus_name_en_add" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_sel_cus_code_add">Select Customer Code</label>
								<select class="form-control" name="txt_sel_cus_code_add" id="txt_sel_cus_code_add" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<?
										$strSQL_cus_code = " SELECT [bom_cus_code] FROM tbl_bom_mst group by [bom_cus_code] order by [bom_cus_code] asc ";
										$objQuery_cus_code = sqlsrv_query($db_con, $strSQL_cus_code) or die ("Error Query [".$strSQL_cus_code."]");
										while($objResult_cus_code = sqlsrv_fetch_array($objQuery_cus_code, SQLSRV_FETCH_ASSOC))
										{
						 				?>
									<option value="<?= $objResult_cus_code["bom_cus_code"]; ?>"><?= $objResult_cus_code["bom_cus_code"]; ?></option>
									<?
										}
										// sqlsrv_close($db_con);
						  			?>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_sel_cus_name_add">Select Project Name</label>
								<select class="form-control" name="txt_sel_cus_name_add" id="txt_sel_cus_name_add" style="width: 100%;">
									<option selected="selected" value="">Choose</option>

									<?
										$strSQL = " SELECT [bom_pj_name] FROM tbl_bom_mst group by [bom_pj_name] order by [bom_pj_name] asc ";
										$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
										while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
										{
						 				?>
									<option value="<?= $objResult["bom_pj_name"]; ?>"><?= $objResult["bom_pj_name"]; ?></option>
									<?
										}
										
						  			?>
								</select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_sel_cus_terminal_type_add">Select Terminal Type</label>
								<select class="form-control" name="txt_sel_cus_terminal_type_add" id="txt_sel_cus_terminal_type_add" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<option value="Kiosk">Kiosk</option>
									<option value="Stand">Stand</option>
								</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_cus_email_add">Customer Email</label>
								<input type="email" class="form-control" name="txt_cus_email_add" id="txt_cus_email_add" />
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label for="txt_sel_cus_type_add">Select Customer Type</label>
								<select class="form-control" name="txt_sel_cus_type_add" id="txt_sel_cus_type_add" style="width: 100%;">
									<option selected="selected" value="">Choose</option>
									<option value="User">User</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div align="right">
						<button type="button" class="btn btn-success btn-sm" onclick="createCustomer();"><i class="fa fa-plus"></i> Create Customer</button>
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

<!-------------dlg alert change Cus password------------>
<!-------------dlg change password------------>
<div class="modal fade" id="modal-change-cus-password" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Change Customer password !!!</h4>
			</div>
			<div class="modal-body">
				<!-- /.login-logo -->
				<div class="login-box-body">
					<p class="login-box-msg"><i class="fa fa-lock"></i> Change password</p>
					<div class="form-group has-feedback">
						<input type="hidden" name="individual_customer_chg" id="individual_customer_chg">
						<label for="individual_pwd_customer_chg">Current password</label>
						<input type="password" name="individual_pwd_customer_chg" id="individual_pwd_customer_chg" class="form-control" placeholder="Enter current password">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="individual_new_pwd_customer_chg">New password</label>
						<input type="password" name="individual_new_pwd_customer_chg" id="individual_new_pwd_customer_chg" maxlength="10" class="form-control" placeholder="Enter new password (5-10 character)">
						<span class="glyphicon glyphicon-lock form-control-feedback"></span>
					</div>
					<div class="form-group has-feedback">
						<label for="individual_re_new_pwd_customer_chg">Confirm password</label>
						<input type="password" name="individual_re_new_pwd_customer_chg" id="individual_re_new_pwd_customer_chg" maxlength="10" class="form-control" placeholder="Enter confirm password (5-10 character)">
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
				<button type="button" class="btn btn-primary btn-sm" onclick="changePassword();">Change password</button>
				<button type="button" class="btn btn-default btn-sm" onclick="closeModal();">Close</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<?
	sqlsrv_close($db_con);
?>