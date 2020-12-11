<?
require_once("../../application.php");

/**********************************************************************************/
/*count user online ***************************************************************/
$sql_MemOnline = "SELECT * FROM tbl_user_online ";
$query_MemOnline = sqlsrv_query($db_con, $sql_MemOnline, $params, $options);
$num_MemOnlin = sqlsrv_num_rows($query_MemOnline);
//print_r(sqlsrv_fetch_object($query_MemOnline));

/**********************************************************************************/
/*count acc lock ******************************************************************/
$strSql_c_acc_lock = " SELECT failed_login_ip_address, count(failed_login_ip_address) AS ColCnt 
FROM tbl_user_failed_login 
WHERE failed_login_date BETWEEN dateadd(minute, -10, getdate()) AND getdate()
GROUP BY failed_login_ip_address having count(failed_login_ip_address) >= '3' ";
$objQuery_c_acc_lock = sqlsrv_query($db_con, $strSql_c_acc_lock, $params, $options);
$num_row_c_acc_lock = sqlsrv_num_rows($objQuery_c_acc_lock);
?>
<script type="text/javascript">
//user online		
$('#spn_usr_online').html('<?=number_format($num_MemOnlin);?>');

//user lock
$('#spn_account_lock').html('<?=number_format($num_row_c_acc_lock);?>');
</script>
<?
sqlsrv_close($db_con);
?>