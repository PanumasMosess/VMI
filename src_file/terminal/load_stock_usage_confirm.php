<?
 require_once("../../application.php");
 //require_once("../../js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_user_session_VMI_GDJ = isset($_SESSION['t_cur_user_session_VMI_GDJ']) ? $_SESSION['t_cur_user_session_VMI_GDJ'] : '';

/*var *****************************************************************************/
$stock_locate = isset($_POST['sel_fj_name']) ? $_POST['sel_fj_name'] : '';
$date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
$date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>
<div class="box-body table-responsive padding">
	<table id="tbl_inventory_terminal" class="table table-bordered table-hover table-striped nowrap">
		<thead>
			<tr style="font-size: 18px;">
				<th colspan="13" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Usage Confirm</b>&nbsp;<b class="btn" id="excel_export"></b>
				<!-- &nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button></th> -->
			</tr>
			<tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="text-align: center;">Actions/Details</th>
				<th>Tag ID</th>
				<th>Part No</th>
				<th>FG Code GDJ</th>
				<th>FG Code GDJ Desc.</th>
				<th style="color: indigo;">Quantity (Pcs.)</th>
				<th>Status</th>
				<th>Confirmed Date</th>
				<th>Project Name</th>
				<th>User Pick</th>
				<th>Pick Date</th>
				<th>Pick Time</th>
			</tr>
		</thead>
		<tbody>
			<?
			if($stock_locate == "ALL"){
				if($t_cur_user_session_VMI_GDJ == "TI" || $t_cur_user_session_VMI_GDJ == "GDJ"){
					$strSql = " 
					SELECT       
					ps_t_tags_code,
					bom_pj_name,
					ps_t_fg_code_gdj,
					bom_fg_desc,
					ps_t_tags_packing_std,
					dn_h_status,
					dn_h_receive_date,
					receive_status,
					usage_pick_by,
					usage_pick_date,
					usage_pick_time,
					bom_part_customer
					
					FROM tbl_dn_head
					left join tbl_dn_tail 
					on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
					left join tbl_picking_head
					on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
					left join tbl_picking_tail
					on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
					left join tbl_bom_mst 
					on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
					and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
					and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
					and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
					and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
					and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
					left join tbl_receive
					on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
					left join tbl_usage_conf
					on tbl_usage_conf.usage_tags_code = tbl_picking_tail.ps_t_tags_code
					where
					dn_h_status = 'Confirmed'  and receive_status = 'USAGE CONFIRM' and (usage_pick_date between '$date_start' and '$date_end')
					order by usage_pick_date desc
					";	
				}else {

					$strSql = " 
					SELECT       
					ps_t_tags_code,
					bom_pj_name,
					ps_t_fg_code_gdj,
					bom_fg_desc,
					ps_t_tags_packing_std,
					dn_h_status,
					dn_h_receive_date,
					receive_status,
					usage_pick_by,
					usage_pick_date,
					usage_pick_time,
					bom_part_customer
					
					FROM tbl_dn_head
					left join tbl_dn_tail 
					on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
					left join tbl_picking_head
					on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
					left join tbl_picking_tail
					on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
					left join tbl_bom_mst 
					on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
					and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
					and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
					and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
					and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
					and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
					left join tbl_receive
					on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
					left join tbl_usage_conf
					on tbl_usage_conf.usage_tags_code = tbl_picking_tail.ps_t_tags_code
					where
					(bom_pj_name IN (select bom_pj_name from tbl_bom_mst where bom_cus_code = '$t_cur_user_session_VMI_GDJ' GROUP BY bom_pj_name)) and
					dn_h_status = 'Confirmed'  and receive_status = 'USAGE CONFIRM' and (usage_pick_date between '$date_start' and '$date_end')
					order by usage_pick_date desc
					";	
				}							

			}else{
				$strSql = " 
SELECT       
ps_t_tags_code,
bom_pj_name,
ps_t_fg_code_gdj,
bom_fg_desc,
ps_t_tags_packing_std,
dn_h_status,
dn_h_receive_date,
receive_status,
usage_pick_by,
usage_pick_date,
usage_pick_time,
bom_part_customer

FROM tbl_dn_head
left join tbl_dn_tail 
on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
left join tbl_picking_head
on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
left join tbl_picking_tail
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
left join tbl_bom_mst 
on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
left join tbl_receive
on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
left join tbl_usage_conf
on tbl_usage_conf.usage_tags_code = tbl_picking_tail.ps_t_tags_code
where
dn_h_status = 'Confirmed' and bom_pj_name = '$stock_locate' and receive_status = 'USAGE CONFIRM' and (usage_pick_date between '$date_start' and '$date_end')
order by usage_pick_date desc
";
			}


$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$tag_id = $objResult['ps_t_tags_code'];
	$tags_fg_code_gdj = $objResult['ps_t_fg_code_gdj'];
	$tags_fg_code_gdj_des = $objResult['bom_fg_desc'];
	$confirm_status = $objResult['receive_status'];
	$confirm_date = $objResult['usage_pick_date'];
    $tags_packing_std = $objResult['ps_t_tags_packing_std'];
	$bom_pj_name = $objResult['bom_pj_name'];
    $user_pick = $objResult['usage_pick_by'];
    $user_pick_date = $objResult['usage_pick_date'];
    $user_pick_time = $objResult['usage_pick_time'];
	$bom_part_customer = $objResult['bom_part_customer'];

	if(strpos($bom_part_customer, '-') !== false){
		$usage_part_customer_arr = explode('-', $bom_part_customer);
	}else{
		$usage_part_customer_arr[1] = $bom_part_customer;
	}

    $user_pick_time_2 = new DateTime($user_pick_time);
    $str_dif = $user_pick_time_2->format('H:i:s');
    
?>
			<tr style="font-size: 13px;">
				<td><?= $row_id; ?></td>
				<td align="center">
					<button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?= var_encode($tag_id); ?>" onclick="openRePrintTag(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print Tags</span></button>
				</td>
				<td><?= $tag_id; ?></td>
				<td><?= $usage_part_customer_arr[1]; ?></td>
				<td><?= $tags_fg_code_gdj; ?></td>
				<td><?= $tags_fg_code_gdj_des; ?></td>
				<td style="color: indigo;"><?= number_format($tags_packing_std); ?></td>
				<td style="color: green;"><?= $confirm_status; ?></td>
				<td><?= $user_pick_date; ?></td>
				<td><?= $bom_pj_name; ?></td>
				<td><?= $user_pick; ?></td>
				<td><?= $user_pick_date; ?></td>
				<td><?= $str_dif; ?></td>
			</tr>
			<?
}
?>
		</tbody>
	</table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_inventory" id="hdn_row_inventory" value="<?= $row_id; ?>" />

<?
 require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
	$(document).ready(function() {
		// <!--datatable search paging-->
		var conTable = jQuery('#tbl_inventory_terminal').DataTable({
			rowReorder: true,			
			"oLanguage": {
				"sSearch": "Filter Data",			
			},
			// columnDefs: [
			//     { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
			//     { orderable: false, targets: '_all' }
			// ],
			pagingType: "full_numbers",

		});

		// $('#min_con').datepicker({
		// 	autoclose: true,
		// 	format: 'yyyy-mm-dd',
		// 	onSelect: function() {
		// 		conTable.draw();
		// 	},
		// 	changeMonth: true,
		// 	changeYear: true
		// });

		// $('#max_con').datepicker({
		// 	autoclose: true,
		// 	format: 'yyyy-mm-dd',
		// 	onSelect: function() {
		// 		conTable.draw();
		// 	},
		// 	changeMonth: true,
		// 	changeYear: true
		// });

		// jQuery.fn.dataTable.ext.search.push(
		// 	function(settings, data, dataIndex) {
		// 		var min = $('#min_con').datepicker('getDate');
		// 		var max = $('#max_con').datepicker('getDate');
		// 		if(max == null){
        //             max = new Date();
        //         }   
		// 		max.setDate(max.getDate()+1); 
		// 		var startDate = new Date(data[8]);
		// 		if (min == null && max == null) return true;
		// 		if (min == null && startDate <= max) return true;
		// 		if (max == null && startDate >= min) return true;
		// 		if (startDate <= max && startDate >= min) return true;
		// 		return false;
		// 	}
		// );

		$("#loadding").modal("hide"); 

		

		// Event listener to the two range filtering inputs to redraw on input
		// $('#min_con, #max_con').change(function() {
		// 	conTable.draw();
		// });

		var buttons = new $.fn.dataTable.Buttons(conTable, {
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Export Usage Confirm',
                titleAttr: 'Excel Usage Confirm Report',
                title: 'Excel Usage Confirm Report',
                exportOptions: {
                    modifier: {
                        page: 'all'
                    },
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                }
            }],
            dom: {
                button: {
                    tag: 'button',
                    className: 'btn btn-default'
                }
            },
        }).container().appendTo($('#excel_export'));		

	});
</script>