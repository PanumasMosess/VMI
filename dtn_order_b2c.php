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
	update_status_b2c($db_con);
  ?>
  <!--------------------------->
  <!-- body  -->
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-caret-right"></i>&nbsp;Confirm DTN Order B2C<small>Delivery Transfer Note</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Confirm DTN Order B2C</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
	  <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-qrcode"></i> Generate Delivery Transfer Note</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Next DTN ID:<span id="spn_load_dtn_no"></span></label>
                <input type="text" id="txt_dtn_running" name="txt_dtn_running" class="form-control input-sm" placeholder="Auto load DTN ID" disabled>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
		  
        </div>
        <!-- /.box-body -->
		<div class="box-footer">
			<button id="btn_gen_dtn_code" type="button" class="btn btn-primary btn-sm" onclick="gen_dtn_code()"><i class="fa fa-qrcode"></i> Generate DTN</button>
		</div>
      </div>
      <!-- /.box -->
	  
		<div class="box box-warning">
			<div class="box-header with-border">
			  <h3 class="box-title"><i class="fa fa-check-square-o"></i> Confirm Delivery Transfer Note</h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
			  <div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label>Current DTN ID:<span id="spn_load_curr_dtn_no"></span><span id="spn_load_encode_curr_dtn_no"></span></label>
					<input type="text" id="txt_curr_dtn_no" name="txt_curr_dtn_no" class="form-control input-sm" placeholder="Auto Receiving DTN ID" disabled>
					<input type="hidden" id="hdn_curr_dtn_no" name="hdn_curr_dtn_no">
					<!-- <span id="hdn_spn_update_status"></span> -->
				  </div>
				  <!-- /.form-group -->
				</div>
			  </div>
			  <!-- /.row -->
			  
			  <div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label>Delivery Date:</label>
					<input type="text" id="txt_dtn_delivery_date" name="txt_dtn_delivery_date" value="<?=date('Y-m-d');?>" class="form-control input-sm" placeholder="Enter Delivery Date" disabled>
				  </div>
				  <!-- /.form-group -->
				</div>
				<div class="col-md-6">
				  <div class="form-group">
					<label>Delivery Time:</label>
					<input type="text" id="txt_dtn_delivery_time" name="txt_dtn_delivery_time" value="<?=date("H:i:s");?>" class="form-control input-sm" placeholder="Enter Delivery Time" disabled>
				  </div>
				  <!-- /.form-group -->
				</div>
			  </div>
			  <!-- /.row -->
			 
			  <div class="row">
				<div class="col-md-6">
				  <div class="form-group">
					<label><img src="<?=$CFG->iconsdir;?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> Driver Identification Card:</label>
					<input type="password" id="txt_dtn_scan_driver_iden_card" name="txt_dtn_scan_driver_iden_card" onKeyPress="if (event.keyCode==13){ return _onScan_driver_iden_card(); }" class="form-control input-sm" placeholder="Scan Driver Identification Card" disabled>
				  </div>
				  <!-- /.form-group -->
				</div>
				<div class="col-md-6">
				  <div class="form-group">
					<label><i class="fa fa-id-card-o"></i> Current Driver Identification Card:</label>
					<input type="password" id="txt_dtn_curr_scan_driver_iden_card" name="txt_dtn_curr_scan_driver_iden_card" class="form-control input-sm" placeholder="Auto Receiving Driver Identification Card" disabled>
				  </div>
				  <!-- /.form-group -->
				</div>
			  </div>
			  <!-- /.row -->
			  
				<div class="box-header with-border">
				  <h3 class="box-title"><i class="fa fa-files-o"></i> Picking Sheet B2C List (Completed Picking B2C QC)</h3> | <font style="color: #F00;">* Please select by Project or Customer</font>
				</div>
				<!-- /.box-header -->
				
				<div class="box-header">
					<button type="button" class="btn btn-success btn-sm" onclick="confirmDTN();"><i class="fa fa-check-square-o"></i> Confirm DTN B2C</button>&nbsp;/&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="_load_waiting_conf_dtn();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
				</div>
				<div style="padding-left: 8px;">
					<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
				</div>					
				<span id="spn_load_waiting_conf_dtn"></span>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->				

		<div class="row">
			<div class="col-xs-12">
				<div class="box box-primary">
					<div class="box-header with-border">
					  <h3 class="box-title"><img src="<?=$CFG->iconsdir;?>/truck.png" height="18px"> Delivery Transfer Note B2C List</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-info btn-sm" onclick="_load_dtn_sheet_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT TOP 1000 ROWS</font>
					</div>
					<!-- /.box-header -->
					<span id="spn_load_dtn_sheet_details"></span>
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
$(document).ready(function()
{
	//toUpperCase
	$('#txt_dtn_scan_driver_iden_card').keyup(function() { this.value = this.value.toUpperCase(); });
	
	//clear
	_load_dtn_no();
	_load_waiting_conf_dtn();
	_load_dtn_sheet_details();
	

	//set format date picker
	$.fn.datepicker.defaults.format = "yyyy-mm-dd";
	
	//date picker
    $('#txt_dtn_delivery_date').datepicker({
      autoclose: true,
	  assumeNearbyYear: true,
	  dateFormat: 'yyyy-mm-dd',
	  todayBtn: "linked",
	  todayHighlight: true,
    });

	/*
	var defaults = $.fn.datepicker.defaults = {
		assumeNearbyYear: false,
		autoclose: false,
		beforeShowDay: $.noop,
		beforeShowMonth: $.noop,
		beforeShowYear: $.noop,
		beforeShowDecade: $.noop,
		beforeShowCentury: $.noop,
		calendarWeeks: false,
		clearBtn: false,
		toggleActive: false,
		daysOfWeekDisabled: [],
		daysOfWeekHighlighted: [],
		datesDisabled: [],
		endDate: Infinity,
		forceParse: true,
		format: 'dd/mm/yyyy',
		keyboardNavigation: true,
		language: 'en',
		minViewMode: 0,
		maxViewMode: 4,
		multidate: false,
		multidateSeparator: ',',
		orientation: "auto",
		rtl: false,
		startDate: -Infinity,
		startView: 0,
		todayBtn: false,
		todayHighlight: false,
		weekStart: 0,
		disableTouchKeyboard: false,
		enableOnReadonly: true,
		showOnFocus: true,
		zIndexOffset: 10,
		container: 'body',
		immediateUpdates: false,
		title: '',
		templates: {
			leftArrow: '&laquo;',
			rightArrow: '&raquo;'
		}
	};
	*/
	
});

function _onScan_driver_iden_card()
{
	if($("#txt_dtn_scan_driver_iden_card").val() != "")
	{
		if(isEnglishchar($("#txt_dtn_scan_driver_iden_card").val())==false)
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please change to english language.</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$("#txt_dtn_scan_driver_iden_card").val('');
				$("#txt_dtn_scan_driver_iden_card").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			//check driver on table
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_dtn_order;?>/load_check_driver_details.php',
			  data: { 
						t_txt_dtn_scan_driver_iden_card: $("#txt_dtn_scan_driver_iden_card").val()
					},
					success: function(response){
				
					if(response == "OK")
					{
						//clear step
						$('#txt_dtn_curr_scan_driver_iden_card').val($('#txt_dtn_scan_driver_iden_card').val());
						$("#txt_dtn_scan_driver_iden_card").val('');
					}
					else if(response == "NG")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C002] --- Driver ID not matching on database</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						$("#txt_dtn_scan_driver_iden_card").val('');
					}
					
				  },
				error: function(){
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
}

//check eng only
function isEnglishchar(str)
{   
    var orgi_text="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-";   
    var str_length=str.length;
    var isEnglish=true;   
    var Char_At="";   
    for(i=0;i<str_length;i++)
	{   
        Char_At=str.charAt(i);   
        if(orgi_text.indexOf(Char_At)==-1)
		{   
            isEnglish=false;   
            break;
        }      
    }   
    return isEnglish; 
}

function openFuncDTNWatingDetails(id)
{
	$('#modal-DTNDetails').modal('show');
	_load_openFuncDTNWaitingSheetDetails(id);
}

function _load_openFuncDTNWaitingSheetDetails(id)
{
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
	setTimeout(function(){
		//$("#spn_load_dtn_details").html(""); //clear span
		$("#spn_load_dtn_details").load("<?=$CFG->src_dtn_order;?>/load_waiting_conf_dtn_popup.php", { t_ps_h_picking_code: t_ps_h_picking_code, t_ps_h_cus_code: t_ps_h_cus_code, t_ps_h_cus_name: t_ps_h_cus_name, t_ps_t_pj_name: t_ps_t_pj_name, t_ps_h_status: t_ps_h_status, t_ps_h_issue_date: t_ps_h_issue_date });
	},300);
}

function _load_dtn_sheet_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_dtn_sheet_details").html(""); //clear span
		$("#spn_load_dtn_sheet_details").load("<?=$CFG->src_dtn_order;?>/load_dtn_sheet_details_b2c.php");
	},300);
}

function _clear_step()
{
	//disabled
	$('#txt_dtn_delivery_date').prop('disabled',false);
	$('#txt_dtn_delivery_time').prop('disabled',false);
	$('#txt_dtn_scan_driver_iden_card').prop('disabled',false);
	
	//clear
	//$('#txt_curr_dtn_no').val('');
	$('#txt_dtn_delivery_date').val("<?=date('Y-m-d');?>");
	$('#txt_dtn_delivery_time').val("<?=date('H:i:s');?>");
	$('#txt_dtn_scan_driver_iden_card').val('');
	$('#txt_dtn_curr_scan_driver_iden_card').val('');
	
	//focus
	$('#txt_dtn_scan_driver_iden_card').focus();
}

function _clear_step_after_conf_dtn()
{
	//disabled
	$('#txt_dtn_delivery_date').prop('disabled',true);
	$('#txt_dtn_delivery_time').prop('disabled',true);
	$('#txt_dtn_scan_driver_iden_card').prop('disabled',true);
	
	//clear
	$('#txt_curr_dtn_no').val('');
	$('#txt_dtn_delivery_date').val("<?=date('Y-m-d');?>");
	$('#txt_dtn_delivery_time').val("<?=date('H:i:s');?>");
	$('#txt_dtn_scan_driver_iden_card').val('');
	$('#txt_dtn_curr_scan_driver_iden_card').val('');
}

function gen_dtn_code()
{
	//load dtn no last updated
	_load_dtn_no();
	
	if($("#txt_dtn_running").val() != "")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>Confirm create DTN ID: <b>"+ $("#txt_dtn_running").val() +"</b> </span>",
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
			  url: '<?=$CFG->src_dtn_order;?>/insert_dtn_no.php',
			  data: { 
						iden_txt_dtn_running: $("#txt_dtn_running").val()
					},
					success: function(response){
				
					if(response == "new")
					{
						//load dtn no
						_load_dtn_no();
						
						//clear step
						$('#txt_curr_dtn_no').val($('#txt_dtn_running').val());
						_load_hdn_encode_curr_dtn_no();
						_clear_step();
					}
					else if(response == "dup")
					{
						//load tags
						_load_dtn_no();
						_load_curr_dtn_no();
						_clear_step();
					}
					
				  },
				error: function(){
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

function _load_hdn_encode_curr_dtn_no()
{
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_encode_curr_dtn_no").html(""); //clear span
		$("#spn_load_encode_curr_dtn_no").load("<?=$CFG->src_dtn_order;?>/get_encode_dtn.php", { curr_dtn_no: $("#txt_curr_dtn_no").val() });
	},300);
}

function _load_dtn_no()
{
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_dtn_no").html(""); //clear span
		$("#spn_load_dtn_no").load("<?=$CFG->src_dtn_order;?>/generate_dtn.php");
	},300);
}

function _load_curr_dtn_no()
{
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_curr_dtn_no").html(""); //clear span
		$("#spn_load_curr_dtn_no").load("<?=$CFG->src_dtn_order;?>/get_curr_dtn.php");
	},300);
}

function _load_waiting_conf_dtn()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_waiting_conf_dtn").html(""); //clear span
		$("#spn_load_waiting_conf_dtn").load("<?=$CFG->src_dtn_order;?>/load_waiting_conf_dtn_b2c.php");
	},300);
}

//check all
function toggle_chk_dtn(source)
{
  checkboxes = document.getElementsByName('_chk_dtn[]');
  for(var i = 0, n = checkboxes.length; i < n; i++)
  {
	checkboxes[i].checked = source.checked;
	//var qty = "txtQtyDI"+(i+1);
	//var weight = "txtWeightDI"+(i+1);
	//document.getElementById(qty).disabled = !source.checked;
	//document.getElementById(weight).disabled = !source.checked;
  }
}

function confirmDTN()
{
	//check No data available in table
	if($("#hdn_row_waiting_conf_dtn").val() == 0)
	{
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
	}
	else
	{
		if($("#txt_curr_dtn_no").val() == "")
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Generate Delivery Transfer Note</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$('#btn_gen_dtn_code').focus();
			}, 3000);
			
			return false ;
		}
		else if($("#txt_dtn_delivery_date").val() == "")
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please input Delivery Date</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$('#txt_dtn_delivery_date').focus();
			}, 3000);
			
			return false ;
		}
		else if($("#txt_dtn_delivery_time").val() == "")
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please input Delivery Time</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$('#txt_dtn_delivery_time').focus();
			}, 3000);
			
			return false ;
		}
		else if($("#txt_dtn_curr_scan_driver_iden_card").val() == "")
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please scan Driver Identification Card</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$('#txt_dtn_scan_driver_iden_card').focus();
			}, 3000);
			
			return false ;
		}
		else
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>Confirm create <b>New Delivery Transfer Note</b></span>",
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
				
				var cbChecked = $("input[name='_chk_dtn[]']:checked").length;
				
				//conf del
				var tmp = 0;
				$("input[name='_chk_dtn[]']:checked").each(function ()
				{
				
					tmp = tmp + 1;
					
					var iden_hdn_ps_h_picking_code = "#hdn_ps_h_picking_code"+$(this).val();
					var iden_hdn_ps_h_picking_code = $(iden_hdn_ps_h_picking_code).val();
					
					$.ajax({
					  type: 'POST',
					  url: '<?=$CFG->src_dtn_order;?>/create_dtn_sheet.php',
					  data: { 
								iden_hdn_ps_h_picking_code: iden_hdn_ps_h_picking_code
								,iden_txt_curr_dtn_no: $("#txt_curr_dtn_no").val()
								,iden_txt_dtn_delivery_date: $("#txt_dtn_delivery_date").val()
								,iden_txt_dtn_delivery_time: $("#txt_dtn_delivery_time").val()
								,iden_txt_dtn_curr_scan_driver_iden_card: $("#txt_dtn_curr_scan_driver_iden_card").val()
								,tmp_sel: tmp
							},
							success: function(response){
							console.log('data: '+ response);
						  },
						error: function(){
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
				if(tmp == 0)
				{
					//dialog ctrl
					$("#modal-default").modal("show");
					$("#al_results").html("[C001] --- Please select at least 1 item !!!");
					
					//hide
					setTimeout(function(){
						$("#modal-default").modal("hide");
					}, 3000);
				}
				else
				{
					//refresh
					if(cbChecked == tmp)
					{
						openRePrintDTNSheet($("#hdn_curr_dtn_no").val());
						_load_waiting_conf_dtn();
						_load_dtn_sheet_details();
						_clear_step_after_conf_dtn();
					}
				}
			  }
			});
		}
	}
}

function openRePrintDTNSheet(id)
{
	setTimeout(function(){
		window.open("<?=$CFG->src_mPDF;?>/print_dtn_sheet_b2c?tag="+ id +"","_blank");
	},500);
}

function openRePrintDTNSheetShotFrom(id)
{
	setTimeout(function(){
		window.open("<?=$CFG->src_mPDF;?>/print_dtn_shotfrom_b2c?tag="+ id +"","_blank");
	},500);
}

function openFuncDTNSheetDetails(id)
{
	$('#modal-DTNDetails').modal('show');
	_load_dtn_details(id);
}

function _load_dtn_details(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_dn_h_dtn_code = str_split_result[0];
	var t_dn_h_cus_code = str_split_result[1];
	var t_dn_h_cus_name = str_split_result[2];
	var t_ps_t_pj_name = str_split_result[3];	
	var t_dn_h_status = str_split_result[4];
	var t_dn_h_delivery_date = str_split_result[5];
	
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_dtn_details").html(""); //clear span
		$("#spn_load_dtn_details").load("<?=$CFG->src_dtn_order;?>/load_dtn_sheet_details_popup.php", { t_dn_h_dtn_code: t_dn_h_dtn_code, t_dn_h_cus_code: t_dn_h_cus_code, t_dn_h_cus_name: t_dn_h_cus_name, t_ps_t_pj_name: t_ps_t_pj_name, t_dn_h_status: t_dn_h_status, t_dn_h_delivery_date: t_dn_h_delivery_date });
	},300);
}
function exportExcelB2C(id){
	setTimeout(function(){
		//href
		window.open("<?=$CFG->src_dtn_order;?>/excel_export_order_dtn_b2c?dtn_code="+ id +"","_blank");
	},500);
}
</script>
</body>
</html>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------dlg DTNDetails-------------------->
<div class="modal fade" id="modal-DTNDetails" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-search"></i> Confirm Order Details</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body table-responsive padding">
		  <span id="spn_load_dtn_details"></span>
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