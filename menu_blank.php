<?
require_once("application.php");

/**********************************************************************************/
/*current var *********************************************************************/
$lang_en_title = isset($_GET['lang_en_title']) ? $_GET['lang_en_title'] : '';
$lang_th_title = isset($_GET['lang_th_title']) ? $_GET['lang_th_title'] : '';
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
	<a href="<?=$CFG->wwwroot;?>/index" class="logo">
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
			<!-- logo -->
		</div>
		
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
			  <!-- Messages: style can be found in dropdown.less-->
			  <li class="dropdown messages-menu">
				  <!--<div id="translators"><div id="google_translate_element"></div></div>-->
				  <div style="float: right; padding: 5px;  color: #FFF;">
					<img src="<?=$CFG->logodir;?>/GDJ.png" height="40px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;"/>
				  </div>
			  </li>
			</ul>
		</div>
	</nav>
</header>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
	<!-- sidebar: style can be found in sidebar.less -->
	<section class="sidebar">
	  <!-- sidebar menu: : style can be found in sidebar.less -->
	  <ul class="sidebar-menu" data-widget="tree">
		<li class="header"><i class="fa fa-list-ul"></i> MAIN NAVIGATION</li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "index" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "404" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "500"){ echo "active"; } ?>">
		  <a href="<?=$CFG->wwwroot;?>/index">
			<i class="fa fa-dashboard"></i> <span>Dashboard</span>
		  </a>
		</li>
		<li class="treeview <? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "login" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "check_login" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "forgot_pass" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "change_pass"){ echo "active"; } ?>">
		  <a href="#">
			<i class="fa fa-lock"></i> <span><?=$CFG->AppNameTitleMini;?> Login</span>
			<i class="fa fa-angle-left pull-right"></i>
		  </a>
		  <ul class="treeview-menu">
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "login" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "check_login" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "forgot_pass" || basename($_SERVER["SCRIPT_FILENAME"], '.php') == "change_pass"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/login"><i class="fa fa-angle-double-right"></i> <span>User/Officer</span></a></li>
			<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "cus_login"){ echo "active"; } ?>"><a href="<?=$CFG->wwwroot;?>/cus_login"><i class="fa fa-angle-double-right"></i> <span>Customer</span></a></li>
		  </ul>
		</li>	
		<li class="header"><i class="fa fa-list-ul"></i> General information</li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "manual"){ echo "active"; } ?>">
		  <a href="<?=$CFG->src_pathattc_manual;?>/xxx.pdf" target="_blank" title="Manual"><i class="fa fa-book"></i> <span>Manual</span></a>
		</li>
		<li class="<? if(basename($_SERVER["SCRIPT_FILENAME"], '.php') == "FAQ"){ echo "active"; } ?>">
		  <a href="<?=$CFG->wwwroot;?>/FAQ" title="FAQ"><i class="fa fa-question-circle-o"></i> <span>FAQ</span></a>
		</li>
		
		<?
			require_once("stats.php");
		?>
		
	  </ul>
	</section>
	<!-- /.sidebar -->
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

<!-- load client ip -->
<span id="load_hdn_ip_client"></span>

<script type="text/javascript">
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
</script>

<!--------------------->
<!-- css dialog -->
<!--------------------->
<style>
.modal.fade .modal-dialog{-webkit-transform:scale(.9);-moz-transform:scale(.9);-ms-transform:scale(.9);transform:scale(.9);top:300px;opacity:0;-webkit-transition:all .3s;-moz-transition:all .3s;transition:all .3s}.modal.fade.in .modal-dialog{-webkit-transform:scale(1);-moz-transform:scale(1);-ms-transform:scale(1);transform:scale(1);-webkit-transform:translate3d(0,-270px,0);transform:translate3d(0,-270px,0);opacity:1}
</style>

<!--------------------->
<!-- dialog console -->
<!--------------------->
<!-------------dlg default------------>
<div class="modal fade" id="modal-default">
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

<!-------------dlg alert waiting------------>
<div class="modal fade" id="modal-waiting-success">
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