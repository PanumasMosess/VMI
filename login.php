<?
require_once("application.php");
require_once("js_css_header.php");

//call function getBrowser
$ua = getBrowser();

//clear session fb
unset($_SESSION['strOwnerType_VMI_GDJ']);
unset($_SESSION['strOwnerType_VMI_GDJ']);
unset($_SESSION['strFacebookID_VMI_GDJ']);
unset($_SESSION['strFacebookName_VMI_GDJ']);

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
		<h1><i class="fa fa-caret-right"></i>&nbsp;Administrator/Customer<small>Enter username and password</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/index"><i class="fa fa-home"></i>Home</a></li><li class="active"><?=$CFG->AppNameTitleMini;?> Login</li><li class="active">Administrator/Customer</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
            <div class="login-box">
			  <!-- /.login-logo -->
			  <div class="login-box-body">
				<p class="login-box-msg"><font style="font-size: 18px;"><i class="fa fa-lock"></i> VMI Login</font></p>
				<form id="loginForm" name="loginForm" method="post" action="<?=$CFG->wwwroot;?>/check_login" onSubmit="JavaScript:return fnchecklogin();">
				  <div class="form-group has-feedback">
					 <label for="user_login">Username</label>
					 <input type="text" name="user_login" id="user_login" class="form-control" placeholder="Enter username">
					 <span class="glyphicon glyphicon-user form-control-feedback"></span>
				  </div>
				  <!--
				  <div class="form-group has-feedback">
					<label for="pwd_login">Password</label>
					<input type="password" name="pwd_login" id="pwd_login" class="form-control" placeholder="Enter password">
					<span class="glyphicon glyphicon-lock form-control-feedback"></span>
				  </div>
				  -->
				  <div class="form-group has-feedback">
					<label>Password</label>
					<div class="input-group" id="show_hide_password">
					  <input type="password" name="pwd_login" id="pwd_login" class="form-control" placeholder="Enter password">
					  <div class="input-group-addon">
						<a href="#"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
					  </div>
					</div>
				  </div>
				  <div class="row">
					<div class="col-xs-1">
						<!-- type hidden temp var -->
					</div>
					<div class="col-xs-11"></div>
				  </div>
				  <div class="row">
					<div class="col-xs-7">
					</div>
					<!-- /.col -->
					<div class="col-xs-5">
					  <button name="login" id="login" type="submit" class="btn btn-primary btn-sm btn-block btn-flat">Log in</button>
					</div>
					<!-- /.col -->
				  </div>
				</form>
	
				<a href="<?=$CFG->wwwroot;?>/index" class="text-center"><i class="fa fa-arrow-circle-o-left"></i> Back</a>

				<br><br><font style="font-size:12px; color:#4B0082;">Your IP : <?=getVisitorIP();?></font>
				
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
	$('#user_login').val('');
	$('#pwd_login').val('');
	
	//Password Show Hide
	$("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
	
	<?
	//check Internet Explorer
	if($ua['name'] == "Internet Explorer")
	{
	?>
	swal({
	  html: true,
	  title: "<span style='font-size: 17px; font-weight: bold;'>Warning !</span>",
	  text: "<span style='font-size: 15px;'>You are logged in via <?=$ua['name'];?>, For the best performance. Please login via Google Chrome. <span class='fa fa-chrome'></span> !!!</span>",
	  type: "warning",
	  showCancelButton: false,
	});
	<?
	}
	?>
	
});
	
<!--check form login-->
function fnchecklogin()
{
	if(document.loginForm.user_login.value == "")
	{
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please input Username</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			document.loginForm.user_login.focus();
		}, 2500);
		
		return false;
	}
	else if(document.loginForm.pwd_login.value == "")
	{
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please input Password</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			document.loginForm.pwd_login.focus();
		}, 2500);
		
		return false;
	}
	
	swal({
	  html: true,
	  title: "<span style='font-size: 17px; font-weight: bold;'>Authenticating connection...</span>",
	  text: "<span style='font-size: 15px; color: #000;'>Please wait for a while</span>",
	  imageUrl: "<?=$CFG->imagedir;?>/ajax-loader3.gif",
	  timer: 8000,
	  showConfirmButton: false,
	  allowOutsideClick: false
	});
	
	document.loginForm.submit();
}
</script>

</body>
</html>