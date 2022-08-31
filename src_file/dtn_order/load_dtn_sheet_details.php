<?
require_once("../../application.php");
require_once("../../js_css_header.php");
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_dtn_sheet" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>DTN Sheet ID</th>
	  <th>Customer Code</th>
	  <th>Customer Name</th>
	  <th>Project Name</th>
	  <th style="color: indigo;">Quantity (Pcs.)</th>
	  <th>Status</th>
	  <th>Delivery Date</th>
	</tr>
	</thead>
	<tbody>
<?
$strSql_DTNSheet = " 
SELECT 
	[dn_h_dtn_code]
      ,[dn_h_cus_code]
      ,[dn_h_cus_name]
	  ,[dn_h_driver_code]
      ,[dn_h_delivery_date]
	  ,[dn_h_status]
	  ,[ps_t_pj_name]
	  ,sum([tags_packing_std]) as sum_picking_std 
  FROM [tbl_dn_head]
  left join
  tbl_dn_tail
  on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
  left join
  tbl_picking_tail
  on tbl_dn_tail.dn_t_picking_code = tbl_picking_tail.ps_t_picking_code
  left join
  tbl_receive
  on tbl_picking_tail.ps_t_tags_code = tbl_receive.receive_tags_code
  left join
  tbl_tags_running
  on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
  where
  dn_h_status = 'Delivery Transfer Note'
  and
  ps_t_pj_name != 'B2C'
  and
  dn_t_status != 'Confirmed'
  group by
  [dn_h_dtn_code]
      ,[dn_h_cus_code]
      ,[dn_h_cus_name]
	  ,[dn_h_driver_code]
      ,[dn_h_delivery_date]
	  ,[dn_h_status]
	  ,[ps_t_pj_name]
order by 
dn_h_dtn_code desc
";

$objQuery_DTNSheet = sqlsrv_query($db_con, $strSql_DTNSheet, $params, $options);
$num_row_DTNSheet = sqlsrv_num_rows($objQuery_DTNSheet);

$row_id_DTNSheet = 0;
while($objResult_DTNSheet = sqlsrv_fetch_array($objQuery_DTNSheet, SQLSRV_FETCH_ASSOC))
{
	$row_id_DTNSheet++;

	$dn_h_dtn_code = $objResult_DTNSheet['dn_h_dtn_code'];
	$dn_h_cus_code = $objResult_DTNSheet['dn_h_cus_code'];
	$dn_h_cus_name = $objResult_DTNSheet['dn_h_cus_name'];
	$ps_t_pj_name = $objResult_DTNSheet['ps_t_pj_name'];
	$dn_h_status = $objResult_DTNSheet['dn_h_status'];
	$sum_picking_std = $objResult_DTNSheet['sum_picking_std'];
	$dn_h_delivery_date = $objResult_DTNSheet['dn_h_delivery_date'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_DTNSheet;?></td>
	  <td align="center">
	  <!--<button type="button" class="btn btn-warning btn-sm" id="<?=$dn_h_dtn_code;?>" onclick="openRefill(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Refill this Pallet ID"><i class="fa fa-pencil-square-o fa-lg"></i></button>&nbsp;&nbsp;-->
	  <button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?=var_encode($dn_h_dtn_code);?>" onclick="openRePrintDTNSheet(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this DTN Sheet ID</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm custom_tooltip" id="<?=var_encode($dn_h_dtn_code);?>" onclick="openRePrintDTNSheetShotFrom(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this DTN Sheet ID (Short form)</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?=$dn_h_dtn_code;?>#####<?=$dn_h_cus_code;?>#####<?=$dn_h_cus_name;?>#####<?=$ps_t_pj_name;?>#####<?=$dn_h_status;?>#####<?=$dn_h_delivery_date;?>" onclick="openFuncDTNSheetDetails(this.id);"><i class="fa fa-search fa-lg"></i><span class="custom_tooltiptext">View</span></button>&nbsp;
	  <button type="button" class="btn btn-warning btn-sm custom_tooltip" id="<?=var_encode($dn_h_dtn_code);?>" onclick="reTurnToConfirmDTN(this.id);"><i class="fa fa-undo fa-lg"></i><span class="custom_tooltiptext">Return to Wait Confirm DTN</span></button>&nbsp;
	  </td>
	  <td><?=$dn_h_dtn_code;?></td>
	  <td><?=$dn_h_cus_code;?></td>
	  <td><?=$dn_h_cus_name;?></td>
	  <td><?=$ps_t_pj_name;?></td>
	  <td style="color: indigo;"><?=$sum_picking_std;?></td>
	  <td><?=$dn_h_status;?></td>
	  <td><?=$dn_h_delivery_date;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_DTNSheet" id="hdn_row_DTNSheet" value="<?=$row_id_DTNSheet;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_dtn_sheet').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_dtn_sheet').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			if(data[7] == "Received"){
				$(row).find('td:eq(7)').css('color', 'green');//green
			}
			else if(data[7] == "Picking"){
				$(row).find('td:eq(7)').css('color', 'gray');
			}
			else if(data[7] == "Confrim Order"){
				$(row).find('td:eq(7)').css('color', 'gray');
			}
			else if(data[7] == "Delivery Transfer Note"){
				$(row).find('td:eq(7)').css('color', 'orange');
			}
			else if(data[7] == "Pick To Use"){
				$(row).find('td:eq(7)').css('color', 'blue');
			}
			else if(data[7] == "Terminal 01"){
				$(row).find('td:eq(7)').css('color', 'limegreen');
			}
			else if(data[7] == "Terminal 02"){
				$(row).find('td:eq(7)').css('color', 'limegreen');
			}
			else if(data[7] == "Terminal 03"){
				$(row).find('td:eq(7)').css('color', 'limegreen');
			}
			else if(data[7] == "Terminal 04"){
				$(row).find('td:eq(7)').css('color', 'limegreen');
			}
			else if(data[7] == "Terminal 05"){
				$(row).find('td:eq(7)').css('color', 'limegreen');
			}
		},
    });
	
});
</script>