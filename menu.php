<?
require_once("get_authorized.php");

/**********************************************************************************/
/*current var *********************************************************************/
$t_cur_pass_code_chg = isset($_SESSION['pass_code_chg']) ? $_SESSION['pass_code_chg'] : '';
$lang_en_title = isset($_GET['lang_en_title']) ? $_GET['lang_en_title'] : '';
$lang_th_title = isset($_GET['lang_th_title']) ? $_GET['lang_th_title'] : '';

/**********************************************************************************/
/*current user fb *****************************************************************/
$strOwnerID_VMI_GDJ = isset($_SESSION['strOwnerID_VMI_GDJ']) ? $_SESSION['strOwnerID_VMI_GDJ'] : '';
$strOwnerType_VMI_GDJ = isset($_SESSION['strOwnerType_VMI_GDJ']) ? $_SESSION['strOwnerType_VMI_GDJ'] : '';
$strFacebookID_VMI_GDJ = isset($_SESSION['strFacebookID_VMI_GDJ']) ? $_SESSION['strFacebookID_VMI_GDJ'] : '';
$strFacebookName_VMI_GDJ = isset($_SESSION['strFacebookName_VMI_GDJ']) ? $_SESSION['strFacebookName_VMI_GDJ'] : '';

//photo profile
$strPicProfile = "https://graph.facebook.com/".$strFacebookID_VMI_GDJ."/picture?type=large";
?>
<!-- @web name -->
<!-- =================================================== -->
<style type="text/css">
/*****system name*****/
#sys_name{text-shadow:0 0 5px #fff}
</style>

<!-- @Page Loader -->
<!-- =================================================== -->
<style>
#_loader_spn{transition:all .3s ease-in-out;opacity:1;visibility:visible;position:fixed;height:100vh;width:100%;background:#fff;z-index:90000}#_loader_spn.fadeOut{opacity:0;visibility:hidden}._load_spinner{width:50px;height:50px;position:absolute;top:calc(50% - 200px);left:calc(50% - 26px);background-color:#333;border-radius:100%;-webkit-animation:sk-scaleout 1s infinite ease-in-out;animation:sk-scaleout 1s infinite ease-in-out}@-webkit-keyframes sk-scaleout{0%{-webkit-transform:scale(0)}100%{-webkit-transform:scale(1);opacity:0}}@keyframes sk-scaleout{0%{-webkit-transform:scale(0);transform:scale(0)}100%{-webkit-transform:scale(1);transform:scale(1);opacity:0}}
</style>

<header class="main-header">
	<!-- Logo -->
	<a href="<?=$CFG->wwwroot;?>/home" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini" id="sys_name"><?=$CFG->AppNameTitleMini;?></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg" id="sys_name"><?=$CFG->AppName;?></span>
    </a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top">
	  <!-- Sidebar toggle button-->
	  <a href="#" class="sidebar-toggle hidden-md hidden-lg" title="Toggle navigation" data-toggle="push-menu" role="button"></a>
		<div class="visible-lg" style="float: left; padding: 5px; color: #FFF;">
			<img src="<?=$CFG->logodir;?>/GDJ.png" height="40px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;"/>
		</div>
		
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
			  <!-- Messages: style can be found in dropdown.less-->
			  <li class="messages-menu">
				<!-- messages -->
			  </li>
			
			  <!-- Messages: style can be found in dropdown.less-->
			  <li class="dropdown messages-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" title="Signal">
				  <i class="fa fa-signal"></i>
				  <span class="label label-warning"><span id="load_alert_main">0&nbsp;</span>ms<span id="spn_load_user_online"></span><span id="load_client"></span></span>
				  <span class="hidden-xs">&nbsp;&nbsp;</span>
				</a>
				<ul class="dropdown-menu">
				  <li class="header">System Info.</li>
				  <li>
					<!-- inner menu: contains the actual data -->
					<ul class="menu">
					  <li><!-- start message -->
						<a href="#" style="color: #000;">
							<div class="pull-left">
								<i class="fa fa-caret-right"></i>&nbsp;&nbsp;Your IP : <font style="font-size: 12px;"><b><?=getVisitorIP();?></b></font> 
							</div>
							<div class="pull-left">
								<i class="fa fa-caret-right"></i>&nbsp;&nbsp;Browser : <font style="font-size: 12px;"><b><?=get_browser_name();?></b></font> 
							</div>
						</a>
					  </li>
					  <!-- end message -->
					</ul>
				  </li>
				  <li class="footer">&nbsp;</li>
				</ul>
			  </li>
			  
			  <!-- User Account: style can be found in dropdown.less -->
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				   <? if($strFacebookID_VMI_GDJ == "")
				   {
				   ?>
				   <img src="<?=$CFG->wwwroot;?>/avatars/<?=get_profile_photo($db_con,$t_cur_user_code_VMI_GDJ,'160');?>" class="user-image" title="User image">
				   <?
				   }
				   else
				   {
				   ?>
				   <img src="<?=$strPicProfile;?>" class="user-image" title="User image">
				   <?					
				   }
				   ?>
				  <span class="hidden-xs"><?=$t_cur_user_code_VMI_GDJ;?></span>
				</a>
				<ul class="dropdown-menu">
				  <!-- User image -->
				  <li class="user-header">
					<p>
					  Your account
					  <small>
					    <? if($strFacebookID_VMI_GDJ != ""){ ?><b>FB : </b><?=$strFacebookName_VMI_GDJ;?><br><? } ?>
						Hello !! <b><?=$objResult_authorized['user_code'];?></b><br>
						Name-EN : <b><?=$objResult_authorized['user_name_en'];?></b><br>
						Name-TH : <b><?=$objResult_authorized['user_name_th'];?></b><br>
						Section : <b><?=$objResult_authorized['user_section'];?></b><br>
						Access rights : <b><?=$objResult_authorized['user_type'];?></b>
					  </small>
					</p>
				  </li>
				  <!-- Menu Body -->
				  <li class="user-body" style="background-color: #F0F8FF;">
					<div class="row">
					  <div class="col-xs-6 text-center">
						<div class="pull-left">
							<a href="#" class="btn btn-default btn-sm btn-flat"><i class="fa fa-user"></i>&nbsp;Profile</a>
						</div>
					  </div>
					  <div class="col-xs-6 text-center">
						<!--<a href="#" onclick="insertLogLockScreen();"><i class="fa fa-caret-right"></i>&nbsp;Lock Screen</a>-->
						<div class="pull-right">
							<img src="<?=$CFG->imagedir;?>/english-lang.png" height="25px" style="background-color: ; border: 1px solid #CCC; border-radius: 4px; padding: 2px;"/></a>&nbsp;<img src="<?=$CFG->imagedir;?>/thai-lang-black.png" height="25px" style="background-color: ; border: 1px solid #CCC; border-radius: 4px; padding: 2px;"/>
						</div>
					  </div>
					</div>
					<!-- /.row -->
				  </li>
				  <!-- Menu Footer-->
				  <!-- Menu Footer-->
				  <li class="user-footer" style="background-color: #F0FFFF;">
					<div class="pull-left">
					  <a href="#" style="color:#000;" onclick="opn_dlg_change_password();" class="btn btn-default btn-sm btn-flat"><i class="fa fa-lock"></i>&nbsp;Change password</a>
					</div>
					<div class="pull-right">
					  <a href="#" style="color:#000;" onclick="opn_dlg_log_out();" class="btn btn-default btn-sm btn-flat"><i class="fa fa-sign-out"></i>&nbsp;Log out</a>
					</div>
				  </li>
				</ul>
			  </li>
			  
			</ul>
		</div>
	</nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- Sidebar user panel -->
	<div class="user-panel">
		<div class="pull-left image">
		   <? if($strFacebookID_VMI_GDJ == "")
		   {
		   ?>
		   <img src="<?=$CFG->wwwroot;?>/avatars/<?=get_profile_photo($db_con,$t_cur_user_code_VMI_GDJ,'160');?>" class="img-circle" title="User image">
		   <?
		   }
		   else
		   {
		   ?>
		   <img src="<?=$strPicProfile;?>" class="img-circle" title="User image">
		   <?					
		   }
		   ?>
		</div>
		<div class="pull-left info">
		  <p><?=$t_cur_user_code_VMI_GDJ;?></p>
		  <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
		</div>
	</div>
	
	<?
	/*-------------------------------------------------*/
	/*-- control group --------------------------------*/
	/*-------------------------------------------------*/
	if($objResult_authorized['user_type'] == "Administrator") //Administrator
	{
	?>
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
	  <!-- sidebar menu: : style can be found in sidebar.less -->
	  <ul class="sidebar-menu" data-widget="tree">
	  
		<li class="header"><i class="fa fa-list-ul"></i> MAIN NAVIGATION</li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "home" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "404" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "500"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "home_bill" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "404" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "500"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/home_bill"><i class="fa fa-credit-card"></i> <span>Dashboard Billing</span></a></li>
		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "print_tags" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "tags_history" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "put_away" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "wms_stock" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "replenishment" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "picking" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "picking_QC" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "print_cover_sheet" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "dtn_order" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "print_do"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-folder-open-o"></i> <span>WMS</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "print_tags"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/print_tags"><i class="fa fa-print"></i> <span>Print Master Tags</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "tags_history"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/tags_history"><i class="fa fa-history"></i> <span>Master Tags History</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "put_away"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/put_away"><i class="fa fa-map-marker"></i> <span>Put-Away (Receive)</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "wms_stock"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/wms_stock"><i class="fa fa-bar-chart"></i> <span>WMS Stock</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "replenishment"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/replenishment"><i class="fa fa-cart-plus"></i> <span>Replenishment Order</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "picking"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/picking"><i class="fa fa-file-text-o"></i> <span>Picking Order</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "picking_QC"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/picking_QC"><i class="fa fa-qrcode"></i> <span>Picking Quality Control</span></a></li>
			<!--<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "print_cover_sheet"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/print_cover_sheet"><i class="fa fa-print"></i> <span>Print Cover Sheet</span></a></li>-->
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "dtn_order"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/dtn_order"><i class="fa fa-check-square-o"></i> <span>Confirm DTN</span></a></li>
		  </ul>
		</li>
		
		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "wms_stock_replenishment" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "wms_usage_confirm"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-cog"></i> <span>WMS-Special</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "wms_stock_replenishment"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/wms_stock_replenishment"><i class="fa fa-cart-arrow-down"></i> <span>Stock Replenishment</span></a></li>
			<!-- <li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "wms_usage_confirm"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/wms_usage_confirm"><i class="fa fa-cubes"></i> <span>Usage Confirm</span></a></li> -->
		  </ul>
		</li>
		
		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_replenishment" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "usage_confirm"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-folder-open-o"></i> <span>Terminal</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_replenishment"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/stock_replenishment"><i class="fa fa-cart-arrow-down"></i> <span>Stock Replenishment</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "usage_confirm"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/usage_confirm"><i class="fa fa-cubes"></i> <span>Usage Confirm</span></a></li>
		  </ul>
		</li>

		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_checking_wms" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_checking_terminal"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-check-circle-o"></i> <span>Location Check</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_checking_wms"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/stock_checking_wms"><i class="fa fa-cube"></i> <span>Stock WMS Check</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_checking_terminal"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/stock_checking_terminal"><i class="fa fa-cube"></i> <span>Stock Terminal Check</span></a></li>
		  </ul>
		</li>

		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "bom_recheck" || basename($_SERVER["SCRIPT_FILENAME"], '.php')  == "replenishment_reject" ||  basename($_SERVER["SCRIPT_FILENAME"], '.php') == "billing") { echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-search"></i> <span>Reporting</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
		    <li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "bom_recheck"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/bom_recheck"><i class="fa fa-search"></i> <span>Bom Recheck</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "replenishment_reject"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/replenishment_reject"><i class="fa fa-search"></i> <span>Replenishment Reject</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "billing"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/billing"><i class="fa fa-search"></i> <span>Billing</span></a></li>
			<li><a href="<?=$CFG->wwwroot;?>/xxxxx"><i class="fa fa-search"></i> <span>Invoice</span></a></li>
		  </ul>
		</li>
		
		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "bom_mst" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "customer_mst" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "driver_mst"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-database"></i> <span>Master Data</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "bom_mst"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/bom_mst"><i class="fa fa-database"></i> <span>BOM</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "customer_mst"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/customer_mst"><i class="fa fa-database"></i> <span>Customer</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "driver_mst"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/driver_mst"><i class="fa fa-database"></i> <span>Driver</span></a></li>
		  </ul>
		</li>
		
		<li class="header"><i class="fa fa-list-ul"></i> Users permissions</li>
		<li><a href="<?=$CFG->wwwroot;?>/xxxxx"><i class="fa fa-users"></i> <span>Users Management</span></a></li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "acc_unlock"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/acc_unlock"><i class="fa fa-unlock-alt"></i> <span>Unlock Users <span class="label label-danger"><span id="spn_account_lock">0</span></span></span></a></li>

		<li class="header"><i class="fa fa-list-ul"></i> General information</li>
		<li><a href="#" title="Manual"><i class="fa fa-book"></i> <span>Manual</span></a></li>
		<li><a href="#" title="FAQ"><i class="fa fa-question-circle-o"></i> <span>FAQ</span></a></li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "user_online"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/user_online"><i class="fa fa-desktop"></i> <span>Users Online <span class="label label-warning"><span id="spn_usr_online">0</span></span></span></a></li>
		
		<?
			require_once("stats.php");
		?>
		
	  </ul>
	</section>
	<!-- /.sidebar -->
	<?
	}
	else if($objResult_authorized['user_type'] == "Customer") //Customer
	{
	?>
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
	  <!-- sidebar menu: : style can be found in sidebar.less -->
	  <ul class="sidebar-menu" data-widget="tree">
	  
		<li class="header"><i class="fa fa-list-ul"></i> MAIN NAVIGATION</li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "home" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "404" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "500"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "home_bill" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "404" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "500"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/home_bill"><i class="fa fa-credit-card"></i> <span>Dashboard Billing</span></a></li>
		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_replenishment" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "usage_confirm"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-folder-open-o"></i> <span>Terminal</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_replenishment"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/stock_replenishment"><i class="fa fa-cart-arrow-down"></i> <span>Stock Replenishment</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "usage_confirm"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/usage_confirm"><i class="fa fa-cubes"></i> <span>Usage Confirm</span></a></li>
		  </ul>
		</li>

		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_checking_wms" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_checking_terminal"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-check-circle-o"></i> <span>Location Check</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "stock_checking_terminal"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/stock_checking_terminal"><i class="fa fa-cube"></i> <span>Stock Terminal Check</span></a></li>
		  </ul>
		</li>

		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "bom_recheck" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "replenishment_reject" ){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-search"></i> <span>Reporting</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li ><i class="fa fa-search"></i> <span>Billing</span></a></li>		
		  </ul>
		</li>
		
		<li class="header"><i class="fa fa-list-ul"></i> General information</li>
		<li><a href="#" title="Manual"><i class="fa fa-book"></i> <span>Manual</span></a></li>
		<li><a href="#" title="FAQ"><i class="fa fa-question-circle-o"></i> <span>FAQ</span></a></li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "user_online"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/user_online"><i class="fa fa-desktop"></i> <span>Users Online <span class="label label-warning"><span id="spn_usr_online">0</span></span></span></a></li>
		
		<?
			require_once("stats.php");
		?>
		
	  </ul>
	</section>
	<!-- /.sidebar -->
	<?
	}
	?>
</aside>

<!-- @Page Loader -->
<!-- =================================================== -->
<div id='_loader_spn'>
  <div class="_load_spinner"></div>
</div>

<script type="text/javascript">
//autorun
window.addEventListener('load', function load() 
{
	//*********************************************************************************/
	//*_loader_spn ********************************************************************/
	const loader = document.getElementById('_loader_spn');
	setTimeout(function() {
	  loader.classList.add('fadeOut');
	}, 300);
	
	//*********************************************************************************/
	//*clear IP ***********************************************************************/
	$('#load_hdn_ip_client').val('');
	//load client ip
	$("#load_hdn_ip_client").load('<?=$CFG->wwwroot;?>/get_ip.php?randval='+ Math.random());
	//delay
	setTimeout(function(){
		//call save ip
		save_ip_client();
	}, 2000);
	
});
</script>

<? 
/*
*******for load data real time*******
<!-- jQuery 3 -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>
*/
//read jquery.min.js from js_css_footer.php
require_once("js_css_footer.php");
?>

<!-- load client ip -->
<span id="load_hdn_ip_client"></span>

<script type="text/javascript">
//load alert
$(document).ready(function() 
{
	//*********************************************************************************/
	/*stage 1 load default ************************************************************/
	$("#load_alert_main").load('<?=$CFG->src_file_alert;?>/load_alert_main.php?randval='+ Math.random());
	$("#load_client").load('<?=$CFG->src_file_alert;?>/load_client.php?randval='+ Math.random());
	load_user_online();
	
	//*****************************************************************************/
	/*stage 2 auto run ************************************************************/
	//load main info
	//$("#load_alert_main").html("0");
	var refreshId1 = setInterval(function()
	{
		//load data and config
		$("#load_alert_main").load('<?=$CFG->src_file_alert;?>/load_alert_main.php?randval='+ Math.random());
		
	}, 15000);
	$.ajaxSetup({ cache: false });
	
	//load client ip
	//$("#load_client").html("0");
	var refreshId2 = setInterval(function()
	{
		//load client
		$("#load_client").load('<?=$CFG->src_file_alert;?>/load_client.php?randval='+ Math.random());
	
	}, 60000);
	$.ajaxSetup({ cache: false });
	
	//load user online
	var refreshId3 = setInterval(function()
	{
		//load user online
		load_user_online();
		
	}, 30000);
	$.ajaxSetup({ cache: false });	
	
});

//**********************************************************/
//*save ip to counter log **********************************/
function save_ip_client()
{
	//not null 
	if($('#load_hdn_ip_client').val() != "" || $('#load_hdn_ip_client').val() != "&nbsp;" || $('#load_hdn_ip_client').val() != null)
	{
		$.ajax({
		  type: 'POST',
		  url: '<?=$CFG->src_file_alert;?>/statistics.php',
		  data: {	
					hdn_ip_client: $('#load_hdn_ip_client').val()
				},
			success: function(response){
				//
			  },
			error: function(){
				
				//dialog ctrl
				alert("[D002] --- Ajax Error !!! Cannot operate");
				
			}
		});
	}
}

//load user online
function load_user_online()
{
	//load data
	//$("#spn_load_user_online").html(""); //clear span
	$("#spn_load_user_online").load("<?=$CFG->src_file_alert;?>/load_user_online.php");
}

function go_index()
{
	window.location.href = "<?=$CFG->wwwroot;?>/home";
}

//open dlg log out
function opn_dlg_log_out()
{
	//dialog ctrl
	swal({
	  html: true,
	  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
	  text: "<span style='font-size: 15px; color: #000;'>You want to log out ?</span>",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonClass: "btn-info",
	  confirmButtonText: "Yes",
	  cancelButtonText: "No",
	  closeOnConfirm: true,
	  closeOnCancel: true
	},
	function(isConfirm) {
	  if (isConfirm) {
		window.location.href = "<?=$CFG->wwwroot;?>/logout";
	  }
	});
}

//open dlg change password
function opn_dlg_change_password()
{
	//dialog ctrl
	$("#modal-change-password").modal("show");
	
	//clear
	$('#individual_pwd_login_chg').val('');
	$('#individual_new_pwd_login_chg').val('');
	$('#individual_re_new_pwd_login_chg').val('');
}

//change password
function change_password()
{
	//check validate
	//check length
	if( $("#individual_pwd_login_chg").val() == "")
	{
		//dialog ctrl
		alert("[C001] --- Enter current password");
		$("#individual_pwd_login_chg").focus();
		
		return false;
	}
	
	if( $("#individual_new_pwd_login_chg").val() == "")
	{
		//dialog ctrl
		alert("[C001] --- Enter new password");
		$("#individual_new_pwd_login_chg").focus();
		
		return false;
	}
	
	if( $("#individual_new_pwd_login_chg").val().length < 5 || $("#individual_new_pwd_login_chg").val().length > 10)
	{
		//dialog ctrl
		alert("[C002] --- Enter new password 5-10 character");
		$("#individual_new_pwd_login_chg").focus();
		
		return false;
	}
	
	if( $("#individual_re_new_pwd_login_chg").val() == "")
	{
		//dialog ctrl
		alert("[C001] --- Enter confirm password");
		$("#individual_re_new_pwd_login_chg").focus();
		
		return false;
	}
	
	if( $("#individual_re_new_pwd_login_chg").val().length < 5 || $("#individual_re_new_pwd_login_chg").val().length > 10)
	{
		//dialog ctrl
		alert("[C002] --- Enter confirm password 5-10 character");
		$("#individual_re_new_pwd_login_chg").focus();
		
		return false;
	}
	
	if( $("#individual_new_pwd_login_chg").val() != $("#individual_re_new_pwd_login_chg").val())
	{
		//dialog ctrl
		alert("[C002] --- The password does not match, Please confirm again. ");
		$("#individual_re_new_pwd_login_chg").focus();
		
		return false;
	}
	
	//dialog waiting ctrl
	$("#modal-waiting-success").modal("show");
	$("#al_results_waiting_success").html("[I001] --- Please wait for a while, system is sending emails....");
	
	//ajax post
	$.ajax({
	  type: 'POST',
	  url: '<?=$CFG->src_user_mst;?>/individual_chg_pass.php',
	  data: {
			individual_user_chg: $('#individual_user_chg').val()
			,individual_pwd_login_chg: $('#individual_pwd_login_chg').val()
			,individual_new_pwd_login_chg: $('#individual_new_pwd_login_chg').val()
			,individual_re_new_pwd_login_chg: $('#individual_re_new_pwd_login_chg').val()
		},
		success: function(response){
			
			//dialog waiting ctrl
			$("#modal-waiting-success").modal("hide");
	
			//dialog ctrl
			$("#modal-al-change-password").modal("show");
			$("#al_results_al_change_password").html(response);
			
			//hide
			//setTimeout(function(){
			//	$("#modal-al-change-password").modal("hide");
			//}, 3000);
			
			//dialog main
			$("#modal-change-password").modal("hide");
		},
		error: function(){
			
			//dialog waiting ctrl
			$("#modal-waiting-success").modal("hide");
			
			//dialog ctrl
			alert("[D002] --- Ajax Error !!! Cannot operate. ");
			
			//hide
			//setTimeout(function(){
			//	$("#modal-al-change-password").modal("hide");
			//}, 3000);
			
			//dialog main
			$("#modal-change-password").modal("hide");
		}
	});
}
</script>

<!--------------------->
<!-- css dialog -->
<!--------------------->
<style>
.modal.fade .modal-dialog{-webkit-transform:scale(.9);-moz-transform:scale(.9);-ms-transform:scale(.9);transform:scale(.9);top:300px;opacity:0;-webkit-transition:all .3s;-moz-transition:all .3s;transition:all .3s}.modal.fade.in .modal-dialog{-webkit-transform:scale(1);-moz-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transform:translate3d(0,-270px,0);transform:translate3d(0,-270px,0);opacity:1}
</style>

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------global dialog console-------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!-------------dlg change password------------>
<div class="modal fade" id="modal-change-password" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Change password !!!</h4>
	  </div>
	  <div class="modal-body">
		  <!-- /.login-logo -->
		  <div class="login-box-body">
			<p class="login-box-msg"><i class="fa fa-lock"></i> Change password</p>
			  <div class="form-group has-feedback">
				<input type="hidden" name="individual_user_chg" id="individual_user_chg" value="<?=$objResult_authorized['user_code'];?>">
				<label for="individual_pwd_login_chg">Current password</label>
				<input type="password" name="individual_pwd_login_chg" id="individual_pwd_login_chg" value="<?=$t_cur_pass_code_chg;?>" class="form-control" placeholder="Enter current password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			  </div>
			  <div class="form-group has-feedback">
			    <label for="individual_new_pwd_login_chg">New password</label>
				<input type="password" name="individual_new_pwd_login_chg" id="individual_new_pwd_login_chg" maxlength="10" class="form-control" placeholder="Enter new password (5-10 character)">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			  </div>
			  <div class="form-group has-feedback">
			    <label for="individual_re_new_pwd_login_chg">Confirm password</label>
				<input type="password" name="individual_re_new_pwd_login_chg" id="individual_re_new_pwd_login_chg" maxlength="10" class="form-control" placeholder="Enter confirm password (5-10 character)">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			  </div>
			<p>
			<b>How to create a strong new password.</b><br>
			&nbsp;<i class="fa fa-angle-double-right"></i> Passwords must be at least 8 characters long.<br>
			&nbsp;<i class="fa fa-angle-double-right"></i> Passwords must contain: (A-Z,a-z,0-9).<br>
			&nbsp;<i class="fa fa-angle-double-right"></i> Do not use real words.<br>
			&nbsp;<i class="fa fa-angle-double-right"></i> The password must not contain the login of the account or a part of its name.
			</p>
		  </div>
		  <!-- /.login-box-body -->
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary btn-sm" onclick="change_password();">Change password</button>
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		</div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-------------dlg alert change password------------>
<div class="modal fade" id="modal-al-change-password" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Warning !!!</h4>
	  </div>
	  <div class="modal-body">
		<p><span id="al_results_al_change_password"></span></p>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		</div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-------------dlg alert waiting------------>
<div class="modal fade" id="modal-waiting-success" style="z-index: 1111;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-body">
		<p><center><span id="al_results_waiting_success"></span><br><img src="<?=$CFG->imagedir;?>/ajax-loader3.gif" border="0" width="120px"></center></p>
	  </div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-------------dlg default------------>
<div class="modal fade" id="modal-default" style="z-index: 1111;" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> Warning !!!</h4>
	  </div>
	  <div class="modal-body">
		<p><span id="al_results"></span></p>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		</div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->