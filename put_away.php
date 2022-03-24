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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Put-Away (Receive)<small>Storage Location</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Put-Away (Receive)</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
	  <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-qrcode"></i> Generate Pallet Tags</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Next Pallet Tags ID:<span id="spn_load_pallet_no"></span></label>
                <input type="text" id="txt_pallet_running" name="txt_pallet_running" class="form-control input-sm" placeholder="Auto load Pallet Tag" disabled>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
		  
        </div>
        <!-- /.box-body -->
		<div class="box-footer">
			<button id="btn_gen_pallet_tags" type="button" class="btn btn-primary btn-sm" onclick="gen_pallet_tags()"><i class="fa fa-qrcode"></i> Generate Pallet Tags</button>
		</div>
      </div>
      <!-- /.box -->

	  <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-cart-arrow-down"></i> Receiving & Put-Away</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Current Pallet Tags ID:<span id="spn_load_curr_pallet_no"></span><span id="spn_load_encode_curr_pallet_no"></span></label>
                <input type="text" id="txt_curr_pallet_no" name="txt_curr_pallet_no" class="form-control input-sm" placeholder="Auto Receiving Pallet Tags ID" disabled>
				<input type="hidden" id="hdn_curr_pallet_no" name="hdn_curr_pallet_no">
              </div>
              <!-- /.form-group -->
			</div>
          </div>
          <!-- /.row -->
		  
		  <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label><img src="<?=$CFG->iconsdir;?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> Tags Lot:<span id="spn_load_pallet_no"></span></label>
                <input type="text" id="txt_scan_master_tags" name="txt_scan_master_tags" onKeyPress="if (event.keyCode==13){ return _onScan_barcode(); }" class="form-control input-sm" placeholder="Scan Master Tags" disabled>
              </div>
              <!-- /.form-group -->
			</div>
			
			<div class="col-md-6">
			  <div class="form-group">
                <label><i class="fa fa-map-marker"></i> Rack / Shelf / Location:<span id="spn_load_pallet_no"></span></label>
                <input type="text" id="txt_curr_pallet_location" name="txt_curr_pallet_location" onKeyPress="if (event.keyCode==13){ return _onScan_location(); }" class="form-control input-sm" placeholder="Enter Rack / Shelf / Location" disabled>
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
		  
			<div class="box-header with-border">
			  <h3 class="box-title"><i class="fa fa-qrcode"></i> Master Tags Details</h3>
			</div>
			<!-- /.box-header -->
				<div class="box-header">
					<button type="button" class="btn btn-success btn-sm" onclick="confirmReceivingPutaway();"><i class="fa fa-check-square-o"></i> Confirm Put-Away</button>&nbsp;|&nbsp;<button type="button" class="btn btn-danger btn-sm" onclick="delTagsCodeSelected();"><i class="fa fa-trash-o"></i> Delete</button>&nbsp;/&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="_load_scan_master_tags_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
				</div>
				<div style="padding-left: 8px;">
					<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
				</div>
				<!-- /.box-header -->
				<span id="spn_load_scan_master_tags_details"></span>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
	
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-cubes"></i> Pallet Tags List (Today)</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-info btn-sm" onclick="_load_pallet_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
					</div>
					<!-- /.box-header -->
					<span id="spn_load_pallet_details"></span>
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
	$('#txt_scan_master_tags').keyup(function() { this.value = this.value.toUpperCase(); });
	$('#txt_curr_pallet_location').keyup(function() { this.value = this.value.toUpperCase(); });

	//load pallet no
	_load_pallet_tags();
	_load_scan_master_tags_details();
	_load_pallet_details();
	
});

//_onScan_barcode
function _onScan_barcode()
{
	if($("#txt_scan_master_tags").val() != "")
	{
		if(isEnglishchar($("#txt_scan_master_tags").val())==false)
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
				$("#txt_scan_master_tags").val('');
				$("#txt_scan_master_tags").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			//insert to temp pre-receive
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_put_away;?>/insert_pre_master_tags.php',
			  data: { 
						iden_txt_scan_master_tags: $("#txt_scan_master_tags").val()
					},
					success: function(response){
					
					//check alert
					if(response == "duplicate")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tag <b>"+ $("#txt_scan_master_tags").val() +"</b> is already exists !</span>",
						  type: "warning",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_scan_master_tags").val('');
							$("#txt_scan_master_tags").focus();
						}, 500);
					}
					else if(response == "duplicate receive")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tag <b>"+ $("#txt_scan_master_tags").val() +"</b> is already exists and available in stock !</span>",
						  type: "warning",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_scan_master_tags").val('');
							$("#txt_scan_master_tags").focus();
						}, 500);
					}
					else if(response == "not match")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tag <b>"+ $("#txt_scan_master_tags").val() +"</b> pattern not match ! <br> This tag is not data available in Master Tags ?</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_scan_master_tags").val('');
							$("#txt_scan_master_tags").focus();
						}, 500);
					}
					else
					{	
						//clear step
						setTimeout(function(){
							$("#txt_scan_master_tags").val('');
							$("#txt_scan_master_tags").focus();
						}, 500);
				
						//refresh
						_load_scan_master_tags_details();
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

function _onScan_location()
{
	if($("#txt_curr_pallet_location").val() != "")
	{
		if(isEnglishchar($("#txt_curr_pallet_location").val())==false)
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
				$("#txt_curr_pallet_location").val('');
				$("#txt_curr_pallet_location").focus();
			}, 2500);
			
			return false;
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

function _load_pallet_tags()
{
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_pallet_no").html(""); //clear span
		$("#spn_load_pallet_no").load("<?=$CFG->src_put_away;?>/generate_pallet.php");
	},300);
}

function _load_curr_pallet_tags()
{
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_curr_pallet_no").html(""); //clear span
		$("#spn_load_curr_pallet_no").load("<?=$CFG->src_put_away;?>/get_curr_pallet.php");
	},300);
}

function _load_hdn_encode_curr_pallet_no()
{
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_encode_curr_pallet_no").html(""); //clear span
		$("#spn_load_encode_curr_pallet_no").load("<?=$CFG->src_put_away;?>/get_encode_pallet.php", { curr_pallet_no: $("#txt_curr_pallet_no").val() });
	},300);
}

function _clear_step()
{
	//disabled
	$('#txt_curr_pallet_location').prop('disabled',false);
	$('#txt_scan_master_tags').prop('disabled',false);
	//clear
	$('#txt_curr_pallet_location').val('');
	$('#txt_scan_master_tags').val('');
	$('#txt_scan_master_tags').focus();
}

function _clear_step_after_putaway()
{
	//disabled
	$('#txt_curr_pallet_location').prop('disabled',true);
	$('#txt_scan_master_tags').prop('disabled',true);
	//clear
	$('#txt_curr_pallet_location').val('');
	$('#txt_scan_master_tags').val('');
	$('#txt_curr_pallet_no').val('');
	$('#hdn_curr_pallet_no').val('');
}

function gen_pallet_tags()
{
	//load pallet no last updated
	_load_pallet_tags();
	
	if($("#txt_pallet_running").val() != "")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>Confirm create Pallet ID: <b>"+ $("#txt_pallet_running").val() +"</b> </span>",
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
			  url: '<?=$CFG->src_put_away;?>/insert_pallet_tags.php',
			  data: { 
						iden_txt_pallet_running: $("#txt_pallet_running").val()
					},
					success: function(response){
				
					if(response == "new")
					{
						//load tags
						_load_pallet_tags();
						
						//clear step
						$('#txt_curr_pallet_no').val($('#txt_pallet_running').val());
						_load_hdn_encode_curr_pallet_no();
						_clear_step();
					}
					else if(response == "dup")
					{
						//load tags
						_load_pallet_tags();
						_load_curr_pallet_tags();
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

function _load_scan_master_tags_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_scan_master_tags_details").html(""); //clear span
		$("#spn_load_scan_master_tags_details").load("<?=$CFG->src_put_away;?>/load_scan_master_tags_details.php");
	},300);
}

function _load_pallet_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_pallet_details").html(""); //clear span
		$("#spn_load_pallet_details").load("<?=$CFG->src_put_away;?>/load_pallet_details.php");
	},300);
}

//check all
function toggle_pre_scan_tags(source)
{
  checkboxes = document.getElementsByName('_chk_pre_scan_tags[]');
  for(var i = 0, n = checkboxes.length; i < n; i++)
  {
	checkboxes[i].checked = source.checked;
	//var qty = "txtQtyDI"+(i+1);
	//var weight = "txtWeightDI"+(i+1);
	//document.getElementById(qty).disabled = !source.checked;
	//document.getElementById(weight).disabled = !source.checked;
  }
}

function openRePrintIndividual(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?tag="+ id +"","_blank");
}

function delTagsCodeSelected()
{
	//check No data available in table
	if($("#hdn_row_pre_scan_tags").val() == 0)
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
		swal({
		  html: true,
		  title: "<span style='font-size: 17px;'>[C003] --- Do you want to delete all selected ?</span>",
		  text: "<span style='font-size: 15px;'>You can scan Master Tags again !</span>",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		},
		function(isConfirm) {
		  if (isConfirm) {
			
			var cbChecked = $("input[name='_chk_pre_scan_tags[]']:checked").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_scan_tags[]']:checked").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_receive_id = "#hdn_pre_receive_id"+$(this).val();
				var iden_hdn_pre_receive_id = $(iden_hdn_pre_receive_id).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_put_away;?>/remove_all_tags_selected.php',
				  data: { 
							iden_hdn_pre_receive_id: iden_hdn_pre_receive_id
						},
						success: function(response){
						//
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
			
			////select at least 1 item
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
					_load_scan_master_tags_details();
				}
			}
		  }
		});
	}
}

function delTagsCode(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_pre_receive_id = str_split_result[0];
	var t_tags_code = str_split_result[1];
	
	swal({
	  html: true,
	  title: "<span style='font-size: 17px;'>[C003] --- Do you want to delete tag <b>"+ t_tags_code +"</b> ?</span>",
	  text: "<span style='font-size: 15px;'>You can scan Master Tags again !</span>",
	  type: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	},
	function(isConfirm) {
	  if (isConfirm) {
		  
		//conf del
		$.ajax({
		  type: 'POST',
		  url: '<?=$CFG->src_put_away;?>/remove_all_tags_selected.php',
		  data: { 
					iden_hdn_pre_receive_id: t_pre_receive_id
				},
				success: function(response){
				
				//refresh
				_load_scan_master_tags_details();
				
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

function confirmReceivingPutaway()
{
	//check No data available in table
	if($("#hdn_row_pre_scan_tags").val() == 0)
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
		if($("#txt_curr_pallet_no").val() == "")
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please Generate Pallet Tags</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$('#btn_gen_pallet_tags').focus();
			}, 3000);
			
			return false ;
		}
		else if($("#txt_curr_pallet_location").val() == "")
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Enter Rack / Shelf / Location</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$('#txt_curr_pallet_location').focus();
			}, 3000);
			
			return false ;
		}
		else
		{
			var cbChecked = $("input[name='_chk_pre_scan_tags[]']").length;
			
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>Confirm Put-Away <br>Location: <b>"+ $("#txt_curr_pallet_location").val() +"</b><br>Pallet ID/Tag Qty: <b>"+ $("#txt_curr_pallet_no").val() +"/"+ cbChecked +"</b> </span>",
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
				
				//put away
				var tmp = 0;
				$("input[name='_chk_pre_scan_tags[]']").each(function ()
				{
					//count for alert not select item
					tmp = tmp + 1;
					
					var iden_hdn_pre_receive_id = "#hdn_pre_receive_id"+$(this).val();
					var iden_hdn_pre_receive_id = $(iden_hdn_pre_receive_id).val();
					
					$.ajax({
					  type: 'POST',
					  url: '<?=$CFG->src_put_away;?>/confirm_putaway.php',
					  data: { 
								iden_hdn_pre_receive_id: iden_hdn_pre_receive_id
								,iden_txt_curr_pallet_no: $("#txt_curr_pallet_no").val()
								,iden_txt_curr_pallet_location: $("#txt_curr_pallet_location").val()
							},
							success: function(response){
							//
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
				
				//refresh
				if(cbChecked == tmp)
				{
					openRePrintPalletID($("#hdn_curr_pallet_no").val());
					_load_scan_master_tags_details();
					_load_pallet_details();
					_clear_step_after_putaway();
				}
			  }
			});	
		}
	}
}

function openRePrintPalletID(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var _id = str_split_result[0];
	var t_tags_fg = str_split_result[1];

	window.open("<?=$CFG->src_mPDF;?>/print_pallet_tags?tag="+ _id +"&fg="+ t_tags_fg +"","_blank");
}

function openRefill(id)
{
	//$('#modal-putaway').modal('show');
}

function openFuncDetails(id)
{
	$('#modal-pallet-details').modal('show');
	_open_pallet_details(id);
}

function _open_pallet_details(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_receive_pallet_code = str_split_result[0];
	var t_tags_fg_code_gdj = str_split_result[1];
	var t_receive_location = str_split_result[2];
	var t_receive_status = str_split_result[3];	
	var t_receive_date = str_split_result[4];
	
	//load pallet no
	setTimeout(function(){
		//$("#spn_load_open_pallet_details").html(""); //clear span
		$("#spn_load_open_pallet_details").load("<?=$CFG->src_put_away;?>/load_data_on_pallet_details.php", { t_receive_pallet_code: t_receive_pallet_code, t_tags_fg_code_gdj: t_tags_fg_code_gdj, t_receive_location: t_receive_location, t_receive_status: t_receive_status, t_receive_date: t_receive_date });
	},300);
}
</script>
<script type="text/javascript">
inactivityTimeout = false;
resetTimeout()
function onUserInactivity() {
    location.reload();
}
function resetTimeout() {
   clearTimeout(inactivityTimeout)
   inactivityTimeout = setTimeout(onUserInactivity, 1000 * 1800)
}
window.onmousemove = resetTimeout
</script>
</body>
</html>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------dlg putaway-------------------->
<!--$('#modal-putaway').modal('show');-->
<div class="modal fade" id="modal-putaway" data-keyboard="false" data-backdrop="static">
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

