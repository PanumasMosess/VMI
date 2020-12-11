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
$t_fg_code_set_abt = isset($_POST['t_fg_code_set_abt']) ? $_POST['t_fg_code_set_abt'] : '';
$t_sku_code_abt = isset($_POST['t_sku_code_abt']) ? $_POST['t_sku_code_abt'] : '';
$t_fg_code_gdj = isset($_POST['t_fg_code_gdj']) ? $_POST['t_fg_code_gdj'] : '';
$t_pj_name = isset($_POST['t_pj_name']) ? $_POST['t_pj_name'] : '';
$t_ship_type = isset($_POST['t_ship_type']) ? $_POST['t_ship_type'] : '';
$t_part_customer = isset($_POST['t_part_customer']) ? $_POST['t_part_customer'] : '';
$t_conv_pack_qty = isset($_POST['t_conv_pack_qty']) ? $_POST['t_conv_pack_qty'] : '';
$t_repn_id = isset($_POST['t_repn_id']) ? $_POST['t_repn_id'] : '';
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_picking_order_details" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	  <tr style="font-size: 13px;">
		<th style="width: 30px;">No.</th>
		<th>Pallet ID</th>
		<th>Tags ID</th>
		<th>FG Code GDJ</th>
		<th>Location</th>
		<th style="color: indigo;">Quantity (Pcs.)</th>
	  </tr>
	  </thead>
	  <tbody>
<?
$strSql_picking_order_details = " 
select top $t_conv_pack_qty 
	  [receive_tags_code]
      ,[receive_pallet_code]
      ,[receive_location]
      ,[tags_fg_code_gdj]
      ,[tags_fg_code_gdj_desc]
	  ,[repn_fg_code_set_abt]
      ,[repn_sku_code_abt]
      ,[repn_pj_name]
	  ,[tags_packing_std]
	  ,[receive_date]
	  from tbl_replenishment 
left join tbl_bom_mst 
on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
left join tbl_tags_running
on tbl_bom_mst.bom_fg_code_gdj = tbl_tags_running.tags_fg_code_gdj
left join tbl_receive
on tbl_tags_running.tags_code = tbl_receive.receive_tags_code
where
repn_conf_status = 'Confirmed'
and
receive_status = 'Received'
and 
receive_repn_id = '$t_repn_id'
and
repn_fg_code_set_abt = '$t_fg_code_set_abt'
and
repn_sku_code_abt = '$t_sku_code_abt'
and
bom_fg_code_gdj = '$t_fg_code_gdj'
and
bom_pj_name = '$t_pj_name'
and
bom_ship_type = '$t_ship_type'
and
bom_part_customer = '$t_part_customer'
group by
 [receive_tags_code]
      ,[receive_pallet_code]
      ,[receive_location]
      ,[tags_fg_code_gdj]
      ,[tags_fg_code_gdj_desc]
	  ,[repn_fg_code_set_abt]
      ,[repn_sku_code_abt]
      ,[repn_pj_name]
	  ,[tags_packing_std]
	  ,[receive_date]
order by 
receive_date asc
,SUBSTRING(receive_pallet_code,3,10) asc
,receive_tags_code asc
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
	$receive_location = $objResult_picking_order_details['receive_location'];
	$tags_packing_std = $objResult_picking_order_details['tags_packing_std'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_picking_order_details;?></td>
	  <td><?=$receive_pallet_code;?></td>
	  <td><?=$receive_tags_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
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
            { orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5 ] },
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