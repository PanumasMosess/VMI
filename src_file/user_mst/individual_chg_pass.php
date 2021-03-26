<?
require_once("../../application.php");
require_once("../../PHPMailer/class.phpmailer.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");

/**********************************************************************************/
/*var *****************************************************************************/
$individual_user_chg = isset($_POST['individual_user_chg']) ? $_POST['individual_user_chg'] : '';
$individual_pwd_login_chg = isset($_POST['individual_pwd_login_chg']) ? $_POST['individual_pwd_login_chg'] : '';
$individual_new_pwd_login_chg = isset($_POST['individual_new_pwd_login_chg']) ? $_POST['individual_new_pwd_login_chg'] : '';
$individual_re_new_pwd_login_chg = isset($_POST['individual_re_new_pwd_login_chg']) ? $_POST['individual_re_new_pwd_login_chg'] : '';

/**********************************************************************************/
/*get user logging ****************************************************************/
$strSQL_user_logging = "select * from tbl_user 
where 
user_code = '".$t_cur_user_code_VMI_GDJ."' 
and 
user_enable = '1'
and
user_type = '$t_cur_user_type_VMI_GDJ'
";
$objQuery_user_logging = sqlsrv_query($db_con, $strSQL_user_logging);
$objResult_user_logging = sqlsrv_fetch_array($objQuery_user_logging, SQLSRV_FETCH_ASSOC);

$tt_user_code = $objResult_user_logging['user_code'];
$tt_user_email = $objResult_user_logging['user_email'];

/**********************************************************************************/
/*Check old pass ******************************************************************/
$strSQL = "select * from tbl_user 
where 
user_code = '".$t_cur_user_code_VMI_GDJ."' 
and
user_pass_md5 = '".md5($individual_pwd_login_chg)."'
and  
user_type = '$t_cur_user_type_VMI_GDJ' 
";
$objQuery = sqlsrv_query($db_con, $strSQL);
$result = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC);

if($result)//Check old pass done
{
	////////////////////////////////////////////////
	//update
	$sqlupdate = " update tbl_user 
	 SET user_pass_md5 = '".md5($individual_re_new_pwd_login_chg)."'
	where 
	user_code = '$individual_user_chg' 
	";
	$resultupdate = sqlsrv_query($db_con, $sqlupdate);

	if($resultupdate)
	{
		echo "[D003] --- Update complete. <br> The new password will be sent to your email. !!!";
		
		////////////////////////////////////////////////
		//send mail
		$mail = new PHPMailer(true);
		$mail->IsSMTP();

		try 
		{
			$t_subject = "Information from ".$CFG->AppNameTitle." (Your password has been changed successfully.)";
			$body = "<b>".$CFG->AppNameTitle."</b> <br>Dear <b><font color='blue'>".$individual_user_chg."</font></b> <br> Subject: Your password has been changed successfully. <br> &nbsp;&nbsp;&nbsp;&nbsp;Your new password: <b><font color='blue'>".trim($individual_re_new_pwd_login_chg)."</font></b><br> <br><br> If you have any questions please contact IT Development Team.<br> ".$CFG->contact_tel_en." <br><br> Yours sincerely, <br> ".$CFG->AppNameTitle." <br><br><br> ----- It is only an automated notification email. ----- <br> ----- Please, do not reply this e-mail address. --------";
			$t_mail_address = $tt_user_email;

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
	}
	else
	{
		echo "[D002] --- Can't operate !!! ";
	}
}
else
{
	echo "[D002] --- You entered the wrong old password.";
}

sqlsrv_close($db_con);
?>