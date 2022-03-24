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
	    <th colspan="19" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;DTN B2C Sale ABB Report</b>&nbsp;<button type="button" class="btn btn-default btn-sm"  onclick="exportExcelSaleB2C_ABB()"><i class="fa fa-bar-chart fa-lg"></i> Export Excel</button></th>			
	</tr>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">Row Number.</th>
	  <th>Receipt Number</th>
	  <th>Order ID</th>
	  <th>Receipt Date</th>
	  <th>Branch</th>
	  <th>POS ID</th>
	  <th>Transport Fee</th>
	  <th>Discount Amount</th>
	  <th style="color: red;">*Full Tax Invoice</th>
      <th>Item Code</th>
      <th>Item Description</th>
	  <th>Qty</th>
	  <th>Unit Price</th>
	  <th>Status Order</th>
	  <th>B2C Case</th>
	  <th>Invoice Address</th>
	  <th>Post Code</th>
	  <th>Contract Name</th>
	  <th>Tel.</th>
	</tr>
	</thead>
	<tbody>
<?

$strSql_sale_report = " 
SELECT [b2c_sale_date]
      ,[b2c_sale_time]
      ,[b2c_sale_order_id]
      ,[b2c_sale_pos_no]
      ,[b2c_sale_inv_no]
      ,[b2c_sale_excluding_vat]
      ,[b2c_sale_tax]
      ,[b2c_sale_including_vat]
      ,[b2c_sale_remark]
      ,[b2c_sale_branch]
	  ,[repn_sku_code_abt]
	  ,[bom_fg_desc]
	  ,[repn_qty]
	  ,[bom_price_sale_per_pcs]
      ,[b2c_sale_transport_fee]
      ,[b2c_sale_discount_amount]
	  ,[b2c_tax_inv]
	  ,[b2c_status]
	  ,[b2c_case]
	  ,[b2c_inv_address]
      ,[b2c_zipcode]
      ,b2c_contact_name
      ,[b2c_tel]
  FROM [tbl_b2c_sale] 
  left join tbl_b2c_detail
  on tbl_b2c_sale.b2c_sale_order_id = tbl_b2c_detail.b2c_repn_order_ref
  left join tbl_replenishment
  on tbl_b2c_detail.b2c_repn_order_ref = tbl_replenishment.repn_order_ref
  left join tbl_bom_mst
  on tbl_replenishment.repn_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
  where  (b2c_sale_date between '$date_start' and '$date_end') and  bom_status = 'Active'  
  order by  [b2c_order_date]  asc
"; 

$objQuery_sale_report = sqlsrv_query($db_con, $strSql_sale_report);
$num_row_sale_report = sqlsrv_num_rows($objQuery_sale_report);

$row_id_report = 0;
while($objResult_DTNSheet = sqlsrv_fetch_array($objQuery_sale_report, SQLSRV_FETCH_ASSOC))
{
	$row_id_report++;

	$b2c_sale_date = $objResult_DTNSheet['b2c_sale_date'];
    $b2c_sale_time = $objResult_DTNSheet['b2c_sale_time'];
    $b2c_sale_order_id = $objResult_DTNSheet['b2c_sale_order_id'];
    $b2c_sale_pos_no = $objResult_DTNSheet['b2c_sale_pos_no'];
    $b2c_sale_inv_no = $objResult_DTNSheet['b2c_sale_inv_no'];
    $b2c_sale_excluding_vat = $objResult_DTNSheet['b2c_sale_excluding_vat'];
    $b2c_sale_tax = $objResult_DTNSheet['b2c_sale_tax'];
    $b2c_sale_including_vat = $objResult_DTNSheet['b2c_sale_including_vat'];
    $b2c_sale_remark = $objResult_DTNSheet['b2c_sale_remark'];
    $b2c_sale_branch = $objResult_DTNSheet['b2c_sale_branch'];
    $repn_sku_code_abt = $objResult_DTNSheet['repn_sku_code_abt'];
    $bom_fg_desc = $objResult_DTNSheet['bom_fg_desc'];
    $repn_qty = $objResult_DTNSheet['repn_qty'];
    $bom_price_sale_per_pcs = $objResult_DTNSheet['bom_price_sale_per_pcs'];
    $b2c_sale_transport_fee = $objResult_DTNSheet['b2c_sale_transport_fee'];
    $b2c_sale_discount_amount = $objResult_DTNSheet['b2c_sale_discount_amount'];
	$b2c_tax_inv = $objResult_DTNSheet['b2c_tax_inv'];
	$b2c_status = $objResult_DTNSheet['b2c_status'];
	$b2c_case = $objResult_DTNSheet['b2c_case'];
	$b2c_inv_address = $objResult_DTNSheet['b2c_inv_address'];
    $b2c_zipcode = $objResult_DTNSheet['b2c_zipcode'];
    $b2c_contact_name = $objResult_DTNSheet['b2c_contact_name'];
    $b2c_tel = $objResult_DTNSheet['b2c_tel'];

    if($b2c_sale_branch == null){
        $b2c_sale_branch = "0";
    }

	if($b2c_tax_inv == null){
		$b2c_tax_inv = '-';
		$b2c_inv_address = '-';
    	$b2c_zipcode = '-';
   	 	$b2c_contact_name = '-';
    	$b2c_tel = '-';
	}

	if($b2c_status == null){
		$b2c_status  = 'Production Process';
	}

	if($b2c_case == null){
		$b2c_case  = 'Normal';
		$b2c_inv_address = '-';
    	$b2c_zipcode = '-';
   	 	$b2c_contact_name = '-';
    	$b2c_tel = '-';
	}
    
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_report;?></td>
	  <td><?=$b2c_sale_inv_no;?></td>
	  <td><?=$b2c_sale_order_id;?></td>
	  <td><?=date( "d/m/y", strtotime($b2c_sale_date));?></td>
	  <td><?=$b2c_sale_branch;?></td>
	  <td><?=$b2c_sale_pos_no;?></td>
	  <td><?=$b2c_sale_transport_fee;?></td>
	  <td><?=$b2c_sale_discount_amount;?></td>
	  <? if($b2c_tax_inv == '-'){?>
		<td><?=$b2c_tax_inv;?></td>
	 <? }else {?>
		<td style="color: blue;"><?=$b2c_tax_inv;?></td>
	 <?}?>
      <td><?=$repn_sku_code_abt;?></td>
      <td><?=$bom_fg_desc;?></td>
      <td><?=$repn_qty;?></td>
      <td><?=$b2c_sale_including_vat;?></td>
	  <td><?=$b2c_status;?></td>
	  <td><?=$b2c_case;?></td>
	  <td><?=$b2c_inv_address;?></td>
	  <td><?=$b2c_zipcode;?></td>
	  <td><?=$b2c_contact_name;?></td>
	  <td><?=$b2c_tel;?></td>
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
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9 ] },
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