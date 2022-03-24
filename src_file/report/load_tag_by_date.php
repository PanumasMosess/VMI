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
	<table id="tbl_history_tags" class="table table-bordered table-hover table-striped nowrap">
		<thead>
			<tr style="font-size: 13px;">
				<th colspan="13" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Tag History</b>&nbsp;<b class="btn" id="excel_export"></b></th>
				<!-- &nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button>-->
			</tr>
			<tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="text-align: center;">Actions/Details</th>
				<th>Tag Code</th>
				<th>Tag GDJ FG Code</th>
				<th>Tag GDJ Description</th>
				<th>Tag Project Name</th>
				<th>Tag Production Plan</th>
				<th style="color: indigo;">Tag Packing Quantity (Pcs.)</th>
				<th>Tag Lot</th>
                <th>Tag Trading From</th>
                <th>Tag issue By</th>
				<th>Tag issue Date</th>
				<th>Tag issue Time</th>
			</tr>
		</thead>
		<tbody>
			<?
$strSql = "SELECT [tags_code]
,[tags_fg_code_gdj]
,[tags_fg_code_gdj_desc]
,[tags_project_name]
,[tags_prod_plan]
,[tags_packing_std]
,[tags_total_qty]
,[tags_token]
,[tags_trading_from]
,[tags_issue_by]
,[tags_issue_date]
,[tags_issue_time]
FROM [tbl_tags_running] WHERE  tags_issue_date between '$date_start' and '$date_end' ";  
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;

while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	
    $tags_code = $objResult['tags_code'];
    $tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
    $tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
	$tags_project_name = $objResult['tags_project_name'];
	$tags_prod_plan = $objResult['tags_prod_plan'];
    $tags_packing_std = $objResult['tags_packing_std'];
    $tags_total_qty = $objResult['tags_total_qty'];
    $tags_token = $objResult['tags_token'];
    $tags_trading_from = $objResult['tags_trading_from'];
	$tags_issue_by = $objResult['tags_issue_by'];
	$tags_issue_date = $objResult['tags_issue_date'];
    $tags_issue_time = $objResult['tags_issue_time'];
	
?>
			<tr style="font-size: 13px;">
				<td><?= $row_id; ?></td>
				<td align="center">
					<button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?= var_encode($tags_code); ?>" onclick="openRePrint(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print Tag</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm custom_tooltip" id="<?=var_encode($tags_token);?>" onclick="openRePrintTagLot(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print Tag Lot</span></button>
				</td>
				<td><?= $tags_code; ?></td>
				<td><?= $tags_fg_code_gdj; ?></td>
				<td><?= $tags_fg_code_gdj_desc; ?></td>
				<td><?= $tags_project_name; ?></td>
				<td><?= $tags_prod_plan; ?></td>
				<td style="color: indigo;"><?= $tags_packing_std; ?></td>
				<td><?= $tags_token; ?></td>
				<td><?= $tags_trading_from; ?></td>
				<td><?= $tags_issue_by; ?></td>
                <td><?= $tags_issue_date; ?></td>
				<td><?= date('H:i:s',strtotime($tags_issue_time)); ?></td>
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

		var dtnTable = jQuery('#tbl_history_tags').DataTable({
			rowReorder: true,
			columnDefs: [{
					orderable: true,
					className: 'reorder',
					targets: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
				},
				{
					orderable: false,
					targets: '_all'
				}
			],
			pagingType: "full_numbers",
		});

		var buttons = new $.fn.dataTable.Buttons(dtnTable, {
			buttons: [{
				extend: 'excel',
				text: '<i class="fa fa-file-excel-o"></i> Export Tag History',
				titleAttr: 'Export Tag History Report',
				title: 'Export Tag History Report',
				exportOptions: {
					modifier: {
						page: 'all'
					},
					columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
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