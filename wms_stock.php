<?
require_once("application.php");
require_once("get_authorized.php");
require_once("js_css_header.php");

$str_usr_allow = array('chitnarong','nuchanart','marisa');

//check user
$str_chk_usr = $objResult_authorized['user_code'];

//user list allow
$array  = $str_usr_allow;
$str_chkAllowUser = strpos_var($str_chk_usr, $array); // will return true
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
      <h1><i class="fa fa-caret-right"></i>&nbsp;WMS Stock<small>Storage Location</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">WMS Stock</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-cubes"></i> Pallet Tags List</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-primary btn-sm" onclick="_load_wms_stock();"><i class="fa fa-bar-chart fa-lg"></i> WMS Stock</button>&nbsp;<button type="button" class="btn btn-success btn-sm" onclick="_load_put_away_tags_on_pallet();"><i class="fa fa-plus fa-lg"></i> Put-Away Tags On Pallet</button>&nbsp;<button type="button" class="btn btn-warning btn-sm" onclick="_load_move_pallet();"><i class="fa fa-location-arrow fa-lg"></i> Move Pallet</button>&nbsp;<button type="button" class="btn btn-warning btn-sm" onclick="_load_move_tags();"><i class="fa fa-location-arrow fa-lg"></i> Move Tags</button><? if($str_chkAllowUser == true){ ?>&nbsp;<button type="button" class="btn btn-default btn-sm bg-maroon" onclick="_load_adjust_inventory();"><i class="fa fa-compress fa-lg"></i> Adjust Inventory</button><? } ?><!--&nbsp;<button type="button" class="btn btn-danger btn-sm" onclick="_load_del_tags_on_pallet();"><i class="fa fa-trash fa-lg"></i> Delete Tags ID on Pellet</button>-->&nbsp;/&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="_load_pallet_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
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
$(document).ready(function()
{
	//toUpperCase
	//$('#txt_scn_put_tag_id').keyup(function() { this.value = this.value.toUpperCase(); });
	//$('#txt_scn_put_pallet').keyup(function() { this.value = this.value.toUpperCase(); });

	//load pallet no
	_load_pallet_details();
	
});

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

function _load_pallet_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_data_main").html(""); //clear span
		$("#spn_load_data_main").load("<?=$CFG->src_put_away;?>/load_pallet_stock.php");
	},300);
}

function openRePrintIndividual(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?tag="+ id +"","_blank");
}

function openRePrintPalletID(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_pallet_tags?tag="+ id +"","_blank");
}

function openRePrintAllTagsOnPalletID(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_all_tags_on_pallet?tag="+ id +"","_blank");
}

function openRefill(id)
{
	$('#modal-refill-putaway').modal('show');
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

function _load_wms_stock()
{
	_load_pallet_details();
}

function _load_move_pallet()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_data_main").html(""); //clear span
		$("#spn_load_data_main").load("<?=$CFG->src_put_away;?>/load_move_pallet.php");
	},300);
}

function _load_move_tags()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_data_main").html(""); //clear span
		$("#spn_load_data_main").load("<?=$CFG->src_put_away;?>/load_move_tags.php");
	},300);
}

function _load_put_away_tags_on_pallet()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_data_main").html(""); //clear span
		$("#spn_load_data_main").load("<?=$CFG->src_put_away;?>/load_put_away_tags_on_pallet.php");
	},300);
}

function _load_adjust_inventory()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_data_main").html(""); //clear span
		$("#spn_load_data_main").load("<?=$CFG->src_put_away;?>/load_adjust_stock.php");
	},300);
}

function _onScan_PalletID()
{
	if($("#txt_move_scn_pallet_id").val() != "")
	{
		if(isEnglishchar($("#txt_move_scn_pallet_id").val())==false)
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
				$("#txt_move_scn_pallet_id").val('');
				$("#txt_move_scn_pallet_id").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			//insert to temp pre-receive
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_put_away;?>/insert_pre_move_pallet.php',
			  data: { 
						iden_txt_move_scn_pallet_id: $("#txt_move_scn_pallet_id").val()
					},
					success: function(response){
					
					//check alert
					if(response == "duplicate")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Pallet ID: <b>"+ $("#txt_move_scn_pallet_id").val() +"</b> is already exists !</span>",
						  type: "warning",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_pallet_id").val('');
							$("#txt_move_scn_pallet_id").focus();
						}, 500);
					}
					else if(response == "not match")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Pallet ID: <b>"+ $("#txt_move_scn_pallet_id").val() +"</b> pattern not match ! <br> This Pallet ID is not data available in Master Pallet ?</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_pallet_id").val('');
							$("#txt_move_scn_pallet_id").focus();
						}, 500);
					}
					else
					{	
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_pallet_id").val('');
							$("#txt_move_scn_pallet_id").focus();
						}, 500);
				
						//refresh
						_load_move_pallet();
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

function _onScan_PalletID_newLocation()
{
	if($("#txt_move_scn_pallet_location").val() != "")
	{
		if(isEnglishchar($("#txt_move_scn_pallet_location").val())==false)
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
				$("#txt_move_scn_pallet_location").val('');
				$("#txt_move_scn_pallet_location").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			if($("#hdn_row_pallet").val() == 0)
			{
				//dialog ctrl
				swal({
				  html: true,
				  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
				  text: "<span style='font-size: 15px; color: #000;'>[C001] --- No data available in table</span>",
				  type: "warning",
				  timer: 2000,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//hide
				setTimeout(function(){
					$("#txt_move_scn_pallet_location").val('');
					$("#txt_move_scn_pallet_location").focus();
				}, 2500);
				
				return false;
			}
			else
			{
				//alert
				swal({
				  html: true,
				  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
				  text: "<span style='font-size: 15px; color: #000;'>Confirm move location to: <b>"+ $("#txt_move_scn_pallet_location").val() +"</b> </span>",
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
					
					var cbChecked = $("input[name='_chk_pre_pallet[]']").length;
					
					//conf del
					var tmp = 0;
					$("input[name='_chk_pre_pallet[]']").each(function ()
					{
						//count for alert not select item
						tmp = tmp + 1;
						
						var iden_hdn_pre_pallet_id = "#hdn_pre_pallet_id"+$(this).val();
						var iden_hdn_pre_pallet_id = $(iden_hdn_pre_pallet_id).val();
						
						//remove
						$.ajax({
						  type: 'POST',
						  url: '<?=$CFG->src_put_away;?>/update_move_pallet.php',
						  data: { 
									iden_hdn_pre_pallet_id: iden_hdn_pre_pallet_id
									,iden_txt_move_scn_pallet_location: $("#txt_move_scn_pallet_location").val()
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
						//refresh
						_load_move_pallet();
					}
					
				  }
				});
			}
		}
	}
}

function _remove_pre_move_pallet()
{
	//check No data available in table
	if($("#hdn_row_pallet").val() == 0)
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
		  title: "<span style='font-size: 17px;'>[C003] --- Do you want to delete all item in table ?</span>",
		  text: "<span style='font-size: 15px;'>You can scan Pallet ID. again !</span>",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		},
		function(isConfirm) {
		  if (isConfirm) {
			
			var cbChecked = $("input[name='_chk_pre_pallet[]']").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_pallet[]']").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_pallet_id = "#hdn_pre_pallet_id"+$(this).val();
				var iden_hdn_pre_pallet_id = $(iden_hdn_pre_pallet_id).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_put_away;?>/remove_pre_move_pallet.php',
				  data: { 
							iden_hdn_pre_pallet_id: iden_hdn_pre_pallet_id
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
					_load_move_pallet();
				}
			}
		  }
		});
	}
}

function _onScan_TagsID()
{
	if($("#txt_move_scn_tag_id").val() != "")
	{
		if(isEnglishchar($("#txt_move_scn_tag_id").val())==false)
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
				$("#txt_move_scn_tag_id").val('');
				$("#txt_move_scn_tag_id").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			//insert to temp pre-receive
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_put_away;?>/insert_pre_move_tags.php',
			  data: { 
						iden_txt_move_scn_tag_id: $("#txt_move_scn_tag_id").val()
					},
					success: function(response){
					
					//check alert
					if(response == "duplicate")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_move_scn_tag_id").val() +"</b> is already exists !</span>",
						  type: "warning",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_tag_id").val('');
							$("#txt_move_scn_tag_id").focus();
						}, 500);
					}
					else if(response == "not match")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_move_scn_tag_id").val() +"</b> pattern not match ! <br> This Tags ID is not data available in Master Tags ?</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_tag_id").val('');
							$("#txt_move_scn_tag_id").focus();
						}, 500);
					}
					else
					{	
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_tag_id").val('');
							$("#txt_move_scn_tag_id").focus();
						}, 500);
				
						//refresh
						_load_move_tags();
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

function _onScan_TagsID_newPallet()
{
	if($("#txt_move_scn_new_pallet").val() != "")
	{
		if(isEnglishchar($("#txt_move_scn_new_pallet").val())==false)
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
				$("#txt_move_scn_new_pallet").val('');
				$("#txt_move_scn_new_pallet").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			if($("#hdn_row_tags").val() == 0)
			{
				//dialog ctrl
				swal({
				  html: true,
				  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
				  text: "<span style='font-size: 15px; color: #000;'>[C001] --- No data available in table</span>",
				  type: "warning",
				  timer: 2000,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//hide
				setTimeout(function(){
					$("#txt_move_scn_new_pallet").val('');
					$("#txt_move_scn_new_pallet").focus();
				}, 2500);
				
				return false;
			}
			else
			{
				//alert
				swal({
				  html: true,
				  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
				  text: "<span style='font-size: 15px; color: #000;'>Confirm move Tag to Pallet: <b>"+ $("#txt_move_scn_new_pallet").val() +"</b> </span>",
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
					
					var cbChecked = $("input[name='_chk_pre_tags[]']").length;
					
					//conf del
					var tmp = 0;
					$("input[name='_chk_pre_tags[]']").each(function ()
					{
						//count for alert not select item
						tmp = tmp + 1;
						
						var iden_hdn_pre_tags_id = "#hdn_pre_tags_id"+$(this).val();
						var iden_hdn_pre_tags_id = $(iden_hdn_pre_tags_id).val();
						
						//remove
						$.ajax({
						  type: 'POST',
						  url: '<?=$CFG->src_put_away;?>/update_move_tags.php',
						  data: { 
									iden_hdn_pre_tags_id: iden_hdn_pre_tags_id
									,iden_txt_move_scn_new_pallet: $("#txt_move_scn_new_pallet").val()
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
						//refresh
						_load_move_tags();
					}
					
				  }
				});
			}
		}
	}
}

function _remove_pre_move_tags()
{
	//check No data available in table
	if($("#hdn_row_tags").val() == 0)
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
		  title: "<span style='font-size: 17px;'>[C003] --- Do you want to delete all item in table ?</span>",
		  text: "<span style='font-size: 15px;'>You can scan Tags ID. again !</span>",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		},
		function(isConfirm) {
		  if (isConfirm) {
			
			var cbChecked = $("input[name='_chk_pre_tags[]']").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_tags[]']").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_tags_id = "#hdn_pre_tags_id"+$(this).val();
				var iden_hdn_pre_tags_id = $(iden_hdn_pre_tags_id).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_put_away;?>/remove_pre_move_tags.php',
				  data: { 
							iden_hdn_pre_tags_id: iden_hdn_pre_tags_id
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
					_load_move_tags();
				}
			}
		  }
		});
	}
}

function _onScan_put_TagsID()
{
	if($("#txt_scn_put_tag_id").val() != "")
	{
		if(isEnglishchar($("#txt_scn_put_tag_id").val())==false)
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
				$("#txt_scn_put_tag_id").val('');
				$("#txt_scn_put_tag_id").focus();
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
						iden_txt_scan_master_tags: $("#txt_scn_put_tag_id").val()
					},
					success: function(response){
					
					//check alert
					if(response == "duplicate")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tag <b>"+ $("#txt_scn_put_tag_id").val() +"</b> is already exists !</span>",
						  type: "warning",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_scn_put_tag_id").val('');
							$("#txt_scn_put_tag_id").focus();
						}, 500);
					}
					else if(response == "duplicate receive")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tag <b>"+ $("#txt_scn_put_tag_id").val() +"</b> is already exists and available in stock !</span>",
						  type: "warning",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_scn_put_tag_id").val('');
							$("#txt_scn_put_tag_id").focus();
						}, 500);
					}
					else if(response == "not match")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tag <b>"+ $("#txt_scn_put_tag_id").val() +"</b> pattern not match ! <br> This tag is not data available in Master Tags ?</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_scn_put_tag_id").val('');
							$("#txt_scn_put_tag_id").focus();
						}, 500);
					}
					else
					{	
						//clear step
						setTimeout(function(){
							$("#txt_scn_put_tag_id").val('');
							$("#txt_scn_put_tag_id").focus();
						}, 500);
				
						//refresh
						_load_put_away_tags_on_pallet();
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

function _onScan_TagsID_put_Pallet()
{
	//check No data available in table
	if($("#hdn_row_put_tags").val() == 0)
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
		if($("#txt_scn_put_pallet").val() == "")
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
				$('#txt_scn_put_pallet').focus();
			}, 3000);
			
			return false ;
		}
		else
		{
			if(isEnglishchar($("#txt_scn_put_pallet").val())==false)
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
					$("#txt_scn_put_pallet").val('');
					$("#txt_scn_put_pallet").focus();
				}, 2500);
				
				return false;
			}
			else
			{
				var cbChecked = $("input[name='_chk_pre_put_tags[]']").length;
				
				swal({
				  html: true,
				  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
				  text: "<span style='font-size: 15px; color: #000;'>Confirm Put-Away <br>Pallet ID/Tag Qty: <b>"+ $("#txt_scn_put_pallet").val() +"/"+ cbChecked +"</b> </span>",
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
					$("input[name='_chk_pre_put_tags[]']").each(function ()
					{
						//count for alert not select item
						tmp = tmp + 1;
						
						var iden_hdn_pre_put_tags_id = "#hdn_pre_put_tags_id"+$(this).val();
						var iden_hdn_pre_put_tags_id = $(iden_hdn_pre_put_tags_id).val();
						
						$.ajax({
						  type: 'POST',
						  url: '<?=$CFG->src_put_away;?>/confirm_putaway_on_pallet.php',
						  data: { 
									iden_hdn_pre_put_tags_id: iden_hdn_pre_put_tags_id
									,iden_txt_scn_put_pallet: $("#txt_scn_put_pallet").val()
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
						_load_put_away_tags_on_pallet();
					}
				  }
				});
			}
		}
	}
}

function _remove_pre_puaway_tags()
{
	//check No data available in table
	if($("#hdn_row_put_tags").val() == 0)
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
		  title: "<span style='font-size: 17px;'>[C003] --- Do you want to delete all item in table ?</span>",
		  text: "<span style='font-size: 15px;'>You can scan Tags ID. again !</span>",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		},
		function(isConfirm) {
		  if (isConfirm) {
			
			var cbChecked = $("input[name='_chk_pre_put_tags[]']").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_put_tags[]']").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_put_tags_id = "#hdn_pre_put_tags_id"+$(this).val();
				var iden_hdn_pre_put_tags_id = $(iden_hdn_pre_put_tags_id).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_put_away;?>/remove_all_tags_selected.php',
				  data: { 
							iden_hdn_pre_receive_id: iden_hdn_pre_put_tags_id
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
					_load_put_away_tags_on_pallet();
				}
			}
		  }
		});
	}
}

function _onScan_TagsID_adjustStock()
{
	if($("#txt_move_scn_tag_id_adjustStock").val() != "")
	{
		if(isEnglishchar($("#txt_move_scn_tag_id_adjustStock").val())==false)
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
				$("#txt_move_scn_tag_id_adjustStock").val('');
				$("#txt_move_scn_tag_id_adjustStock").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			//insert to temp
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_put_away;?>/insert_pre_adj_inventory.php',
			  data: { 
						iden_txt_move_scn_tag_id_adjustStock: $("#txt_move_scn_tag_id_adjustStock").val()
					},
					success: function(response){
					
					//check alert
					if(response == "duplicate")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_move_scn_tag_id_adjustStock").val() +"</b> is already exists !</span>",
						  type: "warning",
						  timer: 1000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_tag_id_adjustStock").val('');
							$("#txt_move_scn_tag_id_adjustStock").focus();
						}, 500);
					}
					else if(response == "not match")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_move_scn_tag_id_adjustStock").val() +"</b> pattern not match ! <br> This Tags ID is not data available in Master Tags ?</span>",
						  type: "error",
						  timer: 1000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_tag_id_adjustStock").val('');
							$("#txt_move_scn_tag_id_adjustStock").focus();
						}, 500);
					}
					else if(response == "not receive")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_move_scn_tag_id_adjustStock").val() +"</b> not receive to inventory !</span>",
						  type: "error",
						  timer: 1000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_tag_id_adjustStock").val('');
							$("#txt_move_scn_tag_id_adjustStock").focus();
						}, 500);
					}
					else
					{	
						//clear step
						setTimeout(function(){
							$("#txt_move_scn_tag_id_adjustStock").val('');
							$("#txt_move_scn_tag_id_adjustStock").focus();
						}, 500);
				
						//refresh
						_load_adjust_inventory();
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

function _remove_adjust_stock()
{
	//check No data available in table
	if($("#hdn_row_adj_stock").val() == 0)
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
		  title: "<span style='font-size: 17px;'>[C003] --- Do you want to delete all item in table ?</span>",
		  text: "<span style='font-size: 15px;'>You can scan Tags ID. again !</span>",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		},
		function(isConfirm) {
		  if (isConfirm) {
			
			var cbChecked = $("input[name='_chk_pre_adj_stock_tags[]']").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_adj_stock_tags[]']").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_adj_stock_tags_id = "#hdn_pre_adj_stock_tags_id"+$(this).val();
				var iden_hdn_pre_adj_stock_tags_id = $(iden_hdn_pre_adj_stock_tags_id).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_put_away;?>/remove_pre_adj_stock_tags.php',
				  data: { 
							iden_hdn_pre_adj_stock_tags_id: iden_hdn_pre_adj_stock_tags_id
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
					_load_adjust_inventory();
				}
			}
		  }
		});
	}
}

function _confirm_adjust_stock()
{
	if($("#hdn_row_adj_stock").val() == 0)
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- No data available in table</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		return false;
	}
	else
	{
		//alert
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>Do you want to confirm adjust stock all item in table ? </span>",
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
			
			var cbChecked = $("input[name='_chk_pre_adj_stock_tags[]']").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_adj_stock_tags[]']").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_adj_stock_tags_id = "#hdn_pre_adj_stock_tags_id"+$(this).val();
				var iden_hdn_pre_adj_stock_tags_id = $(iden_hdn_pre_adj_stock_tags_id).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_put_away;?>/update_adjust_stock.php',
				  data: { 
							iden_hdn_pre_adj_stock_tags_id: iden_hdn_pre_adj_stock_tags_id
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
				//refresh
				_load_adjust_inventory();
			}
			
		  }
		});
	}
}

function _export_stock_by_pallet()
{
	//href
	window.open('<?=$CFG->src_put_away;?>/excel_stock_by_pallet','_blank');
}

function _export_stock_by_tags()
{
	//href
	window.open('<?=$CFG->src_put_away;?>/excel_stock_by_tags','_blank');
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

