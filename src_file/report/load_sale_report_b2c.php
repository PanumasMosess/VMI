<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
$date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';
?>	
<div class="box-body table-responsive padding">
  <table id="tbl_dtn_sheet" class="table table-bordered table-hover table-striped nowrap">
	<thead>
    <tr style="font-size: 13px;">
	    <th colspan="10" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;DTN B2C Sale Report</b>&nbsp;<button type="button" class="btn btn-default btn-sm"  onclick="exportExcelSaleB2C()"><i class="fa fa-bar-chart fa-lg"></i> Export Excel</button></th>			
	</tr>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th style="text-align: center;">Payment date</th>
	  <th>Payment Time</th>
	  <th>Time</th>
	  <th>ID</th>
	  <th>POS ID</th>
	  <th>Full INV. No</th>
	  <th>Exclude VAT</th>
	  <th>VAT.</th>
      <th>Include VAT</th>
	</tr>
	</thead>
	<tbody>
<?

$strSql_sale_report = " 
SELECT  
       [b2c_sale_date]
      ,[b2c_sale_time]
      ,[b2c_sale_count_time]
      ,[b2c_sale_order_id]
      ,[b2c_sale_user_id]
      ,[b2c_sale_pos_no]
      ,[b2c_sale_inv_no]
      ,[b2c_sale_excluding_vat]
      ,[b2c_sale_tax]
      ,[b2c_sale_including_vat]
      ,[b2c_sale_remark]
  FROM [tbl_b2c_sale]
  where  (b2c_sale_date between '$date_start' and '$date_end')
";

$objQuery_sale_report = sqlsrv_query($db_con, $strSql_sale_report);
$num_row_sale_report = sqlsrv_num_rows($objQuery_sale_report);

$row_id_report = 0;
while($objResult_DTNSheet = sqlsrv_fetch_array($objQuery_sale_report, SQLSRV_FETCH_ASSOC))
{
	$row_id_report++;

	$b2c_sale_date = $objResult_DTNSheet['b2c_sale_date'];
	$b2c_sale_time = $objResult_DTNSheet['b2c_sale_time'];
    $b2c_sale_time = date("h:i", strtotime($b2c_sale_time));
	$b2c_sale_count_time = $objResult_DTNSheet['b2c_sale_count_time'];
	$b2c_sale_order_id = $objResult_DTNSheet['b2c_sale_order_id'];
	$b2c_sale_user_id = $objResult_DTNSheet['b2c_sale_user_id'];
	$b2c_sale_pos_no = $objResult_DTNSheet['b2c_sale_pos_no'];
	$b2c_sale_inv_no = $objResult_DTNSheet['b2c_sale_inv_no'];
    $b2c_sale_excluding_vat = $objResult_DTNSheet['b2c_sale_excluding_vat'];
    $b2c_sale_tax = $objResult_DTNSheet['b2c_sale_tax'];
    $b2c_sale_including_vat = $objResult_DTNSheet['b2c_sale_including_vat'];
    $b2c_sale_remark = $objResult_DTNSheet['b2c_sale_remark'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_report;?></td>
	  <td><?=$b2c_sale_date;?></td>
	  <td><?=$b2c_sale_time;?></td>
	  <td>0 Min</td>
	  <td><?=$b2c_sale_user_id;?></td>
	  <td>E05210002A1702</td>
	  <td><?=$b2c_sale_inv_no;?></td>
	  <td><?=$b2c_sale_excluding_vat;?></td>
      <td><?=$b2c_sale_tax;?></td>
      <td><?=$b2c_sale_including_vat;?></td>
	</tr>
<?
}
?>						
	</tbody>
  </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_DTNSheet" id="hdn_row_DTNSheet" value="<?=$row_id_DTNSheet;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	//search
    /*$('#tbl_dtn_sheet').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	// <!--datatable search paging-->
    var dtnTable =	$('#tbl_dtn_sheet').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers"
		// rowCallback: function(row, data, index)
		// {
		// 	//status
		// 	if(data[7] == "Received"){
		// 		$(row).find('td:eq(7)').css('color', 'green');//green
		// 	}
		// 	else if(data[7] == "Picking"){
		// 		$(row).find('td:eq(7)').css('color', 'gray');
		// 	}
		// 	else if(data[7] == "Confrim Order"){
		// 		$(row).find('td:eq(7)').css('color', 'green');
		// 	}
		// 	else if(data[7] == "Delivery Transfer Note"){
		// 		$(row).find('td:eq(7)').css('color', 'orange');
		// 	}
		// 	else if(data[7] == "Pick To Use"){
		// 		$(row).find('td:eq(7)').css('color', 'blue');
		// 	}
		// 	else if(data[7] == "Terminal 01"){
		// 		$(row).find('td:eq(7)').css('color', 'limegreen');
		// 	}
		// 	else if(data[7] == "Terminal 02"){
		// 		$(row).find('td:eq(7)').css('color', 'limegreen');
		// 	}
		// 	else if(data[7] == "Terminal 03"){
		// 		$(row).find('td:eq(7)').css('color', 'limegreen');
		// 	}
		// 	else if(data[7] == "Terminal 04"){
		// 		$(row).find('td:eq(7)').css('color', 'limegreen');
		// 	}
		// 	else if(data[7] == "Terminal 05"){
		// 		$(row).find('td:eq(7)').css('color', 'limegreen');
		// 	}
		// },
    });


});
$("#loadding").modal("hide"); 
</script>