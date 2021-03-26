<?
require_once("../../application.php");
require_once("../../js_css_header.php");


/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';


/*var *****************************************************************************/
$stock_locate = isset($_POST['sel_fj_name']) ? $_POST['sel_fj_name'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>
<div class="box-body table-responsive padding">
    <!-- <div class="row">
        <div class="form-group col-md-3">
            <label>From Date:</label>
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="min" name="min">
            </div>          
        </div>
        <div class="form-group col-md-3">
            <label>To Date:</label>
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="max" name="max">
            </div>
           
        </div>
    </div> -->
    <table id="tbl_inventory_terminal" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 15px;">
                <th colspan="11" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Tag Terminal Checking</b>&nbsp;<b class="btn" id="excel_export"></b></th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px;">No.</th>
                <th style="text-align: center;">Actions/Details</th>
                <th>Tag ID</th>
                <th>Part No</th>
                <th>FG Code GDJ</th>
                <th>FG Code GDJ Desc.</th>
                <th style="color: indigo;">Quantity (Pcs.)</th>
                <th>Status</th>
                <th>In Cart</th>
                <th>Project Name</th>
                <th>Confirmed Date</th>
            </tr>
        </thead>
        <tbody>
            <?
            if($stock_locate == ""){
                $strSql = " 
                SELECT
		
                tags_code,
                tags_fg_code_gdj,
                ps_t_tags_packing_std,
                ps_t_part_customer,
                receive_status,
                receive_date,
                dn_h_issue_date,
                tags_fg_code_gdj_desc,
                conf_qc_tags_code,
                dn_h_status,
                dn_h_receive_date,
                ps_t_pj_name
                    
                    
                    FROM tbl_receive
                    left join tbl_tags_running
                    on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
                    left join tbl_picking_tail
                    on tbl_tags_running.tags_code = tbl_picking_tail.ps_t_tags_code
                    left join tbl_usage_conf_qc
                    on tbl_usage_conf_qc.conf_qc_tags_code = tbl_receive.receive_tags_code
                    left join tbl_picking_head
                    on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
                    left join tbl_dn_tail 
                    on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
                    left join tbl_dn_head
                    on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code
                    
                    where (receive_status != 'USAGE CONFIRM' and receive_status != 'Received' and receive_status != 'Picking' and
                    receive_status != 'Delivery Transfer Note' and dn_h_receive_date between FORMAT (GETDATE() - 7, 'yyyy-MM-dd') and FORMAT (GETDATE(), 'yyyy-MM-dd'))
                ";
            }else{

                $strSql = " 
                SELECT
		
                tags_code,
                tags_fg_code_gdj,
                ps_t_tags_packing_std,
                ps_t_part_customer,
                receive_status,
                receive_date,
                dn_h_issue_date,
                tags_fg_code_gdj_desc,
                conf_qc_tags_code,
                dn_h_status,
                dn_h_receive_date,
                ps_t_pj_name
                    
                    
                    FROM tbl_receive
                    left join tbl_tags_running
                    on tbl_receive.receive_tags_code = tbl_tags_running.tags_code
                    left join tbl_picking_tail
                    on tbl_tags_running.tags_code = tbl_picking_tail.ps_t_tags_code
                    left join tbl_usage_conf_qc
                    on tbl_usage_conf_qc.conf_qc_tags_code = tbl_receive.receive_tags_code
                    left join tbl_picking_head
                    on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
                    left join tbl_dn_tail 
                    on tbl_picking_head.ps_h_picking_code = tbl_dn_tail.dn_t_picking_code
                    left join tbl_dn_head
                    on tbl_dn_tail.dn_t_dtn_code = tbl_dn_head.dn_h_dtn_code
                    
                    where (receive_status = '$stock_locate'  
                    and dn_h_receive_date between FORMAT (GETDATE() - 7, 'yyyy-MM-dd') and FORMAT (GETDATE(), 'yyyy-MM-dd'))     
                ";
            }


$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;

	$tag_id = $objResult['tags_code'];
	$tags_fg_code_gdj = $objResult['tags_fg_code_gdj'];
	$tags_fg_code_gdj_desc = $objResult['tags_fg_code_gdj_desc'];
	$receive_status = $objResult['receive_status'];
	$confirm_date = $objResult['dn_h_receive_date'];
	$ps_t_tags_packing_std = $objResult['ps_t_tags_packing_std'];
	$ps_t_pj_name = $objResult['ps_t_pj_name'];
	$ps_t_part_customer = $objResult['ps_t_part_customer'];
    $conf_qc_tags_code = $objResult['conf_qc_tags_code'];

	$usage_part_customer_arr = explode('-', $ps_t_part_customer);

?>
            <tr style="font-size: 13px;">
                <td><?= $row_id; ?></td>
                <td align="center">
                    <button type="button" class="btn btn-primary btn-sm custom_tooltip" id="<?= var_encode($tag_id); ?>" onclick="openRePrintTag(this.id);"><i class="fa fa-print fa-lg"></i><span class="custom_tooltiptext">Re-Print Tags</span></button>
                </td>
                <td><?= $tag_id; ?></td>
                <?
				if($ps_t_part_customer == NULL)
				{
				?>
					<td>Old Data( < 2020-10)</td>
				<?
				}else{
				?>
					<td><?= $usage_part_customer_arr[1]; ?></td>
				<?
				}
				?>
                <td><?= $tags_fg_code_gdj; ?></td>
                <td><?= $tags_fg_code_gdj_desc; ?></td>
                <?if($ps_t_tags_packing_std == NULL)
				{
				?>
					<td>0</td>
				<?
				}else{
				?>
					<td style="color: indigo;"><?= number_format($ps_t_tags_packing_std); ?></td>
				<?
				}
				?>				
                <td style="color: green;"><?= $receive_status; ?></td>
                <?
                if($conf_qc_tags_code == null ){?>
                <td align="center" >
                    <!-- <i style="color: orange;" class="fa fa-cart-plus fa-lg"></i> -->
                </td>
                <?}else{
                     ?>
                <td align="center" >
                    <i style="color: orange;" class="fa fa-cart-plus fa-lg"></i>
                </td>
                <?
                }
                ?>
               	<?
				if($ps_t_pj_name == NULL)
				{
				?>
					<td>Old Data( < 2020-10)</td>
				<?
				}else{
				?>
					<td><?= $ps_t_pj_name; ?></td>
				<?
				}
				?>

				<?
				if($confirm_date == NULL)
				{
				?>
					<td>2020-10-01</td>
				<?
				}else{
				?>
					<td><?= $confirm_date; ?></td>
				<?
				}
				?>
            </tr>
            <?
}
?>
        </tbody>
    </table>
</div>
<!-- /.box-body -->

<!--alert no item-->
<input type="hidden" name="hdn_row_inventory" id="hdn_row_inventory" value="<?= $row_id; ?>" />

<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
    $(document).ready(function() {
        // <!--datatable search paging-->
        var oTable = jQuery('#tbl_inventory_terminal').DataTable({
            rowReorder: true,
            "oLanguage": {
                "sSearch": "Filter Data"
            },
            // columnDefs: [
            //     { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
            //     { orderable: false, targets: '_all' }
            // ],
            pagingType: "full_numbers",
            // dom: 'Bfrtip',
            // buttons: [{
            //     extend: 'excel',
            //     text: 'Export Billing by Tags',
            // }]
        });

        var buttons = new $.fn.dataTable.Buttons(oTable, {
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Export Tag Recheck by Tags',
                titleAttr: 'Excel Tag Recheck Report',
                title: 'Excel Tag ReCheck',
                exportOptions: {
                    modifier: {
                        page: 'all'
                    },
                    columns: [2, 3, 4, 5, 6, 7, 9, 10]
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

        // $.fn.dataTable.ext.search.push(
        //     function(settings, data, dataIndex) {
        //         var min = $('#min').datepicker('getDate');
        //         var max = $('#max').datepicker('getDate');
        //         max.setDate(max.getDate()+1); 
        //         var startDate = new Date(data[13]);
        //         if (min == null && max == null) return true;
        //         if (min == null && startDate <= max) return true;
        //         if (max == null && startDate >= min) return true;
        //         if (startDate <= max && startDate >= min) return true;
        //         return false;
        //     }
        // );



        // $('#min').datepicker({
        //     autoclose: true,
        //     format: 'yyyy-mm-dd',
        //     onSelect: function() {
        //         oTable.draw();
        //     },
        //     changeMonth: true,
        //     changeYear: true
        // });
        // $('#max').datepicker({
        //     autoclose: true,
        //     format: 'yyyy-mm-dd',
        //     onSelect: function() {
        //         oTable.draw();
        //     },
        //     changeMonth: true,
        //     changeYear: true
        // });


        // // Event listener to the two range filtering inputs to redraw on input
        // $('#min, #max').change(function() {
        //     oTable.draw();
        // });

    });
</script>