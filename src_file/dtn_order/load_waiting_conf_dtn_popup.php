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
$t_ps_h_picking_code = isset($_POST['t_ps_h_picking_code']) ? $_POST['t_ps_h_picking_code'] : '';
$t_ps_h_cus_code = isset($_POST['t_ps_h_cus_code']) ? $_POST['t_ps_h_cus_code'] : '';
$t_ps_h_cus_name = isset($_POST['t_ps_h_cus_name']) ? $_POST['t_ps_h_cus_name'] : '';
$t_ps_t_pj_name = isset($_POST['t_ps_t_pj_name']) ? $_POST['t_ps_t_pj_name'] : '';
$t_ps_h_status = isset($_POST['t_ps_h_status']) ? $_POST['t_ps_h_status'] : '';
$t_ps_h_issue_date = isset($_POST['t_ps_h_issue_date']) ? $_POST['t_ps_h_issue_date'] : '';
?>	
<div class="box-body table-responsive padding">
  <table id="PopupWaitingDTNDetails" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	  <tr style="font-size: 13px;">
		<th style="width: 30px;">No.</th>
		<th>Picking Sheet ID</th>
		<th>Pallet ID</th>
		<th>Tags ID</th>
		<th>FG Code GDJ</th>
		<th>Location</th>
		<th style="color: #00F;">Quantity (Pcs.)</th>
	  </tr>
	  </thead>
	  <tbody>
<?
$strSql_PopupDTNSheetDetails = " 
	SELECT 
	  [ps_t_picking_code]
      ,[ps_t_pallet_code]
      ,[ps_t_tags_code]
	  ,[ps_t_fg_code_gdj]
      ,[ps_t_location]
      ,[ps_t_tags_packing_std]
      ,[ps_t_cus_name]
      ,[ps_t_pj_name]
      ,[ps_t_replenish_unit_type]
      ,[ps_t_terminal_name]
      ,[ps_t_order_type]
      ,[ps_t_status]
      ,[ps_t_issue_date]
  FROM [tbl_picking_tail]
  where
  [ps_t_picking_code] = '$t_ps_h_picking_code'
";

$objQuery_PopupDTNSheetDetails = sqlsrv_query($db_con, $strSql_PopupDTNSheetDetails, $params, $options);
$num_row_PopupDTNSheetDetails = sqlsrv_num_rows($objQuery_PopupDTNSheetDetails);

$row_id_PopupDTNSheetDetails = 0;
while($objResult_PopupDTNSheetDetails = sqlsrv_fetch_array($objQuery_PopupDTNSheetDetails, SQLSRV_FETCH_ASSOC))
{
	$row_id_PopupDTNSheetDetails++;
	
	$ps_t_picking_code = $objResult_PopupDTNSheetDetails['ps_t_picking_code'];
	$ps_t_pallet_code = $objResult_PopupDTNSheetDetails['ps_t_pallet_code'];
	$ps_t_tags_code = $objResult_PopupDTNSheetDetails['ps_t_tags_code'];
	$ps_t_fg_code_gdj = $objResult_PopupDTNSheetDetails['ps_t_fg_code_gdj'];
	$ps_t_location = $objResult_PopupDTNSheetDetails['ps_t_location'];
	$ps_t_tags_packing_std = $objResult_PopupDTNSheetDetails['ps_t_tags_packing_std'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_PopupDTNSheetDetails;?></td>
	  <td><?=$ps_t_picking_code;?></td>
	  <td><?=$ps_t_pallet_code;?></td>
	  <td><?=$ps_t_tags_code;?></td>
	  <td><?=$ps_t_fg_code_gdj;?></td>
	  <td><?=$ps_t_location;?></td>
	  <td style="color: #00F;"><?=$ps_t_tags_packing_std;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_PopupDTNSheetDetails" id="hdn_row_PopupDTNSheetDetails" value="<?=$row_id_PopupDTNSheetDetails;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	$('#PopupWaitingDTNDetails').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": 25,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5,6 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			//if(data[3] == "Confirmed"){
			//	$(row).find('td:eq(3)').css('color', 'Green');
			//}		

		},
    });
	
});
</script>