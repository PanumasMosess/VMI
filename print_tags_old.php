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
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Print Master Tags</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
	
      <div class="box box-info">
		<div class="box-header with-border">
		  <h3 class="box-title"><i class="fa fa-qrcode"></i> Generate Master Tags</h3>
		</div>
		<!-- /.box-header -->
		<div class="box-body">
		  <div class="row">
			<div class="col-md-6">
			  <div class="form-group">
				<label>FG Code GDJ:<span id="spn_load_fg_code_gdj_packing_desc"></span><span id="spn_load_fg_code_gdj_packing_qty"></span></label>
				<select id="sel_fg_code_gdj" name="sel_fg_code_gdj" class="form-control input-sm select2" style="width: 100%;" onchange="func_load_packing_qty(this.value)">
				  <option selected="selected" value="">Choose</option>
				  <?
					$strSQL_fg_code_gdj = " SELECT bom_fg_code_gdj FROM tbl_bom_mst where bom_status = 'Active' group by bom_fg_code_gdj ";
					$objQuery_fg_code_gdj = sqlsrv_query($db_con, $strSQL_fg_code_gdj) or die ("Error Query [".$strSQL_fg_code_gdj."]");
					while($objResult_fg_code_gdj = sqlsrv_fetch_array($objQuery_fg_code_gdj, SQLSRV_FETCH_ASSOC))
					{
				  ?>
					<option value="<?=$objResult_fg_code_gdj["bom_fg_code_gdj"];?>"><?=$objResult_fg_code_gdj["bom_fg_code_gdj"];?></option>
				  <?
					}
				  ?>
				</select>
			  </div>
			  <!-- /.form-group -->
			</div>
			<!-- /.col -->
		  
			<div class="col-md-6">
			  <div class="form-group">
				<label>Description:</label>
				<input type="text" id="txt_fg_code_gdj_desc" name="txt_fg_code_gdj_desc" class="form-control input-sm" placeholder="Auto load Description" disabled>
			  </div>
			  <!-- /.form-group -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
		  
		  <div class="row">
			<div class="col-md-6">
			  <div class="form-group">
				<label>Production Plan Qty.:</label>
				<input type="text" id="txt_prod_plan" name="txt_prod_plan" class="form-control input-sm" maxlength="5" placeholder="Enter Production Plan Qty.">
			  </div>
			  <!-- /.form-group -->
			</div>
			<!-- /.col -->
		  
			<div class="col-md-6">
			  <div class="form-group">
				<label>Packing Standard Qty.:</label>
				<input type="text" id="txt_packing_std" name="txt_packing_std" class="form-control input-sm" maxlength="3" placeholder="Enter Packing standard Qty." disabled>
			  </div>
			  <!-- /.form-group -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.col -->
		  
		  <div class="row">
			<div class="col-md-6">
			  <div class="form-group">
				<label>Total Tags Qty.:</label>
				<input type="text" id="txt_tags_total" name="txt_tags_total" class="form-control input-sm" placeholder="Auto Calculate Tags Qty." disabled>
			  </div>
			  <!-- /.form-group -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
		  
		</div>
		<!-- /.box-body -->
		<div class="box-footer">
			<button type="button" class="btn btn-primary btn-sm" onclick="gen_tags()"><i class="fa fa-qrcode"></i> Generate Master Tags</button>
		</div>
		</div>
		<!-- /.box -->
	  
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-warning">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-qrcode"></i> Master Tags List (Today)</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-info btn-sm" onclick="_load_tags_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT TOP (800) * ROWS</font>
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
	//search
    /*
	$('#tbl_print_tags').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });
	*/
	
	/*
	<!--datatable search paging-->
	$('#tbl_print_tags').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9,10 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
		rowCallback: function(row, data, index)
		{
			//status
			//if(data[2] == "Active"){
			//	$(row).find('td:eq(2)').css('color', 'indigo');
			//}
			//else if(data[2] == "InActive"){
			//	$(row).find('td:eq(2)').css('color', 'red');
			//}		
		},
    });
	*/
	
	/*
	//valid number only
	$("#txt_prod_plan,#txt_packing_std").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [48, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	*/
	
	//valid number only
	$("#txt_prod_plan,#txt_packing_std").keyup(function(e)
	{
		if (/\D/g.test(this.value))
		{
			// Filter non-digits from input value.
			this.value = this.value.replace(/\D/g, '');
		}
		
		//call cal
		tags_calculate();
		
	});
	
	//Initialize Select2 Elements
	$(".select2").select2();
	
	//load tags
	_load_tags_details();
	
});

//valid number only onkeypress
//onkeypress="return isNumber(event)"
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
		
    }
    return true;
}

function func_load_packing_qty(id)
{
	//Load data
	setTimeout(function(){
		//$("#spn_load_fg_code_gdj_packing_desc").html(""); //clear span
		$("#spn_load_fg_code_gdj_packing_desc").load("<?=$CFG->src_print_tags;?>/fg_code_gdj_desc.php", { fg_code_gdj: id });
	},500);
	
	//Load data
	setTimeout(function(){
		//$("#spn_load_fg_code_gdj_packing_qty").html(""); //clear span
		$("#spn_load_fg_code_gdj_packing_qty").load("<?=$CFG->src_print_tags;?>/fg_code_gdj_packing_qty.php", { fg_code_gdj: id });
	},500);
	
	//clear
	$("#txt_prod_plan").val('');
	$("#txt_tags_total").val('');
}

function tags_calculate()
{
	var prod_qty = parseInt($("#txt_prod_plan").val()),packing_qty = parseInt($("#txt_packing_std").val());
	var str_diff = 	prod_qty / packing_qty;
	$("#txt_tags_total").val(Math.ceil(str_diff));
}

function gen_tags()
{
	//check validate 
	if($("#sel_fg_code_gdj").val() == "")
	{	
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Select FG Code GDJ</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$('#sel_fg_code_gdj').select2('open');
		}, 3000);
		
		return false ;
	}
	else if($("#txt_prod_plan").val() == "")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Enter Production Plan Qty.</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$("#txt_prod_plan").focus();
		}, 3000);
		
		return false ;
	}
	else if($("#txt_prod_plan").val() == "0")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Production Plan Qty is Zero.</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$("#txt_prod_plan").focus();
		}, 3000);
		
		return false ;
	}
	else if($("#txt_packing_std").val() == "")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Enter Packing Standard Qty.</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$("#txt_packing_std").focus();
		}, 3000);
		
		return false ;
	}
	else if($("#txt_packing_std").val() == "0")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Packing Standard Qty. is Zero</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$("#txt_packing_std").focus();
		}, 3000);
		
		return false ;
	}
	
	//gen token
	var str_token = tokenGen(30);

	$("#loadding").modal({
		backdrop: "static", //remove ability to close modal with click
		keyboard: false, //remove option to close with keyboard
		show: true //Display loader!
	});
	
	$.ajax({
	  type: 'POST',
	  url: '<?=$CFG->src_print_tags;?>/generate_tags.php',
	  data: { 
				iden_sel_fg_code_gdj: $("#sel_fg_code_gdj").val()
				,iden_txt_fg_code_gdj_desc: $("#txt_fg_code_gdj_desc").val()
				,iden_txt_prod_plan: $("#txt_prod_plan").val()
				,iden_txt_packing_std: $("#txt_packing_std").val()
				,iden_txt_tags_total: $("#txt_tags_total").val()
				,iden_token: str_token
			},
			success: function(response){
			
			//print tags send token encode
			window.open("<?=$CFG->src_mPDF;?>/print_tags?token="+ response +"","_blank");
			
			//clear
			$('#sel_fg_code_gdj').val(null).trigger('change');
			$("#txt_fg_code_gdj_desc").val('');
			$("#txt_prod_plan").val('');
			$("#txt_packing_std").val('');
			$("#txt_tags_total").val('');
			
			//load tags
			_load_tags_details();
			$("#loadding").modal("hide"); 

			
		  },
		error: function(){
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 15px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
			  type: "warning",
			  timer: 3000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
		}
	});
}

function tokenGen(len)
{
    var text = "";
    var charset = "abcdefghijklmnopqrstuvwxyz0123456789";
    for( var i=0; i < len; i++ )
        text += charset.charAt(Math.floor(Math.random() * charset.length));

    return text;
}

function openRePrintIndividual(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?tag="+ id +"","_blank");
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
		$("#spn_load_tags_details").load("<?=$CFG->src_print_tags;?>/load_tags_details.php");
	},300);
}
</script>
<!-- <script type="text/javascript">
inactivityTimeout = false;
resetTimeout()
function onUserInactivity() {
   location.reload();
}
function resetTimeout() {
   clearTimeout(inactivityTimeout)
   inactivityTimeout = setTimeout(onUserInactivity, 1000 * 50)
}
window.onmousemove = resetTimeout
</script> -->
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