<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
$date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';

?>
<div class="box-body table-responsive padding">
	<table id="tbl_history_paicking" class="table table-bordered table-hover table-striped nowrap">
		<thead>
			<tr style="font-size: 13px;">
				<th colspan="13" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Picking History</b>&nbsp;<b class="btn" id="excel_export"></b></th>
				<!-- &nbsp;&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button>-->
			</tr>
			<tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="text-align: center;">Actions/Details</th>
				<th>Picking Code</th>
				<th>Pallet Code</th>
				<th>Customer Code</th>
				<th>FG Code Set ABT</th>
				<th>SKU Code</th>
				<th>FG Code GDJ</th>
				<th style="color: indigo;">Qty.(Pcs.)</th>
				<th>Project Name</th>
				<th>Status</th>
				<th>Issue By</th>
				<th>Issue Date</th>
			</tr>
		</thead>
		<tbody>
			<?
$strSql = " select 
[ps_h_picking_code]
,[ps_t_picking_code]
,[ps_t_pallet_code]
,[ps_h_cus_code]
,[ps_h_cus_name]
,[ps_t_fg_code_set_abt]
,[ps_t_sku_code_abt]
,[ps_t_fg_code_gdj]
,SUM(ps_t_tags_packing_std) as qty
,[ps_t_location]
,[ps_t_cus_name]
,[ps_t_pj_name]
,dn_h_status
,ps_h_status
,picking_status
,[ps_h_issue_by]
,[ps_h_issue_date] 
from 
[tbl_picking_head] 
left join  [tbl_picking_tail] on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code 
left join  tbl_dn_tail on	tbl_picking_tail.ps_t_picking_code = tbl_dn_tail.dn_t_picking_code
left join  tbl_dn_head on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code
left join  tbl_picking_running on tbl_picking_head.ps_h_picking_code = tbl_picking_running.picking_code
where ps_h_issue_date between '$date_start' and '$date_end'
group by 
 [ps_h_picking_code]
,[ps_t_picking_code]
,[ps_h_cus_code]
,[ps_h_cus_name]
,[ps_t_pallet_code]
,[ps_t_fg_code_set_abt]
,[ps_t_sku_code_abt]
,[ps_t_fg_code_gdj]
,ps_t_tags_packing_std
,[ps_t_location]
,[ps_t_cus_name]
,[ps_t_pj_name]
,dn_h_status
,ps_h_status
,picking_status
,[ps_h_issue_by]
,[ps_h_issue_date] order by ps_h_issue_date desc "; 

$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;

while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	
	$ps_h_picking_code = $objResult['ps_h_picking_code'];
	$ps_t_picking_code = $objResult['ps_t_picking_code'];
	$ps_h_cus_code = $objResult['ps_h_cus_code'];
	$ps_h_cus_name = $objResult['ps_h_cus_name'];
	$ps_t_pallet_code = $objResult['ps_t_pallet_code'];
	$ps_t_fg_code_set_abt = $objResult['ps_t_fg_code_set_abt'];
	$ps_t_sku_code_abt = $objResult['ps_t_sku_code_abt'];
	$ps_t_fg_code_gdj = $objResult['ps_t_fg_code_gdj'];
	$sum_ps_t_tags_packing_std = $objResult['qty'];
	$ps_t_location = $objResult['ps_t_location'];
	$ps_t_pj_name = $objResult['ps_t_pj_name'];
	$dn_h_status = $objResult['dn_h_status'];
	$ps_h_status = $objResult['ps_h_status'];
	$ps_h_issue_by = $objResult['ps_h_issue_by'];
	$picking_status = $objResult['picking_status'];
	$ps_h_issue_date = $objResult['ps_h_issue_date'];
	
	//check status received
	if(ltrim(rtrim($dn_h_status)) == null)
	{
		
		$dn_h_status = $picking_status;
	}

	if($sum_ps_t_tags_packing_std == null){
		$sum_ps_t_tags_packing_std = 0;
	}
?>
			<tr style="font-size: 13px;">
				<td style="text-align: center;"><?= $row_id; ?></td>
				<td align="center">
					<button type="button" class="btn btn-success btn-sm custom_tooltip" id="<?=var_encode($ps_h_picking_code);?>" onclick="openRePrintTagWithOrderNum(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-print All Tags(With Order Number) on Picking Sheet ID </span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?=var_encode($ps_h_picking_code);?>" onclick="openRePrintTag(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-print All Tags on Picking Sheet ID</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm custom_tooltip" id="<?= var_encode($ps_h_picking_code); ?>" onclick="openRePrintPickingSheet(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this Picking ID</span></button>
				</td>
				<td><?= $ps_h_picking_code; ?></td>
				<td><?= $ps_t_pallet_code; ?></td>
				<td><?= $ps_h_cus_code; ?></td>
				<td><?= $ps_t_fg_code_set_abt; ?></td>
				<td><?= $ps_t_sku_code_abt; ?></td>
				<td><?= $ps_t_fg_code_gdj; ?></td>
				<td><?= $sum_ps_t_tags_packing_std; ?></td>
				<td><?= $ps_t_pj_name; ?></td>
				<?
		if($dn_h_status == 'Confirmed'){
	  ?>
				<td style="color: green;"><?= $dn_h_status; ?></td>
				<?
		}else if($dn_h_status == 'Delivery Transfer Note'){ 
		?>
				<td style="color: blue;"><?= $dn_h_status; ?></td>
				<?
		} else{
        ?>
				<td style="color: orange;"><?= $ps_h_status; ?></td>
				<?
		}
	  ?>

				<td><?= $ps_h_issue_by; ?></td>
				<td><?= $ps_h_issue_date; ?></td>
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
		//search
		/*$('#tbl_print_tags').DataTable({
		  'paging'      : true,
		  'lengthChange': false,
		  'searching'   : false,
		  'ordering'    : true,
		  'info'        : true,
		  'autoWidth'   : false
		});*/

	    var pickingHis = $('#tbl_history_paicking').DataTable({
			rowReorder: true,
			"oLanguage": {
				"sSearch": "Filter Data"
			},
			pagingType: "full_numbers",
		});

		var buttons = new $.fn.dataTable.Buttons(pickingHis, {
			buttons: [{
				extend: 'excel',
				text: '<i class="fa fa-file-excel-o"></i> Export Picking History',
				titleAttr: 'Excel Picking History Report',
				title: 'Excel Picking History Report',
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