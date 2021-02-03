<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_history_paicking" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Picking Code</th>
	  <th>Pallet  Code</th>
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
where ps_h_issue_date between FORMAT (GETDATE() - 7, 'yyyy-MM-dd') and FORMAT (GETDATE(), 'yyyy-MM-dd')
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
	  <td style="text-align: center;"><?=$row_id;?></td>
	  <td align="center">
	  <button type="button" class="btn btn-primary btn-sm" id="<?=var_encode($ps_t_pallet_code);?>" onclick="openRePrintPallet(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Re-Print this Pallet ID"><i class="fa fa-print fa-lg"></i></button>
	  </td>
	  <td><?=$ps_h_picking_code;?></td>
	  <td ><?=$ps_t_pallet_code;?></td>
	  <td><?=$ps_h_cus_code;?></td>
	  <td><?=$ps_t_fg_code_set_abt;?></td>
	  <td><?=$ps_t_sku_code_abt;?></td>
	  <td><?=$ps_t_fg_code_gdj;?></td>
	  <td ><?=$sum_ps_t_tags_packing_std;?></td>
	  <td><?=$ps_t_pj_name;?></td>
	  <?
		if($dn_h_status == 'Confirmed'){
	  ?>
			<td style="color: green;"><?=$dn_h_status;?></td>
		<?
		}else if($dn_h_status == 'Delivery Transfer Note'){ 
		?>
			<td style="color: blue;"><?=$dn_h_status;?></td>
		<?
		} else{
        ?>
			<td style="color: orange;"><?=$ps_h_status;?></td>
		<?
		}
	  ?>
	  
	  <td><?=$ps_h_issue_by;?></td>
	  <td><?=$ps_h_issue_date;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row" id="hdn_row" value="<?=$row_id;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_print_tags').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	jQuery('#tbl_history_paicking').DataTable( {
		rowReorder: true,
        "oLanguage": {
                "sSearch": "Filter Data"
        },
		pagingType: "full_numbers",
    });
	
});
</script>