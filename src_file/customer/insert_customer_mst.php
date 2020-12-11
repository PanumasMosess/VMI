<?
require_once("../../application.php");
require_once("../../PHPMailer/class.phpmailer.php");

$buffer_date = date("Y-m-d");
$buffer_time = date("H:i:s"); //24H
$buffer_datetime = date("Y-m-d H:i:s");


/**********************************************************************************/
/*var *****************************************************************************/
$txt_cus_user_en_add = isset($_POST['txt_cus_user_en_add_']) ? $_POST['txt_cus_user_en_add_'] : '';
$txt_cus_name_th_add = isset($_POST['txt_cus_name_th_add_']) ? $_POST['txt_cus_name_th_add_'] : '';
$txt_cus_name_en_add = isset($_POST['txt_cus_name_en_add_']) ? $_POST['txt_cus_name_en_add_'] : '';
$txt_sel_cus_code_add = isset($_POST['txt_sel_cus_code_add_']) ? $_POST['txt_sel_cus_code_add_'] : '';
$txt_sel_cus_name_add = isset($_POST['txt_sel_cus_name_add_']) ? $_POST['txt_sel_cus_name_add_'] : '';
$txt_sel_cus_terminal_type_add = isset($_POST['txt_sel_cus_terminal_type_add_']) ? $_POST['txt_sel_cus_terminal_type_add_'] : '';
$txt_sel_cus_type_add = isset($_POST['txt_sel_cus_type_add_']) ? $_POST['txt_sel_cus_type_add_'] : '';
$txt_cus_email_add = isset($_POST['txt_cus_email_add_']) ? $_POST['txt_cus_email_add_'] : '';

$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';

$pass_set = md5('123456789');

//Update Customer mst
$strSql_insert_customer = " 
INSERT INTO tbl_customer_mst
(
 [cus_code]
,[cus_pass_md5]
,[cus_name_th]
,[cus_name_en]
,[cus_with_bom_cus_code]
,[cus_with_bom_pj_name]
,[cus_terminal_type]
,[cus_email]
,[cus_type]
,[cus_status]
,[cus_issue_by]
,[cus_issue_date]
,[cus_issue_time]
,[cus_issue_datetime]
)
VALUES
(
 '$txt_cus_user_en_add'
,'$pass_set'
,'$txt_cus_name_th_add'
,'$txt_cus_name_en_add'
,'$txt_sel_cus_code_add'
,'$txt_sel_cus_name_add'
,'$txt_sel_cus_terminal_type_add'
,'$txt_cus_email_add'
,'$txt_sel_cus_type_add'
,'Active'
,'$t_cur_user_code_VMI_GDJ'
,'$buffer_date'
,'$buffer_time'
,'$buffer_datetime'
)
";

$objQuery_insert_check_stock = sqlsrv_query($db_con, $strSql_insert_customer);

 ////////////////////////////////////////////////
    //send mail
  
//     $mail = new PHPMailer(true);
//     $mail->IsSMTP();

//     $t_subject = "Information from ".$CFG->AppNameTitle." (Your Account VMI-GDJ.)";
//     $body = "<b>".$CFG->AppNameTitle."</b> <br>Dear <b><font color='blue'>".$txt_cus_name_en_add."</font></b> <br> Subject: Your Account has been create successfully. <br> &nbsp;&nbsp;&nbsp;&nbsp;Your User: <b><font color='blue'>".$txt_cus_user_en_add." <br> &nbsp;&nbsp;&nbsp;&nbsp;Your password: <b><font color='blue'>".'123456789'."</font></b><br> <br><br> If you have any questions please contact IT Development Team.<br> ".$CFG->contact_tel_en." <br><br> Yours sincerely, <br> ".$CFG->AppNameTitle." <br><br><br> ----- It is only an automated notification email. ----- <br> ----- Please, do not reply this e-mail address. --------";
//     $t_mail_address = $txt_cus_email_add;

//     $mail->SMTPDebug  = 0;
//     // enables SMTP debug information (for testing)
//     // 0 = off
//     // 1 = errors and messages
//     // 2 = messages only
//     $mail->CharSet = "utf-8";
//     $mail->SMTPAuth = true; // enable SMTP authentication
//     $mail->Host = $CFG->mail_host; // sets the SMTP server
//     $mail->Port = $CFG->mail_port; // set the SMTP port
//     $mail->Username = $CFG->user_smtp_mail; // SMTP account username
//     $mail->Password = $CFG->password_smtp_mail; // SMTP account password

//     $mail->SetFrom($CFG->from_mail, '[Automatic E-mail] no-reply@');
//     $mail->Subject = $t_subject;
//     $mail->MsgHTML($body);
//     //$mail->AddAddress($t_mail_address, $t_mail_address);

//     //send multiple to mail
//     $array_to = $t_mail_address;
//     $array_to = explode(",",$array_to);
//     $count_array = count($array_to);
//     for($z=0; $z<$count_array; $z++)
//     {
//             $mail->AddAddress($array_to[$z], $array_to[$z]); 
//     }

//     $mail->Send();

   
    
    echo "INSERT_OK";
        
sqlsrv_close($db_con);
?>