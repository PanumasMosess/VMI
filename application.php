<?
session_start();

//default Asia/Bangkok
date_default_timezone_set('Asia/Bangkok');

/**********************************************************************************/
/*turn on verbose error reporting (15) to see all warnings and errors *************/
//error_reporting(15);
//define stdClass
//class object {};

//setup the configuration object 
//define stdClass
$CFG = new stdClass;

/**********************************************************************************/
/*config sql server ***************************************************************/
// $CFG->dbhost = "LAC-APPS, 49894";
// $CFG->dbhostPing = "LAC-APPS";
// $CFG->dbname = "VMI";
// $CFG->dbuser = "Allvmi";
// $CFG->dbpass = "brewrL5iRebi4+Ewre0r";

$CFG->dbhost = "27.254.122.55, 49894";
$CFG->dbhostPing = "27.254.122.55";
$CFG->dbname = "VMI_test";
$CFG->dbuser = "Allvmi";
$CFG->dbpass = "brewrL5iRebi4+Ewre0r";

/**********************************************************************************/
/*config path file ****************************************************************/
$CFG->path_host = "http://localhost/";
// $CFG->path_host = "https://lac-apps.albatrossthai.com/";
$CFG->wwwroot_other = "/vmi"; //case check any location
$CFG->wwwroot = "/vmi";

/**********************************************************************************/
/*config system path  *************************************************************/
$CFG->libdir = "lib";
$CFG->iconsdir = "$CFG->wwwroot/icons";
$CFG->imagedir = "$CFG->wwwroot/images";
$CFG->logodir = "$CFG->wwwroot/logo_company";
$CFG->logopoweredby = "$CFG->wwwroot/logo_powerby";
$CFG->languagedir = "language";

/**********************************************************************************/
/*app name  ***********************************************************************/
$CFG->AppName = "VMI-GDJ";
$CFG->AppNameTitle = "VMI-GDJ"; //line noti / mail alert
$CFG->AppNameTitleMini = "VMI";

/**********************************************************************************/
/*config path file  ***************************************************************/
$CFG->src_pathattc_manual = "$CFG->wwwroot/manual";
$CFG->src_master_file = "$CFG->wwwroot/upload_master_file";
$CFG->src_template_master_file = "$CFG->wwwroot/template_master_file";

//src_file
$CFG->src_file_alert = "$CFG->wwwroot/src_file/load_alert";
$CFG->src_mPDF = "$CFG->wwwroot/mPDF";
$CFG->src_bom = "$CFG->wwwroot/src_file/bom";
$CFG->src_user_mst = "$CFG->wwwroot/src_file/user_mst";
$CFG->src_print_tags = "$CFG->wwwroot/src_file/print_tags";
$CFG->src_put_away = "$CFG->wwwroot/src_file/put_away";
$CFG->src_replenishment = "$CFG->wwwroot/src_file/replenishment";
$CFG->src_picking_order = "$CFG->wwwroot/src_file/picking_order";
$CFG->src_dtn_order = "$CFG->wwwroot/src_file/dtn_order";
$CFG->src_location_check = "$CFG->wwwroot/src_file/location_check";
$CFG->src_terminal = "$CFG->wwwroot/src_file/terminal";
$CFG->src_report = "$CFG->wwwroot/src_file/report";
$CFG->src_customer = "$CFG->wwwroot/src_file/customer";
$CFG->src_driver = "$CFG->wwwroot/src_file/driver";
/**********************************************************************************/
/*standard libraries **************************************************************/
require_once("$CFG->libdir/comlib.php");

//application settings
/**********************************************************************************/
/*version & copyright  ************************************************************/
$CFG->App_ver = "1.0.0";
$CFG->App_ver_update = "2020-09-01";
$CFG->App_copyright = "2020";

//contact
$CFG->contact_tel_en = "Tel. +66 3811 0910-2, +66 3811 0915 Fax. +66 3811 0916";
$CFG->contact_tel_th = "Tel. +66 3811 0910-2, +66 3811 0915 Fax. +66 3811 0916";

/**********************************************************************************/
/*link address ********************************************************************/
$CFG->Link_address = "<br>".$CFG->path_host."".$CFG->wwwroot."/index";

////////////////////////////////////////////////////////////////////////////////////
//tools configuration //////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////

/**********************************************************************************/
/*SMTP mailing ********************************************************************/
//var
$ses_company_VMI_GDJ = isset($_SESSION['ses_lang_VMI_GDJ']) ? $_SESSION['ses_lang_VMI_GDJ'] : '';

$CFG->mail_host = get_smtp_mail_config($db_con,"SmtpHost","Active",$ses_company_VMI_GDJ);
$CFG->mail_port = get_smtp_mail_config($db_con,"SmtpPort","Active",$ses_company_VMI_GDJ);
$CFG->user_smtp_mail = get_smtp_mail_config($db_con,"SmtpUsr","Active",$ses_company_VMI_GDJ);
$CFG->password_smtp_mail = get_smtp_mail_config($db_con,"SmtpPass","Active",$ses_company_VMI_GDJ);
$CFG->from_mail = get_smtp_mail_config($db_con,"SmtpFormTitle","Active",$ses_company_VMI_GDJ);

/**********************************************************************************/
/*pattern / shuffle password ******************************************************/
$CFG->pass_shuffle = "123456789";
$CFG->pass_shuffle_digit = "6";
?>