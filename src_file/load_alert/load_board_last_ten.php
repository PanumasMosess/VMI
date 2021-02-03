<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

?>


<!-- TABLE: LATEST ORDERS -->
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"></h3><b>&nbsp;Latest Usage Confirm Orders</b>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table class="table no-margin">
                <thead>
                    <tr>
                        <th>Tag Code</th>
                        <th>FG GDJ Code</th>
                        <th style="text-align: center;">Packing std.</th>
                        <th style="text-align: center;">Project name</th>
                        <th style="text-align: center;">Total Price.</th>
                        <!-- <th>Status</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?
                    $strSql = " 
                    select top(7)						  
                    receive_tags_code
                    ,usage_fg_code_gdj
                    ,tags_packing_std
                    ,usage_price_sale_per_pcs
                    ,receive_status
                    ,usage_terminal_name
                    ,CONVERT(float, usage_price_sale_per_pcs) * CONVERT(float, tags_packing_std) as total_wms_pcs_price
                from tbl_receive
                left join
                    tbl_tags_running
                on
                tbl_receive.receive_tags_code = tbl_tags_running.tags_code
                    left join tbl_picking_tail
                on tbl_tags_running.tags_code = tbl_picking_tail.ps_t_tags_code
                    left join tbl_usage_conf 
                on tbl_tags_running.tags_code = tbl_usage_conf.usage_tags_code		
                group by
                     receive_tags_code
                    ,usage_fg_code_gdj
                    ,tags_packing_std
                    ,usage_price_sale_per_pcs
                    ,receive_status
                    ,usage_terminal_name
                    ,usage_pick_datetime
                order by usage_pick_datetime desc
                    ";
                    
                    $objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
                    $num_row = sqlsrv_num_rows($objQuery);
                    
                    $row_id = 0;
                    while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
                    {
                         
                            $row_id++;
                    
                            $receive_tags_code = $objResult['receive_tags_code'];
                            $usage_fg_code_gdj = $objResult['usage_fg_code_gdj'];
                            $tags_packing_std = $objResult['tags_packing_std'];
                            $usage_terminal_name = $objResult['usage_terminal_name'];
                            $total_wms_pcs_price = $objResult['total_wms_pcs_price']; 
                            $receive_status = $objResult['receive_status']; 
                
                ?>

                    <tr>

                        <td><span style="color: #3c8dbc;" class="product-title"><b><?= $receive_tags_code ?></b></span></td>
                        <td><span style="color: #999; font-size: 14px; font-weight:400;"><?= $usage_fg_code_gdj ?></span></td>
                        <td style="text-align: center;"><span style="color: #999; font-size: 14px; font-weight:400;"><?= $tags_packing_std ?></span></td>
                        <td style="text-align: center;"><span style="color: #999; font-size: 14px; font-weight:400;"><?= $usage_terminal_name ?></span></td>
                        <?
                            if(number_format($total_wms_pcs_price,2) == 0)
                            {
                        ?>
                        <td style="text-align: center;"><span class="label label-danger pull-center"><?= number_format($total_wms_pcs_price, 2) ?> ฿</span></td>

                        <?
                            } else {
                        ?>
                        <td style="text-align: center;"><span class="label label-success pull-center"><?= number_format($total_wms_pcs_price, 2) ?> ฿</span></td>
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
        <!-- /.table-responsive -->
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
        <!-- <a href="<?=$CFG->wwwroot;?>/usage_confirm" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a> -->
    </div>
    <!-- /.box-footer -->
</div>
<!-- /.box -->