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
	<div class="col-md-6"><i class="fa fa-qrcode"></i> Scan Tags ID.: <input type="text" id="txt_move_scn_tag_id" name="txt_move_scn_tag_id" onKeyPress="if (event.keyCode==13){ return _onScan_TagsID(); }" class="form-control input-sm" placeholder="Scan Tags ID." autocomplete="off" autocorrect="off"  spellcheck="false"></div>
	<div class="col-md-6"><i class="fa fa-qrcode"></i> Scan Pallet ID. (Destination): <input type="text" id="txt_move_scn_new_pallet" name="txt_move_scn_new_pallet" onKeyPress="if (event.keyCode==13){ return _onScan_TagsID_newPallet(); }" class="form-control input-sm" placeholder="Scan Pallet ID. (Destination)" autocomplete="off" autocorrect="off"  spellcheck="false"></div>
</div>
<div class="box-body table-responsive padding">
  <table id="tbl_move_tags" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 18px;">
		<th colspan="8" class="bg-yellow"><b><font style="color: #FFF;"><i class="fa fa-location-arrow fa-lg"></i> Move Tags (Move Tags ID to Pallet)</font></b><span style="float: right;"><button type="button" class="btn btn-default btn-sm" onclick="_remove_pre_move_tags()"><i class="fa fa-trash fa-lg"></i> Clear</button></span></th>
	</tr>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th>Tags ID</th>
	  <th>Pallet ID</th>
	  <th>FG Code GDJ</th>
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
	receive_tags_code
	,receive_pallet_code
	,tags_fg_code_gdj
	,receive_location
	,receive_status
	,receive_date
	,tags_packing_std
FROM tbl_pallet_running
left join tbl_receive
on tbl_pallet_running.pallet_code = tbl_receive.receive_pallet_code
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
receive_status = 'Received'
and
receive_tags_code IN (select tags_move_tags_code from tbl_internal_move_tags where receive_tags_code = tags_move_tags_code and tags_move_by = '$t_cur_user_code_VMI_GDJ')
order by 
receive_tags_code desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id_tags = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id_tags++;

	$receive_tags_code = $objResult['receive_tags_code'];
	$receive_pallet_code = $objResult['receive_pallet_code'];
	$tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
	$receive_location = $objResult['receive_location'];
	$receive_status = $objResult['receive_status'];
	$receive_date = $objResult['receive_date'];
	$tags_packing_std = $objResult['tags_packing_std'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_tags;?>
	  <input type="hidden" name="_chk_pre_tags[]" value="<?=$row_id_tags;?>"/>
	  <input type="hidden" name="hdn_pre_tags_id<?=$row_id_tags;?>" id="hdn_pre_tags_id<?=$row_id_tags;?>" value="<?=$receive_tags_code;?>"/>
	  </td>
	  <td style="color: #000; font-weight: bold;"><?=$receive_tags_code;?></td>
	  <td><?=$receive_pallet_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
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
<input type="hidden" name="hdn_row_tags" id="hdn_row_tags" value="<?=$row_id_tags;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_move_tags').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_move_tags').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": -1,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5,6,7 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		
    });
	
	
	<?
	if($row_id_tags != 0)
	{
	?>
	//focus
	$("#txt_move_scn_tag_id").focus();
	<?
	}
	?>
	
	//toUpperCase
	$('#txt_move_scn_tag_id').keyup(function() { this.value = this.value.toUpperCase(); });
	$('#txt_move_scn_new_pallet').keyup(function() { this.value = this.value.toUpperCase(); });
	
});
</script>