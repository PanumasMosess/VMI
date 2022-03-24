<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$iden_sel_trading_from = isset($_POST['iden_sel_trading_from']) ? $_POST['iden_sel_trading_from'] : '';
$iden_sel_fg_code_gdj = isset($_POST['iden_sel_fg_code_gdj']) ? $_POST['iden_sel_fg_code_gdj'] : '';
$iden_txt_fg_code_gdj_desc = isset($_POST['iden_txt_fg_code_gdj_desc']) ? $_POST['iden_txt_fg_code_gdj_desc'] : '';
$iden_txt_prod_plan = isset($_POST['iden_txt_prod_plan']) ? $_POST['iden_txt_prod_plan'] : '';
$iden_txt_packing_std = isset($_POST['iden_txt_packing_std']) ? $_POST['iden_txt_packing_std'] : '';
$iden_txt_tags_total = isset($_POST['iden_txt_tags_total']) ? $_POST['iden_txt_tags_total'] : '';
$iden_token = isset($_POST['iden_token']) ? $_POST['iden_token'] : '';
$iden_sel_project_name = isset($_POST['iden_sel_project_name']) ? $_POST['iden_sel_project_name'] : '';

//get ceil
if($iden_txt_prod_plan > 0)
{
	$str_fifo_picking_pack = ceil($iden_txt_prod_plan / $iden_txt_packing_std);
	$str_conv_pack = floor($iden_txt_prod_plan / $iden_txt_packing_std);
	$str_conv_piece = $iden_txt_prod_plan % $iden_txt_packing_std;
}

////tags 9 digit (000000001)////
///////////////////Get tags no.///////////////////
$strSql_get_tags = " SELECT top(1) tags_code FROM tbl_tags_running order by tags_id DESC ";
$objQuery_get_tags = sqlsrv_query($db_con, $strSql_get_tags, $params, $options);
$num_row_get_tags = sqlsrv_num_rows($objQuery_get_tags);

//check row
if($num_row_get_tags == 0)
{
	for($i_count=1; $i_count<=$iden_txt_tags_total; $i_count++)
	{
		//clear
		$buffer_tags_code = $i_count - 1;
		$sum_tags = $buffer_tags_code + 1;//sum + 1
		$sprintf_tags = sprintf("%09d",$sum_tags);//generate to 9 digit
		$full_tags = $sprintf_tags;//full tags
		
		//row index = total tags
		if($i_count == $iden_txt_tags_total)
		{
			if($str_conv_piece > 0)
			{
				//insert tags
				$strSql_insert_tags = " INSERT INTO tbl_tags_running
						   (
						   [tags_code]
						   ,[tags_fg_code_gdj]
						   ,[tags_fg_code_gdj_desc]
						   ,[tags_project_name]
						   ,[tags_prod_plan]
						   ,[tags_packing_std]
						   ,[tags_total_qty]
						   ,[tags_token]
						   ,[tags_trading_from]
						   ,[tags_issue_by]
						   ,[tags_issue_date]
						   ,[tags_issue_time]
						   ,[tags_issue_datetime]
						   )
					 VALUES
						   (
						   '$full_tags'
						   ,'$iden_sel_fg_code_gdj'
						   ,'$iden_txt_fg_code_gdj_desc'
						   ,'$iden_sel_project_name'
						   ,'$iden_txt_prod_plan'
						   ,'$str_conv_piece'
						   ,'$iden_txt_tags_total'
						   ,'$iden_token'
						   ,'$iden_sel_trading_from'
						   ,'$t_cur_user_code_VMI_GDJ'
						   ,'$buffer_date'
						   ,'$buffer_time'
						   ,'$buffer_datetime'
						   ) 
				";
				$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
			}
			else
			{
				//insert tags
				$strSql_insert_tags = " INSERT INTO tbl_tags_running
						   (
						   [tags_code]
						   ,[tags_fg_code_gdj]
						   ,[tags_fg_code_gdj_desc]
						   ,[tags_project_name]
						   ,[tags_prod_plan]
						   ,[tags_packing_std]
						   ,[tags_total_qty]
						   ,[tags_token]
						   ,[tags_trading_from]
						   ,[tags_issue_by]
						   ,[tags_issue_date]
						   ,[tags_issue_time]
						   ,[tags_issue_datetime]
						   )
					 VALUES
						   (
						   '$full_tags'
						   ,'$iden_sel_fg_code_gdj'
						   ,'$iden_txt_fg_code_gdj_desc'
						   ,'$iden_sel_project_name'
						   ,'$iden_txt_prod_plan'
						   ,'$iden_txt_packing_std'
						   ,'$iden_txt_tags_total'
						   ,'$iden_token'
						   ,'$iden_sel_trading_from'
						   ,'$t_cur_user_code_VMI_GDJ'
						   ,'$buffer_date'
						   ,'$buffer_time'
						   ,'$buffer_datetime'
						   ) 
				";
				$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
			}
		}
		else
		{
			//insert tags
			$strSql_insert_tags = " INSERT INTO tbl_tags_running
					   (
					   [tags_code]
					   ,[tags_fg_code_gdj]
					   ,[tags_fg_code_gdj_desc]
					   ,[tags_project_name]
					   ,[tags_prod_plan]
					   ,[tags_packing_std]
					   ,[tags_total_qty]
					   ,[tags_token]
					   ,[tags_trading_from]
					   ,[tags_issue_by]
					   ,[tags_issue_date]
					   ,[tags_issue_time]
					   ,[tags_issue_datetime]
					   )
				 VALUES
					   (
					   '$full_tags'
					   ,'$iden_sel_fg_code_gdj'
					   ,'$iden_txt_fg_code_gdj_desc'
					   ,'$iden_sel_project_name'
					   ,'$iden_txt_prod_plan'
					   ,'$iden_txt_packing_std'
					   ,'$iden_txt_tags_total'
					   ,'$iden_token'
					   ,'$iden_sel_trading_from'
					   ,'$t_cur_user_code_VMI_GDJ'
					   ,'$buffer_date'
					   ,'$buffer_time'
					   ,'$buffer_datetime'
					   ) 
			";
			$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
		}
	}
}
else
{
	while($objResult_get_tags = sqlsrv_fetch_array($objQuery_get_tags, SQLSRV_FETCH_ASSOC))
	{
		$buffer_tags_code = $objResult_get_tags['tags_code'];
	}
	
	for($i_count=1; $i_count<=$iden_txt_tags_total; $i_count++)
	{
		$sum_tags = $buffer_tags_code + 1 + $i_count - 1;//sum + 1
		$sprintf_tags = sprintf("%09d",$sum_tags);//generate to 9 digit
		$full_tags = $sprintf_tags;//full tags
		
		//row index = total tags
		if($i_count == $iden_txt_tags_total)
		{
			if($str_conv_piece > 0)
			{
				//insert tags
				$strSql_insert_tags = " INSERT INTO tbl_tags_running
						   (
						   [tags_code]
						   ,[tags_fg_code_gdj]
						   ,[tags_fg_code_gdj_desc]
						   ,[tags_project_name]
						   ,[tags_prod_plan]
						   ,[tags_packing_std]
						   ,[tags_total_qty]
						   ,[tags_token]
						   ,[tags_trading_from]
						   ,[tags_issue_by]
						   ,[tags_issue_date]
						   ,[tags_issue_time]
						   ,[tags_issue_datetime]
						   )
					 VALUES
						   (
						   '$full_tags'
						   ,'$iden_sel_fg_code_gdj'
						   ,'$iden_txt_fg_code_gdj_desc'
						   ,'$iden_sel_project_name'
						   ,'$iden_txt_prod_plan'
						   ,'$str_conv_piece'
						   ,'$iden_txt_tags_total'
						   ,'$iden_token'
						   ,'$iden_sel_trading_from'
						   ,'$t_cur_user_code_VMI_GDJ'
						   ,'$buffer_date'
						   ,'$buffer_time'
						   ,'$buffer_datetime'
						   ) 
				";
				$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
			}
			else
			{
				//insert tags
				$strSql_insert_tags = " INSERT INTO tbl_tags_running
						   (
						   	[tags_code]
						   ,[tags_fg_code_gdj]
						   ,[tags_fg_code_gdj_desc]
						   ,[tags_project_name]
						   ,[tags_prod_plan]
						   ,[tags_packing_std]
						   ,[tags_total_qty]
						   ,[tags_token]
						   ,[tags_trading_from]
						   ,[tags_issue_by]
						   ,[tags_issue_date]
						   ,[tags_issue_time]
						   ,[tags_issue_datetime]
						   )
					 VALUES
						   (
						   '$full_tags'
						   ,'$iden_sel_fg_code_gdj'
						   ,'$iden_txt_fg_code_gdj_desc'
						   ,'$iden_sel_project_name'
						   ,'$iden_txt_prod_plan'
						   ,'$iden_txt_packing_std'
						   ,'$iden_txt_tags_total'
						   ,'$iden_token'
						   ,'$iden_sel_trading_from'
						   ,'$t_cur_user_code_VMI_GDJ'
						   ,'$buffer_date'
						   ,'$buffer_time'
						   ,'$buffer_datetime'
						   ) 
				";
				$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
			}
		}
		else
		{
			//insert tags
			$strSql_insert_tags = " INSERT INTO tbl_tags_running
					   (
					   [tags_code]
					   ,[tags_fg_code_gdj]
					   ,[tags_fg_code_gdj_desc]
					   ,[tags_project_name]
					   ,[tags_prod_plan]
					   ,[tags_packing_std]
					   ,[tags_total_qty]
					   ,[tags_token]
					   ,[tags_trading_from]
					   ,[tags_issue_by]
					   ,[tags_issue_date]
					   ,[tags_issue_time]
					   ,[tags_issue_datetime]
					   )
				 VALUES
					   (
					   '$full_tags'
					   ,'$iden_sel_fg_code_gdj'
					   ,'$iden_txt_fg_code_gdj_desc'
					   ,'$iden_sel_project_name'
					   ,'$iden_txt_prod_plan'
					   ,'$iden_txt_packing_std'
					   ,'$iden_txt_tags_total'
					   ,'$iden_token'
					   ,'$iden_sel_trading_from'
					   ,'$t_cur_user_code_VMI_GDJ'
					   ,'$buffer_date'
					   ,'$buffer_time'
					   ,'$buffer_datetime'
					   ) 
			";
			$objQuery_insert_tags = sqlsrv_query($db_con, $strSql_insert_tags);
		}
	}
}

//return token encode
echo var_encode($iden_token);

sqlsrv_close($db_con);
?>