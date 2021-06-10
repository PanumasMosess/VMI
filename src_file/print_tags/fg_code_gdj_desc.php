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
$fg_code_gdj = isset($_POST['fg_code_gdj']) ? $_POST['fg_code_gdj'] : '';

$strSql = " SELECT bom_fg_desc FROM tbl_bom_mst where bom_fg_code_gdj = '$fg_code_gdj' and bom_status = 'Active' group by bom_fg_desc ";
$objQuery = sqlsrv_query($db_con, $strSql);
//$num_row = sqlsrv_has_rows($objQuery);

//clear
$bom_fg_desc = "";

while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$bom_fg_desc = $objResult['bom_fg_desc'];
}
?>

<script type="text/javascript">
$('#txt_fg_code_gdj_desc').val('<?=filter_string($bom_fg_desc);?>');
</script>
<?
sqlsrv_close($db_con);
?>