<!--row -->
  <div class="row">	
	<div class="col-md-6">
	  <div class="box box-solid">
		<!-- /.box-header -->
		<div class="box-body">
			<div class="box-header with-border">
				<i class="fa fa-chrome"></i>
				<h3 class="box-title"> Browser supported</h3>
			</div><br>
			<div class="callout callout-warning">
				<h4>Note!</h4>
				<p>Internet explorer does not support all versions.</p>
			</div>	
			<p><b><?=$CFG->AppName;?></b> supports the following browsers:</p>
			<ul>
				<li><i class="fa fa-chrome"></i> Chrome (latest) -- Full efficiency</li>
				<li><i class="fa fa-firefox"></i> Firefox (latest)</li>
				<li><i class="fa fa-safari"></i> Safari (latest)</li>
				<li><i class="fa fa-opera"></i> Opera (latest)</li>
			</ul>
		</div>
		<!-- /.box-body -->
	  </div>
	  <!-- /.box -->
	</div>
	<!-- ./col -->

	<div class="col-md-6">
	<!------------------------------------->
	<?
	require_once("contact_us.php");
	?>
	<!------------------------------------->
	</div>
	<!-- ./col -->
	
	<div class="col-md-6">
	  <div class="box box-solid">
		<!-- /.box-header -->
		<div class="box-body">
			<div class="box-header with-border">
				<i class="fa fa-cogs"></i>
				<h3 class="box-title"> Powered by</h3>
			</div>
			<ul>
				<div class="col-md-12">
					<img src="<?=$CFG->logopoweredby;?>/iis.png" style="height:25px;"/>&nbsp;&nbsp;<img src="<?=$CFG->logopoweredby;?>/apache.png" style="height:35px;"/>&nbsp;&nbsp;<img src="<?=$CFG->logopoweredby;?>/sql-srv.png" style="height:30px;"/>&nbsp;&nbsp;<img src="<?=$CFG->logopoweredby;?>/MySQL.png" style="height:30px;"/>
					<img src="<?=$CFG->logopoweredby;?>/jq.png" style="height:35px;"/>&nbsp;&nbsp;<img src="<?=$CFG->logopoweredby;?>/bootstrap.png" style="height:30px;"/>&nbsp;&nbsp;<img src="<?=$CFG->logopoweredby;?>/php.png" style="height:40px;"/>&nbsp;&nbsp;<img src="<?=$CFG->logopoweredby;?>/js.png" style="height:20px;"/>
				</div>
			</ul>
		</div>
		<!-- /.box-body -->
	  </div>
	  <!-- /.box -->
	</div>
	<!-- ./col -->

</div>
<!-- ./row -->