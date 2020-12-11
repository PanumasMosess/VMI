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
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">BOM</li>
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
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
					</div>
					
					<!-- /.box-header -->
					<div class="box-body table-responsive padding">
					  <table id="tbl_bom_mst" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 13px;">
						  <th style="width: 30px;">No.</th>
						  <!--<th style="text-align: center;">Actions/Details</th>-->
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
						  <th>Part Customer</th>
						  <th>Cost/Pcs.</th>
						  <th>Price Sale/Pcs.</th>
						  <th>Issue By</th>
						  <th>Issue Datetime</th>
						</tr>
						</thead>
						<tbody>
					<?
					$strSql = " select * from tbl_bom_mst order by bom_issue_datetime desc ";
					
					$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
					$num_row = sqlsrv_num_rows($objQuery);

					$row_id = 0;
					while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
					{
						$row_id++;
						
						$bom_id = $objResult['bom_id'];
						$bom_fg_code_set_abt = $objResult['bom_fg_code_set_abt'];
						$bom_fg_sku_code_abt = $objResult['bom_fg_sku_code_abt'];
						$bom_fg_code_gdj = $objResult['bom_fg_code_gdj'];
						$bom_fg_desc = $objResult['bom_fg_desc'];
						$bom_cus_code = $objResult['bom_cus_code'];
						$bom_cus_name = $objResult['bom_cus_name'];
						$bom_pj_name = $objResult['bom_pj_name'];
						$bom_ctn_code_normal = $objResult['bom_ctn_code_normal'];
						$bom_snp = $objResult['bom_snp'];
						$bom_sku_code = $objResult['bom_sku_code'];
						$bom_ship_type = $objResult['bom_ship_type'];
						$bom_pckg_type = $objResult['bom_pckg_type'];
						$bom_dims_w = $objResult['bom_dims_w'];
						$bom_dims_l = $objResult['bom_dims_l'];
						$bom_dims_h = $objResult['bom_dims_h'];
						$bom_usage = $objResult['bom_usage'];
						$bom_space_paper = $objResult['bom_space_paper'];
						$bom_flute = $objResult['bom_flute'];
						$bom_packing = $objResult['bom_packing'];
						$bom_wms_min = $objResult['bom_wms_min'];
						$bom_wms_max = $objResult['bom_wms_max'];
						$bom_vmi_min = $objResult['bom_vmi_min'];
						$bom_vmi_max = $objResult['bom_vmi_max'];
						$bom_part_customer = $objResult['bom_part_customer'];
						$bom_cost_per_pcs = $objResult['bom_cost_per_pcs'];
						$bom_price_sale_per_pcs = $objResult['bom_price_sale_per_pcs'];
						$bom_status = $objResult['bom_status'];
						$bom_issue_by = $objResult['bom_issue_by'];
						$bom_issue_date = $objResult['bom_issue_date'];
						$bom_issue_time = $objResult['bom_issue_time'];
						$bom_issue_datetime = $objResult['bom_issue_datetime'];
					?>
						<tr style="font-size: 13px;">
						  <td><?=$row_id;?></td>
						  <!--<td align="center">
						  <? if($objResult_authorized['user_type'] == "Administrator") { ?>
						  <button type="button" class="btn btn-primary btn-sm" id="<?=$bom_id;?>" onclick="openFuncEditBom(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil-square-o fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="View" id="<?=$bom_id;?>" onclick="openFuncDetailsBom(this.id);"><i class="fa fa-search fa-lg"></i></button>
						  <? } else { ?>
						  <button type="button" class="btn btn-info btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="View" id="<?=$bom_id;?>" onclick="openFuncDetailsBom(this.id);"><i class="fa fa-search fa-lg"></i></button>
						  <? } ?>
						  </td>-->
						  <td><?=$bom_status;?></td>
						  <td><?=$bom_fg_code_set_abt;?></td>
						  <td><?=$bom_fg_sku_code_abt;?></td>
						  <td><?=$bom_fg_code_gdj;?></td>
						  <td><?=$bom_fg_desc;?></td>
						  <td><?=$bom_cus_code;?></td>
						  <td><?=$bom_cus_name;?></td>
						  <td><?=$bom_pj_name;?></td>
						  <td><?=$bom_ctn_code_normal;?></td>
						  <td><?=$bom_snp;?></td>
						  <td><?=$bom_sku_code;?></td>
						  <td><?=$bom_ship_type;?></td>
						  <td><?=$bom_pckg_type;?></td>
						  <td><?=$bom_dims_w;?></td>
						  <td><?=$bom_dims_l;?></td>
						  <td><?=$bom_dims_h;?></td>
						  <td><?=$bom_usage;?></td>
						  <td><?=$bom_space_paper;?></td>
						  <td><?=$bom_flute;?></td>
						  <td><?=$bom_packing;?></td>
						  <td><?=$bom_wms_min;?></td>
						  <td><?=$bom_wms_max;?></td>
						  <td><?=$bom_vmi_min;?></td>
						  <td><?=$bom_vmi_max;?></td>
						  <td><?=$bom_part_customer;?></td>
						  <td><?=$bom_cost_per_pcs;?></td>
						  <td><?=$bom_price_sale_per_pcs;?></td>
						  <td><?=$bom_issue_by;?></td>
						  <td><?=substr($bom_issue_datetime,0,19);?></td>
						</tr>
					<?
					}
					?>						
						</tbody>
					  </table>
					</div>
					<!-- /.box-body -->
					
					<!--alert no item-->
					<input type="hidden" name="hdn_row" id="hdn_row" value="<?=$row_id;?>" />
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
$(document).ready(function()
{
	//search
    /*$('#tbl_bom_mst').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_bom_mst').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			if(data[1] == "Active"){
				$(row).find('td:eq(1)').css('color', 'indigo');
			}
			else if(data[1] == "InActive"){
				$(row).find('td:eq(1)').css('color', 'red');
			}

			//cost/pcs
			if(data[26] == ".00"){
				$('td:eq(26)', row).html( '0' );
			}
			
			//price sale/pcs
			if(data[27] == ".00"){
				$('td:eq(27)', row).html( '0' );
			}			

		},
    });
	
	<!--Uppercase-->
	//$("#txt_line_vp_code").keyup(function(){ $(this).val( $(this).val().toUpperCase() ); });
	
	//clear step
	$("#progress_upload_bom").hide();
	$("#divresult_upload_bom").hide();
	
	//file type validation
	//Support file type .pdf only	
	$("#bom_file").change(function() {
        var selection = document.getElementById('bom_file');
		for(var i=0; i<selection.files.length; i++)
		{
			//var ext = selection.files[i].name.substr(-3);
			var ext = selection.files[i].name.substr((selection.files[i].name.lastIndexOf('.') +1)); //check type
			//const size = (selection.files[i].size / 1024 / 1024).toFixed(2); //check size
			
			//check type
			if(ext!== "xls" && ext!== "xlsx")
			{
				//dialog ctrl
				alert("[C002] --- Support file type .pdf only");
				$("#bom_file").replaceWith($("#bom_file").val('').clone(true));
				$("#bom_file").focus();
				
				return false ;
			}
		} 
    });
	
});

<!--OpenFrmUploadBom-->
function OpenFrmUploadBom()
{
	//dialog waiting ctrl
	$("#modal-upload-bom").modal("show");
	
	//clear step
	$("#progress_upload_bom").hide();
	$("#divresult_upload_bom").hide();
	
}

<!--ChkSubmit_frmUploadBom-->
function ChkSubmit_frmUploadBom(result)
{
	if($("#bom_file").val() == "")
	{
		//dialog ctrl
		alert("[C001] --- Select file");
		
		//hide
		setTimeout(function(){
			$("#bom_file").replaceWith($("#bom_file").val('').clone(true));
			$("#bom_file").focus();
		}, 500);
		
		return false;
	}
	
	$("#divFrm_upload_bom").hide("slide", { direction: "right" }, 400);
	$("#progress_upload_bom").show("slide", { direction: "left" }, 600);
	$("#divresult_upload_bom").html("<font style='font-size:14px; color: #00F; font-weight:bold;'>[I002] --- Please wait for a while, system is running....</font>");
	$("#divresult_upload_bom").show("slide", { direction: "left" }, 600);
	
	return true;
}

<!--showResult_frmUploadBom-->
function showResult_frmUploadBom(result)
{
	$("#progress_upload_bom").hide("slide", { direction: "right" }, 600);
	$("#divFrm_upload_bom").show("slide", { direction: "left" }, 400);
	
	if(result == 1)
	{
		$("#divresult_upload_bom").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Column in excel file does not match the database.</font>");
				
		<!--Clear value type file-->
		//$("#bom_file").replaceWith($("#bom_file").clone());
		$("#bom_file").replaceWith($("#bom_file").val('').clone(true));

		setTimeout(function(){
			$("#divresult_upload_bom").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_bom").show("slide", { direction: "left" }, 400);
			
		}, 4000);
	}
	else if(result == 2)
	{
		$("#divresult_upload_bom").html("<font style='font-size:14px; color: green; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/Check_icon.svg.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D003] --- Upload BOM file success.</font>");
		
		<!--Clear value type file-->
		//$("#bom_file").replaceWith($("#bom_file").clone());
		$("#bom_file").replaceWith($("#bom_file").val('').clone(true));
		
		setTimeout(function(){
			$("#divresult_upload_bom").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_bom").show("slide", { direction: "left" }, 400);
			
		}, 4000);
		
		//refresh
		setTimeout(function(){
			location.reload();
		}, 5200);
	}
	else if(result == 3)
	{
		//show result
		$("#divresult_upload_bom").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Unable to upload BOM file.</font>");
		
		<!--Clear value type file-->
		//$("#bom_file").replaceWith($("#bom_file").clone());
		$("#bom_file").replaceWith($("#bom_file").val('').clone(true));
		
		setTimeout(function(){
			$("#divresult_upload_bom").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_bom").show("slide", { direction: "left" }, 400);
			
		}, 4000);
	}
	else if(result == 4)
	{
		//show result
		$("#divresult_upload_bom").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C002] --- Error !! Wrong file type (Must be a file (.xls, .xlsx) only.</font>");
		
		<!--Clear value type file-->
		//$("#bom_file").replaceWith($("#bom_file").clone());
		$("#bom_file").replaceWith($("#bom_file").val('').clone(true));
		
		setTimeout(function(){
			$("#divresult_upload_bom").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_bom").show("slide", { direction: "left" }, 400);
			
		}, 4000);
	}
}

function openFuncDownloadBom()
{
	//href
	window.open('<?=$CFG->src_bom;?>/excel_bom_mst','_blank');
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
		<form name="frmUploadBom" action="<?=$CFG->src_bom;?>/bom_upload_exe.php" method="post" enctype="multipart/form-data" target="iframe_target_uploadBom" onSubmit="return ChkSubmit_frmUploadBom();">
		  <div class="modal-body">
			<div class="row">
				<div class="col-md-12">
				  <div class="form-group">
					  <a href="<?=$CFG->src_template_master_file;?>/template_bom_mst.xlsx" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="Excel file"><i class="fa fa-cloud-download"></i>&nbsp;Template BOM Master <i class="fa fa-file-excel-o"></i></a><br>
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
				<div id="divresult_upload_bom" name="divresult_upload_bom"></div><div id="progress_upload_bom" name="progress_upload_bom"><img src="<?=$CFG->imagedir;?>/Fountain.gif"></div><iframe id="iframe_target_uploadBom" name="iframe_target_uploadBom" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
			
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