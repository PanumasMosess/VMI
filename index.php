<?
require_once("application.php");
require_once("js_css_header.php");

//.htaccess
//echo redirectTohttps();

//call function getBrowser
$ua = getBrowser();

/**********************************************************************************/
/*auto redirect *******************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$LOGON_VMI_GDJ = isset($_SESSION['LOGON_VMI_GDJ']) ? $_SESSION['LOGON_VMI_GDJ'] : '';
$user_code_VMI_GDJ = isset($_SESSION['user_code_VMI_GDJ']) ? $_SESSION['user_code_VMI_GDJ'] : '';
?>
<!DOCTYPE html>
<html>
<body class="hold-transition skin-blue sidebar-mini">
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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Dashboard<small>General information</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/index"><i class="fa fa-home"></i>Home</a></li><li class="active">Dashboard</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
	  <!--announcement-->
	  <div class="row">
		<?
		  require_once("announce.php");
		?>
	  </div>
	  <!--row-->
	  
	  <!-- require_once dashboard.php -->
	  <?
	  require_once("dashboard.php");
	  ?>
      <!-- /.row -->
	  
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
<!--Onload this page-->
$(document).ready(function()
{
	//auto hide announce
	$("#div_announce_re").show().delay(5000).queue(function(n) {
	  $(this).hide(1200); n();
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
</script>
</body>
</html>
<?
//check session not null
if($user_code_VMI_GDJ != "" &&  $LOGON_VMI_GDJ == "True")
{
	print "<meta http-equiv='refresh' content='0;url=$CFG->wwwroot/home'>";
}
?>