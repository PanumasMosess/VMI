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
    <div class="row">
        <div class="form-group col-md-3">
            <label>From Date:</label>
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="min" name="min">
            </div>
            <!-- /.input group -->
        </div>
        <div class="form-group col-md-3">
            <label>To Date:</label>
            <div class="input-group date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control pull-right" id="max" name="max">
            </div>
            <!-- /.input group -->
        </div>
    </div>
    <table id="tbl_bill_terminal" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 15px;">
                <th colspan="16" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Billing</b>&nbsp;<b class="btn" id="excel_export"></b></th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px;">No.</th>
                <th style="text-align: center;">Actions/Details</th>
                <th>Tag ID</th>
                <th>Part Customer</th>
                <th>FG Code Set ABT</th>
                <th>Project Name</th>
                <th>Package Code</th>
                <th>SNP (PCS)</th>
                <th>Ship Type</th>
                <th>GDJ Description</th>
                <th>FG Code GDJ</th>
                <th style="color: indigo;">Quantity (Pcs.)</th>
                <th style="color: indigo;">Price (Bath.)</th>
                <th style="color: green; text-align: center;">Status</th>
                <th>User Pick By</th>
                <th>Pick Date</th>
            </tr>
        </thead>
        <tbody>
            <?
            if($stock_locate == "ALL"){
                $strSql = " 
                SELECT 
                usage_tags_code
                ,usage_part_customer
                ,usage_fg_code_set_abt
                ,usage_terminal_name
                ,bom_ctn_code_normal
                ,bom_snp
                ,usage_ship_type
                ,ps_t_tags_packing_std
                ,usage_price_sale_per_pcs
                ,receive_status  
                ,usage_pick_by
                ,usage_pick_date
                ,bom_fg_desc
                ,bom_fg_code_gdj
                
                FROM [tbl_usage_conf]
                left join tbl_bom_mst
                on tbl_bom_mst.bom_fg_code_set_abt = tbl_usage_conf.usage_fg_code_set_abt
                and tbl_bom_mst.bom_fg_sku_code_abt = tbl_usage_conf.usage_sku_code_abt
                and tbl_bom_mst.bom_fg_code_gdj = tbl_usage_conf.usage_fg_code_gdj 
                and tbl_bom_mst.bom_ship_type = tbl_usage_conf.usage_ship_type
                and tbl_bom_mst.bom_part_customer = tbl_usage_conf.usage_part_customer
                and tbl_bom_mst.bom_pj_name = tbl_usage_conf.usage_terminal_name
                left join tbl_receive
                on tbl_receive.receive_tags_code = tbl_usage_conf.usage_tags_code
                left join tbl_picking_tail
                on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
                where receive_status = 'USAGE CONFIRM' 
                group by 
                      usage_tags_code
                      ,usage_part_customer
                      ,usage_fg_code_set_abt
                      ,usage_terminal_name
                      ,bom_ctn_code_normal
                      ,bom_snp
                      ,usage_ship_type
                      ,ps_t_tags_packing_std
                      ,usage_price_sale_per_pcs
                      ,receive_status  
                      ,usage_pick_by
                      ,usage_pick_date
                      ,bom_fg_desc
                      ,bom_fg_code_gdj
                
                   order by usage_pick_date desc                   
                ";
            }else{

                $strSql = " 
                SELECT 
                    usage_tags_code
                   ,usage_part_customer
                   ,usage_fg_code_set_abt
                   ,usage_terminal_name
                   ,bom_ctn_code_normal
                   ,bom_snp
                   ,usage_ship_type
                   ,ps_t_tags_packing_std
                   ,usage_price_sale_per_pcs
                   ,receive_status  
                   ,usage_pick_by
                   ,usage_pick_date
                   ,bom_fg_desc
                   ,bom_fg_code_gdj
                
                FROM [tbl_usage_conf]
                left join tbl_bom_mst
                on tbl_bom_mst.bom_fg_code_set_abt = tbl_usage_conf.usage_fg_code_set_abt
                and tbl_bom_mst.bom_fg_sku_code_abt = tbl_usage_conf.usage_sku_code_abt
                and tbl_bom_mst.bom_fg_code_gdj = tbl_usage_conf.usage_fg_code_gdj 
                and tbl_bom_mst.bom_ship_type = tbl_usage_conf.usage_ship_type
                and tbl_bom_mst.bom_part_customer = tbl_usage_conf.usage_part_customer
                and tbl_bom_mst.bom_pj_name = tbl_usage_conf.usage_terminal_name
                left join tbl_receive
                on tbl_receive.receive_tags_code = tbl_usage_conf.usage_tags_code
                left join tbl_picking_tail
                on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
                where receive_status = 'USAGE CONFIRM' and usage_terminal_name = '$stock_locate'
                group by 
                        usage_tags_code
                        ,usage_part_customer
                        ,usage_fg_code_set_abt
                        ,usage_terminal_name
                        ,bom_ctn_code_normal
                        ,bom_snp
                        ,usage_ship_type
                        ,ps_t_tags_packing_std
                        ,usage_price_sale_per_pcs
                        ,receive_status  
                        ,usage_pick_by
                        ,usage_pick_date
                        ,bom_fg_desc
                        ,bom_fg_code_gdj
                
                   order by usage_pick_date desc
                   
                ";
            }


$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	$usage_tags_code = $objResult['usage_tags_code'];
	$usage_part_customer = $objResult['usage_part_customer'];
	$usage_fg_code_set_abt = $objResult['usage_fg_code_set_abt'];
    $usage_terminal_name = $objResult['usage_terminal_name'];
    $bom_ctn_code_normal = $objResult['bom_ctn_code_normal'];
    $bom_snp = $objResult['bom_snp'];
	$usage_ship_type = $objResult['usage_ship_type'];
    $ps_t_tags_packing_std = $objResult['ps_t_tags_packing_std'];
    $usage_price_sale_per_pcs = $objResult['usage_price_sale_per_pcs'];
    $receive_status = $objResult['receive_status'];
    $usage_pick_by = $objResult['usage_pick_by'];
    $usage_pick_date = $objResult['usage_pick_date'];
    $bom_fg_desc = $objResult['bom_fg_desc'];
    $bom_fg_code_gdj = $objResult['bom_fg_code_gdj'];

    $pcs_num = number_format($ps_t_tags_packing_std);
    $price_num = number_format($usage_price_sale_per_pcs,2);

    $usage_part_customer_arr = explode('-', $usage_part_customer);

    $price = $pcs_num * $price_num;
?>
            <tr style="font-size: 13px;">
                <td><?= $row_id; ?></td>
                <td align="center">
                    <button type="button" class="btn btn-primary btn-sm" id="<?= var_encode($usage_tags_code); ?>" onclick="openRePrintTag(this.id);" data-placement="top" data-toggle="tooltip" data-original-title="Re-Print Tag By Tag ID"><i class="fa fa-print fa-lg"></i></button>
                </td>
                <td><?= $usage_tags_code; ?></td>
                <td><?= $usage_part_customer_arr[1]; ?></td>
                <td><?= $usage_fg_code_set_abt; ?></td>
                <td><?= $usage_terminal_name; ?></td>
                <td><?= $bom_ctn_code_normal; ?></td>
                <td><?= $bom_snp; ?></td>
                <td><?= $usage_ship_type; ?></td>
                <td><?= $bom_fg_desc; ?></td>
                <td><?= $bom_fg_code_gdj?></td>
                <td style="color: indigo;"><?= number_format($ps_t_tags_packing_std); ?></td>
                <td style="color: indigo;"><?= number_format($price,2); ?></td>
                <td style="color: green;"><?= $receive_status; ?></td>
                <td><?= $usage_pick_by; ?></td>
                <td><?= $usage_pick_date; ?></td>
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
        var oTable = jQuery('#tbl_bill_terminal').DataTable({
            rowReorder: true,
            "oLanguage": {
                "sSearch": "Filter Data"
            },
            // columnDefs: [
            //     { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
            //     { orderable: false, targets: '_all' }
            // ],
            // dom: 'Bfrtip',
            // buttons: [{
            //     extend: 'excel',
            //     text: 'Export Billing by Tags',
            // }]
            pagingType: "full_numbers",
        });

        var buttons = new $.fn.dataTable.Buttons(oTable, {
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Export Billing by Tags',
                titleAttr: 'Excel Billing Report',
                title: 'Excel Billing Report',
                exportOptions:{
                    modifier: {
                    page: 'all'
                },
                columns: [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15]
                }
            }],
            dom: {
                button: {
                    tag: 'button',
                    className: 'btn btn-default'
                }
            },
        }).container().appendTo($('#excel_export'));


        $.fn.dataTable.ext.search.push(
            function(settings, data, dataIndex) {
                var min = $('#min').datepicker('getDate');
                var max = $('#max').datepicker('getDate');
                max.setDate(max.getDate()+1); 
                var startDate = new Date(data[15]);
                if (min == null && max == null) return true;
                if (min == null && startDate <= max) return true;
                if (max == null && startDate >= min) return true;
                if (startDate <= max && startDate >= min) return true;
                return false;
            }
        );



        $('#min').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            onSelect: function() {
                oTable.draw();
            },
            changeMonth: true,
            changeYear: true
        });
        $('#max').datepicker({
            autoclose: true,
            format: 'yyyy-mm-dd',
            onSelect: function() {
                oTable.draw();
            },
            changeMonth: true,
            changeYear: true
        });


        // Event listener to the two range filtering inputs to redraw on input
        $('#min, #max').change(function() {
            oTable.draw();
        });

    });
</script>