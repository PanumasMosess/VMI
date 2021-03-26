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
    <table id="tbl_inventory_terminal" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 18px;">
                <th colspan="17" class="bg-light-blue"><b><i class="fa fa-arrows-h fa-lg"></i>&nbsp;Transaction</b>&nbsp;<b class="btn" id="excel_export"></b></th>
            </tr>
            <tr style="font-size: 13px;">
                <th colspan="8" class="bg-black" style="text-align: center;">
                    <b>
                        <font style="color: #FFF;"></font>
                    </b>
                </th>
                <th colspan="3" class="bg-green" style="text-align: center;">
                    <b>
                        <font style="color: #000;">Replenishment</font>
                    </b>
                </th>
                <th colspan="3" class="bg-orange" style="text-align: center;">
                    <b>
                        <font style="color: #000;">Usage Confirm</font>
                    </b>
                </th>
                <th colspan="2" class="bg-light-blue" style="text-align: center;">
                    <b>
                        <font style="color: #000;">Total</font>
                    </b>
                </th>
                <th colspan="1" class="bg-light-blue" style="text-align: center;">
                    <b>
                        <font style="color: #000;">Status</font>
                    </b>
                </th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px;">No.</th>
                <th>FG Code Set ABT</th>
                <th>Project Name</th>
                <th>Package Code</th>
                <th>SNP</th>
                <th>FG Code GDJ</th>
                <th>GDJ Description</th>
                <th>Packing Standard</th>
                <th style="color: indigo;">Quantity (Pcs.)</th>
                <th style="color: indigo;">Price (Bath.)</th>
                <th>Date</th>
                <th style="color: indigo;">Quantity (Pcs.)</th>
                <th style="color: indigo;">Price (Bath.)</th>
                <th>Date</th>
                <th style="color: indigo;">Stock Quantity (Pcs.)</th>
                <th style="color: indigo;">Price (Bath.)</th>
                <th>Status</th>
                        </tr>
                </thead>
                <tbody>
                <?
                if($stock_locate == "ALL"){
                    $strSql = " 
                    SELECT  *
                        FROM (
                        
                        SELECT
                        ps_t_fg_code_set_abt,
                        ps_t_fg_code_gdj,
                        bom_fg_desc,
                        SUM(ps_t_tags_packing_std) as SUM_QTY,
                        dn_h_receive_date,
                        bom_snp,
                        receive_status,
                        bom_packing,
                        bom_ctn_code_normal,
                        usage_pick_by,
                        usage_pick_date,
                        bom_pj_name,
                        bom_price_sale_per_pcs,
                        tags_packing_std
                        
                        FROM tbl_dn_head 
                        left join tbl_dn_tail 
                        on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
                        left join tbl_picking_head
                        on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
                        left join tbl_picking_tail
                        on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
                        left join tbl_bom_mst 
                        on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
                        and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
                        and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
                        and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
                        and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
                        and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
                        left join tbl_receive
                        on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
                        left join tbl_usage_conf
                        on tbl_usage_conf.usage_tags_code = tbl_picking_tail.ps_t_tags_code
                        left join tbl_tags_running
                        on tbl_usage_conf.usage_tags_code = tbl_tags_running.tags_code
                        where
                        dn_h_status = 'Confirmed'  and dn_h_receive_date < '$date_start' 
                        group by 
                        ps_t_fg_code_set_abt,
                        ps_t_fg_code_gdj,
                        bom_fg_desc,
                        ps_t_tags_packing_std,
                        dn_h_receive_date,
                        bom_snp,
                        receive_status,
                        bom_packing,
                        bom_ctn_code_normal,
                        usage_pick_by,
                        usage_pick_date,
                        bom_pj_name,
                        bom_price_sale_per_pcs,
                        tags_packing_std
                        
                        
                        UNION ALL
                        
                        
                        SELECT
                        ps_t_fg_code_set_abt,
                        ps_t_fg_code_gdj,
                        bom_fg_desc,
                        SUM(ps_t_tags_packing_std) as SUM_QTY,
                        dn_h_receive_date,
                        bom_snp,
                        receive_status,
                        bom_packing,
                        bom_ctn_code_normal,
                        usage_pick_by,
                        usage_pick_date,
                        bom_pj_name,
                        bom_price_sale_per_pcs,
                        tags_packing_std
                        FROM tbl_dn_head
                        left join tbl_dn_tail 
                        on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
                        left join tbl_picking_head
                        on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
                        left join tbl_picking_tail
                        on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
                        left join tbl_bom_mst 
                        on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
                        and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
                        and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
                        and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
                        and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
                        and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
                        left join tbl_receive
                        on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
                        left join tbl_usage_conf
                        on tbl_usage_conf.usage_tags_code = tbl_picking_tail.ps_t_tags_code
                        left join tbl_tags_running
                        on tbl_usage_conf.usage_tags_code = tbl_tags_running.tags_code
                        where
                        dn_h_status = 'Confirmed' 
                        and (dn_h_receive_date between '$date_start' and '$date_end') 
                        group by 
                        ps_t_fg_code_set_abt,
                        ps_t_fg_code_gdj,
                        bom_fg_desc,
                        ps_t_tags_packing_std,
                        dn_h_receive_date,
                        bom_snp,
                        receive_status,
                        bom_packing,
                        bom_ctn_code_normal,
                        usage_pick_by,
                        usage_pick_date,
                        bom_pj_name,
                        bom_price_sale_per_pcs,
                        tags_packing_std
                        ) as a
                        order by ps_t_fg_code_set_abt asc, ps_t_fg_code_gdj,dn_h_receive_date, usage_pick_date
        
                                        ";                   
                }else{
                $strSql = " 
            SELECT  *
                FROM (
                
                SELECT
                ps_t_fg_code_set_abt,
                ps_t_fg_code_gdj,
                bom_fg_desc,
                SUM(ps_t_tags_packing_std) as SUM_QTY,
                dn_h_receive_date,
                bom_snp,
                receive_status,
                bom_packing,
                bom_ctn_code_normal,
                usage_pick_by,
                usage_pick_date,
                bom_pj_name,
                bom_price_sale_per_pcs,
                tags_packing_std
                
                FROM tbl_dn_head 
                left join tbl_dn_tail 
                on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
                left join tbl_picking_head
                on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
                left join tbl_picking_tail
                on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
                left join tbl_bom_mst 
                on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
                and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
                and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
                and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
                and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
                and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
                left join tbl_receive
                on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
                left join tbl_usage_conf
                on tbl_usage_conf.usage_tags_code = tbl_picking_tail.ps_t_tags_code
                left join tbl_tags_running
                on tbl_usage_conf.usage_tags_code = tbl_tags_running.tags_code
                where
                dn_h_status = 'Confirmed'  and dn_h_receive_date < '$date_start' and receive_status = '$stock_locate' 
                group by 
                ps_t_fg_code_set_abt,
                ps_t_fg_code_gdj,
                bom_fg_desc,
                ps_t_tags_packing_std,
                dn_h_receive_date,
                bom_snp,
                receive_status,
                bom_packing,
                bom_ctn_code_normal,
                usage_pick_by,
                usage_pick_date,
                bom_pj_name,
                bom_price_sale_per_pcs,
                tags_packing_std
                
                
                UNION ALL
                
                
                SELECT
                ps_t_fg_code_set_abt,
                ps_t_fg_code_gdj,
                bom_fg_desc,
                SUM(ps_t_tags_packing_std) as SUM_QTY,
                dn_h_receive_date,
                bom_snp,
                receive_status,
                bom_packing,
                bom_ctn_code_normal,
                usage_pick_by,
                usage_pick_date,
                bom_pj_name,
                bom_price_sale_per_pcs,
                tags_packing_std
                FROM tbl_dn_head
                left join tbl_dn_tail 
                on tbl_dn_head.dn_h_dtn_code = tbl_dn_tail.dn_t_dtn_code
                left join tbl_picking_head
                on tbl_dn_tail.dn_t_picking_code = tbl_picking_head.ps_h_picking_code
                left join tbl_picking_tail
                on tbl_picking_head.ps_h_picking_code = tbl_picking_tail.ps_t_picking_code
                left join tbl_bom_mst 
                on tbl_picking_tail.ps_t_fg_code_set_abt = tbl_bom_mst.bom_fg_code_set_abt
                and tbl_picking_tail.ps_t_sku_code_abt = tbl_bom_mst.bom_fg_sku_code_abt
                and tbl_picking_tail.ps_t_fg_code_gdj = tbl_bom_mst.bom_fg_code_gdj
                and tbl_picking_tail.ps_t_pj_name = tbl_bom_mst.bom_pj_name
                and tbl_picking_tail.ps_t_ship_type = tbl_bom_mst.bom_ship_type
                and tbl_picking_tail.ps_t_part_customer = tbl_bom_mst.bom_part_customer
                left join tbl_receive
                on tbl_receive.receive_tags_code = tbl_picking_tail.ps_t_tags_code
                left join tbl_usage_conf
                on tbl_usage_conf.usage_tags_code = tbl_picking_tail.ps_t_tags_code
                left join tbl_tags_running
                on tbl_usage_conf.usage_tags_code = tbl_tags_running.tags_code
                where
                dn_h_status = 'Confirmed' and bom_pj_name = '$stock_locate' 
                and (dn_h_receive_date between '$date_start' and '$date_end') 
                group by 
                ps_t_fg_code_set_abt,
                ps_t_fg_code_gdj,
                bom_fg_desc,
                ps_t_tags_packing_std,
                dn_h_receive_date,
                bom_snp,
                receive_status,
                bom_packing,
                bom_ctn_code_normal,
                usage_pick_by,
                usage_pick_date,
                bom_pj_name,
                bom_price_sale_per_pcs,
                tags_packing_std
                ) as a
                order by ps_t_fg_code_set_abt asc, ps_t_fg_code_gdj,dn_h_receive_date, usage_pick_date

                                ";
                }
$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;

$temp_for_check = array();

while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	

    $ps_t_fg_code_set_abt = $objResult['ps_t_fg_code_set_abt'];   
    $ps_t_fg_code_gdj = $objResult['ps_t_fg_code_gdj'];
    $bom_fg_desc = $objResult['bom_fg_desc'];
    $SUM_QTY = $objResult['SUM_QTY'];
    $dn_h_receive_date = $objResult['dn_h_receive_date'];
    $bom_snp = $objResult['bom_snp'];
    $receive_status = $objResult['receive_status'];
    $bom_packing = $objResult['bom_packing'];
    $bom_ctn_code_normal = $objResult['bom_ctn_code_normal'];
    $usage_pick_by = $objResult['usage_pick_by'];
    $usage_pick_date = $objResult['usage_pick_date'];
    $bom_pj_name = $objResult['bom_pj_name'];        
    $bom_price_sale_per_pcs = $objResult['bom_price_sale_per_pcs']; 
    $tags_packing_std = $objResult['tags_packing_std']; 	

    $temp_abt = array();
 
    
    array_push($temp_abt, $ps_t_fg_code_set_abt,$ps_t_fg_code_gdj, $bom_fg_desc,$SUM_QTY, 
    $dn_h_receive_date, $bom_snp, $receive_status, $bom_packing, $bom_ctn_code_normal, $usage_pick_by, 
    $usage_pick_date, $bom_pj_name,$bom_price_sale_per_pcs,$tags_packing_std);
    array_push($temp_for_check, $temp_abt);
      
   

    // $usage_part_customer_arr = explode('-', $usage_part_customer);

    // $price = $pcs_num * $price_num;

    // var_dump($temp_for_check);
// $result = array();
// foreach ($temp_for_check as $element) {
//     $result[$element[1]][] = $element;
// }
// // var_dump($result);
// foreach($result as $kk => $va)
// { 
   
// }  
}
?>
<?

$abt;
$gdj;
$show = 0;
$temp_for_next_line = 0;
foreach ($temp_for_check as $key=>$element) {
    $row_id++;   
   //var_dump($key);
   if($key == 0){
        $abt = $element[0];
        $gdj = $element[1];
        if($element[6] == 'USAGE CONFIRM'){
            $show = ($element[3] - $element[13]);
        }else{
            $show = $element[3];
        }      
   }else{
       if(($abt == $element[0]) && ($gdj == $element[1])){
            if($element[6] == 'USAGE CONFIRM'){   
                $show = ($temp_for_next_line + $element[3]) - $element[13];
                $temp_for_next_line = $show;  
            }else{
                $show = $show + $element[3];     
                $temp_for_next_line = $show;           
            }
       }else{
            $temp_for_next_line = 0;
            $abt = $element[0];
            $gdj = $element[1];
            $show = $element[3] - $element[13];
            $temp_for_next_line = $show;
       }
   }
?>
    <tr style="font-size: 13px;">
    <td><?= $row_id; ?></td>
    <td>
        <?= $element[0]; ?>
    </td>
    <td><?= $element[11]; ?></td>
    <td><?= $element[8]; ?></td>
    <td><?= $element[5]; ?></td>
    <td><?= $element[1];?></td>
    <td><?= $element[2]; ?></td>           
    <td><?= $element[7];?></td>
    <? if($element[6] == $stock_locate){?>
        <td style="color: green;"><?= $element[3];?></td>
    <?}else{?> 
        <td style="color: green;"><?= $element[3];?></td>
    <? 
    }
    ?>
    <? if($element[6] == $stock_locate){?>
        <td style="color: green;"><?= number_format($element[12] * $element[3],2);?></td>
    <?}else{?> 
        <td style="color: green;"><?= number_format($element[12] * $element[3],2);?></td>
    <? 
    }
    ?>
    <? if($element[6] == $stock_locate){?>
        <td><?= $element[4];?></td>
    <?}else{?> 
        <td><?= $element[4];?></td>
    <? 
    }
    ?>
    <? if($element[6] == 'USAGE CONFIRM'){?>
        <td style="color: red;"><?= $element[13];?></td>
    <?}else{?> 
        <td>0</td>
    <? 
    }
    ?>
    <? if($element[6] == 'USAGE CONFIRM'){?>
        <td style="color: red;"><?= number_format($element[12] * $element[13],2);?></td>
    <?}else{?> 
        <td>0</td>
    <? 
    }
    ?>
    <? if($element[6] == 'USAGE CONFIRM'){?>
        <td ><?= $element[10];?></td>
    <?}else{?> 
        <td>-</td>
    <? 
    }
    ?>
       <? if($show > 0){?>
        <td style="color: green;"><?= $show;?></td>
    <?}else{?> 
        <td style="color: red;"><?= 0 - $show;?></td>
    <? 
    }
    ?>  
    <td><?=number_format($show * $element[12],2)?></td>

    <? if($element[6] == $stock_locate){?>
        <td style="color: green;">IN</td>
    <?}else{?> 
        <td style="color: red;">OUT</td>
    <? 
    }
    ?>
    <?
    }
    ?>

    </tr>
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
            pagingType: "full_numbers",

        });

        var buttons = new $.fn.dataTable.Buttons(oTable, {
            buttons: [{
                extend: 'excel',
                text: '<i class="fa fa-file-excel-o"></i> Export Transaction',
                titleAttr: 'Excel Transaction Report',
                title: 'Excel Transaction Report',
                exportOptions: {
                    modifier: {
                        page: 'all'
                    },
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
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
        //         max.setDate(max.getDate() + 1);
        //         var startDate = new Date(data[13]);
        //         if (min == null && max == null) return true;
        //         if (min == null && startDate <= max) return true;
        //         if (max == null && startDate >= min) return true;
        //         if (startDate <= max && startDate >= min) return true;
        //         return false;
        //     }
        // );




        // // Event listener to the two range filtering inputs to redraw on input
        // $('#min, #max').change(function() {
        //     oTable.draw();
        // });

    });
</script>