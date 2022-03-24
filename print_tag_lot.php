<?
require_once("application.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
<html>

<style>
	/** SPINNER CREATION **/
	.modal-dialog-load {
		padding-top: 15%;
		padding-left: 10%;
	}

	.loader {
		position: relative;
		text-align: center;
		margin: 15px auto 25px auto;
		z-index: 9999;
		display: block;
		width: 80px;
		height: 80px;
		border: 10px solid rgba(0, 0, 0, .3);
		border-radius: 50%;
		border-top-color: #000;
		animation: spin 1s ease-in-out infinite;
		-webkit-animation: spin 1s ease-in-out infinite;
	}

	@keyframes spin {
		to {
			-webkit-transform: rotate(360deg);
		}
	}

	@-webkit-keyframes spin {
		to {
			-webkit-transform: rotate(360deg);
		}
	}
</style>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <?
	require_once("menu.php");
  ?>
  <!--------------------------->
  <!-- body  -->
  <!--------------------------->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-caret-right"></i>&nbsp;Print Master Tags<small>Generate tags for products</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Print Lot Tags</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
      <div class="box box-info">  
		<div class="row">
			<div class="col-xs-12">
				<div >
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-qrcode"></i> Master Lot Tags List (Today)</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-info btn-sm" onclick="_load_tags_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
					</div>
					<!-- /.box-header -->
					<span id="spn_load_tags_details"></span>
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
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
$(document).ready(function()
{	
	_load_tags_details();
	
});


function openRePrintIndividual(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?tag="+ id +"","_blank");
}

function openRePrintLot(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tag_lot?token="+ id +"","_blank");
}

function openRePrintSet(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?token="+ id +"","_blank");
}

function _load_tags_details()
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_tags_details").html(""); //clear span
		$("#spn_load_tags_details").load("<?=$CFG->src_report;?>/load_tags_lot.php");
	},300);
}
</script>
<script type="text/javascript">
inactivityTimeout = false;
resetTimeout();
function onUserInactivity() {
   location.reload();
}
function resetTimeout() {
   clearTimeout(inactivityTimeout)
   inactivityTimeout = setTimeout(onUserInactivity, 1000 * 1800)
}
window.onmousemove = resetTimeout
</script>
</body>
</html>

<!-- Model loading -->
<div class="modal fade" id="loadding" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog modal-dialog-load  modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="loader"></div>
			</div>
		</div>
	</div>
</div>