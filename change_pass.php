<?
require_once("application.php");
require_once("js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$user_code_chg = isset($_SESSION['user_code_chg']) ? $_SESSION['user_code_chg'] : '';
$pass_code_chg = isset($_SESSION['pass_code_chg']) ? $_SESSION['pass_code_chg'] : '';
$hdn_t_type_name_chg = isset($_SESSION['hdn_t_type_name_chg']) ? $_SESSION['hdn_t_type_name_chg'] : '';
$pass_code_chg_normal = isset($_SESSION['pass_code_chg_normal']) ? $_SESSION['pass_code_chg_normal'] : '';

//check no flow
if($user_code_chg == "")
{
	print "<meta http-equiv=refresh content=0;URL=$CFG->wwwroot/index>";
	exit();
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
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1><i class="fa fa-caret-right"></i>&nbsp;Change password</h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/index"><i class="fa fa-home"></i>Home</a></li><li class="active">Login</li><li class="active">Change password</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content"> 
		<div class="row">
            <div class="login-box">
			  <!-- /.login-logo -->
			  <div class="login-box-body">
				<p class="login-box-msg"><i class="fa fa-lock"></i> Change password</p>
				<form id="loginForm" name="loginForm" method="post" action="<?=$CFG->wwwroot;?>/check_change_pass" onSubmit="JavaScript:return fncheckchg();">
				  <div class="form-group has-feedback">
					 <label for="user_login_chg">Username</label>
					 <input type="text" name="user_login_chg" id="user_login_chg" value="<?=$user_code_chg;?>" maxlength="13" readonly="readonly" class="form-control" placeholder="Enter Username">
					<span class="glyphicon glyphicon-user form-control-feedback"></span>
				  </div>
				  <div class="form-group has-feedback">
					<label for="pwd_login_chg">Password</label>
					<input type="password" name="pwd_login_chg" id="pwd_login_chg" value="<?=$pass_code_chg;?>" readonly="readonly" class="form-control" placeholder="Enter Password">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				  </div>
				  <div class="form-group has-feedback">
					<label for="new_pwd_login_chg">New password</label>
					<input type="password" name="new_pwd_login_chg" id="new_pwd_login_chg" maxlength="10" class="form-control" placeholder="Enter new password (5-10 character)">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				  </div>
				  <div class="form-group has-feedback">
					<label for="re_new_pwd_login_chg">Confirm password</label>
					<input type="password" name="re_new_pwd_login_chg" id="re_new_pwd_login_chg" maxlength="10" class="form-control" placeholder="Enter confirm password (5-10 character)">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				  </div>
				  <div class="row">
					<div class="col-xs-5">
					<input type="hidden" name="hdn_t_type_name_chg" id="hdn_t_type_name_chg" value="<?=$hdn_t_type_name_chg;?>">
					<input type="hidden" name="hdn_pass_code_chg_normal" id="hdn_pass_code_chg_normal" value="<?=$pass_code_chg_normal;?>">
					</div>
					<!-- /.col -->
					<div class="col-xs-7">
					  <button name="login" id="login" type="submit" class="btn btn-primary btn-sm btn-block btn-flat">Change password</button>
					</div>
					<!-- /.col -->
				  </div>
				</form>
				<p>
				<b>How to create a strong new password.</b><br>
				&nbsp;<i class="fa fa-angle-double-right"></i> Passwords must be at least 8 characters long.<br>
				&nbsp;<i class="fa fa-angle-double-right"></i> Passwords must contain: (A-Z,a-z,0-9).<br>
				&nbsp;<i class="fa fa-angle-double-right"></i> Do not use real words.<br>
				&nbsp;<i class="fa fa-angle-double-right"></i> The password must not contain the login of the account or a part of its name.
				</p>
				<a href="login" class="text-center"><i class="fa fa-arrow-circle-o-left"></i> Back</a>
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
<script language="javascript">
$(function () {
	$('input').iCheck({
	  checkboxClass: 'icheckbox_square-blue',
	  radioClass: 'iradio_square-blue',
	  increaseArea: '20%' // optional
	});
});
</script>

<script language="javascript">
<!--Onload this page-->
$(document).ready(function() 
{	
	//clear
	$('#new_pwd_login_chg').val('');
	$('#re_new_pwd_login_chg').val('');
});

//check change pass
function fncheckchg()
{
	if(document.loginForm.new_pwd_login_chg.value == "")
	{
		//dialog ctrl
		$("#modal-default").modal("show");
		$("#al_results").html("[C001] --- Enter new password");
		
		//hide
		setTimeout(function(){
			$("#modal-default").modal("hide");
			document.loginForm.new_pwd_login_chg.focus();
		}, 3000);
		
		return false;
	}
	
	if((document.loginForm.new_pwd_login_chg.value.length < 5) || (document.loginForm.new_pwd_login_chg.value.length > 10))
	{
		//dialog ctrl
		$("#modal-default").modal("show");
		$("#al_results").html("[C002] --- Enter new password 5-10 character");
		
		//hide
		setTimeout(function(){
			$("#modal-default").modal("hide");
			document.loginForm.new_pwd_login_chg.focus();
		}, 3000);
		
		return false;
	}
	
	if(document.loginForm.re_new_pwd_login_chg.value == "")
	{
		//dialog ctrl
		$("#modal-default").modal("show");
		$("#al_results").html("[C001] --- Enter confirm password");
		
		//hide
		setTimeout(function(){
			$("#modal-default").modal("hide");
			document.loginForm.re_new_pwd_login_chg.focus();
		}, 3000);
		
		return false;
	}
	
	if(document.loginForm.new_pwd_login_chg.value != document.loginForm.re_new_pwd_login_chg.value)
	{
		//dialog ctrl
		$("#modal-default").modal("show");
		$("#al_results").html("[C002] --- The password does not match, Please confirm again.");
		
		//hide
		setTimeout(function(){
			$("#modal-default").modal("hide");
			document.loginForm.re_new_pwd_login_chg.focus();
		}, 3000);
		
		return false;
	}
	
	//dialog waiting ctrl
	$("#modal-waiting-success").modal("show");
	$("#al_results_waiting_success").html("[I001] --- Please wait for a while, system is sending emails....");
	
	document.loginForm.submit();
}
</script>
		
</body>
</html>