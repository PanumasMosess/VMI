<?
 require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_user_session_VMI_GDJ = isset($_SESSION['t_cur_user_session_VMI_GDJ']) ? $_SESSION['t_cur_user_session_VMI_GDJ'] : '';
/*var *****************************************************************************/
$stock_locate = isset($_POST['sel_fj_name']) ? $_POST['sel_fj_name'] : '';
$date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
$date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';

$id_ = $stock_locate."#####".$date_start."#####".$date_end;

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>
<div class="box-body table-responsive padding">
	<table id="tbl_inventory_terminal" class="table table-bordered table-hover table-striped nowrap">
		<thead>
			<tr style="font-size: 18px;">
				<th colspan="11" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Stock Blance</b>&nbsp;<b class="btn" id="excel_export"></b>
				
				&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" id="<?=$id_;?>" onclick="_print_stock_by_tags(this.id);"><i class="fa fa-indent fa-lg"></i> Print Tags Stock</button></th>
			</tr>
			<tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="text-align: center;">Actions/Details</th>
				<th>Delivery Transfer Note</th>
				<th>Tag ID</th>
				<th>Part No</th>
				<th>FG Code GDJ</th>
				<th>FG Code GDJ Desc.</th>
				<th style="color: indigo;">Quantity (Pcs.)</th>
				<th>Status</th>
				<th>Project Name</th>
				<th>Confirmed Date</th>
			</tr>
		</thead>
		<tbody>
			<?
if($stock_locate == "ALL"){

	if($t_cur_user_session_VMI_GDJ == "IT" || $t_cur_user_session_VMI_GDJ == "GDJ"){
		$strSql = "
		SELECT	
	tags_code,
	tags_fg_code_gdj,
	ps_t_tags_packing_std,
	ps_t_part_customer,
	receive_status,
	receive_date,
	dn_h_issue_date,
	tags_fg_code_gdj_desc,
	conf_qc_tags_code,
	dn_h_status,
	dn_h_receive_date,
	ps_t_pj_name,
	dn_t_dtn_code
		
		
	FROM tbl_receive
	left join tbl_tags_running
	on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
	left join tbl_picking_tail
	on tbl_tags_running.tags_code = tbl_picking_tail.ps_t_tags_code
	left join tbl_usage_conf_qc
	on tbl_usage_conf_qc.conf_qc_tags_code = tbl_receive.receive_tags_code
	left join tbl_picking_head
	on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
	left join tbl_dn_tail 
	on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
	left join tbl_dn_head
	on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code
	
	where (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
	receive_status != 'Delivery Transfer Note')  and (dn_h_receive_date between '$date_start' and '$date_end')";
	
	} else {

		$strSql = " 
		SELECT
			
		tags_code,
		tags_fg_code_gdj,
		ps_t_tags_packing_std,
		ps_t_part_customer,
		receive_status,
		receive_date,
		dn_h_issue_date,
		tags_fg_code_gdj_desc,
		conf_qc_tags_code,
		dn_h_status,
		dn_h_receive_date,
		ps_t_pj_name,
		dn_t_dtn_code
			
			
		FROM tbl_receive
		left join tbl_tags_running
		on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
		left join tbl_picking_tail
		on tbl_tags_running.tags_code = tbl_picking_tail.ps_t_tags_code
		left join tbl_usage_conf_qc
		on tbl_usage_conf_qc.conf_qc_tags_code = tbl_receive.receive_tags_code
		left join tbl_picking_head
		on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
		left join tbl_dn_tail 
		on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
		left join tbl_dn_head
		on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code
		
		where (receive_status IN (select bom_pj_name from tbl_bom_mst where bom_cus_code = '$t_cur_user_session_VMI_GDJ' GROUP BY bom_pj_name))  and (dn_h_receive_date between '$date_start' and '$date_end' )";
	}
	

}else{

	$strSql = " 
	SELECT
		
	tags_code,
	tags_fg_code_gdj,
	ps_t_tags_packing_std,
	ps_t_part_customer,
	receive_status,
	receive_date,
	dn_h_issue_date,
	tags_fg_code_gdj_desc,
	conf_qc_tags_code,
	dn_h_status,
	dn_h_receive_date,
	ps_t_pj_name,
	dn_t_dtn_code
		
	
	FROM tbl_receive
	left join tbl_tags_running
	on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
	left join tbl_picking_tail
	on tbl_tags_running.tags_code = tbl_picking_tail.ps_t_tags_code
	left join tbl_usage_conf_qc
	on tbl_usage_conf_qc.conf_qc_tags_code = tbl_receive.receive_tags_code
	left join tbl_picking_head
	on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
	left join tbl_dn_tail 
	on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
	left join tbl_dn_head
	on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code
		
	where receive_status = '$stock_locate'  and (dn_h_receive_date between '$date_start' and '$date_end')
";
}


$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$tag_id = $objResult['tags_code'];
	$tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
	$receive_status = $objResult['receive_status'];
	$confirm_date = $objResult['dn_h_receive_date'];
	$ps_t_tags_packing_std = $objResult['ps_t_tags_packing_std'];
	$ps_t_pj_name = $objResult['ps_t_pj_name'];
	$ps_t_part_customer = $objResult['ps_t_part_customer'];
	$dn_t_dtn_code = $objResult['dn_t_dtn_code'];

	if(strpos($ps_t_part_customer, '-') !== false){
		$usage_part_customer_arr = explode('-', $ps_t_part_customer);
	}else{
		$usage_part_customer_arr[1] = $ps_t_part_customer;
	}
	
?>
			<tr style="font-size: 13px;">
				<td><?= $row_id; ?></td>
				<td align="center">
					<button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?= var_encode($tag_id); ?>" onclick="openRePrintTag(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print Tags</span></button>
				</td>
				<td><?= $dn_t_dtn_code; ?></td>
				<td><?= $tag_id; ?></td>
				<?
				if($ps_t_part_customer == NULL)
				{
				?>
					<td>Old Data( < 2020-10)</td>
				<?
				}else{
				?>
					<td><?= $usage_part_customer_arr[1]; ?></td>
				<?
				}
				?>
				<td><?= $tags_fg_code_gdj; ?></td>
				<td><?= $tags_fg_code_gdj_desc; ?></td>
				<?if($ps_t_tags_packing_std == NULL)
				{
				?>
					<td>0</td>
				<?
				}else{
				?>
					<td style="color: indigo;"><?= number_format($ps_t_tags_packing_std); ?></td>
				<?
				}
				?>				
				<td style="color: green;"><?= $receive_status; ?></td>
				<?
				if($ps_t_pj_name == NULL)
				{
				?>
					<td>Old Data( < 2020-10)</td>
				<?
				}else{
				?>
					<td><?= $ps_t_pj_name; ?></td>
				<?
				}
				?>

				<?
				if($confirm_date == NULL)
				{
				?>
					<td>2020-10-01</td>
				<?
				}else{
				?>
					<td><?= $confirm_date; ?></td>
				<?
				}
				?>
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
		var blanceTable = jQuery('#tbl_inventory_terminal').DataTable({
			rowReorder: true,
			"oLanguage": {
				"sSearch": "Filter Data"
			},
			// columnDefs: [
			//     { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
			//     { orderable: false, targets: '_all' }
			// ],
			pagingType: "full_numbers",

		});

		// jQuery.fn.dataTable.ext.search.push(
		// 	function(settings, data, dataIndex) {
		// 		var min = $('#min_replenish').datepicker('getDate');
		// 		var max = $('#max_replenish').datepicker('getDate');
		// 		if(max == null){
        //             max = new Date();
        //         }   
		// 		max.setDate(max.getDate() + 1);
		// 		var startDate = new Date(data[9]);
		// 		if (min == null && max == null) return true;
		// 		if (min == null && startDate <= max) return true;
		// 		if (max == null && startDate >= min) return true;
		// 		if (startDate <= max && startDate >= min) return true;
		// 		return false;
		// 	}
		// );

		var buttons = new $.fn.dataTable.Buttons(blanceTable, {
			buttons: [{
				extend: 'excel',
				text: '<i class="fa fa-file-excel-o"></i> Export Stock Blance',
				titleAttr: 'Excel Stock Blance Report',
				title: 'Excel Stock Blance Report',
				exportOptions: {
					modifier: {
						page: 'all'
					},
					columns: [2, 3, 4, 5, 6, 7, 8, 9, 10]
				}
			}],
			dom: {
				button: {
					tag: 'button',
					className: 'btn btn-default'
				}
			},
		}).container().appendTo($('#excel_export'));

		$("#loadding").modal("hide");

		// $('#min_replenish').datepicker({
		// 	autoclose: true,
		// 	format: 'yyyy-mm-dd',
		// 	onSelect: function() {
		// 		blanceTable.draw();
		// 	},
		// 	changeMonth: true,
		// 	changeYear: true
		// });
		// $('#max_replenish').datepicker({
		// 	autoclose: true,
		// 	format: 'yyyy-mm-dd',
		// 	onSelect: function() {
		// 		blanceTable.draw();
		// 	},
		// 	changeMonth: true,
		// 	changeYear: true,
					
		// });

		// // Event listener to the two range filtering inputs to redraw on input
		// $('#min_replenish, #max_replenish').change(function() {
		// 	blanceTable.draw();
		// });
	});
</script>