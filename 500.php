<?
require_once("application.php");
require_once("js_css_header.php");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';
$LOGON_VMI_GDJ = isset($_SESSION['LOGON_VMI_GDJ']) ? $_SESSION['LOGON_VMI_GDJ'] : '';
?>
<!DOCTYPE html>
<html>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
	<?
	//check flow
	if($t_cur_user_code_VMI_GDJ == "" &&  $LOGON_VMI_GDJ == "")
	{
		require_once("menu_blank.php");
		
		//link
		$t_link = "$CFG->wwwroot/index";
	}
	else if($t_cur_user_code_VMI_GDJ != "" &&  $LOGON_VMI_GDJ == "True")
	{
		require_once("menu.php");
		
		//link
		$t_link = "$CFG->wwwroot/home";
	}
	?>
  <!--------------------------->
  <!-- body  -->
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="<?=$t_link;?>"><i class="fa fa-home"></i>Home</a></li><li class="active">500 error</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
      <div class="error-page">
		<h2 class="headline text-red"> 500</h2>
        <div class="error-content">
		  <h3><i class="fa fa-warning text-red"></i> Oops! Something went wrong.</h3>
		  <br>
          <p>
            We will work on fixing that right away.
            Meanwhile, you may <a href="<?=$t_link;?>">return to dashboard</a>.
          </p>
		  <a href="javascript:history.back()" class="btn btn-info"><i class="fa fa-arrow-left"></i> Go Back</a>
        </div>
        <!-- /.error-content -->
      </div>
      <!-- /.error-page -->
	  <!--<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>-->
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