<?
require_once("application.php");
require_once("js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

/**********************************************************************************/
/*current user fb *****************************************************************/
$strOwnerID_VMI_GDJ = isset($_SESSION['strOwnerID_VMI_GDJ']) ? $_SESSION['strOwnerID_VMI_GDJ'] : '';
$strOwnerType_VMI_GDJ = isset($_SESSION['strOwnerType_VMI_GDJ']) ? $_SESSION['strOwnerType_VMI_GDJ'] : '';
$strFacebookID_VMI_GDJ = isset($_SESSION['strFacebookID_VMI_GDJ']) ? $_SESSION['strFacebookID_VMI_GDJ'] : '';
$strFacebookName_VMI_GDJ = isset($_SESSION['strFacebookName_VMI_GDJ']) ? $_SESSION['strFacebookName_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$user_login = isset($_POST['user_login']) ? $_POST['user_login'] : '';
$pwd_login = isset($_POST['pwd_login']) ? $_POST['pwd_login'] : '';
$login = isset($_POST['login']) ? $_POST['login'] : '';
//print_r($_POST);

//get company fix by login and db
$strSQL_com = " select * from tbl_company_mst";
$objQuery_com = sqlsrv_query($db_con, $strSQL_com) or die ("Error Query [".$strSQL_com."]");
while($objResult_com = sqlsrv_fetch_array($objQuery_com, SQLSRV_FETCH_ASSOC))
{
	$t_com_code = $objResult_com['com_code'];
	$t_com_name_th = $objResult_com['com_name_th'];
	$t_com_name_en = $objResult_com['com_name_en'];
}

//counter login attempt in 10 minute (ban 10 minute)
$ip = $user_login;
$strSQL_login_attempt = " SELECT count(failed_login_ip_address) AS failed_login_attempt FROM tbl_user_failed_login WHERE failed_login_ip_address = '$ip'  AND failed_login_date BETWEEN dateadd(minute, -10, getdate()) AND getdate() ";
$objQuery_login_attempt = sqlsrv_query($db_con, $strSQL_login_attempt);
$objResult_login_attempt = sqlsrv_fetch_array($objQuery_login_attempt, SQLSRV_FETCH_ASSOC);
//$num_row_login_attempt = sqlsrv_num_rows($objQuery_login_attempt);
//count row
$failed_login_attempt = $objResult_login_attempt['failed_login_attempt'];
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
					//check captcha_code
					//code for check server side validation
					if(isset($_POST['login']))
					{
						// Captcha verification is Correct. Final Code Execute here!		
						//$msg="<span style='color:green'>The Validation code has been matched.</span>";		

						//ban login failed 3 time (ban 10 minute)
						if($failed_login_attempt >= 3)
						{
							echo "<table width='100%' align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' style='border: 0px solid #000000;'>";
							echo "<tr>";
							echo "<td><center><img src='$CFG->imagedir/No_admittance_sign.png' width='40' border='0'>
							<br><font color='red' style='font-size:15px;'>Your Login failed 3 times.</font> <br> <font color='black' style='font-size:13px;'>The account is locked for 10 minutes.</font>
							</center></td>";
							echo "</tr>";
							echo "</table>";
						}
						else
						{
							//check not null
							if(($user_login != '') and ($pwd_login != ''))
							{
								$strSQL_authen = "select * from tbl_user
								where 
								tbl_user.user_code = '".trim($user_login)."' 
								and 
								tbl_user.user_pass_md5 = '".md5(trim($pwd_login))."' 
								";
								$objQuery_authen = sqlsrv_query($db_con, $strSQL_authen);
								$objResult_authen = sqlsrv_fetch_array($objQuery_authen, SQLSRV_FETCH_ASSOC);
								 
								if($objResult_authen) //Check authen true (Registered user)
								{
									if($objResult_authen["user_enable"] == "1") //check enable user 1
									{
										if($objResult_authen["user_force_pass_chg"] == "1") //force change pass
										{
											//clear session
											unset($_SESSION['user_code_chg']);
											unset($_SESSION['pass_code_chg']);
											unset($_SESSION['pass_code_chg_normal']);
											unset($_SESSION['hdn_t_type_name_chg']);

											//create session change password
											$_SESSION['user_code_chg'] = trim($objResult_authen['user_code']);
											$_SESSION['pass_code_chg'] = trim($objResult_authen['user_pass_md5']);
											$_SESSION['pass_code_chg_normal'] = trim($pwd_login);
											$_SESSION['hdn_t_type_name_chg'] = "Staff";
											session_write_close();
											
											//force change pass
											print "<meta http-equiv=refresh content=3;URL=$CFG->wwwroot/change_pass>";
											echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000;'>";
											echo "<tr>";
											echo "<td><center><font color='red' style='font-size:15px;'><b>Warning !!!</b></font>
											<br><font color='red' style='font-size:13px;'>Please change your password.</font>
											<br><img src='$CFG->imagedir/ajax-loader3.gif' border='0' width='120px'>
											</center></td>";
											echo "</tr>";
											echo "</table>";
										}
										else if($objResult_authen["user_force_pass_chg"] == "0")
										{
											//clear session company
											unset($_SESSION['ses_company_VMI_GDJ']);
											unset($_SESSION['ses_company_en_VMI_GDJ']);

											//Create session
											$_SESSION['LOGON_VMI_GDJ'] = "True";
											$_SESSION['user_code_VMI_GDJ'] = trim($user_login);
											
											//session for user management
											$_SESSION['t_cur_user_code_VMI_GDJ'] = trim($objResult_authen['user_code']);
											$_SESSION['t_cur_user_type_VMI_GDJ'] = trim($objResult_authen['user_type']);
											$_SESSION['t_cur_user_session_VMI_GDJ'] = trim($objResult_authen['user_section']);
											
											//create session company
											$_SESSION['ses_company_VMI_GDJ'] = $t_com_code;
											$_SESSION['ses_company_en_VMI_GDJ'] = $t_com_name_en;

											session_write_close();
								
											//save log logging
											$sqlInsert = "insert into tbl_user_login_log(user_ip_login_log,user_code_login_log,user_pass_login_log,user_login_page,user_login_status,user_issue_date_login_log,user_issue_time_login_log,user_issue_datetime_login_log)values('".$user_login."','".$objResult_authen['user_code']."','".md5(trim($pwd_login))."','".trim($objResult_authen['user_type'])."','Login success','$buffer_date','$buffer_time','$buffer_datetime')";
											$result_sqlInsert = sqlsrv_query($db_con, $sqlInsert);
											
											print "<meta http-equiv=refresh content=5;URL=$CFG->wwwroot/home>";
											echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000;'>";
											echo "<tr>";
											echo "<td><center><img src='$CFG->imagedir/Check_icon.svg.png' width='40' border='0'><br><font color='green' style='font-size:15px;'><b>Authentication completed !!!</b></font>
											<br>-------------------------------
											<br><img src='$CFG->wwwroot/avatars/".get_profile_photo($db_con,$user_login,'160')."' class='img-circle' width='70' title='User image'>
											<br><font style='font-size:13px;'><b>Hello !!! </b>".$objResult_authen['user_code']."</font>
											<br><font style='font-size:13px;'><b>Name-EN : </b>".ucwords($objResult_authen['user_name_en'])."</font>
											<br><font style='font-size:13px;'><b>Name-TH : </b>".$objResult_authen['user_name_th']."</font>
											<br><font style='font-size:13px;'><b>Section : </b>".$objResult_authen['user_section']."</font>
											<br><font style='font-size:13px;'><b>Access rights : </b>".$objResult_authen['user_type']."</font>
											<br>-------------------------------
											<br><br><a href='$CFG->wwwroot/home'><img src='$CFG->iconsdir/Arrows-Right-round-icon.png' width='30' border='0'>
											<br>Next</a>
											<br><img src='$CFG->imagedir/ajax-loader3.gif' border='0' width='120px'>
											</center></td>";
											echo "</tr>";
											echo "</table>";
										}
									}
									else if($objResult_authen["user_enable"] == "0") //check enable user 0
									{
										//save log logging
										$sqlInsert = "insert into tbl_user_login_log(user_ip_login_log,user_code_login_log,user_pass_login_log,user_login_page,user_login_status,user_issue_date_login_log,user_issue_time_login_log,user_issue_datetime_login_log)values('".$user_login."','".$objResult_authen['user_code']."','".md5(trim($pwd_login))."','".trim($objResult_authen['user_type'])."','User disable','$buffer_date','$buffer_time','$buffer_datetime')";
										$result_sqlInsert = sqlsrv_query($db_con, $sqlInsert);
											
										echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000;'>";
										echo "<tr>";
										echo "<td><center><img src='$CFG->imagedir/No_admittance_sign.png' width='40' border='0'><br><font color='red' style='font-size:15px;'><b>Warning !!!</b></font>
										<br><font color='black' style='font-size:13px;'>This account has been disabled.</font>
										<br><font color='black' style='font-size:13px;'>Please contact the system administrator (Head Office) / IT Development Team.</font>
										<br><br><a href='$CFG->wwwroot/login'><img src='$CFG->iconsdir/Arrows-Left-round-icon.png' width='30' border='0'>
										<br>Back</a>
										</center></td>";
										echo "</tr>";
										echo "</table>";
									}
									
									//query delete login attempt
									$query_delete_login_attempt = sqlsrv_query($db_con, " DELETE FROM tbl_user_failed_login WHERE failed_login_ip_address = '$ip' ");
								}
								else //Check authen false (Unregister user)
								{
									//save log logging
									$sqlInsert = "insert into tbl_user_login_log(user_ip_login_log,user_code_login_log,user_pass_login_log,user_login_page,user_login_status,user_issue_date_login_log,user_issue_time_login_log,user_issue_datetime_login_log)values('".$user_login."','".$user_login."','".md5(trim($pwd_login))."','Not registered','Login fail','$buffer_date','$buffer_time','$buffer_datetime')";
									$result_sqlInsert = sqlsrv_query($db_con, $sqlInsert);
									
									//query insert login attempt
									$query_insert_login_attempt = sqlsrv_query($db_con, " INSERT INTO tbl_user_failed_login (failed_login_ip_address,failed_login_date) VALUES ( '$ip','$buffer_datetime' ) ");
									
									echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000;'>";
									echo "<tr>";
									echo "<td><center><img src='$CFG->imagedir/No_admittance_sign.png' width='40' border='0'><br><font color='red' style='font-size:15px;'>The username or password is incorrect !!!</font>
									<br><font color='black' style='font-size:13px;'>Please contact the system administrator (Head Office) / IT Development Team.</font>
									<br><br><a href='$CFG->wwwroot/login'><img src='$CFG->iconsdir/Arrows-Left-round-icon.png' width='30' border='0'>
									<br>Back</a>
									</center></td>";
									echo "</tr>";
									echo "</table>";
								}
								
								sqlsrv_close($db_con);
							}
						}
					}
					else
					{
						//can't refresh page
						print "<meta http-equiv=refresh content=3;URL=$CFG->wwwroot/login>";						
						echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000;'>";
						echo "<tr>";
						echo "<td><center><img src='$CFG->imagedir/No_admittance_sign.png' width='40' border='0'><br><font color='red' style='font-size:15px;'><b>Warning !!!</b></font>
						<br><font color='black' style='font-size:13px;'>Authentication failed !!!</font>
						<br><img src='$CFG->imagedir/ajax-loader3.gif' border='0' width='120px'>
						</center></td>";
						echo "</tr>";
						echo "</table>";
					}
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