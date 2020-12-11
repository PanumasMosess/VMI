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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Picking Quality Control</h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Picking Quality Control</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-qrcode"></i> Scan Picking Sheet ID</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<div class="row">
							<div class="col-md-6">
							  <div class="form-group">
								<label><img src="<?=$CFG->iconsdir;?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> Picking Sheet ID:</label>
								<input type="password" id="txt_scn_picking_id" name="txt_scn_picking_id" onKeyPress="if (event.keyCode==13){ return _onScan_pickingID(); }" class="form-control input-sm" placeholder="Scan Picking Sheet ID" autocomplete="off" autocorrect="off" spellcheck="false">
							  </div>
							  <!-- /.form-group -->
							</div>
							<!-- /.col -->
						  
							<div class="col-md-6">
							  <div class="form-group">
								<label>Cover Sheet:</label>
								<input type="text" id="txt_cover_id" name="txt_cover_id" class="form-control input-sm" placeholder="Enter Cover Sheet" autocomplete="off" autocorrect="off" spellcheck="false" disabled>
							  </div>
							  <!-- /.form-group -->
							</div>
							<!-- /.col -->
						</div>
					</div>
				</div>
				
				<div class="box box-warning">
					<!--<div style="height: 590px; overflow:auto; alignment-adjust:central;">-->
						<span id="spn_load_picking_sheet_details"></span>
					<!--</div>-->
				</div>
				
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
	$('#txt_scn_picking_id').keyup(function() { this.value = this.value.toUpperCase(); });
	$('#txt_cover_id').keyup(function() { this.value = this.value.toUpperCase(); });

	//load picking sheet qc
	_load_picking_sheet_qc();
	$("#txt_scn_picking_id").focus();
	
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

function _load_picking_sheet_qc()
{
	//load dtn
	setTimeout(function(){
		//$("#spn_load_picking_sheet_details").html(""); //clear span
		$("#spn_load_picking_sheet_details").load("<?=$CFG->src_picking_order;?>/load_picking_sheet_qc.php", { t_txt_scn_picking_id: $('#txt_scn_picking_id').val() });
	},300);
}

function _clear_begin()
{
	//clear
	$("#txt_scn_picking_id").val("");
	$("#txt_scn_picking_id").focus();
	
	//disabled
	$('#txt_cover_id').prop('disabled',true);
	$("#txt_cover_id").val("");
	
	_load_picking_sheet_qc();
}

function _onScan_pickingID()
{
	if($("#txt_scn_picking_id").val() != "")
	{
		if(isEnglishchar($("#txt_scn_picking_id").val())==false)
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please change to english language.</span>",
			  type: "warning",
			  timer: 2500,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$("#txt_scn_picking_id").val('');
				$("#txt_scn_picking_id").focus();
			}, 2900);
			
			return false;
		}
		else
		{
			//check driver on table
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_picking_order;?>/load_check_picking_sheet.php',
			  data: { 
						t_txt_scn_picking_id: $("#txt_scn_picking_id").val()
					},
					success: function(response){
				
					if(response == "OK")
					{
						//clear
						//disabled
						$('#txt_cover_id').prop('disabled',false);
						//clear
						$("#txt_cover_id").val("COVER_01");
						
						_load_picking_sheet_qc();
					}
					else if(response == "NG")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C002] --- Picking Sheet ID not matching on database</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						$("#txt_scn_picking_id").val('');
						$("#txt_scn_picking_id").focus();
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

function _onScan_CheckTags()
{
	if($("#txt_qc_scn_tag_id").val() != "")
	{
		if(isEnglishchar($("#txt_qc_scn_tag_id").val())==false)
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please change to english language.</span>",
			  type: "warning",
			  timer: 2500,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$("#txt_qc_scn_tag_id").val('');
				$("#txt_qc_scn_tag_id").focus();
			}, 2900);
			
			return false;
		}
		else
		{
			//post check data
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_picking_order;?>/validate_picking_qc.php',
			  data: { 
						iden_txt_scn_picking_id: $("#txt_scn_picking_id").val()
						,iden_txt_cover_id: $("#txt_cover_id").val()
						,iden_txt_qc_scn_tag_id: $("#txt_qc_scn_tag_id").val()
					},
					success: function(response){
				
					if(response == "OK")
					{
						_load_picking_sheet_qc();
					}
					else if(response == "NG")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C002] --- Incorrect data, <br>TAGS ID. Does not match !!!</span>",
						  type: "error",
						  timer: 4000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						_load_picking_sheet_qc();

						//clear
						$('#txt_qc_scn_tag_id').val('');
						$('#txt_qc_scn_tag_id').focus();
					}
					else if(response == "DUL")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 15px; color: #000;'>[C002] --- Duplicate TAGS ID. !!!</span>",
						  type: "error",
						  timer: 4000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						_load_picking_sheet_qc();

						//clear
						$('#txt_qc_scn_tag_id').val('');
						$('#txt_qc_scn_tag_id').focus();
					}
					
				  },
				error: function(){
					//dialog ctrl
					swal({
					  html: true,
					  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					  text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
					  type: "warning",
					  timer: 4000,
					  showConfirmButton: false,
					  allowOutsideClick: false
					});
				}
			});

		}
	}
}

function _remove_pre_picking_qc()
{
	//check No data available in table
	if($("#hdn_row_PickingQCSheetDetails").val() == 0)
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
		
		return false;
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
			
			var cbChecked = $("input[name='_chk_pre_picking_qc[]']").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_picking_qc[]']").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_picking_tags_id = "#hdn_pre_picking_tags_id"+$(this).val();
				var iden_hdn_pre_picking_tags_id = $(iden_hdn_pre_picking_tags_id).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_picking_order;?>/remove_pre_picking_qc.php',
				  data: { 
							iden_hdn_pre_picking_tags_id: iden_hdn_pre_picking_tags_id
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
				_clear_begin();
			}
			
		  }
		});
	}	
}

function  _confirm_pre_picking_qc()
{
	//check No data available in table
	if($("#hdn_row_PickingQCSheetDetails").val() == 0)
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
		
		return false;
	}
	else if(parseInt($("#hdn_row_PickingQCSheetDetails").val()) != parseInt($("#hdn_row_ChkCompleteScan").val()))
	{
		//call
		var row_check = parseInt($("#hdn_row_PickingQCSheetDetails").val());
		var scan_check = parseInt($("#hdn_row_ChkCompleteScan").val());
		var str_minus = row_check - scan_check;
		
		//Scan is not finished, missing 2 items
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C003] --- Scan is not finished, missing "+str_minus+" items</span>",
		  type: "error",
		  timer: 2500,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		setTimeout(function(){
			$("#txt_qc_scn_tag_id").val('');
			$("#txt_qc_scn_tag_id").focus();
		}, 2900);
		
		return false;
	}
	else 
	{
		//alert
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>Confirm Picking Quality Control <br>Picking Sheet ID: <b>"+ $("#txt_scn_picking_id").val() +"</b> </span>",
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
			
			var cbChecked = $("input[name='_chk_pre_picking_qc[]']").length;
			
			//conf del
			var tmp = 0;
			$("input[name='_chk_pre_picking_qc[]']").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_pre_picking_tags_id = "#hdn_pre_picking_tags_id"+$(this).val();
				var iden_hdn_pre_picking_tags_id = $(iden_hdn_pre_picking_tags_id).val();
				
				var iden_hdn_pre_picking_iden_rows = "#hdn_pre_picking_iden_rows"+$(this).val();
				var iden_hdn_pre_picking_iden_rows = $(iden_hdn_pre_picking_iden_rows).val();
				
				//remove
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_picking_order;?>/confirm_picking_qc.php',
				  data: { 
							iden_hdn_pre_picking_tags_id: iden_hdn_pre_picking_tags_id
							,iden_hdn_pre_picking_iden_rows: iden_hdn_pre_picking_iden_rows
							,hdn_row_PickingQCSheetDetails: $("#hdn_row_PickingQCSheetDetails").val()
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
				//print cover sheet
				
				
				//refresh
				_clear_begin();
			}
			
		  }
		});
	}
}
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

