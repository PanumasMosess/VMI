<?
require_once("../../application.php");
require_once("../../js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';


/*var *****************************************************************************/
$stock_locate = isset($_POST['sel_fj_name']) ? $_POST['sel_fj_name'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>
<div class="box-body table-responsive padding">
	<div class="row">
		<div class="form-group col-md-3">
			<label>From Date:</label>
			<div class="input-group date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input type="text" class="form-control pull-right" id="min_replenish" name="min_replenish">
			</div>
			<!-- /.input group -->
		</div>
		<div class="form-group col-md-3">
			<label>From Date:</label>
			<div class="input-group date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input type="text" class="form-control pull-right" id="max_replenish" name="max_replenish">
			</div>
			<!-- /.input group -->
		</div>
	</div>
	<table id="tbl_inventory_terminal" class="table table-bordered table-hover table-striped nowrap">
		<thead>
			<tr style="font-size: 18px;">
				<th colspan="9" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Stock Replnishment</b>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button></th>
			</tr>
			<tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="text-align: center;">Actions/Details</th>
				<th>Tag ID</th>
				<th>FG Code GDJ</th>
				<th>FG Code GDJ Desc.</th>
				<th style="color: indigo;">Quantity (Pcs.)</th>
				<th>Status</th>
				<th>Confirmed Date</th>
			</tr>
		</thead>
		<tbody>
			<?
$strSql = " 
SELECT

ps_t_tags_code,
ps_t_fg_code_gdj,
bom_fg_desc,
ps_t_tags_packing_std,
dn_h_status,
dn_h_receive_date,
receive_status

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
where
dn_h_status = 'Confirmed' and bom_pj_name = '$stock_locate'  and  receive_status != 'USAGE CONFIRM'
order by dn_h_receive_date desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$tag_id = $objResult['ps_t_tags_code'];
	$tags_fg_code_gdj = $objResult['ps_t_fg_code_gdj'];
	$tags_fg_code_gdj_des = $objResult['bom_fg_desc'];
	$confirm_status = $objResult['dn_h_status'];
	$confirm_date = $objResult['dn_h_receive_date'];
	$tags_packing_std = $objResult['ps_t_tags_packing_std'];
?>
			<tr style="font-size: 13px;">
				<td><?= $row_id; ?></td>
				<td align="center">
					<button type="button" class="btn btn-primary btn-sm" id="<?= var_encode($tag_id); ?>" onclick="openRePrintTag(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Re-Print ALL Tags On Pallet"><i class="fa fa-print fa-lg"></i></button>
				</td>
				<td><?= $tag_id; ?></td>
				<td><?= $tags_fg_code_gdj; ?></td>
				<td><?= $tags_fg_code_gdj_des; ?></td>
				<td style="color: indigo;"><?= number_format($tags_packing_std); ?></td>
				<td style="color: green;"><?= $confirm_status; ?></td>
				<td><?= $confirm_date; ?></td>
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
require_once("../../js_css_footer.php");
?>

<script language="javascript">
	$(document).ready(function() {
		// <!--datatable search paging-->
		var replnishmentTable = $('#tbl_inventory_terminal').DataTable({
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
		$.fn.dataTable.ext.search.push(
			function(settings, data, dataIndex) {
				var min = $('#min_replenish').datepicker('getDate');
				var max = $('#max_replenish').datepicker('getDate');
				var startDate = new Date(data[7]);
				if (min == null && max == null) return true;
				if (min == null && startDate <= max) return true;
				if (max == null && startDate >= min) return true;
				if (startDate <= max && startDate >= min) return true;
				return false;
			}
		);



		$('#min_replenish').datepicker({
			autoclose: true,
			format: 'yyyy-mm-dd',
			onSelect: function() {
				replnishmentTable.draw();
			},
			changeMonth: true,
			changeYear: true
		});
		$('#max_replenish').datepicker({
			autoclose: true,
			format: 'yyyy-mm-dd',
			onSelect: function() {
				replnishmentTable.draw();
			},
			changeMonth: true,
			changeYear: true
		});

		// Event listener to the two range filtering inputs to redraw on input
		$('#min_replenish, #max_replenish').change(function() {
			replnishmentTable.draw();
		});
	});
</script>