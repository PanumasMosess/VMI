<?
require_once("../../application.php");
require_once("../../js_css_header.php");
?>	
<!-- /.box-header -->
<div class="box-body table-responsive padding">
  <table id="tbl_picking_order" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th><input type="checkbox" class="largerRadio" onClick="toggle_chk_picking(this)" data-placement="top" data-toggle="tooltip" data-original-title="Select all"/></th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Order Status</th>
	  <th>Refill Type/Unit Type</th>
	  <th>Delivery Date</th>
	  <th>FG Code Set</th>
	  <th>Component Code</th>
	  <th>Part Customer</th>
	  <th style="color: #00F;">FG Code GDJ</th>
	  <th style="color: #00F;">Quantity (Pcs.)</th>
	  <th style="color: orange;">FIFO Picking (Pcs.)</th>
	  <!--<th style="color: indigo;">WMS Stock On Hand (Pcs.)</th>-->
	  <th>Terminal Name</th>
	  <th>Customer Code</th>
	  <th>Project Name</th>
	  <th>Issue By</th>
	  <th>Issue Datetime</th>
	  <!-- <th>repn_qty</th>
	  <th>bom_packing</th>
	  <th>ceil($repn_qty / $bom_packing)</th>
	  <th>floor($repn_qty / $bom_packing)</th>
	  <th>$repn_qty % $bom_packing</th> -->
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
repn_conf_status = 'Confirmed' and repn_pj_name = 'B2C'
order by repn_id desc ";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$repn_id = $objResult['repn_id'];
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
	$bom_cus_name = $objResult['bom_cus_name'];
	$bom_pj_name = $objResult['bom_pj_name'];
	$bom_ship_type = $objResult['bom_ship_type'];
	$bom_snp = $objResult['bom_snp'];
	$bom_usage = $objResult['bom_usage'];
	$bom_packing = $objResult['bom_packing'];
	$bom_part_customer = $objResult['bom_part_customer'];
	
	/*
	//conv to pack
	if($bom_packing > 0)
	{ 
		$str_conv_pack = ceil($repn_qty / $bom_packing); 
	}
	else
	{
		$str_conv_pack = 0;
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
	*/
	
	//conv to pack
	if($bom_packing > 0)
	{
		$str_fifo_picking_pack = ceil($repn_qty / $bom_packing);
		$str_conv_pack = floor($repn_qty / $bom_packing); 
		$str_conv_piece = $repn_qty % $bom_packing;

		if($repn_qty < $bom_packing){

			$str_fifo_picking_pack++;

		}
		
		//check piece
		if($str_conv_piece > 0)
		{
			if($str_conv_pack > 0)
			{
				$remark_pack_piece = $str_conv_pack." Pack / ".$str_conv_piece." Pcs.";
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
		$remark_pack_piece = $str_conv_pack." Pack / ".$str_conv_piece." Pcs.";
	}
	
	//fifo picking qty
	$str_fifo_pick_qry =  get_fifo_pick_qty($db_con,$repn_id);
	
	/*
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
	*/
	
	//check split tags 
	$str_count_split_tags = check_picking_split_tags($db_con,$repn_id);
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id;?></td>
	  <?
	  //if($str_stock < $repn_qty)
	  //{
	  ?>
	  <!--<td></td>-->
	  <? 
	  //}
	  //else
	  //{
		//check split tags
		if($repn_qty != $str_fifo_pick_qry)
		{
		?>
		<td style="color: #00F;">Split Tags</td>
		<?
		}
		else
		{
	  ?>
	  <td>
		<input type="checkbox" name="_chk_picking[]" class="largerRadio" onclick="checkSelect(this.checked,'<?=$row_id;?>')" value="<?=$row_id;?>"/>
		<input type="hidden" name="hdn_repn_id<?=$row_id;?>" id="hdn_repn_id<?=$row_id;?>" value="<?=$repn_id;?>"/>
		<input type="hidden" name="hdn_repn_fg_code_set_abt<?=$row_id;?>" id="hdn_repn_fg_code_set_abt<?=$row_id;?>" value="<?=$repn_fg_code_set_abt;?>"/>
		<input type="hidden" name="hdn_repn_sku_code_abt<?=$row_id;?>" id="hdn_repn_sku_code_abt<?=$row_id;?>" value="<?=$repn_sku_code_abt;?>"/>
		<input type="hidden" name="hdn_bom_fg_code_gdj<?=$row_id;?>" id="hdn_bom_fg_code_gdj<?=$row_id;?>" value="<?=$bom_fg_code_gdj;?>"/>
		<input type="hidden" name="hdn_bom_cus_code<?=$row_id;?>" id="hdn_bom_cus_code<?=$row_id;?>" value="<?=$bom_cus_code;?>"/>
		<input type="hidden" name="hdn_bom_cus_name<?=$row_id;?>" id="hdn_bom_cus_name<?=$row_id;?>" value="<?=$bom_cus_name;?>"/>
		<input type="hidden" name="hdn_bom_pj_name<?=$row_id;?>" id="hdn_bom_pj_name<?=$row_id;?>" value="<?=$bom_pj_name;?>"/>
		<input type="hidden" name="hdn_bom_ship_type<?=$row_id;?>" id="hdn_bom_ship_type<?=$row_id;?>" value="<?=$bom_ship_type;?>"/>
		<input type="hidden" name="hdn_bom_part_customer<?=$row_id;?>" id="hdn_bom_part_customer<?=$row_id;?>" value="<?=$bom_part_customer;?>"/>
		<input type="hidden" name="hdn_str_conv_pack<?=$row_id;?>" id="hdn_str_conv_pack<?=$row_id;?>" value="<?=$str_fifo_picking_pack;?>"/>
	  </td>
	  <?
		}
	  //}
	  ?>
	  
	  <td align="right">
	  
	  <? /* ?>
	  <? if($repn_qty != $str_fifo_pick_qry){ ?><button type="button" class="btn btn-danger btn-sm" id="<?=$repn_fg_code_set_abt;?>#####<?=$repn_sku_code_abt;?>#####<?=$bom_fg_code_gdj;?>#####<?=$bom_pj_name;?>#####<?=$bom_ship_type;?>#####<?=$bom_part_customer;?>#####<?=$str_fifo_picking_pack;?>#####<?=$repn_qty;?>#####<?=$repn_id;?>" onclick="openFuncSplitTags(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Split Tags"><i class="glyphicon glyphicon-resize-full"></i></button>&nbsp;&nbsp;<? } else { ?><? if($str_count_split_tags != 0){ ?><button type="button" class="btn btn-warning btn-sm" id="<?=$repn_fg_code_set_abt;?>#####<?=$repn_sku_code_abt;?>#####<?=$bom_fg_code_gdj;?>#####<?=$bom_pj_name;?>#####<?=$bom_ship_type;?>#####<?=$bom_part_customer;?>#####<?=$str_fifo_picking_pack;?>#####<?=$repn_qty;?>#####<?=$repn_id;?>" onclick="openFuncReturnSplitTags(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Return Split Tags"><i class="glyphicon glyphicon-resize-small"></i></button>&nbsp;&nbsp;<? } else { ?><button type="button" class="btn btn-warning btn-sm" id="<?=$repn_id;?>" onclick="openReturnReplenish(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Return To Replenishment Order"><i class="fa fa-undo fa-lg"></i></button>&nbsp;&nbsp;<? } } ?><button type="button" class="btn btn-info btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="View" id="<?=$repn_fg_code_set_abt;?>#####<?=$repn_sku_code_abt;?>#####<?=$bom_fg_code_gdj;?>#####<?=$bom_pj_name;?>#####<?=$bom_ship_type;?>#####<?=$bom_part_customer;?>#####<?=$str_fifo_picking_pack;?>#####<?=$repn_id;?>" onclick="openFuncDetails(this.id);"><i class="fa fa-search fa-lg"></i></button>
	  <? */ ?>
	  
	  
	  <? if($repn_qty != $str_fifo_pick_qry){ ?><button type="button" class="btn btn-danger btn-sm custom_tooltip" id="<?=$repn_fg_code_set_abt;?>#####<?=$repn_sku_code_abt;?>#####<?=$bom_fg_code_gdj;?>#####<?=$bom_pj_name;?>#####<?=$bom_ship_type;?>#####<?=$bom_part_customer;?>#####<?=$str_fifo_picking_pack;?>#####<?=$repn_qty;?>#####<?=$repn_id;?>" onclick="openFuncSplitTags(this.id)"><i class="glyphicon glyphicon-resize-full"></i><span class="custom_tooltiptext">Split Tags</span></button>&nbsp;&nbsp;<? } ?><? if($str_count_split_tags != 0){ ?><button type="button" class="btn btn-warning btn-sm custom_tooltip" id="<?=$repn_fg_code_set_abt;?>#####<?=$repn_sku_code_abt;?>#####<?=$bom_fg_code_gdj;?>#####<?=$bom_pj_name;?>#####<?=$bom_ship_type;?>#####<?=$bom_part_customer;?>#####<?=$str_fifo_picking_pack;?>#####<?=$repn_qty;?>#####<?=$repn_id;?>" onclick="openFuncReturnSplitTags(this.id)"><i class="glyphicon glyphicon-resize-small"></i><span class="custom_tooltiptext">Return Split Tags</span></button>&nbsp;&nbsp;<? } ?><? if($str_count_split_tags == 0){ ?><button type="button" class="btn btn-warning btn-sm custom_tooltip" id="<?=$repn_id;?>" onclick="openReturnReplenish(this.id)"><i class="fa fa-undo fa-lg"></i><span class="custom_tooltiptext">Return To Replenishment Order</span></button>&nbsp;&nbsp;<? } ?><button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?=$repn_fg_code_set_abt;?>#####<?=$repn_sku_code_abt;?>#####<?=$bom_fg_code_gdj;?>#####<?=$bom_pj_name;?>#####<?=$bom_ship_type;?>#####<?=$bom_part_customer;?>#####<?=$str_fifo_picking_pack;?>#####<?=$repn_id;?>#####<?=$repn_qty;?>" onclick="openFuncDetails(this.id);"><i class="fa fa-search fa-lg"></i><span class="custom_tooltiptext">View</span></button>
	 
	  </td>
	  <td><?=$repn_conf_status;?></td>
	  <td><?=$repn_order_type;?>/<?=$repn_unit_type;?></td>
	  <td style="font-weight: bold;"><?=$repn_delivery_date;?></td>
	  <td><?=$repn_fg_code_set_abt;?></td>
	  <td><?=$repn_sku_code_abt;?></td>
	  <td><?=$bom_part_customer;?></td>
	  <td style="color: #00F;"><?=$bom_fg_code_gdj;?></td>
	  <td style="color: #00F;"><?=$repn_qty;?> (<?=$remark_pack_piece;?>)</td>
	  <td style="color: orange;"><?=$str_fifo_pick_qry;?> (<?=$str_fifo_picking_pack;?> Pack)</td>
	  <!--<td style="color: indigo;"><?=number_format($str_stock);?> (<?=$str_stock_conv_pack;?> Pack)</td>-->
	  <td><?=$repn_terminal_name;?></td>
	  <td><?=$bom_cus_code;?></td>
	  <td><?=$bom_pj_name;?></td>
	  <td><?=$repn_by;?></td>
	  <td><?=substr($repn_datetime,0,19);?></td>
	  <!-- <td><?=$repn_qty;?></td>
	  <td><?=$bom_packing;?></td>
	  <td><?=$str_fifo_picking_pack;?></td>
	  <td><?=$str_conv_pack;?></td>
	  <td><?=$str_conv_piece;?></td> -->
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_waiting_conf_picking" id="hdn_row_waiting_conf_picking" value="<?=$row_id;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	// Setup - add a text input to each footer cell
    $('#tbl_picking_order thead tr').clone(true).appendTo( '#tbl_picking_order thead' );
	$('#tbl_picking_order thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
		
		//sel columnDefs
		if(i != 0 && i != 1 && i != 2 && i != 3 && i != 16)
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
	
	var table = $('#tbl_picking_order').DataTable( {
		rowReorder: true,
		"aLengthMenu": [[10,25, 50, 75, 100, -1], [10,25, 50, 75, 100, "All"]],
		"iDisplayLength": 10,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,3,4,5,6,7,8,9,10,11,12,13,14,15,16 ] },
            { orderable: false, targets: '_all' }
        ],
        orderCellsTop: true,
        fixedHeader: true,
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			if(data[3] == "Confirmed"){
				$(row).find('td:eq(3)').css('color', 'Green');
			}

			//status
			if(data[4] == "VMI Order"){
				$(row).find('td:eq(4)').css('color', 'indigo');
			}
			else if(data[4] == "Special Order"){
				$(row).find('td:eq(4)').css('color', 'red');
			}
		},
    } );
	
	/*
	<!--datatable search paging-->
	$('#tbl_picking_order').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": 25,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,3,4,5,6,7,8,9,10,11,12,13,14,15,16 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			if(data[3] == "Confirmed"){
				$(row).find('td:eq(3)').css('color', 'Green');
			}

			//status
			if(data[4] == "VMI Order"){
				$(row).find('td:eq(4)').css('color', 'indigo');
			}
			else if(data[4] == "Special Order"){
				$(row).find('td:eq(4)').css('color', 'red');
			}

		},
    });
	*/
	
});
</script>