<?
require_once("../../application.php");
//require_once("../../js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/	
$t_txt_scn_picking_id = isset($_POST['t_txt_scn_picking_id']) ? $_POST['t_txt_scn_picking_id'] : '';
?>	
<div class="box-body table-responsive padding">
  <table id="DTNSheetDetails" class="table_css">
	<thead>
	<tr style="font-size: 18px;">
		<th colspan="8" class="bg-orange"><b><font style="color: #000;"><img src="<?=$CFG->imagedir;?>/home-picking-39800f6b031d1e62ed132e2d15a5ba8e.png" height="32px"> Picking Quality Control</font></b><span style="float: right;"><button type="button" class="btn btn-default btn-sm" onclick="_confirm_pre_picking_qc();"><i class="fa fa-check-circle-o fa-lg"></i> Confirm Picking Quality Control</button>&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_remove_pre_picking_qc()"><i class="fa fa-trash fa-lg"></i> Clear</button></span></th>
	</tr>
	<?
	if($t_txt_scn_picking_id != "")
	{
	?>
	  <tr style="font-size: 13px;">
		<th colspan="8"><div class="col-md-6"><i class="fa fa-qrcode"></i> Scan Tags ID <input type="text" id="txt_qc_scn_tag_id" name="txt_qc_scn_tag_id" onKeyPress="if (event.keyCode==13){ return _onScan_CheckTags(); }" class="form-control input-lg" placeholder="Scan Tags ID" autocomplete="off" autocorrect="off"  spellcheck="false"></div></th>
	  </tr>
	<?
	}
	?>
	  <tr style="font-size: 13px;">
		<th style="width: 30px;">No.</th>
		<th style="width: 80px;">QC Status</th>
		<th>Tags ID/Cover Sheet</th>
		<th>Pallet ID</th>
		<th>FG Code GDJ</th>
		<th>Location</th>
		<th>Part Customer/FG Desc.</th>
		<th style="color: #00F;">Quantity (Pcs.)</th>
	  </tr>
	  </thead>
	  <tbody>
<?
$strSql_PickingQCSheetDetails = " 
SELECT 
[ps_t_picking_code]
,[ps_t_ref_replenish_code]
,[ps_t_pallet_code]
,[ps_t_tags_code]
,[ps_t_fg_code_set_abt]
,[ps_t_sku_code_abt]
,[ps_t_fg_code_gdj]
,[bom_fg_desc]
,[ps_t_location]
,[ps_t_tags_packing_std]
,[ps_t_cus_name]
,[ps_t_pj_name]
,[ps_t_replenish_unit_type]
,[ps_t_replenish_qty_to_pack]
,[ps_t_terminal_name]
,[ps_t_order_type]
,[ps_t_part_customer]
,[ps_t_status]
,[ps_h_qc]
,[ps_t_issue_date]
,[pick_pre_qc_cover_code]
,[pick_pre_qc_tags_code]
FROM [tbl_picking_head]
left join tbl_picking_tail
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
left join tbl_bom_mst
on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
left join tbl_picking_tail_pre_qc
on tbl_picking_tail.ps_t_tags_code = tbl_picking_tail_pre_qc.pick_pre_qc_tags_code
where
[ps_t_picking_code] = '$t_txt_scn_picking_id'
and 
[ps_h_qc] is NULL
and bom_status = 'Active'
GROUP by
[ps_t_picking_code]
,[ps_t_ref_replenish_code]
,[ps_t_pallet_code]
,[ps_t_tags_code]
,[ps_t_fg_code_set_abt]
,[ps_t_sku_code_abt]
,[ps_t_fg_code_gdj]
,[bom_fg_desc]
,[ps_t_location]
,[ps_t_tags_packing_std]
,[ps_t_cus_name]
,[ps_t_pj_name]
,[ps_t_replenish_unit_type]
,[ps_t_replenish_qty_to_pack]
,[ps_t_terminal_name]
,[ps_t_order_type]
,[ps_t_part_customer]
,[ps_t_status]
,[ps_h_qc]
,[ps_t_issue_date]
,[pick_pre_qc_cover_code]
,[pick_pre_qc_tags_code]
order by pick_pre_qc_tags_code asc
";

$objQuery_PickingQCSheetDetails = sqlsrv_query($db_con, $strSql_PickingQCSheetDetails, $params, $options);
$num_row_PickingQCSheetDetails = sqlsrv_num_rows($objQuery_PickingQCSheetDetails);

$row_id_PickingQCSheetDetails = 0;
$str_cal_qty = 0;
$str_check_complete_scan = 0;
if($num_row_PickingQCSheetDetails == 0)
{
?>
	<tr style="font-size: 14px;">
	  <td colspan="8" style="text-align: center;">No data available in table</td>
	</tr>
<?	
}
else
{
	while($objResult_PickingQCSheetDetails = sqlsrv_fetch_array($objQuery_PickingQCSheetDetails, SQLSRV_FETCH_ASSOC))
	{
		$row_id_PickingQCSheetDetails++;
		
		$ps_t_picking_code = $objResult_PickingQCSheetDetails['ps_t_picking_code'];
		$ps_t_pallet_code = $objResult_PickingQCSheetDetails['ps_t_pallet_code'];
		$ps_t_tags_code = $objResult_PickingQCSheetDetails['ps_t_tags_code'];
		$ps_t_fg_code_set_abt = $objResult_PickingQCSheetDetails['ps_t_fg_code_set_abt'];
		$ps_t_sku_code_abt = $objResult_PickingQCSheetDetails['ps_t_sku_code_abt'];
		$ps_t_fg_code_gdj = $objResult_PickingQCSheetDetails['ps_t_fg_code_gdj'];
		$bom_fg_desc = $objResult_PickingQCSheetDetails['bom_fg_desc'];
		$ps_t_location = $objResult_PickingQCSheetDetails['ps_t_location'];
		$ps_t_pj_name = $objResult_PickingQCSheetDetails['ps_t_pj_name'];
		$ps_t_tags_packing_std = $objResult_PickingQCSheetDetails['ps_t_tags_packing_std'];
		$ps_t_replenish_qty_to_pack = $objResult_PickingQCSheetDetails['ps_t_replenish_qty_to_pack'];
		$ps_t_order_type = $objResult_PickingQCSheetDetails['ps_t_order_type'];
		$ps_t_terminal_name = $objResult_PickingQCSheetDetails['ps_t_terminal_name'];
		$ps_t_ref_replenish_code = $objResult_PickingQCSheetDetails['ps_t_ref_replenish_code'];
		$ps_t_part_customer = $objResult_PickingQCSheetDetails['ps_t_part_customer'];
		$pick_pre_qc_cover_code = $objResult_PickingQCSheetDetails['pick_pre_qc_cover_code'];
		$pick_pre_qc_tags_code = $objResult_PickingQCSheetDetails['pick_pre_qc_tags_code'];
		
		//cal
		$str_cal_qty = $str_cal_qty + $ps_t_tags_packing_std;
		
		//qc check
		if(ltrim(rtrim($pick_pre_qc_tags_code)) == "")
		{
			$str_icon = "";
		}
		else
		{
			$str_icon = "fa fa-check-circle-o";
			
			//check complete scan tags id
			$str_check_complete_scan = $str_check_complete_scan + 1;
		}
		
		//concat /
		if($pick_pre_qc_cover_code == "")
		{
			$pick_pre_qc_cover_code = "";
		}
		else
		{
			$pick_pre_qc_cover_code = "/ ".$pick_pre_qc_cover_code;
		}
	?>
		<tr style="font-size: 14px;">
		  <td>
		  <?=$row_id_PickingQCSheetDetails;?>
		  <input type="hidden" name="_chk_pre_picking_qc[]" value="<?=$row_id_PickingQCSheetDetails;?>"/>
		  <input type="hidden" name="hdn_pre_picking_tags_id<?=$row_id_PickingQCSheetDetails;?>" id="hdn_pre_picking_tags_id<?=$row_id_PickingQCSheetDetails;?>" value="<?=$ps_t_tags_code;?>"/>
		  <input type="hidden" name="hdn_pre_picking_iden_rows<?=$row_id_PickingQCSheetDetails;?>" id="hdn_pre_picking_iden_rows<?=$row_id_PickingQCSheetDetails;?>" value="<?=$row_id_PickingQCSheetDetails;?>"/>
		  </td>
		  <td style="text-align: center;"><i class="<?=$str_icon;?>" style="color: green; font-size: 30px;"></i></td>
		  <td><?=$ps_t_tags_code;?><br><?=$pick_pre_qc_cover_code;?></td>
		  <td><?=$ps_t_pallet_code;?></td>
		  <td><?=$ps_t_fg_code_gdj;?></td>
		  <td><?=$ps_t_location;?></td>
		  <td><?=$ps_t_part_customer;?><br>/ <?=$bom_fg_desc;?></td>
		  <td style="color: #000;"><?=$ps_t_tags_packing_std;?></td>
		</tr>
	<?
	}
	?>						
	</tbody>
	<tfoot>
		<tr style="font-size: 14px;">
			<th colspan="7" style="text-align:right; color: #00F;">Total (Pcs.):</th>
			<th style="text-align:left; color: #00F;"><?=$str_cal_qty;?> (<?=$row_id_PickingQCSheetDetails;?> PACK)</th>
		</tr>
	</tfoot>
<?
}
?>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_PickingQCSheetDetails" id="hdn_row_PickingQCSheetDetails" value="<?=$row_id_PickingQCSheetDetails;?>" />
<input type="hidden" name="hdn_row_ChkCompleteScan" id="hdn_row_ChkCompleteScan" value="<?=$str_check_complete_scan;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	<?
	if($num_row_PickingQCSheetDetails != 0)
	{
	?>
	//focus
	$("#txt_qc_scn_tag_id").focus();
	<?
	}
	?>
	
	//toUpperCase
	$('#txt_qc_scn_tag_id').keyup(function() { this.value = this.value.toUpperCase(); });
	
});
</script>