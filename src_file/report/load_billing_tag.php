<?
require_once("../../application.php");
// require_once("../../js_css_header.php");


/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';


/*var *****************************************************************************/
$stock_locate = isset($_POST['sel_fj_name']) ? $_POST['sel_fj_name'] : '';
$date_start = isset($_POST['date_start_']) ? $_POST['date_start_'] : '';
$date_end = isset($_POST['date_end_']) ? $_POST['date_end_'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");
?>
<div class="box-body table-responsive padding">
    <table id="tbl_bill_terminal" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 15px;">
                <th colspan="17" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Billing</b>&nbsp;<b class="btn" id="excel_export"></b></th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px;">No.</th>
                <th>Tag Code</th>
                <th>FG Code Set ABT</th>
                <th>Usage Date</th>
                <th>Project name</th>
                <th>Part no</th>
                <th>Carton code</th>
                <th>STD</th>
                <th>Ship to</th>
                <th style="color: indigo;">Carton Quantity (Pcs.)</th>
                <th style="color: indigo;">Pad Quantity (Pcs.)</th>
                <th style="color: indigo;">Insert Quantity (Pcs.)</th>
                <th style="color: indigo;">Cover Quantity (Pcs.)</th>
                <th style="color: indigo;">Angle Quantity (Pcs.)</th>
                <th style="color: indigo;">Price (Bath.)</th>
                <th style="color: indigo;">Total cost</th>
                <th>User Pick By</th>
            </tr>
        </thead>
        <tbody>
            <?
            if($stock_locate == "ALL"){
                $strSql = " 
                SELECT
                usage_tags_code
               ,usage_fg_code_set_abt
               ,usage_pick_date
               ,usage_terminal_name
               ,usage_part_customer
               ,bom_ctn_code_normal
               ,bom_snp
               ,usage_ship_type
               ,usage_price_sale_per_pcs
               ,usage_pick_by
               ,bom_pckg_type
               ,tags_packing_std
         
         FROM [tbl_usage_conf]
         left join tbl_bom_mst
         on tbl_bom_mst.bom_fg_code_set_abt = tbl_usage_conf.usage_fg_code_set_abt
         and tbl_bom_mst.bom_fg_sku_code_abt = tbl_usage_conf.usage_sku_code_abt
         and tbl_bom_mst.bom_fg_code_gdj = tbl_usage_conf.usage_fg_code_gdj 
         and tbl_bom_mst.bom_ship_type = tbl_usage_conf.usage_ship_type
         and tbl_bom_mst.bom_part_customer = tbl_usage_conf.usage_part_customer
         and tbl_bom_mst.bom_pj_name = tbl_usage_conf.usage_terminal_name
         left join tbl_tags_running
         on tbl_usage_conf.usage_tags_code = tbl_tags_running.tags_code
         where (usage_pick_date between '$date_start' and '$date_end') 
         group by 
         
         usage_tags_code
         ,usage_fg_code_set_abt
         ,usage_pick_date
         ,usage_terminal_name
         ,usage_part_customer
         ,bom_ctn_code_normal
         ,bom_snp
         ,usage_ship_type
         ,usage_price_sale_per_pcs
         ,usage_pick_by
         ,bom_pckg_type
         ,tags_packing_std
         
            order by usage_pick_date desc                 
                ";
            }else{
                
                $strSql = " 
                SELECT
                usage_tags_code
                ,usage_fg_code_set_abt
                ,usage_pick_date
                ,usage_terminal_name
                ,usage_part_customer
                ,bom_ctn_code_normal
                ,bom_snp
                ,usage_ship_type
                ,usage_price_sale_per_pcs
                ,usage_pick_by
                ,bom_pckg_type
                ,tags_packing_std
         
         FROM [tbl_usage_conf]
         left join tbl_bom_mst
         on tbl_bom_mst.bom_fg_code_set_abt = tbl_usage_conf.usage_fg_code_set_abt
         and tbl_bom_mst.bom_fg_sku_code_abt = tbl_usage_conf.usage_sku_code_abt
         and tbl_bom_mst.bom_fg_code_gdj = tbl_usage_conf.usage_fg_code_gdj 
         and tbl_bom_mst.bom_ship_type = tbl_usage_conf.usage_ship_type
         and tbl_bom_mst.bom_part_customer = tbl_usage_conf.usage_part_customer
         and tbl_bom_mst.bom_pj_name = tbl_usage_conf.usage_terminal_name
         left join tbl_tags_running
         on tbl_usage_conf.usage_tags_code = tbl_tags_running.tags_code
         where (usage_pick_date between '$date_start' and '$date_end')
         and usage_terminal_name = '$stock_locate'
         group by 
         
         usage_tags_code
         ,usage_fg_code_set_abt
         ,usage_pick_date
         ,usage_terminal_name
         ,usage_part_customer
         ,bom_ctn_code_normal
         ,bom_snp
         ,usage_ship_type
         ,usage_price_sale_per_pcs
         ,usage_pick_by
         ,bom_pckg_type
         ,tags_packing_std
         
            order by usage_pick_date desc
                   
                ";
            }


$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

	//$usage_tags_code = $objResult['usage_tags_code'];
    $usage_tags_code = $objResult['usage_tags_code'];	
	$usage_fg_code_set_abt = $objResult['usage_fg_code_set_abt'];
    $usage_pick_date = $objResult['usage_pick_date'];
    $usage_terminal_name = $objResult['usage_terminal_name'];
    $bom_part_customer = $objResult['usage_part_customer'];
    $bom_ctn_code_normal = $objResult['bom_ctn_code_normal'];
	$bom_snp = $objResult['bom_snp'];
    $usage_ship_type = $objResult['usage_ship_type'];
    $tags_packing_std = $objResult['tags_packing_std'];
    $usage_price_sale_per_pcs = $objResult['usage_price_sale_per_pcs'];
    $usage_pick_date = $objResult['usage_pick_date'];
    $usage_pick_by = $objResult['usage_pick_by'];
    $bom_pckg_type = $objResult['bom_pckg_type'];

    $pcs_num = $tags_packing_std;
    $price_num = $usage_price_sale_per_pcs;

    $usage_part_customer_arr = explode('-', $bom_part_customer);

    $price = $pcs_num * $price_num;
?>
            <tr style="font-size: 13px;">
                <td><?= $row_id; ?></td>
                <td><?= $usage_tags_code; ?></td>
                <td><?= $usage_fg_code_set_abt; ?></td>
                <td><?= $usage_pick_date; ?></td>
                <td><?= $usage_terminal_name; ?></td>
                <td><?= $usage_part_customer_arr[1]; ?></td>
                <td><?= $bom_ctn_code_normal; ?></td>
                <td><?= $bom_snp; ?></td>
                <td><?= $usage_ship_type; ?></td>
                <? if($bom_pckg_type == 'CARTON BOX'){?>
                <td align="center"><?= $pcs_num; ?></td>
                <?}else{?>
                <td align="center">-</td>
                <?
                }
                ?>
                <? if($bom_pckg_type == 'PAD'){?>
                <td align="center"><?= $pcs_num; ?></td>
                <?}else{?>
                <td align="center">-</td>
                <?
                }
                ?>
                <? if($bom_pckg_type == 'INSERT'){?>
                <td align="center"><?= $pcs_num; ?></td>
                <?}else{?>
                <td align="center">-</td>
                <?
                }
                ?>
                <? if($bom_pckg_type == 'COVER'){?>
                <td align="center"><?= $pcs_num; ?></td>
                <?}else{?>
                <td align="center">-</td>
                <?
                }
                ?>
                <? if($bom_pckg_type == 'ANGLE'){?>
                <td align="center"><?= $pcs_num; ?></td>
                <?}else{?>
                <td align="center">-</td>
                <?
                }
                ?>
                <td style="color: indigo;"><?= number_format($price_num, 2); ?></td>
                <td style="color: indigo;"><?= number_format($price, 2); ?></td>
                <td><?= $usage_pick_by; ?></td>
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
            pagingType: "full_numbers",
        });
        $("#loadding").modal("hide");

        var buttons = new $.fn.dataTable.Buttons(oTable, {
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Export Billing by Tags',
                titleAttr: 'Excel Billing Report',
                title: 'Excel Billing Report',
                exportOptions: {
                    modifier: {
                        page: 'all'
                    },
                    columns: [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
                }
            }],
            dom: {
                button: {
                    tag: 'button',
                    className: 'btn btn-default'
                }
            },
        }).container().appendTo($('#excel_export'));

    });
</script>