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
<div class="box-header with-border">
	<div class="col-md-6"><i class="fa fa-qrcode"></i> Scan Pallet ID.: <font style="color: red; font-size:12px;">* สแกน Pallet ID ที่ต้องการย้าย</font><input type="text" id="txt_move_scn_pallet_id" name="txt_move_scn_pallet_id" onKeyPress="if (event.keyCode==13){ return _onScan_PalletID(); }" class="form-control input-sm" placeholder="Scan Pallet ID." autocomplete="off" autocorrect="off"  spellcheck="false"></div>
	<div class="col-md-6"><i class="fa fa-qrcode"></i> Scan Location (Destination): <font style="color: red; font-size:12px;">* สแกน Location ที่จะย้ายไป</font> <input type="text" id="txt_move_scn_pallet_location" name="txt_move_scn_pallet_location" onKeyPress="if (event.keyCode==13){ return _onScan_PalletID_newLocation(); }" class="form-control input-sm" placeholder="Scan Location (Destination)" autocomplete="off" autocorrect="off"  spellcheck="false"></div>
</div>
<div class="box-body table-responsive padding">
  <table id="tbl_move_pallet" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 18px;">
		<th colspan="9" class="bg-yellow"><b><font style="color: #FFF;"><i class="fa fa-arrows"></i> Move Pallet </font><font style="color: #FFF; font-size:12px">( ย้ายโลเคชั่นทั้งพาเลท สามารถย้ายได้ครั้งละหลายพาเลท )</font></b><span style="float: right;"><button type="button" class="btn btn-default btn-sm" onclick="_remove_pre_move_pallet();"><i class="fa fa-trash fa-lg"></i> Clear</button></span></th>
	</tr>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Pallet ID</th>
	  <th>FG Code GDJ</th>
	  <th>Project</th>
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
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
	,sum(tags_packing_std) as sum_pkg_std
FROM tbl_pallet_running
left join tbl_receive
on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status = 'Received'
and
receive_pallet_code IN (select pl_move_pallet_code from tbl_internal_move_pallet where receive_pallet_code = pl_move_pallet_code and pl_move_by = '$t_cur_user_code_VMI_GDJ')
group by
	receive_pallet_code
	,tags_fg_code_gdj
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
order by 
receive_pallet_code desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id_pallet = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id_pallet++;

	$receive_pallet_code = $objResult['receive_pallet_code'];
	$tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
	$tags_project_name = $objResult['tags_project_name'];
	$receive_location = $objResult['receive_location'];
	$receive_status = $objResult['receive_status'];
	$receive_date = $objResult['receive_date'];
	$tags_packing_std = $objResult['sum_pkg_std'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_pallet;?>
	  <input type="hidden" name="_chk_pre_pallet[]" value="<?=$row_id_pallet;?>"/>
	  <input type="hidden" name="hdn_pre_pallet_id<?=$row_id_pallet;?>" id="hdn_pre_pallet_id<?=$row_id_pallet;?>" value="<?=$receive_pallet_code;?>"/>
	  </td>
	  <td align="center">
	  <!--<button type="button" class="btn btn-warning btn-sm" id="<?=$receive_pallet_code;?>" onclick="openRefill(this.id)" data-placement="top" data-toggle="tooltip" data-original-title="Refill this Pallet ID"><i class="fa fa-pencil-square-o fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-sm" id="<?=var_encode($receive_pallet_code);?>" onclick="openRePrintPalletID(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Re-Print this Pallet ID"><i class="fa fa-print fa-lg"></i></button>&nbsp;&nbsp;--><button type="button" class="btn btn-info btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="View" id="<?=$receive_pallet_code;?>#####<?=$tags_fg_code_gdj;?>#####<?=$receive_location;?>#####<?=$receive_status;?>#####<?=$receive_date;?>" onclick="openFuncDetails(this.id);"><i class="fa fa-search fa-lg"></i></button>
	  </td>
	  <td style="color: #000; font-weight: bold;"><?=$receive_pallet_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
	  <td><?=$tags_project_name;?></td>
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
<input type="hidden" name="hdn_row_pallet" id="hdn_row_pallet" value="<?=$row_id_pallet;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_move_pallet').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_move_pallet').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": -1,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		
    });
	
	
	<?
	if($row_id_pallet != 0)
	{
	?>
	//focus
	$("#txt_move_scn_pallet_id").focus();
	<?
	}
	?>
	
	//toUpperCase
	$('#txt_move_scn_pallet_id').keyup(function() { this.value = this.value.toUpperCase(); });
	$('#txt_move_scn_pallet_location').keyup(function() { this.value = this.value.toUpperCase(); });
	
});
</script>