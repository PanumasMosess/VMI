<?
require_once("application.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

$strSql = " select tags_code,receive_tags_code 
from tbl_tags_running 
left join tbl_receive
on tbl_tags_running.tags_code = tbl_receive.receive_tags_code
where 
tags_code not in (select receive_tags_code from tbl_receive) ";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_count = 0;
$row_count_del = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_count++;
	$tags_code = $objResult['tags_code'];
	
	//delete tags not put away
	$strSql_del = " DELETE FROM [tbl_tags_running] WHERE tags_code = '$tags_code ' ";
	$objQuery_del = sqlsrv_query($db_con, $strSql_del);
	if($objQuery_del)
	{
		$row_count_del = $row_count_del + 1;
	}
}

echo "Tags Total: ".$row_count." / Delete Total:".$row_count_del;

sqlsrv_close($db_con);
?>