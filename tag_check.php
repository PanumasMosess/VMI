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
        <h1><i class="fa fa-caret-right"></i>&nbsp;Tag Terminal Checking<small>Storage Location</small></h1>
        <ol class="breadcrumb">
          <li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
          <li class="active">Tag Terminal Checking</li>
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
                    <select id="sel_fj_name" name="sel_fj_name" class="form-control select2" style="width: 100%;" onchange="func_load_tag_recheck(this.value)">
                      <?
											if(($objResult_authorized['user_type'] == "Administrator" && $objResult_authorized['user_section'] == "IT") || ($objResult_authorized['user_type'] == "Administrator" && $objResult_authorized['user_section'] == "GDJ")){
												$strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
											?>
                      <option value="ALL" selected="selected">All Project</option>
                      <?
											}else{
												$cus_code = $objResult_authorized['user_section'];
												if($cus_code == "IT"){
													$strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
												}else{
													$strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst where bom_cus_code = '$cus_code' group by bom_pj_name";
												}
												
												?>
                      <option selected="selected" value="ALL">All Project</option>
                      <?
											}
											?>
                      <?
					                              //  $strSQL_fj_name = " SELECT bom_pj_name FROM tbl_bom_mst group by bom_pj_name";
					                                $objQuery_fj_name = sqlsrv_query($db_con, $strSQL_fj_name) or die ("Error Query [".$strSQL_fj_name."]");
					                                while($objResult_fj_name = sqlsrv_fetch_array($objQuery_fj_name, SQLSRV_FETCH_ASSOC))
					                            {
				                                ?>
                      <option value="<?= $objResult_fj_name["bom_pj_name"]; ?>"><?= $objResult_fj_name["bom_pj_name"]; ?></option>
                      <?
					                            }
				                                 ?>
                    </select>
                  </div>&nbsp;
                  <div class="col-md-1"><button type="button" class="btn btn-info btn-md" onclick="_load_tag_reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button></div>
                </div>
              </div>
              <div style="padding-left: 8px;">
                <i class="fa fa-filter" style="color: #00F;"></i>
                <font style="color: #00F;">SQL >_ SELECT * ROWS - 7 Day</font>
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
    $(document).ready(function() {
      //load wrong bom in stock
      _load_tag_reload();

    });

    //check eng only
    function isEnglishchar(str) {
      var orgi_text = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890._-";
      var str_length = str.length;
      var isEnglish = true;
      var Char_At = "";
      for (i = 0; i < str_length; i++) {
        Char_At = str.charAt(i);
        if (orgi_text.indexOf(Char_At) == -1) {
          isEnglish = false;
          break;
        }
      }
      return isEnglish;
    }

    function _load_tag_reload() {
      		// <!--datatable search paging-->
			$("#loadding").modal({
				backdrop: 'static', //remove ability to close modal with click
				keyboard: false, //remove option to close with keyboard
				show: true //Display loader!
			});
      //Load data
      setTimeout(function() {
        $("#spn_load_tag_check").load("<?= $CFG->src_report; ?>/load_tag_recheck.php", {
          sel_fj_name: ""
        });
      }, 300);
    }
    
		function func_load_tag_recheck(value) {
      		// <!--datatable search paging-->
			$("#loadding").modal({
				backdrop: 'static', //remove ability to close modal with click
				keyboard: false, //remove option to close with keyboard
				show: true //Display loader!
			});

			setTimeout(function() {
				//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
				$("#spn_load_tag_check").load("<?= $CFG->src_report; ?>/load_tag_recheck.php", {
					sel_fj_name: value
				});
			}, 500);
		}

  	function openRePrintTag(id) {
			window.open("<?= $CFG->src_mPDF; ?>/print_tag_on_tag?tag=" + id + "", "_blank");
		}
  </script>
</body>

</html>

<!-- Model loading -->
<div class="modal fade" id="loadding" tabindex="-1" role="dialog" aria-labelledby="loadMeLabel">
	<div class="modal-dialog modal-dialog-load  modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body text-center">
				<div class="loader"></div>
			</div>
		</div>
	</div>
</div>