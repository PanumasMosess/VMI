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
$var_arr_list = isset($_POST['var_arr_list']) ? $_POST['var_arr_list'] : '';

$str_split_TagsProject = explode(',', $var_arr_list);
$str_count_arr = count($str_split_TagsProject);
$i_rows = 0 ;
$str_tags = "";

foreach ($str_split_TagsProject as $str_tagsProject) {
	
	$str_split = explode('#####', $str_tagsProject);
	$str_tags = $str_split[0];
	
    $sqlDel = " DELETE FROM [dbo].[tbl_usage_conf_qc] WHERE conf_qc_tags_code = '$str_tags' ";
	$resultDel = sqlsrv_query($db_con, $sqlDel);
	
	$i_rows++;
}

//ctrl alert
if($str_count_arr == $i_rows)
{
	echo "SUC#####<b>--> Delete Tags Success.</b>";
}
else
{
	echo "ERR#####Error!!!<b>--> Cannot operate !!!</b>";
}

sqlsrv_close($db_con);
?>