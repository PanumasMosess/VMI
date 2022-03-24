<?
require_once("../../application.php");
require_once("../../js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>
<div class="box-header with-border">
	<div class="col-md-6"><i class="fa fa-qrcode"></i> Scan Tags ID.: <input type="text" id="txt_scn_put_tag_id" name="txt_scn_put_tag_id" onKeyPress="if (event.keyCode==13){ return _onScan_put_TagsID(); }" class="form-control input-sm" placeholder="Scan Tags ID." autocomplete="off" autocorrect="off"  spellcheck="false"></div>
	<div class="col-md-6"><i class="fa fa-qrcode"></i> Scan Pallet ID.: <input type="text" id="txt_scn_put_pallet" name="txt_scn_put_pallet" onKeyPress="if (event.keyCode==13){ return _onScan_TagsID_put_Pallet(); }" class="form-control input-sm" placeholder="Scan Pallet ID." autocomplete="off" autocorrect="off"  spellcheck="false"></div>
</div>
<div class="box-body table-responsive padding">
  <table id="tbl_pre_put_tags" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 18px;">
		<th colspan="7" class="bg-green"><b><font style="color: #FFF;"><i class="fa fa-plus fa-lg"></i> Put-Away Tags On Pallet</font></b><span style="float: right;"><button type="button" class="btn btn-default btn-sm" onclick="_remove_pre_puaway_tags()"><i class="fa fa-trash fa-lg"></i> Clear</button></span></th>
	</tr>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th>Tags ID</th>
	  <th>FG Code GDJ</th>
	  <th>Project</th>
	  <th>Description</th>
	  <th>Customer Code</th>
	  <th>Packing STD Qty.(Pcs.)</th>
	</tr>
	</thead>
	<tbody>
<?
$strSql = " 
SELECT *  FROM tbl_pre_receive
left join
tbl_tags_running
on tbl_pre_receive.pre_receive_tags_code = tbl_tags_running.tags_code
where
pre_receive_issue_by = '$t_cur_user_code_VMI_GDJ'
order by pre_receive_id desc
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id_tags = 0;
while($objResult_pre_scan_tags = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id_tags++;

	$pre_receive_id = $objResult_pre_scan_tags['pre_receive_id'];
	$tags_code = $objResult_pre_scan_tags['tags_code'];
	$tags_fg_code_gdj = $objResult_pre_scan_tags['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult_pre_scan_tags['tags_fg_code_gdj_desc'];
	$tags_project_name = $objResult_pre_scan_tags['tags_project_name'];
	$tags_prod_plan = $objResult_pre_scan_tags['tags_prod_plan'];
	$tags_packing_std = $objResult_pre_scan_tags['tags_packing_std'];
	$tags_total_qty = $objResult_pre_scan_tags['tags_total_qty'];
	$tags_token = $objResult_pre_scan_tags['tags_token'];
	$tags_issue_by = $objResult_pre_scan_tags['tags_issue_by'];
	$tags_issue_date = $objResult_pre_scan_tags['tags_issue_date'];
	$tags_issue_time = $objResult_pre_scan_tags['tags_issue_time'];
	$tags_issue_datetime = $objResult_pre_scan_tags['tags_issue_datetime'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_tags;?>
	  <input type="hidden" name="_chk_pre_put_tags[]" value="<?=$row_id_tags;?>"/>
	  <input type="hidden" name="hdn_pre_put_tags_id<?=$row_id_tags;?>" id="hdn_pre_put_tags_id<?=$row_id_tags;?>" value="<?=$pre_receive_id;?>"/>
	  </td>
	  <td><?=$tags_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
	  <td><?=$tags_fg_code_gdj_desc;?></td>
	  <td><?=$tags_project_name;?></td>
	  <td><?=get_cus_code($db_con,'print_tags',$tags_fg_code_gdj,$tags_fg_code_gdj_desc);?></td>
	  <td><?=$tags_packing_std;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_put_tags" id="hdn_row_put_tags" value="<?=$row_id_tags;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_pre_put_tags').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_pre_put_tags').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": -1,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5,6 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		
    });
	
	
	<?
	if($row_id_tags != 0)
	{
	?>
	//focus
	$("#txt_scn_put_tag_id").focus();
	<?
	}
	?>
	
	//toUpperCase
	$('#txt_scn_put_tag_id').keyup(function() { this.value = this.value.toUpperCase(); });
	$('#txt_scn_put_pallet').keyup(function() { this.value = this.value.toUpperCase(); });
	
});
</script>