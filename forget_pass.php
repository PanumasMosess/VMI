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
		<h1><i class="fa fa-caret-right"></i>&nbsp;Administrator/Customer<small>Enter username and email for get new password</small></h1>
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
				<p class="login-box-msg"><font style="font-size: 18px;"><i class="fa fa-key"></i> VMI Forget Password</font></p>
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
				  <!-- <div class="form-group has-feedback">
					<label>Password</label>
					<div class="input-group" id="show_hide_password">
					  <input type="password" name="pwd_login" id="pwd_login" class="form-control" placeholder="Enter password">
					  <div class="input-group-addon">
						<a href="#"><i class="fa fa-eye-slash" aria-hidden="true"></i></a>
					  </div>
					</div>
				  </div> -->
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
					  <button  type="submit" onclick="fncheckReset();" class="btn btn-primary btn-sm btn-block btn-flat">Confirm</button>
					</div>
					<!-- /.col -->
				  </div>
	
				<a href="<?=$CFG->wwwroot;?>/login" class="text-center"><i class="fa fa-arrow-circle-o-left"></i> Login</a>
				
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
// <!--Onload this page-->
$(document).ready(function() 
{
	//clear
	$('#user_login').val('');
	

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
	
// <!--check form login-->
function fncheckReset()
{
	if($('#user_login').val() == "")
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
            $("#user_login").focus();
		}, 2500);
		
		return false;
	}else{
        $.ajax({
                    type: 'POST',
                    url: '<?= $CFG->src_user_mst; ?>/confirm_reset_pass.php',
                    data: {
                        ajax_user_login: $("#user_login").val(),
                    },
                    success: function(response) {

                        //clear
                        $("#user_login").val('');

                        swal({
                            html: true,
                            title: "<span style='font-size: 20px; font-weight: bold;'>แก้ไขข้อมูลสำเร็จ</span>",
                            text: "<span style='font-size: 15px; color: #000;'>เข้าสู่ระบบเพื่อใช้งาน</span>",
                            type: "success",
                            timer: 2000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });

                    },
                    error: function() {
                        //dialog ctrl
                        swal({
                            html: true,
                            title: "<span style='font-size: 15px; font-weight: bold;'>Warning</span>",
                            text: "<span style='font-size: 15px; color: #000;'>[D002]ติดต่อ Admin</span>",
                            type: "warning",
                            timer: 3000,
                            showConfirmButton: false,
                            allowOutsideClick: false
                        });
                    }
                });
    }
}
</script>

</body>
</html> 