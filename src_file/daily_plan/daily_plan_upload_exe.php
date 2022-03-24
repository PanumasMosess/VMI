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

//var file upload
$fileupload = $_FILES['daily_plan_file']['tmp_name'];
$fileupload_name = $_FILES['daily_plan_file']['name'];

//Check file upload
if($fileupload)
{
	$array_last = explode(".",$fileupload_name);
	$c = count($array_last)-1; 
	$lastname = strtolower($array_last[$c]) ;
	
	if(($lastname == "xls") || ($lastname == "xlsx"))
	{
		//Rename , copy text file
		$buffer_file_name = "Daily Plan".".".$lastname;
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
		$strSQL_check_col = "select * from tbl_daily_plan ";
		$objQuery_check_col = sqlsrv_query($db_con, $strSQL_check_col);
		$sum_fields = sqlsrv_num_fields($objQuery_check_col);

		$sum_fields = $sum_fields - 5;
		if($sum_fields != $sum_highestColumn)
		{
			sleep(2);
			////echo "<script>alert('Error !! Column in excel file does not match the database.');</script>";
			echo "<script>window.top.window.showResult_frmUploadDailyPlan('1');</script>";
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
			
			//Plan Date
			//Production Plan (ft2)
			//check number not null
			if($result["Plan Date"] == null){ $tmp_plan_date = 0; }else{ $tmp_plan_date = $result["Plan Date"]; }
			if($result["Production Plan (ft2)"] == null){ $tmp_prod_plan = 0; }else{ $tmp_prod_plan = $result["Production Plan (ft2)"]; }

			//Process Insert / update to database
			//Check dulplicate
			$strSQL_Check = " select * from tbl_daily_plan where plan_date = '$tmp_plan_date' ";
			$objQuery = sqlsrv_query($db_con, $strSQL_Check);
								
			//Check Boolean true / false
			$objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);
			if($objResult) //Update
			{  
				$strSQL_update = " UPDATE [dbo].[tbl_daily_plan]
					   SET [plan_date] = '$tmp_plan_date'
						  ,[plan_ft2_value] = '".trim($tmp_prod_plan)."'
						  ,[plan_issue_by] = '".$t_cur_user_code_VMI_GDJ."'
						  ,[plan_issue_date] = '$buffer_date'
						  ,[plan_issue_time] = '$buffer_time'
						  ,[plan_issue_datetime] = '$buffer_datetime'
					 WHERE plan_date = '$tmp_plan_date' ";
				$objQuery_update = sqlsrv_query($db_con, $strSQL_update);
			}
			else //Insert
			{
				$strSQL_insert = "
				INSERT INTO [dbo].[tbl_daily_plan]
					   (
					   [plan_date]
					   ,[plan_ft2_value]
					   ,[plan_issue_by]
					   ,[plan_issue_date]
					   ,[plan_issue_time]
					   ,[plan_issue_datetime]
					   )
				 VALUES
					   (
					   '$tmp_plan_date'
					   ,'".trim($tmp_prod_plan)."'
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
			//echo "<script>alert('Upload FG (ft^2) file success.');</script>";
			echo "<script>window.top.window.showResult_frmUploadDailyPlan('2');</script>";
		}
		else
		{
			sleep(2);
			////echo "<script>alert('Error !! Unable to upload FG (ft^2) file.');</script>";
			echo "<script>window.top.window.showResult_frmUploadDailyPlan('3');</script>";
		}

		sqlsrv_close($db_con);
	}
	else
	{
		sleep(2);
		////echo "<script>alert('Error !! Wrong file type (Must be a file (.xls, .xlsx) only.');</script>";
		echo "<script>window.top.window.showResult_frmUploadDailyPlan('4');</script>";
	}
}
?>