<div class="col-md-12">
  <div class="box box-solid">
	<!-- /.box-header -->
	<div class="box-body">
		<?
			//call function getBrowser
			$ua = getBrowser();
			
			//check Microsoft Edge
			if(strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false)
			{
				$tmp_browser = "Microsoft Edge";
			}
			else
			{
				$tmp_browser = $ua['name'];
			}
		?>
		<div id="div_announce_re">
		<div class="box-header no-border">
			<p><img src="<?=$CFG->logodir;?>/GDJ3.jpg" style="height:115px; border: 1.5px solid #ddd; border-radius: 4px;padding: 5px;"/></p>
			<h5><font style="color:#000;"><i><li class="fa fa-hand-o-right"></li>&nbsp;<?=$CFG->AppName;?> version <?=$CFG->App_ver;?> -- Last updated <?=$CFG->App_ver_update;?></i></font></h5>
			<h5><font style="color:#000;"><i><li class="fa fa-hand-o-right"></li>&nbsp;The system will work at full efficiency on the Google Chrome.</i> <i class="fa fa-chrome"></i></font></h5>
			<h5><font style="color:#4B0082;"><li class="fa fa-hand-o-right"></li>&nbsp;Your IP : <?=getVisitorIP();?></font> | <font style="color:#4B0082;">Browser : <?=$tmp_browser;?></font></h5>
		</div>
		</div>
		
		<div class="callout callout-info hidden-xs-down" style="margin-bottom: 3px;">
			<h4><i class="fa fa-bullhorn"></i> Announcement !!!</h4>
			<p><MARQUEE onmouseover='this.stop()' onmouseout='this.start()' scrollAmount='10' scrollDelay='0'>VMI-GDJ Version 1.0.0 Contact for use at IT Team (Development-New Digitalize System Section)</marquee></p>
			
			<p><?=$CFG->AppName;?> version <?=$CFG->App_ver;?> </p>
			<ul>
				<li>Available on Android and iOS mobile devices via Google Chrome. <i class="fa fa-android"></i>&nbsp;<i class="fa fa-apple"></i></li>
			</ul>
			
		</div>
	</div>
	<!-- /.box-body -->
  </div>
  <!-- /.box -->
</div>
<!-- ./col -->