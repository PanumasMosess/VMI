<?
require_once("../../application.php");
require_once("../../js_css_header.php");
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_picking_sheet" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Picking Sheet ID</th>
	  <th>Customer Code</th>
	  <th>Customer Name</th>
	  <th>Project Name</th>
	  <th style="color: #00F;">Quantity (Pcs.)</th>
	  <th>Status</th>
	  <th>Quality Control</th>
	  <th>Issue Date</th>
	</tr>
	</thead>
	<tbody>
<?
$strSql_PickingSheet = " 
SELECT 
	[ps_h_picking_code]
	,[ps_h_cus_code]
	,[ps_h_cus_name]
	,[ps_t_pj_name]
	,[ps_h_status]
	,sum([ps_t_tags_packing_std]) as sum_tags_packing_std
	,[ps_h_issue_date]
FROM [tbl_picking_head]
left join
tbl_picking_tail
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
where
ps_h_status = 'Picking'
and
ps_h_qc is NULL
group by
	[ps_h_picking_code]
	,[ps_h_cus_code]
	,[ps_h_cus_name]
	,[ps_t_pj_name]
	,[ps_h_status]
	,[ps_h_issue_date]
order by 
ps_h_picking_code desc
";

$objQuery_PickingSheet = sqlsrv_query($db_con, $strSql_PickingSheet, $params, $options);
$num_row_PickingSheet = sqlsrv_num_rows($objQuery_PickingSheet);

$row_id_PickingSheet = 0;
while($objResult_PickingSheet = sqlsrv_fetch_array($objQuery_PickingSheet, SQLSRV_FETCH_ASSOC))
{
	$row_id_PickingSheet++;

	$ps_h_picking_code = $objResult_PickingSheet['ps_h_picking_code'];
	$ps_h_cus_code = $objResult_PickingSheet['ps_h_cus_code'];
	$ps_h_cus_name = $objResult_PickingSheet['ps_h_cus_name'];
	$ps_t_pj_name = $objResult_PickingSheet['ps_t_pj_name'];
	$ps_h_status = $objResult_PickingSheet['ps_h_status'];
	$sum_tags_packing_std = $objResult_PickingSheet['sum_tags_packing_std'];
	$ps_h_issue_date = $objResult_PickingSheet['ps_h_issue_date'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_PickingSheet;?></td>
	  <td align="center">
	  <!--<button type="button" class="btn btn-warning btn-sm" id="<?=$ps_h_picking_code;?>" onclick="openRefill(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Refill this Pallet ID"><i class="fa fa-pencil-square-o fa-lg"></i></button>&nbsp;&nbsp;--><button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?=var_encode($ps_h_picking_code);?>" onclick="openRePrintPickingSheet(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this Picking Sheet ID</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?=var_encode($ps_h_picking_code);?>" onclick="openRePrintSet_on_picking_sheet(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-print All Tags on Picking Sheet ID</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?=$ps_h_picking_code;?>#####<?=$ps_h_cus_code;?>#####<?=$ps_h_cus_name;?>#####<?=$ps_t_pj_name;?>#####<?=$ps_h_status;?>#####<?=$ps_h_issue_date;?>" onclick="openFuncPickingSheetDetails(this.id);"><i class="fa fa-search fa-lg"></i><span class="custom_tooltiptext">View</span></button>
	  &nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?=$ps_h_picking_code;?>#####<?=$ps_h_cus_code;?>#####<?=$ps_h_cus_name;?>#####<?=$ps_t_pj_name;?>#####<?=$ps_h_status;?>#####<?=$ps_h_issue_date;?>" onclick="restoreToConfirmedReplenishment(this.id);"><i class="fa fa-undo fa-lg"></i><span class="custom_tooltiptext">Return to Confirmed Replenishment</span></button>
	  </td>
	  <td><?=$ps_h_picking_code;?></td>
	  <td><?=$ps_h_cus_code;?></td>
	  <td><?=$ps_h_cus_name;?></td>
	  <td><?=$ps_t_pj_name;?></td>
	  <td style="color: #00F;"><?=$sum_tags_packing_std;?></td>
	  <td><?=$ps_h_status;?></td>
	  <td style="color: #F00;">Waiting Picking QC</td>
	  <td><?=$ps_h_issue_date;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_PickingSheet" id="hdn_row_PickingSheet" value="<?=$row_id_PickingSheet;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_picking_sheet').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	// <!--datatable search paging-->
	$('#tbl_picking_sheet').DataTable( {
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