<?
require_once("../../application.php");
header("Content-Type:text/html; charset=utf-8");
require_once '../../PHPExcelClasses/PHPExcel.php'; //PHPExcel
include '../../PHPExcelClasses/PHPExcel/IOFactory.php'; //PHPExcel_IOFactory - Reader	

//close error
ini_set('display_errors', 0);
//Turn off all error reporting
error_reporting(0);

//bypass execu time
//ini_set('MAX_EXECUTION_TIME', '-1');

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

//var file upload
$fileupload = $_FILES['bom_file']['tmp_name'];
$fileupload_name = $_FILES['bom_file']['name'];

//Check file upload
if($fileupload)
{
	$array_last = explode(".",$fileupload_name);
	$c = count($array_last)-1; 
	$lastname = strtolower($array_last[$c]) ;
	
	if(($lastname == "xls") || ($lastname == "xlsx"))
	{
		//Rename , copy text file
		$buffer_file_name = "BOM MASTER".".".$lastname;
		copy($fileupload,"../../upload_master_file/".$buffer_file_name);

		////PHPExcel////
		$inputFileName = ("../../upload_master_file/".$buffer_file_name);
		$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($inputFileName);

		$objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();

		//Check count fields excel
		$sum_highestColumn = PHPExcel_Cell::columnIndexFromString($highestColumn);

		$headingsArray = $objWorksheet->rangeToArray('A1:'.$highestColumn.'1',null, true, true, true);
		$headingsArray = $headingsArray[1];

		$r = -1;
		$namedDataArray = array();
		for ($row = 2; $row <= $highestRow; ++$row)
		{
			$dataRow = $objWorksheet->rangeToArray('A'.$row.':'.$highestColumn.$row,null, true, true, true);
			if ((isset($dataRow[$row]['A'])) && ($dataRow[$row]['A'] > ''))
			{
				++$r;
				foreach($headingsArray as $columnKey => $columnHeading)
				{
					$namedDataArray[$r][$columnHeading] = $dataRow[$row][$columnKey];
				}
			}
		}
		
		//echo '<pre>';
		//var_dump($namedDataArray);
		//echo '</pre><hr />';
		
		//Check count fields table
		$strSQL_check_col = "select * from tbl_bom_mst ";
		$objQuery_check_col = sqlsrv_query($db_con, $strSQL_check_col);
		$sum_fields = sqlsrv_num_fields($objQuery_check_col);

		$sum_fields = $sum_fields - 6;
		if($sum_fields != $sum_highestColumn)
		{
			sleep(2);
			////echo "<script>alert('Error !! Column in excel file does not match the database.');</script>";
			echo "<script>window.top.window.showResult_frmUploadBom('1');</script>";
			die;
		}	
		
		//// Insert to sql Database ////
		$i = 0;
		
		//clear
		$objQuery_insert = false;
		$objQuery_update = false;
		
		foreach ($namedDataArray as $result)
		{
			//rows
			$i++;
			
			//check number not null
			if($result["Cost/Pcs"] == null){ $tmp_cost_per_pcs = 0; }else{ $tmp_cost_per_pcs = $result["Cost/Pcs"]; }
			if($result["Price Sale/Pcs"] == null){ $tmp_price_sale_per_pcs = 0; }else{ $tmp_price_sale_per_pcs = $result["Price Sale/Pcs"]; }

			//Process Insert / update to database
			//Check dulplicate
			$strSQL_Check = " select [bom_prefix] from tbl_bom_mst where [bom_prefix] = '".strtoupper($result["Prefix ID"])."' ";
			$objQuery = sqlsrv_query($db_con, $strSQL_Check);
								
			//Check Boolean true / false
			$objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);
			if($objResult) //Update
			{  
				$strSQL_update = " UPDATE [dbo].[tbl_bom_mst]
					   SET [bom_fg_code_set_abt] = '".trim(strtoupper($result["FG Code Set ABT"]))."'
						  ,[bom_fg_sku_code_abt] = '".trim(strtoupper($result["Component Code ABT"]))."'
						  ,[bom_fg_code_gdj] = '".trim(strtoupper($result["FG Code GDJ"]))."'
						  ,[bom_fg_desc] = '".trim(strtoupper(func_remove_char($result["Description"])))."'
						  ,[bom_cus_code] = '".trim(strtoupper($result["Customer Code"]))."'
						  ,[bom_cus_name] = '".trim(strtoupper(func_remove_char($result["Customer Name"])))."'
						  ,[bom_pj_name] = '".trim(strtoupper($result["Project Name"]))."'
						  ,[bom_ctn_code_normal] = '".trim(strtoupper($result["Carton Code Normal"]))."'
						  ,[bom_snp] = '".trim($result["SNP"])."'
						  ,[bom_sku_code] = '".trim(func_remove_char(strtoupper($result["Code"])))."'
						  ,[bom_ship_type] = '".trim(strtoupper($result["Ship to Type"]))."'
						  ,[bom_pckg_type] = '".trim(strtoupper($result["Package Type"]))."'
						  ,[bom_dims_w] = '".trim($result["W"])."'
						  ,[bom_dims_l] = '".trim($result["L"])."'
						  ,[bom_dims_h] = '".trim($result["H"])."'
						  ,[bom_usage] = '".trim($result["Usage"])."'
						  ,[bom_space_paper] = '".trim(strtoupper(func_remove_char($result["Space Paper"])))."'
						  ,[bom_flute] = '".trim($result["Flute"])."'
						  ,[bom_packing] = '".trim($result["Packing"])."'
						  ,[bom_wms_min] = '".trim($result["WMS Min"])."'
						  ,[bom_wms_max] = '".trim($result["WMS Max"])."'
						  ,[bom_vmi_min] = '".trim($result["VMI Min"])."'
						  ,[bom_vmi_max] = '".trim($result["VMI Max"])."'
						  ,[bom_part_customer] = '".trim(strtoupper($result["Part Customer"]))."'
						  ,[bom_cost_per_pcs] = '$tmp_cost_per_pcs'
						  ,[bom_price_sale_per_pcs] = '$tmp_price_sale_per_pcs'
						  ,[bom_issue_by] = '".$t_cur_user_code_VMI_GDJ."'
						  ,[bom_issue_date] = '$buffer_date'
						  ,[bom_issue_time] = '$buffer_time'
						  ,[bom_issue_datetime] = '$buffer_datetime'
					 WHERE [bom_prefix] = '".strtoupper($result["Prefix ID"])."'
					 ";
				$objQuery_update = sqlsrv_query($db_con, $strSQL_update);
			}
			else //Insert
			{
				$strSQL_insert = "
				INSERT INTO [dbo].[tbl_bom_mst]
					   (
						  [bom_prefix]
						  ,[bom_fg_code_set_abt]
						  ,[bom_fg_sku_code_abt]
						  ,[bom_fg_code_gdj]
						  ,[bom_fg_desc]
						  ,[bom_cus_code]
						  ,[bom_cus_name]
						  ,[bom_pj_name]
						  ,[bom_ctn_code_normal]
						  ,[bom_snp]
						  ,[bom_sku_code]
						  ,[bom_ship_type]
						  ,[bom_pckg_type]
						  ,[bom_dims_w]
						  ,[bom_dims_l]
						  ,[bom_dims_h]
						  ,[bom_usage]
						  ,[bom_space_paper]
						  ,[bom_flute]
						  ,[bom_packing]
						  ,[bom_wms_min]
						  ,[bom_wms_max]
						  ,[bom_vmi_min]
						  ,[bom_vmi_max]
						  ,[bom_part_customer]
						  ,[bom_cost_per_pcs]
						  ,[bom_price_sale_per_pcs]
						  ,[bom_status]
						  ,[bom_issue_by]
						  ,[bom_issue_date]
						  ,[bom_issue_time]
						  ,[bom_issue_datetime]
					   )
				 VALUES
					   (
					   '".trim($result["Prefix ID"])."'
					   ,'".trim(strtoupper($result["FG Code Set ABT"]))."'
					   ,'".trim(strtoupper($result["Component Code ABT"]))."'
					   ,'".trim(strtoupper($result["FG Code GDJ"]))."'
					   ,'".trim(strtoupper(func_remove_char($result["Description"])))."'
					   ,'".trim(strtoupper($result["Customer Code"]))."'
					   ,'".trim(strtoupper(func_remove_char($result["Customer Name"])))."'
					   ,'".trim(strtoupper($result["Project Name"]))."'
					   ,'".trim(strtoupper($result["Carton Code Normal"]))."'
					   ,'".trim($result["SNP"])."'
					   ,'".trim(func_remove_char(strtoupper($result["Code"])))."'
					   ,'".trim(strtoupper($result["Ship to Type"]))."'
					   ,'".trim(strtoupper($result["Package Type"]))."'
					   ,'".trim($result["W"])."'
					   ,'".trim($result["L"])."'
					   ,'".trim($result["H"])."'
					   ,'".trim($result["Usage"])."'
					   ,'".trim(strtoupper(func_remove_char($result["Space Paper"])))."'
					   ,'".trim($result["Flute"])."'
					   ,'".trim($result["Packing"])."'
					   ,'".trim($result["WMS Min"])."'
					   ,'".trim($result["WMS Max"])."'
					   ,'".trim($result["VMI Min"])."'
					   ,'".trim($result["VMI Max"])."'
					   ,'".trim(strtoupper($result["Part Customer"]))."'
					   ,'$tmp_cost_per_pcs'
					   ,'$tmp_price_sale_per_pcs'
					   ,'Active'
					   ,'".$t_cur_user_code_VMI_GDJ."'
					   ,'$buffer_date'
					   ,'$buffer_time'
					   ,'$buffer_datetime'
					   )
				";
				$objQuery_insert = sqlsrv_query($db_con, $strSQL_insert);
			}
		}
		
		//check insert update
		if(($objQuery_update) || ($objQuery_insert))
		{
			sleep(2);
			//echo "<script>alert('Upload BOM file success.');</script>";
			echo "<script>window.top.window.showResult_frmUploadBom('2');</script>";
		}
		else
		{
			sleep(2);
			////echo "<script>alert('Error !! Unable to upload BOM file.');</script>";
			echo "<script>window.top.window.showResult_frmUploadBom('3');</script>";
		}
		
		sqlsrv_close($db_con);
	}
	else
	{
		sleep(2);
		////echo "<script>alert('Error !! Wrong file type (Must be a file (.xls, .xlsx) only.');</script>";
		echo "<script>window.top.window.showResult_frmUploadBom('4');</script>";
	}
}
?>