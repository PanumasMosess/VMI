<?
require_once("../../application.php");
//require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/	
$t_pj_name = isset($_POST['t_pj_name']) ? $_POST['t_pj_name'] : '';
$t_txt_scn_driver_id = isset($_POST['t_txt_scn_driver_id']) ? $_POST['t_txt_scn_driver_id'] : '';
$t_txt_scn_dtn_id = isset($_POST['t_txt_scn_dtn_id']) ? $_POST['t_txt_scn_dtn_id'] : '';
?>
<div class="box-body table-responsive padding">
	<table id="DTNSheetDetails" class="table_css">
		<thead>
			<?
	if($t_txt_scn_dtn_id != "")
	{
	?>
			<tr style="font-size: 15px;">
				<th colspan="8">
					<div class="col-md-4"><i class="fa fa-qrcode"></i> Scan Tags ID <input type="text" id="txt_qc_scn_tag_id" name="txt_qc_scn_tag_id" onKeyPress="if (event.keyCode==13){ return _onScan_CheckTags(); }" class="form-control input-lg" placeholder="Scan Tags ID" autocomplete="off" autocorrect="off" spellcheck="false"></div>
				</th>
			</tr>
			<?
	}
	?>
			<tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="width: 80px;">QC Status</th>
				<th>Refill Type</th>
				<th>Refill Unit</th>
				<th>Tags ID</th>
				<th>FG Code GDJ</th>
				<th>Part Customer</th>
				<th style="color: #00F;">Quantity (Pcs.)</th>
			</tr>
		</thead>
		<tbody>
			<?
$strSql_DTNSheetDetails = " 
	SELECT 
	[dn_t_dtn_code]
	,[dn_h_status]
	,[ps_t_picking_code]
	,[ps_t_ref_replenish_code]
	,[ps_t_pallet_code]
	,[ps_t_tags_code]
	,[ps_t_fg_code_gdj]
	,[bom_fg_desc]
	,[bom_ctn_code_normal]
	,[ps_t_part_customer]
	,[ps_t_location]
	,[ps_t_tags_packing_std]
	,[ps_t_cus_name]
	,[ps_t_pj_name]
	,[ps_t_order_type]
	,[ps_t_replenish_unit_type]
	,[ps_t_replenish_qty_to_pack]
	,[ps_t_terminal_name]
	,[ps_t_order_type]
	,[ps_t_status]
	,[ps_t_issue_date]
	,[repn_qc_tags_code]
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
left join
tbl_replenishment_qc
on tbl_picking_tail.ps_t_tags_code = tbl_replenishment_qc.repn_qc_tags_code
and tbl_dn_head.dn_h_driver_code = tbl_replenishment_qc.repn_qc_driver_code
and tbl_dn_head.dn_h_dtn_code = tbl_replenishment_qc.repn_qc_dtn_code
where
[dn_t_dtn_code] = '$t_txt_scn_dtn_id'
and
[dn_h_status] = 'Delivery Transfer Note' 
ORDER BY repn_qc_tags_code DESC, ps_t_tags_code ASC
";

$objQuery_DTNSheetDetails = sqlsrv_query($db_con, $strSql_DTNSheetDetails, $params, $options);
$num_row_DTNSheetDetails = sqlsrv_num_rows($objQuery_DTNSheetDetails);

$row_id_DTNSheetDetails = 0;
$str_cal_qty = 0;
$str_check_complete_scan = 0;
if($num_row_DTNSheetDetails == 0)
{
?>
			<tr style="font-size: 18px;">
				<td colspan="8" style="text-align: center;">No data available in table</td>
			</tr>
			<?	
}
else
{
	while($objResult_DTNSheetDetails = sqlsrv_fetch_array($objQuery_DTNSheetDetails, SQLSRV_FETCH_ASSOC))
	{
		$row_id_DTNSheetDetails++;
		
		$dn_t_dtn_code = $objResult_DTNSheetDetails['dn_t_dtn_code'];
		$ps_t_picking_code = $objResult_DTNSheetDetails['ps_t_picking_code'];
		$ps_t_pallet_code = $objResult_DTNSheetDetails['ps_t_pallet_code'];
		$ps_t_tags_code = $objResult_DTNSheetDetails['ps_t_tags_code'];
		$ps_t_fg_code_gdj = $objResult_DTNSheetDetails['ps_t_fg_code_gdj'];
		$bom_fg_desc = $objResult_DTNSheetDetails['bom_fg_desc'];
		$bom_ctn_code_normal = $objResult_DTNSheetDetails['bom_ctn_code_normal'];
		$repn_unit_type = $objResult_DTNSheetDetails['ps_t_replenish_unit_type'];
		$repn_order_type = $objResult_DTNSheetDetails['ps_t_order_type'];
		$repn_part_customer = $objResult_DTNSheetDetails['ps_t_part_customer'];
		$ps_t_location = $objResult_DTNSheetDetails['ps_t_location'];
		$ps_t_pj_name = $objResult_DTNSheetDetails['ps_t_pj_name'];
		$ps_t_tags_packing_std = $objResult_DTNSheetDetails['ps_t_tags_packing_std'];
		$ps_t_replenish_qty_to_pack = $objResult_DTNSheetDetails['ps_t_replenish_qty_to_pack'];
		$ps_t_order_type = $objResult_DTNSheetDetails['ps_t_order_type'];
		$ps_t_terminal_name = $objResult_DTNSheetDetails['ps_t_terminal_name'];
		$ps_t_ref_replenish_code = $objResult_DTNSheetDetails['ps_t_ref_replenish_code'];
		$repn_qc_tags_code = $objResult_DTNSheetDetails['repn_qc_tags_code'];
		
		//cal
		$str_cal_qty = $str_cal_qty + $ps_t_tags_packing_std;
		
		//qc check
		if(ltrim(rtrim($repn_qc_tags_code)) == "")
		{
			$str_icon = "";
		}
		else
		{
			$str_icon = "fa fa-check-circle-o";
			
			//check complete scan tags id
			$str_check_complete_scan = $str_check_complete_scan + 1;
		}
	?>
			<tr style="height: 60px; font-size: 15px;">
				<td><?= $row_id_DTNSheetDetails; ?></td>
				<td style="text-align: center;"><i class="<?= $str_icon; ?>" style="color: green; font-size: 30px;"></i></td>
				<td><?= $repn_order_type; ?></td>
				<td><?= $repn_unit_type; ?></td>
				<td><?= $ps_t_tags_code; ?></td>
				<td><?= $ps_t_fg_code_gdj; ?></td>
				<td><?= $repn_part_customer; ?><br><?= $bom_fg_desc; ?></td>
				<td style="color: #000;"><?= $ps_t_tags_packing_std; ?></td>
			</tr>
			<?
	}
	?>
		</tbody>
		<tfoot>
			<tr style="height: 30px; font-size: 18px;">
				<th colspan="7" style="text-align:right; color: #00F;">Total (Pcs.):</th>
				<th style="text-align:left; color: #00F;"><?= $str_cal_qty; ?> (<?= $row_id_DTNSheetDetails; ?> PACK)</th>
			</tr>
		</tfoot>
		<?
}
?>
	</table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_DTNSheetDetails" id="hdn_row_DTNSheetDetails" value="<?= $row_id_DTNSheetDetails; ?>" />
<input type="hidden" name="hdn_row_ChkCompleteScan" id="hdn_row_ChkCompleteScan" value="<?= $str_check_complete_scan; ?>" />
<?
/*
if($num_row_DTNSheetDetails != 0)
{
?>
<style type="text/css">
	#pos_fixed {
		position: fixed;
		top: 61%;
		left: 50%;
		margin-right: -50%;
		transform: translate(-50%, -50%);
		z-index: 1000;
		text-align: center;
		opacity: 2.5;
		border-radius: 10px;
		padding: 5px;
		margin: 2px;
	}
</style>
<button id="pos_fixed" onclick="openScanCheck()" type="button" style="width: 350px; padding: 10px;" class="button btn btn-block btn-warning btn-lg"><img src="icons/kindpng_4802869.png" height="40px" style=""><br>
	<font style="color: #00F;">SCAN TAGS ID</font>
</button>
<?
}
*/
?>

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
	$(document).ready(function() {
		/*
	$('#DTNSheetDetails').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[10], [10]],
		"iDisplayLength": 10,
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
		footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
			///////////////////////////
			// Total qty
            total = api
                .column( 6 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 6, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
          

            // Total filtered rows on the selected column (code part added)
            var sumCo11Filtered = display.map(el => data[el][6]).reduce((a, b) => intVal(a) + intVal(b), 0 );
          
            // Update footer
            $( api.column( 6 ).footer() ).html(
                //''+pageTotal.toLocaleString('en') +' ('+ total.toLocaleString('en') +' total) (' + sumCo11Filtered.toLocaleString('en') +' filtered)'
				''+total.toLocaleString('en')+' (<?= $row_id_DTNSheetDetails; ?> PACK)'
            );
		},
		
    });
	*/


		if (<?= $num_row_DTNSheetDetails; ?> != 0) {

			//focus
			$("#txt_qc_scn_tag_id").focus();

		}


		//toUpperCase
		$('#txt_qc_scn_tag_id').keyup(function() {
			this.value = this.value.toUpperCase();
		});

	});
</script>