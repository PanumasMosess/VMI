<?
require_once("../../application.php");
header("Content-Type:text/html; charset=utf-8");
require_once '../../PHPExcelClasses/PHPExcel.php'; //PHPExcel
include '../../PHPExcelClasses/PHPExcel/IOFactory.php'; //PHPExcel_IOFactory - Reader	

//close error
ini_set('display_errors', 0);
//Turn off all error reporting
error_reporting(0);

//bypass execu time / memory limit
ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '-1');

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$txt_sel_cus = isset($_POST['txt_sel_cus']) ? $_POST['txt_sel_cus'] : '';

//var file upload
$fileupload = $_FILES['order_file']['tmp_name'];
$fileupload_name = $_FILES['order_file']['name'];

//ALBATROSS LOGISTICS CO., LTD. , MAZDA SALES (THAILAND) CO.,LTD.
//check customer
if ($txt_sel_cus == "Pack") {
	// //Check file upload
	if ($fileupload) {
		$array_last = explode(".", $fileupload_name);
		$c = count($array_last) - 1;
		$lastname = strtolower($array_last[$c]);

		if (($lastname == "xls") || ($lastname == "xlsx")) {
			//Rename , copy text file
			$buffer_file_name = "ORDER MASTER" . "." . $lastname;
			copy($fileupload, "../../upload_order_file/" . $buffer_file_name);

			////PHPExcel////
			$inputFileName = ("../../upload_order_file/" . $buffer_file_name);
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($inputFileName);

			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();

			//Check count fields excel
			$sum_highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);

			$headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
			$headingsArray = $headingsArray[1];

			$r = -1;
			$namedDataArray = array();
			for ($row = 2; $row <= $highestRow; ++$row) {
				$dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
				if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
					++$r;
					foreach ($headingsArray as $columnKey => $columnHeading) {
						$namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
					}
				}
			}

			//echo '<pre>';
			//var_dump($namedDataArray);
			//echo '</pre><hr />';

			//Check count fields table
			$strSQL_check_col = "select * from tbl_replenishment ";
			$objQuery_check_col = sqlsrv_query($db_con, $strSQL_check_col);
			$sum_fields = sqlsrv_num_fields($objQuery_check_col);

			$sum_fields = $sum_fields - 16;
			if ($sum_fields != $sum_highestColumn) {
				sleep(2);
				////echo "<script>alert('Error !! Column in excel file does not match the database.');</script>";
				echo "<script>window.top.window.showResult_frmUploadOrder('1');</script>";
				die;
			}

			//// Insert to sql Database ////
			$i = 0;
			$tigger_date_check = 0;

			//clear
			$objQuery_insert = false;
			$objQuery_update = false;

			foreach ($namedDataArray as $result_check) {
				//var check 
				$chk_delivery_date = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($result_check["Delivery Date"]));

				//check cus code 
				if ($chk_delivery_date == "1900-01-01") {
					$tigger_date_check = $tigger_date_check + 1;
				}
			}

			$count_data = 0;
			//check data form bom 
			foreach ($namedDataArray as $result_check) {

				//var check 
				$str_fg_code_cus = strtoupper($result_check["FG Code Set ABT"]);
				$str_component_code_cus = strtoupper($result_check["Component Code ABT"]);
				$str_fg_code_gdj = strtoupper($result_check["FG Code GDJ"]);
				$str_pj_name = strtoupper($result_check["Project Name"]);
				$str_ship_type = strtoupper($result_check["Ship Type"]);
				$str_part_customer = strtoupper($result_check["Part Customer"]);

				///Check BOM //
				$str_sql_check = "select * from tbl_bom_mst where bom_fg_code_set_abt = '$str_fg_code_cus' and bom_fg_sku_code_abt = '$str_component_code_cus' and bom_fg_code_gdj = '$str_fg_code_gdj' and bom_pj_name = '$str_pj_name' and bom_ship_type = '$str_ship_type' and bom_part_customer = '$str_part_customer' and bom_status = 'Active'";
				$objQuery_check_bom = sqlsrv_query($db_con, $str_sql_check, $params, $options);
				$num_row_chk_ = sqlsrv_num_rows($objQuery_check_bom);

				if ($num_row_chk_ > 0) {

					$count_data++;
				} else {
					break;
				}
			}

			//OK
			if (($tigger_date_check == 0) && (count($namedDataArray) == $count_data)) {
				foreach ($namedDataArray as $result) {
					//rows
					$i++;

					//check number not null
					if ($result["Order Qty."] == null) {
						$tmp_order_qty = 0;
					} else {
						$tmp_order_qty = $result["Order Qty."];
					}

					//var check 
		 		    $str_component_code_cus = strtoupper($result["Component Code ABT"]);
					$str_fg_code_gdj = strtoupper($result["FG Code GDJ"]);
					$str_customer_code = strtoupper($result["Customer Code"]);
					$str_pj_name = strtoupper($result["Project Name"]);
					$str_ship_type = strtoupper($result["Ship Type"]);
					$str_part_customer = strtoupper($result["Part Customer"]);
					$str_unit_type = ucfirst(strtolower($result["Unit Type"]));
					$str_delivery_date = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($result["Delivery Date"]));

					//ceil for abt (qty = packing)
					$str_ceil_pcs_to_packing = auto_adjQty_pcs_to_packing($db_con, $str_fg_code_cus, $str_component_code_cus, $str_fg_code_gdj, $str_pj_name, $str_ship_type, $str_part_customer, $tmp_order_qty);

					//Process Insert / update to database
					//Check dulplicate
					$strSQL_Check = " select * from tbl_replenishment where [repn_order_ref] = '$str_order_ref' and [repn_fg_code_set_abt] = '$str_fg_code_cus' and [repn_sku_code_abt] = '$str_component_code_cus' and [repn_fg_code_gdj] = '$str_fg_code_gdj' and [repn_pj_name] = '$str_pj_name' and [repn_ship_type] = '$str_ship_type' and [repn_part_customer] = '$str_part_customer' and [repn_delivery_date] = '$str_delivery_date' ";
					$objQuery = sqlsrv_query($db_con, $strSQL_Check);

					//Check Boolean true / false
					$objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);
					if ($objResult) //Update
					{
						$strSQL_update = " UPDATE [dbo].[tbl_replenishment]
							SET [repn_order_ref] = '$str_order_ref'
							  ,[repn_fg_code_set_abt] = '$str_fg_code_cus'
							  ,[repn_sku_code_abt] = '$str_component_code_cus'
							  ,[repn_fg_code_gdj] = '$str_fg_code_gdj'
							  ,[repn_customer_code] = '$str_customer_code'
							  ,[repn_pj_name] = '$str_pj_name'
							  ,[repn_ship_type] = '$str_ship_type'
							  ,[repn_part_customer] = '$str_part_customer'
							  ,[repn_qty] = '$str_ceil_pcs_to_packing'
							  ,[repn_unit_type] = '$str_unit_type'
							  ,[repn_terminal_name] = '" . trim(func_remove_char($result["Terminal Name"])) . "'
							  ,[repn_order_type] = '" . trim(func_remove_char($result["Order Type"])) . "'
							  ,[repn_delivery_date] = '$str_delivery_date'
							  ,[repn_by] = '" . $t_cur_user_code_VMI_GDJ . "'
							  ,[repn_date] = '$buffer_date'
							  ,[repn_time] = '$buffer_time'
							  ,[repn_datetime] = '$buffer_datetime'
							WHERE [repn_order_ref] = '$str_order_ref' and [repn_fg_code_set_abt] = '$str_fg_code_cus' and [repn_sku_code_abt] = '$str_component_code_cus' and [repn_fg_code_gdj] = '$str_fg_code_gdj' and [repn_pj_name] = '$str_pj_name' and [repn_ship_type] = '$str_ship_type' and [repn_part_customer] = '$str_part_customer' and [repn_delivery_date] = '$str_delivery_date' ";
						$objQuery_update = sqlsrv_query($db_con, $strSQL_update);
					} else //Insert
					{
						$strSQL_insert = "
						INSERT INTO [dbo].[tbl_replenishment]
							   (
							   [repn_order_ref]
							   ,[repn_fg_code_set_abt]
							   ,[repn_sku_code_abt]
							   ,[repn_fg_code_gdj]
							   ,[repn_customer_code]
							   ,[repn_pj_name]
							   ,[repn_ship_type]
							   ,[repn_part_customer]
							   ,[repn_qty]
							   ,[repn_unit_type]
							   ,[repn_terminal_name]
							   ,[repn_order_type]
							   ,[repn_delivery_date]
							   ,[repn_by]
							   ,[repn_date]
							   ,[repn_time]
							   ,[repn_datetime]
							   )
						 VALUES
							   (
							   '$str_order_ref'
							   ,'$str_fg_code_cus'
							   ,'$str_component_code_cus'
							   ,'$str_fg_code_gdj'
							   ,'$str_customer_code'
							   ,'$str_pj_name'
							   ,'$str_ship_type'
							   ,'$str_part_customer'
							   ,'$str_ceil_pcs_to_packing'
							   ,'$str_unit_type'
							   ,'" . trim(func_remove_char($result["Terminal Name"])) . "'
							   ,'" . trim(func_remove_char($result["Order Type"])) . "'
							   ,'$str_delivery_date'
							   ,'" . $t_cur_user_code_VMI_GDJ . "'
							   ,'$buffer_date'
							   ,'$buffer_time'
							   ,'$buffer_datetime'
							   )
						";
						$objQuery_insert = sqlsrv_query($db_con, $strSQL_insert);
					}
				}

				//check insert update
				if (($objQuery_update) || ($objQuery_insert)) {
					sleep(2);
					//echo "<script>alert('Upload BOM file success.');</script>";
					echo "<script>window.top.window.showResult_frmUploadOrder('2');</script>";
				} else {
					sleep(2);
					////echo "<script>alert('Error !! Unable to upload BOM file.');</script>";
					echo "<script>window.top.window.showResult_frmUploadOrder('3');</script>";
				}
			} else {
				$data_back = $count_data + 1;
				sleep(2);
				////echo "<script>alert('Error !! Unable to upload BOM file.');</script>";
				echo "<script>window.top.window.showResult_frmUploadOrder('Row ที่ $data_back');</script>";
			}


			sqlsrv_close($db_con);
		} else {
			sleep(2);
			////echo "<script>alert('Error !! Wrong file type (Must be a file (.xls, .xlsx) only.');</script>";
			echo "<script>window.top.window.showResult_frmUploadOrder('4');</script>";
		}
	}
} else if ($txt_sel_cus == "Split") {
	//Check file upload
	if ($fileupload) {
		$array_last = explode(".", $fileupload_name);
		$c = count($array_last) - 1;
		$lastname = strtolower($array_last[$c]);

		if (($lastname == "xls") || ($lastname == "xlsx")) {
			//Rename , copy text file
			$buffer_file_name = "ORDER MASTER" . "." . $lastname;
			copy($fileupload, "../../upload_order_file/" . $buffer_file_name);

			////PHPExcel////
			$inputFileName = ("../../upload_order_file/" . $buffer_file_name);
			$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);
			$objReader->setReadDataOnly(true);
			$objPHPExcel = $objReader->load($inputFileName);

			$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
			$highestRow = $objWorksheet->getHighestRow();
			$highestColumn = $objWorksheet->getHighestColumn();

			//Check count fields excel
			$sum_highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);

			$headingsArray = $objWorksheet->rangeToArray('A1:' . $highestColumn . '1', null, true, true, true);
			$headingsArray = $headingsArray[1];

			$r = -1;
			$namedDataArray = array();
			for ($row = 2; $row <= $highestRow; ++$row) {
				$dataRow = $objWorksheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, null, true, true, true);
				if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > '')) {
					++$r;
					foreach ($headingsArray as $columnKey => $columnHeading) {
						$namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
					}
				}
			}

			//echo '<pre>';
			//var_dump($namedDataArray);
			//echo '</pre><hr />';

			//Check count fields table
			$strSQL_check_col = "select * from tbl_replenishment ";
			$objQuery_check_col = sqlsrv_query($db_con, $strSQL_check_col);
			$sum_fields = sqlsrv_num_fields($objQuery_check_col);

			$sum_fields = $sum_fields - 15;
			if ($sum_fields != $sum_highestColumn) {
				sleep(2);
				////echo "<script>alert('Error !! Column in excel file does not match the database.');</script>";
				echo "<script>window.top.window.showResult_frmUploadOrder('1');</script>";
				die;
			}

			//// Insert to sql Database ////
			$i = 0;
			$tigger_date_check = 0;

			//clear
			$objQuery_insert = false;
			$objQuery_update = false;

			foreach ($namedDataArray as $result_check) {
				//var check 
				$chk_delivery_date = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($result_check["Delivery Date"]));

				//check cus code 
				if ($chk_delivery_date == "1900-01-01") {
					$tigger_date_check = $tigger_date_check + 1;
				}
			}

			$count_data = 0;
			//check data form bom 
			foreach ($namedDataArray as $result_check) {

				//var check 
				$str_fg_code_cus = strtoupper($result_check["FG Code Set ABT"]);
				$str_component_code_cus = strtoupper($result_check["Component Code ABT"]);
				$str_fg_code_gdj = strtoupper($result_check["FG Code GDJ"]);
				$str_pj_name = strtoupper($result_check["Project Name"]);
				$str_ship_type = strtoupper($result_check["Ship Type"]);
				$str_part_customer = strtoupper($result_check["Part Customer"]);

				///Check BOM //
				$str_sql_check = "select * from tbl_bom_mst where bom_fg_code_set_abt = '$str_fg_code_cus' and bom_fg_sku_code_abt = '$str_component_code_cus' and bom_fg_code_gdj = '$str_fg_code_gdj' and bom_pj_name = '$str_pj_name' and bom_ship_type = '$str_ship_type' and bom_part_customer = '$str_part_customer' and bom_status = 'Active'";
				$objQuery_check_bom = sqlsrv_query($db_con, $str_sql_check, $params, $options);
				$num_row_chk_ = sqlsrv_num_rows($objQuery_check_bom);

				if ($num_row_chk_ > 0) {
					$count_data++;
				} else {
					break;
				}
			}

			//OK
			if (($tigger_date_check == 0) and (count($namedDataArray) == $count_data)) {
				foreach ($namedDataArray as $result) {
					//rows
					$i++;

					//check number not null
					if ($result["Order Qty."] == null) {
						$tmp_order_qty = 0;
					} else {
						$tmp_order_qty = $result["Order Qty."];
					}

					//var check 
					$str_order_ref = strtoupper($result["Order Ref."]);
					$str_fg_code_cus = strtoupper($result["FG Code Set ABT"]);
					$str_component_code_cus = strtoupper($result["Component Code ABT"]);
					$str_fg_code_gdj = strtoupper($result["FG Code GDJ"]);
					$str_customer_code = strtoupper($result["Customer Code"]);
					$str_pj_name = strtoupper($result["Project Name"]);
					$str_ship_type = strtoupper($result["Ship Type"]);
					$str_part_customer = strtoupper($result["Part Customer"]);
					$str_unit_type = ucfirst(strtolower($result["Unit Type"]));
					$str_delivery_date = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($result["Delivery Date"]));

					//Process Insert / update to database
					//Check dulplicate
					$strSQL_Check = " select * from tbl_replenishment where [repn_order_ref] = '$str_order_ref' and [repn_fg_code_set_abt] = '$str_fg_code_cus' and [repn_sku_code_abt] = '$str_component_code_cus' and [repn_fg_code_gdj] = '$str_fg_code_gdj' and [repn_pj_name] = '$str_pj_name' and [repn_ship_type] = '$str_ship_type' and [repn_part_customer] = '$str_part_customer' and [repn_delivery_date] = '$str_delivery_date' ";
					$objQuery = sqlsrv_query($db_con, $strSQL_Check);

					//Check Boolean true / false
					$objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);
					if ($objResult) //Update
					{
						$strSQL_update = " UPDATE [dbo].[tbl_replenishment]
							SET [repn_order_ref] = '$str_order_ref'
							  ,[repn_fg_code_set_abt] = '$str_fg_code_cus'
							  ,[repn_sku_code_abt] = '$str_component_code_cus'
							  ,[repn_fg_code_gdj] = '$str_fg_code_gdj'
							  ,[repn_customer_code] = '$str_customer_code'
							  ,[repn_pj_name] = '$str_pj_name'
							  ,[repn_ship_type] = '$str_ship_type'
							  ,[repn_part_customer] = '$str_part_customer'
							  ,[repn_qty] = '$tmp_order_qty'
							  ,[repn_unit_type] = '$str_unit_type'
							  ,[repn_terminal_name] = '" . trim(func_remove_char($result["Terminal Name"])) . "'
							  ,[repn_order_type] = '" . trim(func_remove_char($result["Order Type"])) . "'
							  ,[repn_delivery_date] = '$str_delivery_date'
							  ,[repn_by] = '" . $t_cur_user_code_VMI_GDJ . "'
							  ,[repn_date] = '$buffer_date'
							  ,[repn_time] = '$buffer_time'
							  ,[repn_datetime] = '$buffer_datetime'
							WHERE [repn_order_ref] = '$str_order_ref' and [repn_fg_code_set_abt] = '$str_fg_code_cus' and [repn_sku_code_abt] = '$str_component_code_cus' and [repn_fg_code_gdj] = '$str_fg_code_gdj' and [repn_pj_name] = '$str_pj_name' and [repn_ship_type] = '$str_ship_type' and [repn_part_customer] = '$str_part_customer' and [repn_delivery_date] = '$str_delivery_date' ";
						$objQuery_update = sqlsrv_query($db_con, $strSQL_update);
					} else //Insert
					{
						$strSQL_insert = "
						INSERT INTO [dbo].[tbl_replenishment]
							   (
							   [repn_order_ref]
							   ,[repn_fg_code_set_abt]
							   ,[repn_sku_code_abt]
							   ,[repn_fg_code_gdj]
							   ,[repn_customer_code]
							   ,[repn_pj_name]
							   ,[repn_ship_type]
							   ,[repn_part_customer]
							   ,[repn_qty]
							   ,[repn_unit_type]
							   ,[repn_terminal_name]
							   ,[repn_order_type]
							   ,[repn_delivery_date]
							   ,[repn_by]
							   ,[repn_date]
							   ,[repn_time]
							   ,[repn_datetime]
							   )
						 VALUES
							   (
							   '$str_order_ref'
							   ,'$str_fg_code_cus'
							   ,'$str_component_code_cus'
							   ,'$str_fg_code_gdj'
							   ,'$str_customer_code'
							   ,'$str_pj_name'
							   ,'$str_ship_type'
							   ,'$str_part_customer'
							   ,'$tmp_order_qty'
							   ,'$str_unit_type'
							   ,'" . trim(func_remove_char($result["Terminal Name"])) . "'
							   ,'" . trim(func_remove_char($result["Order Type"])) . "'
							   ,'$str_delivery_date'
							   ,'" . $t_cur_user_code_VMI_GDJ . "'
							   ,'$buffer_date'
							   ,'$buffer_time'
							   ,'$buffer_datetime'
							   )
						";
						$objQuery_insert = sqlsrv_query($db_con, $strSQL_insert);
					}
				}

				//check insert update
				if (($objQuery_update) || ($objQuery_insert)) {
					sleep(2);
					//echo "<script>alert('Upload BOM file success.');</script>";
					echo "<script>window.top.window.showResult_frmUploadOrder('2');</script>";
				} else {
					sleep(2);
					////echo "<script>alert('Error !! Unable to upload BOM file.');</script>";
					echo "<script>window.top.window.showResult_frmUploadOrder('3');</script>";
				}
			} else {
				$data_back = $count_data + 1;
				sleep(2);
				////echo "<script>alert('Error !! Unable to upload BOM file.');</script>";
				echo "<script>window.top.window.showResult_frmUploadOrder('Row ที่ $data_back');</script>";
			}

			sqlsrv_close($db_con);
		} else {
			sleep(2);
			////echo "<script>alert('Error !! Wrong file type (Must be a file (.xls, .xlsx) only.');</script>";
			echo "<script>window.top.window.showResult_frmUploadOrder('4');</script>";
		}
	}
} else {
	sleep(2);
	////echo "<script>alert('Error !! Unable to upload BOM file.');</script>";
	echo "<script>window.top.window.showResult_frmUploadOrder('5');</script>";
}


