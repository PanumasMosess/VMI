<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_print_tags" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Put away</th>
	  <th>Tags ID</th>
	  <th>FG Code GDJ</th>
	  <th>Description</th>
	  <th>Customer Code</th>
	  <th>Production Plan Qty.</th>
	  <th style="color: indigo;">Packing STD Qty.(Pcs.)</th>
	  <th>Total Tags Qty</th>
	  <th>Lot Token</th>
	  <th>Issue By</th>
	  <th>Issue Datetime</th>
	</tr>
	</thead>
	<tbody>
<?
$strSql = " EXEC sp_print_tags_history ";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$str_icon_rec = "";
$str_status_rec = "";

while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;
	
	$tags_code = $objResult['tags_code'];
	$tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
	$tags_prod_plan = $objResult['tags_prod_plan'];
	$tags_packing_std = $objResult['tags_packing_std'];
	$tags_total_qty = $objResult['tags_total_qty'];
	$tags_token = $objResult['tags_token'];
	$tags_issue_by = $objResult['tags_issue_by'];
	$tags_issue_date = $objResult['tags_issue_date'];
	$tags_issue_time = $objResult['tags_issue_time'];
	$tags_issue_datetime = $objResult['tags_issue_datetime'];
	$receive_status = $objResult['receive_status'];
	
	//check status received
	if(ltrim(rtrim($receive_status)) != "")
	{
		$str_icon_rec = "fa fa-check-circle-o fa-lg";
		$str_status_rec = "Received";
	}
	else
	{
		$str_icon_rec = "";
		$str_status_rec = "";
	}
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id;?></td>
	  <td align="center">
	  <button type="button" class="btn btn-primary btn-sm" id="<?=var_encode($tags_code);?>" onclick="openRePrintIndividual(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Re-Print this Tag ID"><i class="fa fa-print fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-info btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="Re-Print this Lot Token" id="<?=var_encode($tags_token);?>" onclick="openRePrintSet(this.id);"><i class="fa fa-print fa-lg"></i></button>
	  </td>
	  <td style="text-align: center; vertical-align: middle; color: green;"><?=$str_status_rec;?><!--<i class="<?=$str_icon_rec;?>" style="color: green;"></i>--></td>
	  <td style="color: #000; font-weight: bold;"><?=$tags_code;?></td>
	  <td><?=$tags_fg_code_gdj;?></td>
	  <td><?=$tags_fg_code_gdj_desc;?></td>
	  <td><?=get_cus_code($db_con,'print_tags',$tags_fg_code_gdj,$tags_fg_code_gdj_desc);?></td>
	  <td><?=$tags_prod_plan;?></td>
	  <td style="color: indigo;"><?=$tags_packing_std;?></td>
	  <td><?=$tags_total_qty;?></td>
	  <td><?=$tags_token;?></td>
	  <td><?=$tags_issue_by;?></td>
	  <td><?=substr($tags_issue_datetime,0,19);?></td>
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
	
	<!--datatable search paging-->
	$('#tbl_print_tags').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9,10,11,12 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
    });
	
});
</script>