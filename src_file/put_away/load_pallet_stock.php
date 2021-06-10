<?
require_once("../../application.php");
require_once("../../js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_inventory" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 18px;">
		<th colspan="9" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i> WMS Stock</b>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_pallet()"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Pallet</button>&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button></th>
	</tr>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Pallet ID</th>
	  <th>FG Code GDJ</th>
	  <th>FG Code GDJ Desc.</th>
	  <th>Location</th>
	  <th style="color: indigo;">Quantity (Pcs.)</th>
	  <th>Status</th>
	  <th>Receive Date</th>
	</tr>
	</thead>
	<tbody>
<?
$strSql = " 
SELECT 
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,receive_location
	,receive_status
	,receive_date
	,sum(tags_packing_std) as sum_pkg_std
FROM  tbl_receive
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status = 'Received'
group by
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_fg_code_gdj_desc
	,receive_location
	,receive_status
	,receive_date
order by 
receive_pallet_code desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$receive_pallet_code = $objResult['receive_pallet_code'];
	$tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
	$receive_location = $objResult['receive_location'];
	$receive_status = $objResult['receive_status'];
	$receive_date = $objResult['receive_date'];
	$tags_packing_std = $objResult['sum_pkg_std'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id;?></td>
	  <td align="center">
	  <!--<button type="button" class="btn btn-warning btn-sm" id="<?=$receive_pallet_code;?>" onclick="openRefill(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Refill this Pallet ID"><i class="fa fa-pencil-square-o fa-lg"></i></button>&nbsp;&nbsp;--><button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?=var_encode($receive_pallet_code);?>#####<?=$tags_fg_code_gdj;?>" onclick="openRePrintPalletID(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this Pallet ID</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?=var_encode($receive_pallet_code);?>" onclick="openRePrintAllTagsOnPalletID(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print ALL Tags On Pallet</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip"  id="<?=$receive_pallet_code;?>#####<?=$tags_fg_code_gdj;?>#####<?=$receive_location;?>#####<?=$receive_status;?>#####<?=$receive_date;?>" onclick="openFuncDetails(this.id);"><i class="fa fa-search fa-lg"></i><span class="custom_tooltiptext">View</span></button>
	  </td>
	  <td><?=$receive_pallet_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
	  <td><?=$tags_fg_code_gdj_desc;?></td>
	  <td><?=$receive_location;?></td>
	  <td style="color: indigo;"><?=number_format($tags_packing_std);?></td>
	  <td style="color: green;"><?=$receive_status;?></td>
	  <td><?=$receive_date;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_inventory" id="hdn_row_inventory" value="<?=$row_id;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_inventory').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_inventory').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		
    });
	
});
</script>