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
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Replenishment Order</li>
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
						<button type="button" class="btn btn-primary btn-sm" onclick="OpenFrmUploadOrder();"><i class="fa fa-cloud-upload"></i> Upload Order</button>&nbsp;|&nbsp;<button type="button" class="btn btn-success btn-sm" onclick="confSelOrder();"><i class="fa fa-check-square-o"></i> Confirm Order</button>&nbsp;/&nbsp;<button type="button" class="btn btn-danger btn-sm" onclick="_delete_normal_order();"><i class="fa fa-trash fa-lg"></i> Delete</button>&nbsp;|&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="javascript:location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font> | <font style="color: #F00;">Refill Type: VMI Order, Special Order Can't delete.</font>
					</div>
					
					<!-- /.box-header -->
					<div class="box-body table-responsive padding">
					  <table id="tbl_replenishment_order" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 13px;">
						  <th style="width: 30px;">No.</th>
						  <th><input type="checkbox" class="largerRadio" onClick="toggle_repn(this)" data-placement="top" data-toggle="tooltip" data-original-title="Select all"/></th>
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
					<?
					$strSql = " select * from tbl_replenishment 
					left join tbl_bom_mst 
					on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
					and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
					and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
					and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
					and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
					and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
					where
					repn_conf_status is null
					order by repn_id desc ";
					
					$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
					$num_row = sqlsrv_num_rows($objQuery);

					$row_id = 0;
					while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
					{
						$row_id++;
	  
						$repn_id = $objResult['repn_id'];
						$repn_order_ref = $objResult['repn_order_ref'];
						$repn_fg_code_set_abt = $objResult['repn_fg_code_set_abt'];
						$repn_sku_code_abt = $objResult['repn_sku_code_abt'];
						$repn_qty = $objResult['repn_qty'];
						$repn_unit_type = $objResult['repn_unit_type'];
						$repn_terminal_name = $objResult['repn_terminal_name'];
						$repn_order_type = $objResult['repn_order_type'];
						$repn_delivery_date = $objResult['repn_delivery_date'];
						$repn_by = $objResult['repn_by'];
						$repn_date = $objResult['repn_date'];
						$repn_time = $objResult['repn_time'];
						$repn_datetime = $objResult['repn_datetime'];
						$repn_conf_status = $objResult['repn_conf_status'];
						$repn_conf_remark = $objResult['repn_conf_remark'];
						$repn_conf_by = $objResult['repn_conf_by'];
						$repn_conf_date = $objResult['repn_conf_date'];
						$repn_conf_time = $objResult['repn_conf_time'];
						$repn_conf_datetime = $objResult['repn_conf_datetime'];
						$bom_fg_code_gdj = $objResult['bom_fg_code_gdj'];
						$bom_cus_code = $objResult['bom_cus_code'];
						$bom_pj_name = $objResult['bom_pj_name'];
						$bom_ship_type = $objResult['bom_ship_type'];
						$bom_snp = $objResult['bom_snp'];
						$bom_usage = $objResult['bom_usage'];
						$bom_packing = $objResult['bom_packing'];
						$bom_part_customer = $objResult['bom_part_customer'];
						
						//conv to pack
						if($bom_packing > 0)
						{
							$str_fifo_picking_pack = ceil($repn_qty / $bom_packing);
							$str_conv_pack = floor($repn_qty / $bom_packing); 
							$str_conv_piece = $repn_qty % $bom_packing;
							
							//check piece
							if($str_conv_piece > 0)
							{
								if($str_conv_pack > 0)
								{
									$remark_pack_piece = $str_conv_pack." Pack, ".$str_conv_piece." Pcs.";
								}
								else
								{
									$remark_pack_piece = $str_conv_piece." Pcs.";
								}
							}
							else
							{
								$remark_pack_piece = $str_conv_pack." Pack";
							}
						}
						else
						{
							$str_fifo_picking_pack = 0;
							$str_conv_pack = 0;
							$str_conv_piece = 0;							
							$remark_pack_piece = $str_conv_pack." Pack, ".$str_conv_piece." Pcs.";
						}
						
						//get stock each fg code gdj
						$str_stock = get_stock_each_fg_gdj($db_con,$repn_fg_code_set_abt,$repn_sku_code_abt,$bom_fg_code_gdj,$bom_pj_name,$bom_ship_type,$bom_part_customer);
						if($bom_packing > 0)
						{
							$str_stock_conv_pack = ceil($str_stock / $bom_packing);
						}
						else
						{
							$str_stock_conv_pack = 0;
						}
						
						//control color  VMI Order = indigo, Special Order = red
						if($repn_order_type == "VMI Order")
						{
							$str_order_color = "indigo";
						}
						else if($repn_order_type == "Special Order")
						{
							$str_order_color = "red";
						}
						else
						{
							$str_order_color = "black";
						}
					?>
						<tr style="font-size: 13px;">
						  <td><?=$row_id;?></td>
						  <?
						  if($str_stock < $repn_qty)
						  {
						  ?>
						  <td></td>
						  <? 
						  }
						  else
						  {
							//allow project
							//if($repn_terminal_name == "TSESA")
							//{
							  ?>
							  <td>
							  <input type="checkbox" name="_chk_repn_order[]" class="largerRadio" onclick="checkSelect(this.checked,'<?=$row_id;?>')" value="<?=$row_id;?>"/>
							  <input type="hidden" name="hdn_repn_id<?=$row_id;?>" id="hdn_repn_id<?=$row_id;?>" value="<?=$repn_id;?>"/>
							  <input type="hidden" name="hdn_repn_order_type<?=$row_id;?>" id="hdn_repn_order_type<?=$row_id;?>" value="<?=$repn_order_type;?>"/>
							  <input type="hidden" name="hdn_repn_order_ref<?=$row_id;?>" id="hdn_repn_order_ref<?=$row_id;?>" value="<?=$repn_order_ref;?>"/>
							  <input type="hidden" name="hdn_fifo_picking_pack<?=$row_id;?>" id="hdn_fifo_picking_pack<?=$row_id;?>" value="<?=$str_fifo_picking_pack;?>"/>
							  <input type="hidden" name="hdn_repn_qty<?=$row_id;?>" id="hdn_repn_qty<?=$row_id;?>" value="<?=$repn_qty;?>"/>
							  </td>
							  <?								
							//}
							//else
							//{
							  ?>
							  <!--<td></td>-->
							  <?
							//}
						  }
						  ?>
						  <td align="center">
						  <button type="button" class="btn btn-primary btn-sm" id="<?=$repn_id;?>#####<?=$repn_order_type;?>#####<?=$repn_order_ref;?>#####<?=$str_fifo_picking_pack;?>#####<?=$repn_qty;?>" onclick="openFuncConfirm(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Confirm"><i class="fa fa-check-square-o fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="<?=$repn_id;?>" onclick="openFuncReject(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Reject"><i class="fa fa-times fa-lg"></i></button>
						  </td>
						  <td><font style="color: <?=$str_order_color;?>"><?=$repn_order_type;?></font>/<?=$repn_unit_type;?></td>
						  <td><?=$repn_order_ref;?></td>
						  <td style="font-weight: bold;"><?=$repn_delivery_date;?></td>
						  <td><?=$repn_fg_code_set_abt;?></td>
						  <td><?=$repn_sku_code_abt;?></td>
						  <td><?=$bom_part_customer;?></td>
						  <td style="color: #00F;"><?=$bom_fg_code_gdj;?></td>
						  <td style="color: #00F;"><?=$repn_qty;?> (<?=$remark_pack_piece;?>)</td>
						  <td style="color: orange;"><?=$bom_packing;?> (<?=$str_fifo_picking_pack;?> Pack)</td>
						  <td style="color: indigo;"><?=number_format($str_stock);?> (<?=$str_stock_conv_pack;?> Pack)</td>
						  <td><?=$repn_terminal_name;?></td>
						  <td><?=$bom_cus_code;?></td>
						  <td><?=$bom_pj_name;?></td>
						  <td><?=$repn_by;?></td>
						  <td><?=substr($repn_datetime,0,19);?></td>
						</tr>
					<?
					}
					?>						
						</tbody>
						<!--<tfoot>
							<tr>
								<th></th>
								<th></th>
								<th></th>
								<th>Refill Type/Unit Type</th>
								<th>Ref No.</th>
								<th>Delivery Date</th>
								<th>FG Code Set</th>
								<th>Component Code</th>
								<th>Part Customer</th>
								<th>FG Code GDJ</th>
								<th>Quantity (Pcs.)</th>
								<th>FIFO Picking (Pcs.)</th>
								<th>WMS Stock On Hand (Pcs.)</th>
								<th>Terminal Name</th>
								<th>Customer Code</th>
								<th>Project Name</th>
								<th>Issue By</th>
								<th></th>
							</tr>
						</tfoot>-->
					  </table>
					</div>
					<!-- /.box-body -->
					
					<!--alert no item-->
					<input type="hidden" name="hdn_row_replenish" id="hdn_row_replenish" value="<?=$row_id;?>" />
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
	// Setup - add a text input to each footer cell
    $('#tbl_replenishment_order thead tr').clone(true).appendTo( '#tbl_replenishment_order thead' );
	$('#tbl_replenishment_order thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
		
		//sel columnDefs
		if(i != 0 && i != 1 && i != 2 && i != 17)
		{
			$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
	 
			$( 'input', this ).on( 'keyup change', function () {
				if ( table.column(i).search() !== this.value ) {
					table
						.column(i)
						.search( this.value )
						.draw();
				}
			} );
		}
		else
		{
			$(this).html( '' );
		}
    } );
	
	var table = $('#tbl_replenishment_order').DataTable( {
		rowReorder: true,
		"aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
		"iDisplayLength": 10,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17 ] },
            { orderable: false, targets: '_all' }
        ],
        orderCellsTop: true,
        fixedHeader: true
    } );
	
	//search
    /*$('#tbl_replenishment_order').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	/*$('#tbl_replenishment_order').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			//if(data[2] == "VMI Order"){
			//	$(row).find('td:eq(2)').css('color', 'indigo');
			//}
			//else if(data[2] == "Special Order"){
			//	$(row).find('td:eq(2)').css('color', 'red');
			//}		

		},
    });*/	
	
	//clear step
	$("#progress_upload_order").hide();
	$("#divresult_upload_order").hide();
	
	//file type validation
	//Support file type .pdf only	
	$("#order_file").change(function() {
        var selection = document.getElementById('order_file');
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
				$("#order_file").replaceWith($("#order_file").val('').clone(true));
				$("#order_file").focus();
				
				return false ;
			}
		} 
    });
	
});

function openFuncConfirm(id)
{
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
		  url: '<?=$CFG->src_replenishment;?>/confirm_replenishment_order.php',
		  data: { 
					iden_t_repn_id: t_repn_id
					,iden_t_order_type: t_order_type
					,iden_t_ref_no: t_ref_no
					,iden_t_fifo_picking_pack: t_fifo_picking_pack
					,iden_hdn_repn_qty: t_repn_qty
				},
				success: function(response){
			
				//refresh
				location.reload();
				
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

//check all
function toggle_repn(source)
{
  checkboxes = document.getElementsByName('_chk_repn_order[]');
  for(var i = 0, n = checkboxes.length; i < n; i++)
  {
	checkboxes[i].checked = source.checked;
	//var qty = "txtQtyDI"+(i+1);
	//var weight = "txtWeightDI"+(i+1);
	//document.getElementById(qty).disabled = !source.checked;
	//document.getElementById(weight).disabled = !source.checked;
  }
}

function confSelOrder()
{
	//check No data available in table
	if($("#hdn_row_replenish").val() == 0)
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
			
			var cbChecked = $("input[name='_chk_repn_order[]']:checked").length;
			
			//conf
			var tmp = 0;
			$("input[name='_chk_repn_order[]']:checked").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_repn_id = "#hdn_repn_id"+$(this).val();
				var iden_hdn_repn_id = $(iden_hdn_repn_id).val();
				
				var iden_hdn_repn_order_type = "#hdn_repn_order_type"+$(this).val();
				var iden_hdn_repn_order_type = $(iden_hdn_repn_order_type).val();
				
				var iden_hdn_repn_order_ref = "#hdn_repn_order_ref"+$(this).val();
				var iden_hdn_repn_order_ref = $(iden_hdn_repn_order_ref).val();
				
				var iden_hdn_fifo_picking_pack = "#hdn_fifo_picking_pack"+$(this).val();
				var iden_hdn_fifo_picking_pack = $(iden_hdn_fifo_picking_pack).val();
				
				var iden_hdn_repn_qty = "#hdn_repn_qty"+$(this).val();
				var iden_hdn_repn_qty = $(iden_hdn_repn_qty).val();
				
				//post
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_replenishment;?>/confirm_replenishment_order.php',
				  data: { 
							iden_t_repn_id: iden_hdn_repn_id
							,iden_t_order_type: iden_hdn_repn_order_type
							,iden_t_ref_no: iden_hdn_repn_order_ref	
							,iden_t_fifo_picking_pack: iden_hdn_fifo_picking_pack
							,iden_hdn_repn_qty: iden_hdn_repn_qty
						},
						success: function(response){
						//refresh
						location.reload();
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

function openFuncReject(id)
{
	$("#modal-reject-order").modal("show");
	$("#hdn_reject_id").val(id);
}

function ConfFuncReject()
{
	if($("#txt_reject_remark").val() == "")
	{
		$("#txt_reject_remark").focus();
		
		return false ;
	}
	else
	{		
		//accepted order
		$.ajax({
		  type: 'POST',
		  url: '<?=$CFG->src_replenishment;?>/reject_replenishment_order.php',
		  data: { 
					iden_repn_id: $("#hdn_reject_id").val()
					,iden_repn_reject_remark: $("#txt_reject_remark").val()
				},
				success: function(response){
			
				//refresh
				location.reload();
				
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

function _delete_normal_order()
{
	//check No data available in table
	if($("#hdn_row_replenish").val() == 0)
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
			
			$("input[name='_chk_repn_order[]']:checked").each(function ()
			{
				//count for alert not select item
				tmp = tmp + 1;
				
				var iden_hdn_repn_id = "#hdn_repn_id"+$(this).val();
				var iden_hdn_repn_id = $(iden_hdn_repn_id).val();
				
				var iden_hdn_repn_order_type = "#hdn_repn_order_type"+$(this).val();
				var iden_hdn_repn_order_type = $(iden_hdn_repn_order_type).val();
				
				var iden_hdn_repn_order_ref = "#hdn_repn_order_ref"+$(this).val();
				var iden_hdn_repn_order_ref = $(iden_hdn_repn_order_ref).val();
				
				//Normal Order only
				if(iden_hdn_repn_order_type == "Normal Order")
				{
					//post
					$.ajax({
					  type: 'POST',
					  url: '<?=$CFG->src_replenishment;?>/del_replenishment_order.php',
					  data: {
								iden_t_repn_id: iden_hdn_repn_id
								,iden_t_order_type: iden_hdn_repn_order_type
								,iden_t_ref_no: iden_hdn_repn_order_ref	
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
				}
				
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
					location.reload();
				}
			}
			
		  }
		});
	}
}

<!--OpenFrmUploadOrder-->
function OpenFrmUploadOrder()
{
	//dialog waiting ctrl
	$("#modal-upload-order").modal("show");
	
	//clear step
	$("#progress_upload_order").hide();
	$("#divresult_upload_order").hide();
	
}

<!--ChkSubmit_frmUploadorder-->
function ChkSubmit_frmUploadorder(result)
{
	if($("#txt_sel_cus").val() == "")
	{
		//dialog ctrl
		alert("[C001] --- Select Customer");
		
		//hide
		setTimeout(function(){
			$("#txt_sel_cus").focus();
		}, 500);
		
		return false;
	}	
	else if($("#order_file").val() == "")
	{
		//dialog ctrl
		alert("[C001] --- Select file");
		
		//hide
		setTimeout(function(){
			$("#order_file").replaceWith($("#order_file").val('').clone(true));
			$("#order_file").focus();
		}, 500);
		
		return false;
	}
	
	$("#divFrm_upload_order").hide("slide", { direction: "right" }, 400);
	$("#progress_upload_order").show("slide", { direction: "left" }, 600);
	$("#divresult_upload_order").html("<font style='font-size:14px; color: #00F; font-weight:bold;'>[I002] --- Please wait for a while, system is running....</font>");
	$("#divresult_upload_order").show("slide", { direction: "left" }, 600);
	
	return true;
}

<!--showResult_frmUploadOrder-->
function showResult_frmUploadOrder(result)
{
	$("#progress_upload_order").hide("slide", { direction: "right" }, 600);
	$("#divFrm_upload_order").show("slide", { direction: "left" }, 400);
	
	if(result == 1)
	{
		$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Column in excel file does not match the database.</font>");
				
		<!--Clear value type file-->
		//$("#order_file").replaceWith($("#order_file").clone());
		$("#order_file").replaceWith($("#order_file").val('').clone(true));

		setTimeout(function(){
			$("#divresult_upload_order").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_order").show("slide", { direction: "left" }, 400);
			
		}, 4000);
	}
	else if(result == 2)
	{
		$("#divresult_upload_order").html("<font style='font-size:14px; color: green; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/Check_icon.svg.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D003] --- Upload order file success.</font>");
		
		<!--Clear value type file-->
		//$("#order_file").replaceWith($("#order_file").clone());
		$("#order_file").replaceWith($("#order_file").val('').clone(true));
		
		setTimeout(function(){
			$("#divresult_upload_order").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_order").show("slide", { direction: "left" }, 400);
			
		}, 4000);
		
		//refresh
		setTimeout(function(){
			location.reload();
		}, 5200);
	}
	else if(result == 3)
	{
		//show result
		$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [D002] --- Error !! Unable to upload order file.</font>");
		
		<!--Clear value type file-->
		//$("#order_file").replaceWith($("#order_file").clone());
		$("#order_file").replaceWith($("#order_file").val('').clone(true));
		
		setTimeout(function(){
			$("#divresult_upload_order").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_order").show("slide", { direction: "left" }, 400);
			
		}, 4000);
	}
	else if(result == 4)
	{
		//show result
		$("#divresult_upload_order").html("<font style='font-size:14px; color: #F00; font-weight:bold;'><img src='<?=$CFG->imagedir;?>/No_admittance_sign.png' width='60' border='0'><br>&nbsp;<i class='fa fa-caret-right'></i> [C002] --- Error !! Wrong file type (Must be a file (.xls, .xlsx) only.</font>");
		
		<!--Clear value type file-->
		//$("#order_file").replaceWith($("#order_file").clone());
		$("#order_file").replaceWith($("#order_file").val('').clone(true));
		
		setTimeout(function(){
			$("#divresult_upload_order").hide("slide", { direction: "right" }, 1000);
			$("#divFrm_upload_order").show("slide", { direction: "left" }, 400);
			
		}, 4000);
	}
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
		<form name="frmUploadorder" action="<?=$CFG->src_replenishment;?>/upload_order_exe.php" method="post" enctype="multipart/form-data" target="iframe_target_uploadorder" onSubmit="return ChkSubmit_frmUploadorder();">
		  <div class="modal-body">
			<div class="row">
				<div class="col-md-12">
				  <div class="form-group">
					  <a href="<?=$CFG->src_template_master_file;?>/template_upload_order_mst.xlsx" target="_blank" data-placement="top" data-toggle="tooltip" data-original-title="Excel file"><i class="fa fa-cloud-download"></i>&nbsp;Template order master <i class="fa fa-file-excel-o"></i></a><br>
					  
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
						  <?
							$strSQL = " SELECT bom_cus_name FROM tbl_bom_mst group by bom_cus_name order by bom_cus_name asc ";
							$objQuery = sqlsrv_query($db_con, $strSQL) or die ("Error Query [".$strSQL."]");
							while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
							{
						  ?>
							<option value="<?=$objResult["bom_cus_name"];?>"><?=$objResult["bom_cus_name"];?></option>
						  <?
							}
							sqlsrv_close($db_con);
						  ?>
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
				<div id="divresult_upload_order" name="divresult_upload_order"></div><div id="progress_upload_order" name="progress_upload_order"><img src="<?=$CFG->imagedir;?>/Fountain.gif"></div><iframe id="iframe_target_uploadorder" name="iframe_target_uploadorder" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
			
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