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
$curr_picking_no = isset($_POST['curr_picking_no']) ? $_POST['curr_picking_no'] : '';
?>

<script type="text/javascript">
$('#hdn_curr_picking_no').val('<?=var_encode($curr_picking_no);?>');
</script>
<?
sqlsrv_close($db_con);
?>