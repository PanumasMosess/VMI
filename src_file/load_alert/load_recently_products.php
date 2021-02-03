<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

?>



<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title"></h3><b>&nbsp;Recently Added Products</b>

        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
            <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <ul class="products-list product-list-in-box">
            <?
                    $strSql = " 
                    SELECT TOP (4)       
                     [bom_fg_code_set_abt]
                    ,[bom_fg_desc]    
                    ,[bom_pj_name]
                    ,[bom_packing]
	                ,CONVERT(float, bom_price_sale_per_pcs) * CONVERT(float, bom_packing) as total_pcs_price
                     FROM [dbo].[tbl_bom_mst] ORDER BY bom_issue_datetime desc
                    ";
                    
                    $objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
                    $num_row = sqlsrv_num_rows($objQuery);
                    
                    $row_id = 0;
                    while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
            {
                         
                            $row_id++;
                    
                            $bom_fg_code_set_abt = $objResult['bom_fg_code_set_abt'];
                            $bom_fg_desc = $objResult['bom_fg_desc'];
                            $bom_pj_name = $objResult['bom_pj_name'];
                            $bom_packing = $objResult['bom_packing'];
                            $total_pcs_price = $objResult['total_pcs_price']; 
                
            ?>
            <li class="item">
                <div class="product-info">
                    <span style="color: #3c8dbc;" class="product-title"><b><?= $bom_fg_code_set_abt ?></b> </span>
                    <span class="label label-success pull-right"><?= $total_pcs_price ?> ฿.</span>
                    <span class="product-description">
                        <?= $bom_fg_desc ?>
                    </span>
                </div>
            </li>
            <?
            }                   
            ?>
            <!-- /.item -->
        </ul>
    </div>
    <!-- /.box-body -->
    <div class="box-footer text-center">
        <!-- <a href="<?= $CFG->wwwroot; ?>/bom_mst" class="uppercase">View All Products</a> -->
    </div>
    <!-- /.box-footer -->
</div>