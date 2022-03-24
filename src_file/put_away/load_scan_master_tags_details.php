<?
require_once("../../application.php");
require_once("../../get_authorized.php");
require_once("../../js_css_header.php");
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_scan_mst_tags" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th><input type="checkbox" class="largerRadio" onClick="toggle_pre_scan_tags(this)" data-placement="top" data-toggle="tooltip" data-original-title="Select all"/></th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Tags ID</th>
	  <th>FG Code GDJ</th>
	  <th>Description</th>
	  <th>Project Name</th>
	  <th>Packing STD Qty.(Pcs.)</th>
	</tr>
	</thead>
	<tbody>
<?
$strSql_pre_scan_tags = " SELECT *  FROM tbl_pre_receive
left join
tbl_tags_running
on tbl_pre_receive.pre_receive_tags_code = tbl_tags_running.tags_code
where
pre_receive_issue_by = '$t_cur_user_code_VMI_GDJ'
order by pre_receive_id desc
";

$objQuery_pre_scan_tags = sqlsrv_query($db_con, $strSql_pre_scan_tags, $params, $options);
$num_row_pre_scan_tags = sqlsrv_num_rows($objQuery_pre_scan_tags);

$row_id_pre_scan_tags = 0;
while($objResult_pre_scan_tags = sqlsrv_fetch_array($objQuery_pre_scan_tags, SQLSRV_FETCH_ASSOC))
{
	$row_id_pre_scan_tags++;
	
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
	  <td><?=$row_id_pre_scan_tags;?></td>
	  <td>
	  <input type="checkbox" name="_chk_pre_scan_tags[]" class="largerRadio" onclick="checkSelect(this.checked,'<?=$row_id_pre_scan_tags;?>')" value="<?=$row_id_pre_scan_tags;?>"/>
	  <input type="hidden" name="hdn_pre_receive_id<?=$row_id_pre_scan_tags;?>" id="hdn_pre_receive_id<?=$row_id_pre_scan_tags;?>" value="<?=$objResult_pre_scan_tags['pre_receive_id'];?>"/>
	  </td>
	  <td align="center">
	  <button type="button" class="btn btn-danger btn-sm custom_tooltip" id="<?=$pre_receive_id;?>#####<?=$tags_code;?>" onclick="delTagsCode(this.id);"><i class="fa fa-trash-o fa-lg"></i><span class="custom_tooltiptext">Delete this Tag ID</span></button>&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?=var_encode($tags_code);?>" onclick="openRePrintIndividual(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print this Tag ID</span></button>
	  </td>
	  <td><?=$tags_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
	  <td><?=$tags_fg_code_gdj_desc;?></td>
	  <td><?=$tags_project_name;?></td>
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
<input type="hidden" name="hdn_row_pre_scan_tags" id="hdn_row_pre_scan_tags" value="<?=$row_id_pre_scan_tags;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_scan_mst_tags').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_scan_mst_tags').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[-1], ["All"]],
		"iDisplayLength": -1,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,3,4,5,6,7 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		
    });
	
});
</script>