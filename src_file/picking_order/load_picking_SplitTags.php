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
$t_repn_qty = isset($_POST['t_repn_qty']) ? $_POST['t_repn_qty'] : '';
$t_repn_id = isset($_POST['t_repn_id']) ? $_POST['t_repn_id'] : '';

settype($str_total_qty_c, "integer");
// while($str_total_qty_c < $t_repn_qty){

// $str_total_qty_c = 0;
// //Check top select
// $strSql_split_tags_c = " 
// select top $t_conv_pack_qty
// 	  [receive_tags_code]
//       ,[receive_pallet_code]
//       ,[receive_location]
//       ,[tags_fg_code_gdj]
//       ,[tags_fg_code_gdj_desc]
// 	  ,[repn_fg_code_set_abt]
//       ,[repn_sku_code_abt]
//       ,[repn_pj_name]
// 	  ,[tags_packing_std]
// 	  ,[receive_date]
// 	  from tbl_replenishment 
// left join tbl_bom_mst 
// on tbl_replenishment.repn_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
// and tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
// and tbl_replenishment.repn_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
// and tbl_replenishment.repn_pj_name = tbl_bom_mst.bom_pj_name
// and tbl_replenishment.repn_ship_type = tbl_bom_mst.bom_ship_type
// and tbl_replenishment.repn_part_customer = tbl_bom_mst.bom_part_customer
// left join tbl_tags_running
// on tbl_bom_mst.bom_fg_code_gdj = tbl_tags_running.tags_fg_code_gdj
// left join tbl_receive
// on tbl_tags_running.tags_code = tbl_receive.receive_tags_code
// where
// repn_conf_status = 'Confirmed'
// and
// receive_status = 'Received'
// and 
// receive_repn_id = '$t_repn_id'
// and
// repn_fg_code_set_abt = '$t_fg_code_set_abt'
// and
// repn_sku_code_abt = '$t_sku_code_abt'
// and
// bom_fg_code_gdj = '$t_fg_code_gdj'
// and
// bom_pj_name = '$t_pj_name'
// and
// bom_ship_type = '$t_ship_type'
// and
// bom_part_customer = '$t_part_customer'
// and 
// bom_status = 'Active'
// group by
//  [receive_tags_code]
//       ,[receive_pallet_code]
//       ,[receive_location]
//       ,[tags_fg_code_gdj]
//       ,[tags_fg_code_gdj_desc]
// 	  ,[repn_fg_code_set_abt]
//       ,[repn_sku_code_abt]
//       ,[repn_pj_name]
// 	  ,[tags_packing_std]
// 	  ,[receive_date]
// order by 
// receive_date asc
// ,SUBSTRING(receive_pallet_code,3,10) asc
// ,receive_tags_code asc
// ";

// // and 
// // receive_repn_id = '$t_repn_id'

// $objQuery_split_tags_c = sqlsrv_query($db_con, $strSql_split_tags_c, $params, $options);
// $num_row_split_tags_c = sqlsrv_num_rows($objQuery_split_tags_c);

// $str_total_qty_c= 0;

// while($objResult_split_tags_c = sqlsrv_fetch_array($objQuery_split_tags_c, SQLSRV_FETCH_ASSOC))
// {
	
// 	$tags_packing_std_C = $objResult_split_tags_c['tags_packing_std'];
	
// 	//sum total qty
// 	$str_total_qty_c = $str_total_qty_c + $tags_packing_std_C;
// }

// if($str_total_qty_c < $t_repn_qty){
// 	$t_conv_pack_qty++;
// }

// }

?>	

<div class="box-body table-responsive padding">
  <table id="tbl_picking_splitTags" class="table table-bordered table-hover table-striped nowrap">
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
//receive_status for case split tags (reserve)
$strSql_split_tags = " 
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
and
bom_status = 'Active'
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

// and 
// receive_repn_id = '$t_repn_id'

$objQuery_split_tags = sqlsrv_query($db_con, $strSql_split_tags, $params, $options);
$num_row_split_tags = sqlsrv_num_rows($objQuery_split_tags);

$row_id_split_tags = 0;
$str_total_qty= 0;

while($objResult_split_tags = sqlsrv_fetch_array($objQuery_split_tags, SQLSRV_FETCH_ASSOC))
{
	$row_id_split_tags++;
	
	$receive_pallet_code = $objResult_split_tags['receive_pallet_code'];
	$receive_tags_code = $objResult_split_tags['receive_tags_code'];
	$tags_fg_code_gdj = $objResult_split_tags['tags_fg_code_gdj'];
	$receive_location = $objResult_split_tags['receive_location'];
	$tags_packing_std = $objResult_split_tags['tags_packing_std'];
	
	//sum total qty
	$str_total_qty = $str_total_qty + $tags_packing_std;
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_split_tags;?></td>
	  <td><?=$receive_pallet_code;?></td>
	  <td><?=$receive_tags_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
	  <td><?=$receive_location;?></td>
	  <td style="color: indigo;"><?=$tags_packing_std;?></td>
	</tr>
<?
}

//diff 
$str_split_tags = $str_total_qty - $t_repn_qty;

// //check case
// if($str_total_qty < $t_repn_qty)
// {

// }
// else
// {
	
// }
?>						
	</tbody>
  </table>
  <font style="color: #00F"><hr>
  <b><i class="fa fa-caret-right"></i> Summary</b><br></font>
  <font style="color: #000"><i class="fa fa-angle-double-right"></i> Order Qty.: <?=$t_repn_qty;?> Pcs.<br>
  <i class="fa fa-angle-double-right"></i> FIFO Picking Total Qty.: <?=$str_total_qty;?> Pcs.<br></font>
  <font style="color: #F00"><i class="fa fa-angle-double-right"></i> Auto Split Tags Total Qty.: <?=$str_split_tags;?> Pcs.<br>
  </font>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_split_tags" id="hdn_row_split_tags" value="<?=$row_id_split_tags;?>" />
<input type="hidden" name="hdn_t_repn_id" id="hdn_t_repn_id" value="<?=$t_repn_id;?>" />
<input type="hidden" name="hdn_split_tags_qty" id="hdn_split_tags_qty" value="<?=$str_split_tags;?>" />


<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	$('#tbl_picking_splitTags').DataTable( {
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