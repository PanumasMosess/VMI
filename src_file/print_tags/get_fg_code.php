<?
require_once("../../application.php");

$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';

$proJect = $_GET['projectId'];

if(($t_cur_user_code_VMI_GDJ == 'Suthapha') || ($t_cur_user_code_VMI_GDJ == 'Chamaiporn') 
|| ($t_cur_user_code_VMI_GDJ == 'Anattra') || ($t_cur_user_code_VMI_GDJ == 'Panalee')){

    $sql = "SELECT bom_fg_code_gdj, ft2_value
    FROM [tbl_bom_mst] 
    left join tbl_fg_ft2_mst on tbl_bom_mst.bom_fg_code_gdj = tbl_fg_ft2_mst.ft2_fg_code 
    where bom_status = 'Active' and bom_pj_name IN ('$proJect') group by bom_fg_code_gdj, ft2_value";

}else{
    $sql = "SELECT bom_fg_code_gdj
    FROM [tbl_bom_mst] 
    left join tbl_fg_ft2_mst on tbl_bom_mst.bom_fg_code_gdj = tbl_fg_ft2_mst.ft2_fg_code 
    where bom_status = 'Active' and bom_pj_name IN ('$proJect') group by bom_fg_code_gdj";
}

$query = sqlsrv_query($db_con, $sql);
 
$json = array();
while($result = sqlsrv_fetch_array($query, SQLSRV_FETCH_ASSOC)) {    
    array_push($json, $result);
}
echo json_encode($json);
?>