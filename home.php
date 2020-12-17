<?
require_once("application.php");
require_once("get_authorized.php");
require_once("js_css_header.php");

//set project is setup terminal
$str_terminal = array('TSESA','TSPT','TSRA');
?>
<!DOCTYPE html>
<html>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?
	require_once("menu.php");
  ?>
  <!--------------------------->
  <!-- body  ------------------>
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-caret-right"></i>&nbsp;Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Dashboard</li>
      </ol>
    </section>
	
	<!------------------------------------------------->
	<!-- control group -------------------------------->
	<!------------------------------------------------->

		<!-- Main content -->
		<section class="content"> 
		  <!--announcement-->
		  <div class="row">
			<?
			  require_once("announce.php");
			?>
		  </div>
		  
		<?
		/*-------------------------------------------------*/
		/*-- control group --------------------------------*/
		/*-------------------------------------------------*/
		if($objResult_authorized['user_type'] == "Administrator") //Administrator
		{
		?>
		  <i class="fa fa-bar-chart"> <b>WMS Monitoring</b></i>
		  <div class="row">
			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-aqua">
			<div class="inner">
			  <font style="font-size:10px;">Total (Tags)/Today (Tags)</font>
			  <h4><span id="spn_db_print_tags_total"></span> / <span id="spn_db_print_tags_today"></span></h4>

			  <p>Print Master Tags</p>
			</div>
			<div class="icon">
			  <i class="fa fa-print"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/print_tags" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>

			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-green">
			<div class="inner">
			  <font style="font-size:10px;">Total (Tags)/Today (Tags)</font>
			  <h4><span id="spn_db_total_putaway"></span> / <span id="spn_db_total_putaway_today"></span></h4>

			  <p>Put-away Order</p>
			</div>
			<div class="icon">
			  <i class="fa fa-map-marker"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/put_away" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>

			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-yellow">
			<div class="inner">
			  <font style="font-size:10px;">Total Order</font>
			  <h4><span id="spn_db_replenishment"></span></h4>

			  <p>Replenishment Order</p>
			</div>
			<div class="icon">
			  <i class="fa fa-cart-plus"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>

			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-teal">
			<div class="inner">
			  <font style="font-size:10px;">Total Picking Sheet</font>
			  <h4><span id="spn_db_total_picking_qty"></span></h4>

			  <p>Picking Order</p>
			</div>
			<div class="icon">
			  <i class="fa fa-files-o"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/picking" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>

			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-green">
			<div class="inner">
			  <font style="font-size:10px;">Total Confirm Order</font>
			  <h4><span id="spn_db_total_picking_confirm_qty"></span></h4>

			  <p>Confirm Order</p>
			</div>
			<div class="icon">
			  <i class="fa fa-check-square-o"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/dtn_order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>
			
			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-aqua">
			<div class="inner">
			  <font style="font-size:10px;">Total DTN</font>
			  <h4><span id="spn_db_total_dtn_qty"></span></h4>

			  <p>Print Delivery Order</p>
			</div>
			<div class="icon">
			  <img src="<?=$CFG->iconsdir;?>/truck.png" height="50px">
			</div>
			<a href="<?=$CFG->wwwroot;?>/dtn_order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>
		  </div>
		  
		  <i class="fa fa-bar-chart"> <b>Terminal Monitoring</b></i>
		  <div class="row">
			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-gray">
			<div class="inner">
			  <font style="font-size:10px;">Today (Tags)</font>
			  <h4><span id="spn_db_total_stock_repnish"></span></h4>

			  <p>Stock Replenishment</p>
			</div>
			<div class="icon">
			  <i class="fa fa-cart-arrow-down"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/stock_replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>

			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-blue">
			<div class="inner">
			  <font style="font-size:10px;">Today (Tags)</font>
			  <h4><span id="spn_db_total_tags_usage_conf"></span></h4>

			  <p>Usage Confirm</p>
			</div>
			<div class="icon">
			  <i class="fa fa-cubes"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/usage_confirm" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>
		  </div>
		  
		  <div class="box box-info">
			<div class="box-header with-border">
			  <h3 class="box-title"><i class="fa fa-bar-chart"> <b>Stock Monitoring</b></i></h3>
			</div>
			
			<div class="box-body">
				<div class="row">
					<div class="col-lg-3 col-xs-6">
					  <div class="small-box bg-lime">
						<div class="inner">
						  <font style="font-size:10px; color: #000;">Total (Pcs./Tags)</font>
						  <h4 style="color: #000;"><span id="spn_db_wms_stock_pcs"></span> / <span id="spn_db_wms_stock_pack"></span></h4>

						  <p style="color: #000;"><b>WMS</b></p>
						</div>
						<div class="icon">
						  <i class="fa fa-bar-chart"></i>
						</div>
						<a href="<?=$CFG->wwwroot;?>/wms_stock" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
					  </div>
					</div>
					<!--for loop get all project-->
					<?
					$str_implode_all_PJ = _get_all_project_name($db_con);
					//explode
					$separated_all_PJ = explode(",", $str_implode_all_PJ);
					$num_all_PJ_separated = count($separated_all_PJ);
					
					foreach ($separated_all_PJ as $value_all_PJ) 
					{
						//check word
						$str_chk_pj = $value_all_PJ;
						
						//project list allow
						$array  = $str_terminal;
						$str_chk = strpos_var($str_chk_pj, $array); // will return true
						
						if($str_chk == false)
						{
							$str_word_pre_fix = "Project";
						}
						else
						{
							$str_word_pre_fix = "Terminal";
						}
					?>
					<div class="col-lg-3 col-xs-6">
					  <div class="small-box bg-lime">
						<div class="inner">
						  <font style="font-size:10px; color: #000;">Total (Pcs./Tags)</font>
						  <h4 style="color: #000;"><?=number_format(get_vmi_stock($db_con,$value_all_PJ,'pcs'));?> / <?=number_format(get_vmi_stock($db_con,$value_all_PJ,'pack'));?></h4>

						  <p style="color: #000;"><?=$str_word_pre_fix;?> <b><?=$value_all_PJ;?></b></p>
						</div>
						<div class="icon">
						  <i class="fa fa-bar-chart"></i>
						</div>
						<a href="<?=$CFG->wwwroot;?>/xxxxxx" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
					  </div>
					</div>
					<?			
					}
					?>
				  </div>
			</div>
		  </div>
		  
		  <i class="glyphicon glyphicon-alert"> <b>MAX/MIN Alert</b></i>
		  <div class="row">
			<div class="col-lg-3 col-xs-6">
			  <div class="small-box bg-red">
				<div class="inner">
				  <font style="font-size:10px;">Total Alert</font>
				  <h4>0</h4>

				  <p>WMS Order (MIN/MAX)</p>
				</div>
				<div class="icon">
				  <i class="fa fa-exclamation-triangle"></i>
				</div>
				<a href="<?=$CFG->wwwroot;?>/Order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			  </div>
			</div>
			
			<div class="col-lg-3 col-xs-6">
			  <div class="small-box bg-red">
				<div class="inner">
				  <font style="font-size:10px;">Total Alert</font>
				  <h4>0</h4>

				  <p>VMI Order (MIN/MAX)</p>
				</div>
				<div class="icon">
				  <i class="fa fa-exclamation-triangle"></i>
				</div>
				<a href="<?=$CFG->wwwroot;?>/Order" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			  </div>
			</div>
			
		  </div>
		  
		  <i class="fa fa-bar-chart"> <b>Master Data: Material BOM</b></i>
		  <div class="row">
			<div class="col-lg-3 col-xs-6">
			  <!-- small box -->
			  <div class="small-box bg-gray">
				<div class="inner">
				  <font style="font-size:10px;">Active/InActive</font>
				  <h4><span id="spn_db_bom_active"></span> / <span id="spn_db_bom_inactive"></span></h4>

				  <p>BOM DATA</p>
				</div>
				<div class="icon">
				  <i class="fa fa-database"></i>
				</div>
				<a href="<?=$CFG->wwwroot;?>/bom_mst" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			  </div>
			</div>
		  </div>
		
		<?
		}
		else if($objResult_authorized['user_type'] == "Customer") //Customer
		{
		?>
		  <i class="fa fa-bar-chart"> <b>Terminal Monitoring</b></i>
		  <div class="row">
			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-gray">
			<div class="inner">
			  <font style="font-size:10px;">Today (Tags)</font>
			  <h4><span id="spn_db_total_stock_repnish"></span></h4>

			  <p>Stock Replenishment</p>
			</div>
			<div class="icon">
			  <i class="fa fa-cart-arrow-down"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/stock_replenishment" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>

			<div class="col-lg-3 col-xs-6">
			<div class="small-box bg-blue">
			<div class="inner">
			  <font style="font-size:10px;">Today (Tags)</font>
			  <h4><span id="spn_db_total_tags_usage_conf"></span></h4>

			  <p>Usage Confirm</p>
			</div>
			<div class="icon">
			  <i class="fa fa-cubes"></i>
			</div>
			<a href="<?=$CFG->wwwroot;?>/usage_confirm" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
			</div>
			</div>
		  </div>
		
		  <div class="box box-info">
			<div class="box-header with-border">
			  <h3 class="box-title"><i class="fa fa-bar-chart"> <b>Stock Monitoring</b></i></h3>
			</div>
			
			<div class="box-body">
				<div class="row">
					<!--for loop get all project-->
					<?
					$str_implode_all_PJ = _get_all_project_name($db_con);
					//explode
					$separated_all_PJ = explode(",", $str_implode_all_PJ);
					$num_all_PJ_separated = count($separated_all_PJ);
					
					foreach ($separated_all_PJ as $value_all_PJ) 
					{
						//check word
						$str_chk_pj = $value_all_PJ;
						
						//project list allow
						$array  = $str_terminal;
						$str_chk = strpos_var($str_chk_pj, $array); // will return true
						
						if($str_chk == false)
						{
							$str_word_pre_fix = "Project";
						}
						else
						{
							$str_word_pre_fix = "Terminal";
						}
					?>
					<div class="col-lg-3 col-xs-6">
					  <div class="small-box bg-lime">
						<div class="inner">
						  <font style="font-size:10px; color: #000;">Total (Pcs./Tags)</font>
						  <h4 style="color: #000;"><?=number_format(get_vmi_stock($db_con,$value_all_PJ,'pcs'));?> / <?=number_format(get_vmi_stock($db_con,$value_all_PJ,'pack'));?></h4>

						  <p style="color: #000;"><?=$str_word_pre_fix;?> <b><?=$value_all_PJ;?></b></p>
						</div>
						<div class="icon">
						  <i class="fa fa-bar-chart"></i>
						</div>
						<a href="<?=$CFG->wwwroot;?>/xxxxxx" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
					  </div>
					</div>
					<?			
					}
					?>
				  </div>
			</div>
		  </div>
		<?
		}
		?>
		
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
});
</script>
</body>
</html>