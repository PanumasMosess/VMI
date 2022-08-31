<?
require_once("../../application.php");

$objQuery_delete_vmi_duplicate = sqlsrv_query($db_con, " EXEC sp_db_delete_duplcate_replinsh_vmi ");

$strSql = "EXEC Replinish_Normal";
$objQuery = sqlsrv_query($db_con, $strSql);

$row_id = 0;
$json = array();
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
    $row_id++;

         //conv to pack
         if ($objResult["bom_packing"] > 0) {
            $str_fifo_picking_pack = ceil($objResult["repn_qty"] / $objResult["bom_packing"]);
            $str_conv_pack = floor($objResult["repn_qty"] / $objResult["bom_packing"]);
            $str_conv_piece = $objResult["repn_qty"] % $objResult["bom_packing"];


            //check piece
            if ($str_conv_piece > 0) {
                if ($str_conv_pack > 0) {
                    $remark_pack_piece = $str_conv_pack . " Pack, " . $str_conv_piece . " Pcs.";
                } else {
                    $remark_pack_piece = $str_conv_piece . " Pcs.";
                }
            } else {
                $remark_pack_piece = $str_conv_pack . " Pack";
            }
        } else {
            $str_fifo_picking_pack = 0;
            $str_conv_pack = 0;
            $str_conv_piece = 0;
            $remark_pack_piece = $str_conv_pack . " Pack, " . $str_conv_piece . " Pcs.";
        }

        //get stock each fg code gdj
        $pj_name = $objResult["bom_pj_name"];
        $fg_gdj  = $objResult["bom_fg_code_gdj"];
        $exc_str_stock = sqlsrv_query($db_con, " EXEC sp_db_get_stock_each_fg_gdj '$fg_gdj', '$pj_name' ");
        $objResult_str_stock = sqlsrv_fetch_array($exc_str_stock, SQLSRV_FETCH_ASSOC);

        if($objResult_str_stock['sum_packing_std'] == NULL){ $str_stock = '0'; } else { $str_stock = $objResult_str_stock['sum_packing_std']; }

    //  $str_stock = get_stock_each_fg_gdj($db_con, $objResult["repn_fg_code_set_abt"], $objResult["repn_sku_code_abt"], $objResult["bom_fg_code_gdj"], $objResult["bom_pj_name"], $objResult["bom_ship_type"], $objResult["bom_part_customer"]);
        if ($objResult["bom_packing"] > 0) {
            $str_stock_conv_pack = ceil($str_stock / $objResult["bom_packing"]);
        } else {
            $str_stock_conv_pack = 0;
        }


    $json_array_ = array(
        "row_id" => $row_id,
        "repn_id" => $objResult['repn_id'],
        "repn_order_ref" => $objResult['repn_order_ref'],
        "repn_sku_code_abt" => $objResult['repn_sku_code_abt'],
		"repn_fg_code_set_abt" => $objResult['repn_fg_code_set_abt'],
		"repn_qty" => $objResult['repn_qty'],
        "repn_unit_type" => $objResult['repn_unit_type'],
		"repn_terminal_name" => $objResult['repn_terminal_name'],
		"repn_order_type" => $objResult['repn_order_type'],
        "repn_delivery_date" => $objResult['repn_delivery_date'],
        "repn_by" => $objResult['repn_by'],
        "repn_date" => $objResult['repn_date'],
        "repn_time" => $objResult['repn_time'],
        "repn_datetime" => $objResult['repn_datetime'],
        "repn_conf_status" => $objResult['repn_conf_status'],
        "bom_fg_code_gdj" => $objResult['bom_fg_code_gdj'],
        "bom_cus_code" => $objResult['bom_cus_code'],
        "bom_pj_name" => $objResult['bom_pj_name'],
        "bom_ship_type" => $objResult['bom_ship_type'],
        "bom_packing" => $objResult['bom_packing'],
        "bom_part_customer" => $objResult['bom_part_customer'],
        "str_fifo_picking_pack" => $str_fifo_picking_pack,
        "remark_pack_piece" => $remark_pack_piece,
        "str_stock" => $str_stock,
        "str_stock_conv_pack" => $str_stock_conv_pack,
        "repn_datetime_cut" => substr($objResult['repn_datetime'], 0, 19)
    );
	
    array_push($json, $json_array_);
}
echo json_encode($json);
?>

