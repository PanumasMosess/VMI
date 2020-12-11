<?
require_once("../../application.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

////tags 9 digit (000000001)////
///////////////////Get pallet no.///////////////////
$strSql_get_tags = " SELECT SUBSTRING(pallet_code,3,10) as substr_pallet_code FROM tbl_pallet_running where pallet_status = 'Waiting' and pallet_issue_by = '$t_cur_user_code_VMI_GDJ' order by pallet_id desc ";
$objQuery_get_tags = sqlsrv_query($db_con, $strSql_get_tags, $params, $options);
$num_row_get_tags = sqlsrv_num_rows($objQuery_get_tags);

//clear
$buffer_tags_code = 0;

while($objResult_get_tags = sqlsrv_fetch_array($objQuery_get_tags, SQLSRV_FETCH_ASSOC))
{
	$buffer_tags_code = $objResult_get_tags['substr_pallet_code'];
}

$sum_tags = $buffer_tags_code;
$sprintf_tags = sprintf("%09d",$sum_tags);//generate to 9 digit
$full_tags = "PL".$sprintf_tags;//full tags
?>

<script type="text/javascript">
$('#txt_curr_pallet_no').val('<?=$full_tags;?>');
$('#hdn_curr_pallet_no').val('<?=var_encode($full_tags);?>');
</script>
<?
sqlsrv_close($db_con);
?>