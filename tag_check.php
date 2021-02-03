<?
require_once("application.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
<html>
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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Tag Check<small>Storage Location</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Tag Check</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-cubes"></i> Tag List</h3>
					</div>
					<!-- /.box-header -->					
					<div class="box-header">
          <div class="row">
									<div class="col-md-3">
										<select id="sel_fj_name" name="sel_fj_name" class="form-control select2" style="width: 100%;" onchange="_load_tag_missing(this.value)">
											<option selected="selected" value="">Select Project Name</option>
											<option value="ALL">ALL Project</option>
											<?
					                                $strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
					                                $objQuery_fj_name = sqlsrv_query($db_con, $strSQL_fj_name) or die ("Error Query [".$strSQL_fj_name."]");
					                                while($objResult_fj_name = sqlsrv_fetch_array($objQuery_fj_name, SQLSRV_FETCH_ASSOC))
					                            {
				                                ?>
											<option value="<?= $objResult_fj_name["bom_pj_name"]; ?>"><?= $objResult_fj_name["bom_pj_name"]; ?></option>
											<?
					                            }
				                                 ?>
										</select>
									</div>&nbsp;<button type="button" class="btn btn-info btn-md" onclick="_load_tag_missing();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
								</div>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
					</div>
					<span id="spn_load_tag_check"></span>
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


	//load wrong bom in stock
	_load_tag_missing("");
	
});

//check eng only
function isEnglishchar(str)
{   
    var orgi_text="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-";   
    var str_length=str.length;
    var isEnglish=true;   
    var Char_At="";   
    for(i=0;i<str_length;i++)
	{   
        Char_At=str.charAt(i);   
        if(orgi_text.indexOf(Char_At)==-1)
		{   
            isEnglish=false;   
            break;
        }      
    }   
    return isEnglish; 
}

function _load_tag_missing(value)
{

	//Load data
	setTimeout(function(){
		$("#spn_load_tag_check").load("<?=$CFG->src_report;?>/load_tag_recheck.php", {
      sel_fj_name : value
    });
	},300);
}


</script>
</body>
</html>


