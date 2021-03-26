<?
require_once("application.php");
require_once("js_css_header.php");
require_once("PHPMailer/class.phpmailer.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$t_cur_ses_company_VMI_GDJ = isset($_SESSION['ses_company_VMI_GDJ']) ? $_SESSION['ses_company_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$user_login_chg = isset($_POST['user_login_chg']) ? $_POST['user_login_chg'] : '';//user
$pwd_login_chg = isset($_POST['pwd_login_chg']) ? $_POST['pwd_login_chg'] : '';//old pass
$new_pwd_login_chg = isset($_POST['new_pwd_login_chg']) ? $_POST['new_pwd_login_chg'] : '';
$re_new_pwd_login_chg = isset($_POST['re_new_pwd_login_chg']) ? $_POST['re_new_pwd_login_chg'] : '';//new pass
$hdn_t_type_name_chg = isset($_POST['hdn_t_type_name_chg']) ? $_POST['hdn_t_type_name_chg'] : '';
$hdn_pass_code_chg_normal = isset($_POST['hdn_pass_code_chg_normal']) ? $_POST['hdn_pass_code_chg_normal'] : '';
$hdn_your_ip = isset($_POST['hdn_your_ip']) ? $_POST['hdn_your_ip'] : '';
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
					//check not null
					if(($user_login_chg != '') and ($re_new_pwd_login_chg != ''))
					{
						//incase Staff
						if($hdn_t_type_name_chg == "Staff") 
						{
							//update pass
							$sqlUpdate = "update tbl_user set user_pass_md5 = '".md5(trim($re_new_pwd_login_chg))."',user_force_pass_chg = '0' where user_code = '".trim($user_login_chg)."' ";
							$result_sqlUpdate = sqlsrv_query($db_con, $sqlUpdate);
							
							//get user details
							$strSQL_authen = "select * from tbl_user 
							where 
							user_code = '".trim($user_login_chg)."' ";
							$objQuery_authen = sqlsrv_query($db_con, $strSQL_authen);
							$objResult_authen = sqlsrv_fetch_array($objQuery_authen, SQLSRV_FETCH_ASSOC);
							
							//save log logging
							$sqlInsert = "insert into tbl_user_login_log(user_ip_login_log,user_code_login_log,user_pass_login_log,user_login_page,user_login_status,user_issue_date_login_log,user_issue_time_login_log,user_issue_datetime_login_log)values('".$user_login_chg."','".$objResult_authen['user_code']."','".md5(trim($re_new_pwd_login_chg))."','".trim($objResult_authen['user_type'])."','Login success','$buffer_date','$buffer_time','$buffer_datetime')";
							$result_sqlInsert = sqlsrv_query($db_con, $sqlInsert);
							
							//send mail
							$mail = new PHPMailer(true);
							$mail->IsSMTP(); 

							try 
							{
								$t_subject = "Information from ".$CFG->AppNameTitle." (Your password has been changed successfully.)";
								$body = "<b>".$CFG->AppNameTitle."</b> <br>Dear <b><font color='blue'>".$objResult_authen['user_code']."</font></b> <br> Subject: Your password has been changed successfully. <br> &nbsp;&nbsp;&nbsp;&nbsp;Your new password: <b><font color='blue'>".trim($re_new_pwd_login_chg)."</font></b> <br><br> If you have any questions please contact IT Development Team. <br> ".$CFG->contact_tel_en." <br><br> Yours sincerely, <br> ".$CFG->AppNameTitle." <br><br><br> ----- It is only an automated notification email. ----- <br> ----- Please, do not reply this e-mail address. --------";
								$t_mail_address = $objResult_authen['user_email'];

								$mail->SMTPDebug  = 0;
								// enables SMTP debug information (for testing)
								// 0 = off
								// 1 = errors and messages
								// 2 = messages only
								$mail->CharSet = "utf-8";
								$mail->SMTPAuth = true; // enable SMTP authentication
								$mail->SMTPSecure = 'tls';  // Enable TLS encryption, `ssl` also accepted
								$mail->Host = $CFG->mail_host; // sets the SMTP server
								$mail->Port = $CFG->mail_port; // set the SMTP port
								$mail->Username = $CFG->user_smtp_mail; // SMTP account username
								$mail->Password = $CFG->password_smtp_mail; // SMTP account password

								$mail->SetFrom($CFG->from_mail, '[Automatic E-mail] no-reply@');
								$mail->Subject = $t_subject;
								$mail->MsgHTML($body);
								//$mail->AddAddress($t_mail_address, $t_mail_address);

								//send multiple to mail
								$array_to = $t_mail_address;
								$array_to = explode(",",$array_to);
								$count_array = count($array_to);
								for($z=0; $z<$count_array; $z++)
								{
									$mail->AddAddress($array_to[$z], $array_to[$z]); 
								}
								
								$mail->Send();
							} 
							catch (phpmailerException $e)
							{
								//echo $e->errorMessage(); //Pretty error messages from PHPMailer
							} 
							catch (Exception $e)
							{
								//echo $e->getMessage(); //Boring error messages from anything else!
							}

							//create session login
							unset($_SESSION['user_code_chg']);
							unset($_SESSION['pass_code_chg']);
							unset($_SESSION['pass_code_chg_normal']);
							unset($_SESSION['hdn_t_type_name_chg']);
							
							//clear session company
							unset($_SESSION['ses_company_VMI_GDJ']);
							unset($_SESSION['ses_company_en_VMI_GDJ']);

							//Create session
							$_SESSION['LOGON_VMI_GDJ'] = "True";
							$_SESSION['user_code_VMI_GDJ'] = trim($user_login_chg);
							
							//session for user management
							$_SESSION['t_cur_user_code_VMI_GDJ'] = trim($objResult_authen['user_code']);
							$_SESSION['t_cur_user_type_VMI_GDJ'] = trim($objResult_authen['user_type']);
							
							//create session company
							$_SESSION['ses_company_VMI_GDJ'] = $t_com_code;
							$_SESSION['ses_company_en_VMI_GDJ'] = $t_com_name_en;

							session_write_close();
							
							?>
							<script type="text/javascript">
								//hide dialog
								$("#modal-waiting-success").modal("hide");
							</script>
							<?
							
							print "<meta http-equiv=refresh content=5;URL=$CFG->wwwroot/home>";
							echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000;'>";
							echo "<tr>";
							echo "<td><center><img src='$CFG->imagedir/Check_icon.svg.png' width='40' border='0'><br><font color='green' style='font-size:15px;'><b>Authentication completed !!!</b></font>
							<br>-------------------------------
							<br><img src='$CFG->wwwroot/avatars/".get_profile_photo($db_con,$user_login_chg,'160')."' class='img-circle' width='70' title='User image'>
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
						sqlsrv_close($db_con);
					}
					else
					{
						//no authentication
						print "<meta http-equiv=refresh content=3;URL=$CFG->wwwroot/change_pass>";
						
						echo "<table align='center' cellpadding='0' cellspacing='0' bordercolor='#000000' bgcolor='#F1F1F1' style='border: 0px solid #000000;'>";
						echo "<tr>";
						echo "<td><center><img src='$CFG->imagedir/No_admittance_sign.png' width='40' border='0'><br><font color='red' style='font-size:15px;'><b>Warning !!!</b></font>
						<br><font color='red' style='font-size:13px;'>Authentication failed !!!</font>
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