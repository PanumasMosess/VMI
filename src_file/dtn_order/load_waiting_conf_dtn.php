<?
require_once("../../application.php");
require_once("../../js_css_header.php");
?>
<div class="box-body table-responsive padding">
  <table id="tbl_conf_dtn" class="table table-bordered table-hover table-striped nowrap">
	<thead>
	<tr style="font-size: 13px;">
	  <th style="width: 30px;">No.</th>
	  <th><input type="checkbox" class="largerRadio" onClick="toggle_chk_dtn(this)" data-placement="top" data-toggle="tooltip" data-original-title="Select all"/></th>
	  <th style="text-align: center;">Actions/Details</th>
	  <th>Picking Sheet ID</th>
	  <th>Customer Code</th>
	  <th>Customer Name</th>
	  <th>Project Name</th>
	  <th style="color: indigo;">Quantity (Pcs.)</th>
	  <th>Status</th>
	  <th>Quality Control</th>
	  <th>Receive Date</th>
	</tr>
	</thead>
	<tbody>
<?
$strSql_conf_dtn_order = " 
SELECT 
	[ps_h_picking_code]
	,[ps_h_cus_code]
	,[ps_h_cus_name]
	,[ps_t_pj_name]
	,[ps_h_status]
	,sum([ps_t_tags_packing_std]) as sum_tags_packing_std
	,[ps_h_issue_date]
FROM [tbl_picking_head]
left join
tbl_picking_tail
on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
where 
ps_h_status = 'Picking'
and
ps_h_qc = 'Completed'
group by
	[ps_h_picking_code]
	,[ps_h_cus_code]
	,[ps_h_cus_name]
	,[ps_t_pj_name]
	,[ps_h_status]
	,[ps_h_issue_date]
order by 
ps_h_picking_code desc
";

$objQuery_conf_dtn_order = sqlsrv_query($db_con, $strSql_conf_dtn_order, $params, $options);
$num_row_conf_dtn_order = sqlsrv_num_rows($objQuery_conf_dtn_order);

$row_id_conf_dtn_order = 0;
while($objResult_conf_dtn_order = sqlsrv_fetch_array($objQuery_conf_dtn_order, SQLSRV_FETCH_ASSOC))
{
	$row_id_conf_dtn_order++;

	$ps_h_picking_code = $objResult_conf_dtn_order['ps_h_picking_code'];
	$ps_h_cus_code = $objResult_conf_dtn_order['ps_h_cus_code'];
	$ps_h_cus_name = $objResult_conf_dtn_order['ps_h_cus_name'];
	$ps_t_pj_name = $objResult_conf_dtn_order['ps_t_pj_name'];
	$ps_h_status = $objResult_conf_dtn_order['ps_h_status'];
	$sum_tags_packing_std = $objResult_conf_dtn_order['sum_tags_packing_std'];
	$ps_h_issue_date = $objResult_conf_dtn_order['ps_h_issue_date'];
?>
	<tr style="font-size: 13px;">
	  <td><?=$row_id_conf_dtn_order;?></td>
	  <td>
		<input type="checkbox" name="_chk_dtn[]" class="largerRadio" onclick="checkSelect(this.checked,'<?=$row_id_conf_dtn_order;?>')" value="<?=$row_id_conf_dtn_order;?>"/>
		<input type="hidden" name="hdn_ps_h_picking_code<?=$row_id_conf_dtn_order;?>" id="hdn_ps_h_picking_code<?=$row_id_conf_dtn_order;?>" value="<?=$ps_h_picking_code;?>"/>
	  </td>	  
	  <td align="center">
	  <button type="button" class="btn btn-info btn-sm" data-placement="top" data-toggle="tooltip" data-original-title="View" id="<?=$ps_h_picking_code;?>#####<?=$ps_h_cus_code;?>#####<?=$ps_h_cus_name;?>#####<?=$ps_t_pj_name;?>#####<?=$ps_h_issue_date;?>" onclick="openFuncDTNWatingDetails(this.id);"><i class="fa fa-search fa-lg"></i></button>
	  </td>
	  <td><?=$ps_h_picking_code;?></td>
	  <td><?=$ps_h_cus_code;?></td>
	  <td><?=$ps_h_cus_name;?></td>
	  <td><?=$ps_t_pj_name;?></td>
	  <td style="color: indigo;"><?=$sum_tags_packing_std;?></td>
	  <td><?=$ps_h_status;?></td>
	  <td style="color: green;">Completed</td>
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
<input type="hidden" name="hdn_row_waiting_conf_dtn" id="hdn_row_waiting_conf_dtn" value="<?=$row_id_conf_dtn_order;?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
$(document).ready(function()
{
	// Setup - add a text input to each footer cell
    $('#tbl_conf_dtn thead tr').clone(true).appendTo( '#tbl_conf_dtn thead' );
	$('#tbl_conf_dtn thead tr:eq(1) th').each( function (i) {
        var title = $(this).text();
		
		//sel columnDefs
		if(i != 0 && i != 1 && i != 2)
		{
			$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
	 
			$( 'input', this ).on( 'keyup change', function () {
				if ( table.column(i).search() !== this.value ) {
					table
						.column(i)
						.search( this.value )
						.draw();
				}
			} );
		}
		else
		{
			$(this).html( '' );
		}
    } );
	
	var table = $('#tbl_conf_dtn').DataTable( {
		rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": 25,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,3,4,5,6,7,8,9 ] },
            { orderable: false, targets: '_all' }
        ],
        orderCellsTop: true,
        fixedHeader: true,
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			if(data[8] == "Received"){
				$(row).find('td:eq(8)').css('color', 'green');//green
			}
			else if(data[8] == "Picking"){
				$(row).find('td:eq(8)').css('color', 'gray');
			}
			else if(data[8] == "Confrim Order"){
				$(row).find('td:eq(8)').css('color', 'gray');
			}
			else if(data[8] == "Delivery Transfer Note"){
				$(row).find('td:eq(8)').css('color', 'orange');
			}
			else if(data[8] == "Pick To Use"){
				$(row).find('td:eq(8)').css('color', 'blue');
			}
		},
    } );
	
	/*
	<!--datatable search paging-->
	$('#tbl_conf_dtn').DataTable( {
        rowReorder: true,
		"aLengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "All"]],
		"iDisplayLength": 25,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,3,4,5,6,7,8,9 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			if(data[8] == "Received"){
				$(row).find('td:eq(8)').css('color', 'green');//green
			}
			else if(data[8] == "Picking"){
				$(row).find('td:eq(8)').css('color', 'gray');
			}
			else if(data[8] == "Confrim Order"){
				$(row).find('td:eq(8)').css('color', 'gray');
			}
			else if(data[8] == "Delivery Transfer Note"){
				$(row).find('td:eq(8)').css('color', 'orange');
			}
			else if(data[8] == "Pick To Use"){
				$(row).find('td:eq(8)').css('color', 'blue');
			}
		},
    });
	*/
	
});
</script>