<?
require_once("application.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
<html>
<meta http-equiv="refresh" content="1200;url=https://lac-apps.albatrossthai.com/vmi/prod_daily_reccord" />
<body style="background-color: #87CEEB;">
<section class="content">
	<table width="100%" border="0">
	<tr>
		<td colspan="2" style="text-align: center;"><div class="visible-lg" style="float: left; padding-bottom: 4px; color: #FFF;">
			<img src="<?= $CFG->logodir; ?>/dscm.jpg" height="80px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;" />&nbsp;<img src="<?=$CFG->logodir;?>/gdjm.png" height="80px" style="background-color: #; border: 1px solid #DDD; border-radius: 4px; padding: 2px;"/>
		</div></td>
		<td colspan="2" style="text-align: right;"><h1 style="font-size:2vw;"><font style="background-color: #FFFFFF;">&nbsp;SHIFT: <b><span id="spn_load_curr_shift">--</span></b>&nbsp;</font></h1></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: center; background-color: #;"><h1 style="font-size:4vw;"><i class="fa fa-trophy" aria-hidden="true"></i> <b>PRODUCTION DAILY RECORD</b> <i class="fa fa-trophy" aria-hidden="true"></i></h1></td>
	</tr>
	<tr>
		<td colspan="1" width="35%" style="text-align: center;"><h1 style="font-size:2vw; background-color: #FFFFFF;">DATE: <b><span id="spn_load_curr_date">--:--:--</span></b></h1></td>
		<td colspan="1" width="20%" style="text-align: left; background-color: #;"><h1 style="font-size:2vw; background-color: #FFFFFF;">&nbsp;</h1></td>
		<td colspan="1" width="25%" style="text-align: left; background-color: #;"><h1 style="font-size:2vw; background-color: #FFFFFF;">&nbsp;</h1></td>
		<td colspan="1" width="20%" style="text-align: left; background-color: #;"><h1 style="font-size:2vw; background-color: #FFFFFF;">TIME: <b><span id="spn_load_curr_time">--:--:--</span></b></h1></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: center; background-color: #;"><table width="85%" border="0" align="center">
			<tr>
				<td width="40%" style="text-align: left;"><h1 style="font-size:4vw;">&nbsp;&nbsp;PLAN:<span id="spn_load_curr_main_plan"></span></h1></td>
				<td width="50%" style="text-align: right;"><h1 style="font-size:5vw; background-color: #FFFFFF; color: #000000;">&nbsp;<b><span id="spn_load_curr_plan"><?=number_format('0',2);?></span></b>&nbsp;</h1></td>
				<td width="10%" style="text-align: center;"><h1 style="font-size:5vw;">ft&#178;</h1></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: center; background-color: #;"><table width="85%" border="0" align="center">
			<tr>
				<td width="40%" style="text-align: left;"><h1 style="font-size:4vw;">&nbsp;&nbsp;ACTUAL:</h1></td>
				<td width="50%" style="text-align: right;"><h1 style="font-size:5vw; background-color: #FFFFFF; color: #0000FF;">&nbsp;<b><span id="spn_load_curr_act"><?=number_format('0',2);?></span></b>&nbsp;</h1></td>
				<td width="10%" style="text-align: center;"><h1 style="font-size:5vw;">ft&#178;</h1></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: center; background-color: #;"><table width="85%" border="0" align="center">
			<tr>
				<td width="40%" style="text-align: left;"><h1 style="font-size:4vw;">&nbsp;&nbsp;BALANCE:</h1></td>
				<td width="50%" style="text-align: right;"><h1 style="font-size:5vw; background-color: #FFFFFF; color: #000000;">&nbsp;<b><span id="spn_load_curr_bal"><?=number_format('0',2);?></span></b>&nbsp;</h1></td>
				<td width="10%" style="text-align: center;"><h1 style="font-size:5vw;">ft&#178;</h1></td>
			</tr>
		</table></td>
	</tr>
	<tr>
		<td colspan="4" style="text-align: center; background-color: #;"><table width="100%" border="0" align="center">
			<!--<tr>
				<td width="40%" style="text-align: left;"><h1 style="font-size:5vw;">&nbsp;&nbsp;EFFICIENCY:</h1></td>
				<td width="50%" style="text-align: right;"><h1 style="font-size:5vw; background-color: #FFFFFF; color: #000000;">&nbsp;<b><span id="spn_load_curr_eff"><?=number_format('0',2);?></span></b>&nbsp;</h1></td>
				<td width="10%" style="text-align: center;"><h1 style="font-size:5vw;">%</h1></td>
			</tr>-->
			<tr>
				<td colspan="6" width="100%" style="text-align: left;"><h1 style="font-size:2vw; background-color: #FFFFFF;">&nbsp;&nbsp;EFFICIENCY:</h1></td>
			</tr>
			<tr>
				<td width="23%" style="text-align: center;"><h1 style="font-size:4vw;">ACTUAL:</h1></td>
				<td width="20%" style="text-align: center;"><h1 style="font-size:5vw; background-color: #FFFFFF; color: #000000;">&nbsp;<b><span id="spn_load_curr_eff_act"><?=number_format('0',2);?></span></b>&nbsp;</h1></td>
				<td width="7%" style="text-align: center;"><h1 style="font-size:5vw;">%</h1></td>
				<td width="23%" style="text-align: center;"><h1 style="font-size:4vw;">DIFF.:</h1></td>
				<td width="20%" style="text-align: center;"><h1 style="font-size:5vw; background-color: #FFFFFF; color: #000000;">&nbsp;<b><span id="spn_load_curr_eff_diff"><?=number_format('0',2);?></span></b>&nbsp;</h1></td>
				<td width="7%" style="text-align: center;"><h1 style="font-size:5vw;">%</h1></td>
			</tr>
		</table></td>
	</tr>
	</table>
	
	<!--<center><p><MARQUEE behavior="ALTERNATE" onmouseover='this.stop()' onmouseout='this.start()' scrollAmount='10' scrollDelay='0'><h1 style="font-size:1vw; color: red;">Under Development</h1></marquee></p></center>-->

</section>
<? 
require_once("js_css_footer.php"); 
?>
</body>
</html>
<script type="text/javascript">
	//load alert
	$(document).ready(function() {

		//step 1st
		//load plan
		setTimeout(function() {
			$("#spn_load_curr_main_plan").load('<?=$CFG->src_prod_board;?>/load_curr_plan.php?randval=' + Math.random());
		}, 1000);
		
		
		//*****************************************************************************/
		/*Load date time **************************************************************/
		var refreshId1 = setInterval(function() {
			//load data
			$("#spn_load_curr_date").load('<?=$CFG->src_prod_board;?>/load_curr_date.php?randval=' + Math.random());
			$("#spn_load_curr_time").load('<?=$CFG->src_prod_board;?>/load_curr_time.php?randval=' + Math.random());

		}, 1000);
		$.ajaxSetup({
			cache: false
		});
		
		//*****************************************************************************/
		/*Load shift ******************************************************************/
		var refreshId2 = setInterval(function() {
			//load data
			$("#spn_load_curr_shift").load('<?=$CFG->src_prod_board;?>/load_curr_shift.php?randval=' + Math.random());

		}, 1000);
		$.ajaxSetup({
			cache: false
		});
		
		//*****************************************************************************/
		/*Load plan *******************************************************************/
		var refreshId3 = setInterval(function() {
			//load data
			$("#spn_load_curr_main_plan").load('<?=$CFG->src_prod_board;?>/load_curr_plan.php?randval=' + Math.random());

		}, 60000);
		$.ajaxSetup({
			cache: false
		});


	});
</script>