<?
require_once("application.php");
header("Content-Type:text/html; charset=utf-8");
require_once 'PHPExcelClasses/PHPExcel.php'; //PHPExcel
include 'PHPExcelClasses/PHPExcel/IOFactory.php'; //PHPExcel_IOFactory - Reader	

//close error
ini_set('display_errors', 0);
//Turn off all error reporting
error_reporting(0);

//bypass execu time / memory limit
ini_set('MAX_EXECUTION_TIME', '-1');
ini_set('memory_limit', '-1');

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_ELT = isset($_SESSION['t_cur_user_code_ELT']) ? $_SESSION['t_cur_user_code_ELT'] : '';
$t_cur_user_type_ELT = isset($_SESSION['t_cur_user_type_ELT']) ? $_SESSION['t_cur_user_type_ELT'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

//var file upload
$fileupload = $_FILES['my_file']['tmp_name'];
$fileupload_name = $_FILES['my_file']['name'];

//Check file upload
if($fileupload)
{
	$array_last = explode(".",$fileupload_name);
	$c = count($array_last)-1; 
	$lastname = strtolower($array_last[$c]) ;
	
	if(($lastname == "xls") || ($lastname == "xlsx"))
	{
		//Rename , copy text file
		$buffer_file_name = "emp tracking".".".$lastname;
		copy($fileupload,"upload_master_file/".$buffer_file_name);

		////PHPExcel////
		$inputFileName = ("upload_master_file/".$buffer_file_name);
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
		
		// echo '<pre>';
		// var_dump($namedDataArray);
		// echo '</pre><hr />';
		
		/*
		//Check count fields table
		$strSQL_check_col = "select * from tbl_emp_tracking ";
		$objQuery_check_col = sqlsrv_query($db_con, $strSQL_check_col);
		$sum_fields = sqlsrv_num_fields($objQuery_check_col);

		$sum_fields = $sum_fields - 10;
		if($sum_fields != $sum_highestColumn)
		{
			sleep(2);
			////echo "<script>alert('Error !! Column in excel file does not match the database.');</script>";
			echo "NG";
			die;
		}
		*/
		
		$i = 0;
		foreach ($namedDataArray as $result)
		{
			//rows
			$i++;
			
			//delete
			$strSQL_del = " delete tbl_tags_running WHERE tags_code = '".trim($result["usage_tags_code"])."' ";
			$objQuery_del = sqlsrv_query($db_con, $strSQL_del);
			
			$strSQL_del1 = " delete tbl_receive WHERE receive_tags_code = '".trim($result["usage_tags_code"])."' ";
			$objQuery_del1 = sqlsrv_query($db_con, $strSQL_del1);
		}
		
		echo $i;
	}
	else
	{
		echo "Error!! ประเภทไฟล์ผิด ต้องเป็นไฟล์ (.xls,.xlsx) เท่านั้น";
	}
}
?>