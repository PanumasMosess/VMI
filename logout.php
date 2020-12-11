<?
require_once("application.php");
require_once("js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

//save log logging
$sqlInsert = "insert into tbl_user_login_log(user_ip_login_log,user_code_login_log,user_login_page,user_login_status,user_issue_date_login_log,user_issue_time_login_log,user_issue_datetime_login_log)values('','".$t_cur_user_code_VMI_GDJ."','','Log out','$buffer_date','$buffer_time','$buffer_datetime')";
$result_sqlInsert = sqlsrv_query($db_con, $sqlInsert);
			
//delete session
unset($_SESSION['LOGON_VMI_GDJ']);
unset($_SESSION['user_code_VMI_GDJ']);
unset($_SESSION['ses_lang_VMI_GDJ']);

//session for user management
unset($_SESSION['t_cur_user_code_VMI_GDJ']);
unset($_SESSION['t_cur_user_type_VMI_GDJ']);

//clear session company
unset($_SESSION['ses_company_VMI_GDJ']);
unset($_SESSION['ses_company_en_VMI_GDJ']);

//clear session fb
unset($_SESSION['strOwnerID_VMI_GDJ']);
unset($_SESSION['strOwnerType_VMI_GDJ']);
unset($_SESSION['strFacebookID_VMI_GDJ']);
unset($_SESSION['strFacebookName_VMI_GDJ']);

//unset($_SESSION['name']); // will delete just the name data
//session_destroy(); // will delete ALL data associated with that user.
?>

<!DOCTYPE html>
<html>
<body class="hold-transition login-page skin-blue sidebar-mini">
<div class="wrapper">

  <?
	require_once("menu_blank.php");
  ?>
  <!--------------------------->
  <!-- body  -->
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	<!-- Main content -->
    <section class="content"> 
		<div class="row">
			<div class="login-box">
			  <!-- /.login-logo -->
			  <div class="login-box-body">
					<?
						print "<meta http-equiv=refresh content=2;URL=index>";
						echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000; font-size:14px;'>";
						echo "<tr>";
						echo "<td><center><font color='#8A2BE2'>Logout successfully</font><br><img src='$CFG->imagedir/ajax-loader3.gif' border='0' width='120px'></center></td>";
						echo "</tr>";
						echo "</table>";
					?>
				</div>
			  <!-- /.login-box-body -->
			</div>
			<!-- /.login-box -->
		</div>
	<!-- ./row -->
	</section>
	<!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <!--------------------------->
  <!-- /.body -->
  <!--------------------------->
  <?
	require_once("footer.php");
  ?>

  <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<? 
require_once("js_css_footer.php"); 
?>
</body>
</html>
<?
sqlsrv_close($db_con);
?>