<?
require_once("../../application.php");
require_once("../../get_authorized.php");
require_once("../../js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$t_receive_pallet_code = isset($_POST['t_receive_pallet_code']) ? $_POST['t_receive_pallet_code'] : '';
$t_tags_fg_code_gdj = isset($_POST['t_tags_fg_code_gdj']) ? $_POST['t_tags_fg_code_gdj'] : '';
$t_receive_location = isset($_POST['t_receive_location']) ? $_POST['t_receive_location'] : '';
$t_receive_status = isset($_POST['t_receive_status']) ? $_POST['t_receive_status'] : '';
$t_receive_date = isset($_POST['t_receive_date']) ? $_POST['t_receive_date'] : '';
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_picking_order_details" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	  <tr style="font-size: 13px;">
		<th style="width: 30px;">No.</th>
		<th>Pallet ID</th>
		<th>Tags ID</th>
		<th>FG Code GDJ</th>
		<th>Project</th>
		<th>Location</th>
		<th style="color: indigo;">Quantity (Pcs.)</th>
	  </tr>
	  </thead>
	  <tbody>
<?
$strSql_picking_order_details = " 
SELECT 
	receive_pallet_code
	,receive_tags_code
	,tags_fg_code_gdj
	,tags_project_name
	,receive_location
	,receive_status
	,receive_date
	,tags_packing_std
FROM tbl_receive
left join tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where
((receive_status = 'Received') or (receive_status = 'Sinbin'))
and
receive_pallet_code = '$t_receive_pallet_code'
and
tags_fg_code_gdj = '$t_tags_fg_code_gdj'
and 
receive_date = '$t_receive_date'
order by 
receive_pallet_code desc
,receive_tags_code desc
";

$objQuery_picking_order_details = sqlsrv_query($db_con, $strSql_picking_order_details, $params, $options);
$num_row_picking_order_details = sqlsrv_num_rows($objQuery_picking_order_details);

$row_id_picking_order_details = 0;
while($objResult_picking_order_details = sqlsrv_fetch_array($objQuery_picking_order_details, SQLSRV_FETCH_ASSOC))
{
	$row_id_picking_order_details++;
	
	$receive_pallet_code = $objResult_picking_order_details['receive_pallet_code'];
	$receive_tags_code = $objResult_picking_order_details['receive_tags_code'];
	$tags_fg_code_gdj = $objResult_picking_order_details['tags_fg_code_gdj'];
	$tags_project_name = $objResult_picking_order_details['tags_project_name'];
	$receive_location = $objResult_picking_order_details['receive_location'];
	$tags_packing_std = $objResult_picking_order_details['tags_packing_std'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_picking_order_details;?></td>
	  <td><?=$receive_pallet_code;?></td>
	  <td><?=$receive_tags_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
	  <td><?=$tags_project_name;?></td>
	  <td><?=$receive_location;?></td>
	  <td style="color: indigo;"><?=$tags_packing_std;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_picking_order_details" id="hdn_row_picking_order_details" value="<?=$row_id_picking_order_details;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	$('#tbl_picking_order_details').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": 25,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5,6 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		
    });
	
});
</script>