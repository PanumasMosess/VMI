<?
require_once("../../application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$palate_code = isset($_POST['palate_code']) ? $_POST['palate_code'] : '';
$tags_code = isset($_POST['tags_code']) ? $_POST['tags_code'] : '';
$fg_code = isset($_POST['fg_code']) ? $_POST['fg_code'] : '';
$qty_stock = isset($_POST['qty_stock']) ? $_POST['qty_stock'] : '';
$status_stock = isset($_POST['status_stock']) ? $_POST['status_stock'] : '';
$location_stock = isset($_POST['location_stock']) ? $_POST['location_stock'] : '';

//check duplicate list checking stock
$strSQL_dup_check_stock = "select stock_tags_code from tbl_stock_checking 
where 
stock_tags_code = '$tags_code'
";
$objQuery_dup_check_stock = sqlsrv_query($db_con, $strSQL_dup_check_stock);
$result_dup_check_stock = sqlsrv_fetch_array($objQuery_dup_check_stock, SQLSRV_FETCH_ASSOC);

if($result_dup_check_stock)//check duplicate done
{
    echo "DUPLICATE";
}
else
{
   		//insert temp list check stock
		$strSql_insert_check_stock = " 
		INSERT INTO [dbo].[tbl_stock_checking]
		   (
            [stock_pallet_code]
		   ,[stock_tags_code]
           ,[stock_tags_fg_code_gdj]
           ,[stock_location]
           ,[stock_tags_packing_std]
           ,[stock_status]
           ,[stock_date]
		   )
		VALUES
		   (
		   '$palate_code'
		   ,'$tags_code'
		   ,'$fg_code'
		   ,'$location_stock'
           ,'$qty_stock'
           ,'$status_stock'
           ,'$buffer_date'
		   )
		";
        $objQuery_insert_check_stock = sqlsrv_query($db_con, $strSql_insert_check_stock);
        
        echo "INSERT_OK";
}


sqlsrv_close($db_con);
?>