<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
$date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';
?>
<div class="box-body table-responsive padding">
	<table id="tbl_history_dtn" class="table table-bordered table-hover table-striped nowrap">
		<thead>
			<tr style="font-size: 13px;">
				<th colspan="12" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;DTN History</b>&nbsp;<b class="btn" id="excel_export"></b></th>
				<!-- &nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button>-->
			</tr>
			<tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="text-align: center;">Actions/Details</th>
				<th>DTN Sheet ID</th>
				<th>Customer Code</th>
				<th>Customer Name</th>
				<th>Project Name</th>
				<th style="color: indigo;">Quantity (Pcs.)</th>
				<th>Status</th>
				<th>FG Code GDJ</th>
				<th>SKU Code</th>
				<th>Part Customer</th>
				<th>Delivery Date</th>
			</tr>
		</thead>
		<tbody>
			<?
$strSql = " select 
[dn_h_dtn_code]
,[dn_h_cus_code]
,[dn_h_cus_name]
,[dn_h_driver_code]
,[dn_h_delivery_date]
,[dn_h_status]
,[ps_t_fg_code_gdj]
,[ps_t_sku_code_abt]
,[ps_t_part_customer]
,[ps_t_pj_name]
,[dn_h_issue_date]
,[dn_h_issue_datetime]
,sum([tags_packing_std]) as sum_picking_std 
FROM [tbl_dn_head]
left join
tbl_dn_tail
on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
left join
tbl_picking_tail
on tbl_dn_tail.dn_t_picking_code = tbl_picking_tail.ps_t_picking_code
left join
tbl_receive
on tbl_picking_tail.ps_t_tags_code = tbl_receive.receive_tags_code
left join
tbl_tags_running
on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
where dn_h_issue_date between '$date_start'  and  '$date_end'
group by
 [dn_h_dtn_code]
,[dn_h_cus_code]
,[dn_h_cus_name]
,[dn_h_driver_code]
,[dn_h_delivery_date]
,[dn_h_status]
,[ps_t_fg_code_gdj]
,[ps_t_sku_code_abt]
,[ps_t_part_customer]
,[ps_t_pj_name]
,[dn_h_issue_date]
,[dn_h_issue_datetime]
order by [dn_h_issue_datetime] desc ";  
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;

while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	
    $dn_h_dtn_code = $objResult['dn_h_dtn_code'];
    $dn_h_cus_code = $objResult['dn_h_cus_code'];
    $dn_h_cus_name = $objResult['dn_h_cus_name'];
    $dn_h_driver_code = $objResult['dn_h_driver_code'];
    $dn_h_delivery_date = $objResult['dn_h_delivery_date'];
    $dn_h_status = $objResult['dn_h_status'];
	$ps_t_pj_name = $objResult['ps_t_pj_name'];
    $ps_t_part_customer = $objResult['ps_t_part_customer'];
    $dn_h_issue_date = $objResult['dn_h_issue_date'];
	$sum_picking_std = $objResult['sum_picking_std'];
	$ps_t_fg_code_gdj = $objResult['ps_t_fg_code_gdj'];
	$ps_t_sku_code_abt = $objResult['ps_t_sku_code_abt'];
	$ps_t_part_customer = $objResult['ps_t_part_customer'];
	
?>
			<tr style="font-size: 13px;">
				<td><?= $row_id; ?></td>
				<td align="center">
					<button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?= var_encode($dn_h_dtn_code); ?>" onclick="openRePrintDTNSheet(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this DTN Sheet ID</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?= var_encode($dn_h_dtn_code); ?>" onclick="openRePrintDtn(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print By Pallet</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?= $dn_h_dtn_code; ?>#####<?= $dn_h_cus_code; ?>#####<?= $dn_h_cus_name; ?>#####<?= $ps_t_pj_name; ?>#####<?= $dn_h_status; ?>#####<?= $dn_h_delivery_date; ?>" onclick="openFuncDTNSheetDetails(this.id);"><i class="fa fa-search fa-lg"></i><span class="custom_tooltiptext">View</span></button>
				</td>
				<td><?= $dn_h_dtn_code; ?></td>
				<td><?= $dn_h_cus_code; ?></td>
				<td><?= $dn_h_cus_name; ?></td>
				<td><?= $ps_t_pj_name; ?></td>
				<td style="color: indigo;"><?= $sum_picking_std; ?></td>
				<td><?= $dn_h_status; ?></td>
				<td><?= $ps_t_fg_code_gdj; ?></td>
				<td><?= $ps_t_sku_code_abt; ?></td>
				<td><?= $ps_t_part_customer; ?></td>
				<td><?= $dn_h_delivery_date; ?></td>
			</tr>
			<?
}
?>
		</tbody>
	</table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row" id="hdn_row" value="<?= $row_id; ?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
	$(document).ready(function() {

		var dtnTable = jQuery('#tbl_history_dtn').DataTable({
			rowReorder: true,
			columnDefs: [{
					orderable: true,
					className: 'reorder',
					targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
				},
				{
					orderable: false,
					targets: '_all'
				}
			],
			pagingType: "full_numbers",
			rowCallback: function(row, data, index) {
				//status
				if (data[7] == "Received") {
					$(row).find('td:eq(7)').css('color', 'green'); //green
				} else if (data[7] == "Picking") {
					$(row).find('td:eq(7)').css('color', 'gray');
				} else if (data[7] == "Confrim Order") {
					$(row).find('td:eq(7)').css('color', 'gray');
				} else if (data[7] == "Delivery Transfer Note") {
					$(row).find('td:eq(7)').css('color', 'orange');
				} else if (data[7] == "Pick To Use") {
					$(row).find('td:eq(7)').css('color', 'blue');
				} else if (data[7] == "Terminal 01") {
					$(row).find('td:eq(7)').css('color', 'limegreen');
				} else if (data[7] == "Terminal 02") {
					$(row).find('td:eq(7)').css('color', 'limegreen');
				} else if (data[7] == "Terminal 03") {
					$(row).find('td:eq(7)').css('color', 'limegreen');
				} else if (data[7] == "Terminal 04") {
					$(row).find('td:eq(7)').css('color', 'limegreen');
				} else if (data[7] == "Terminal 05") {
					$(row).find('td:eq(7)').css('color', 'limegreen');
				}
			},
		});

		var buttons = new $.fn.dataTable.Buttons(dtnTable, {
			buttons: [{
				extend: 'excel',
				text: '<i class="fa fa-file-excel-o"></i> Export DTN History',
				titleAttr: 'Excel DTN History Report',
				title: 'Excel DTN History Report',
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

		$("#loadding").modal("hide");

	});
</script>