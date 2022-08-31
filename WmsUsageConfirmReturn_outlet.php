<?
require_once("application.php");
require_once("get_authorized.php");
require_once("js_css_header.php");
?>
<!DOCTYPE html>
<html>
<style>
/* sweet-overlay support multiple alert */
.sweet-overlay{z-index:2000!important;}

/* select2 custom font size */
.select2-selection {
  -webkit-box-shadow: 0;
  box-shadow: 0;
  background-color: #fff;
  border: 0;
  border-radius: 0;
  color: #555555;
  font-size: 12px;
  outline: 0;
  min-height: 32px;
  text-align: left;
}

.select2-results__options{
    font-size:12px !important;
 }
 
.select2-selection__rendered {
  font-size: 12px;
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
      <h1><i class="fa fa-caret-right"></i>&nbsp;คืนสินค้า (Retrun)<small>สแกน Tags เพื่อคืนสินค้าเข้า stock</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">คืนสินค้า (Retrun)</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-cubes"></i> Stock Blance <small><font style="color: red; font-size:12px;">**Stock ล่าสุดที่อยู่ใน outlet store</font></small></h3>
					</div>
					<!-- /.box-header -->
										
					<div class="box-body table-responsive padding">
						<table id="tbl_wms_stock_fg" class="table table-bordered table-hover table-striped nowrap">
							<thead>
								<tr style="font-size: 17px;">
									<th colspan="7" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Stock Blance</b><span class="pull-right"><button type="button" class="btn btn-info btn-sm" onclick="_clear()"><i class="fa fa-refresh fa-lg"></i> Refresh</button></span></th>
								</tr>
								<tr style="font-size: 13px;">
									<th style="width: 30px;">No.</th>
									<th style="text-align: center;">Actions/Details</th>
									<th>Project Name</th>
									<th>FG Code GDJ</th>
									<th>Component Code</th>
									<th>Part customer</th>
									<th style="color: indigo;">Quantity (Pcs.)</th>
								</tr>
							</thead>
							<tbody style="font-size: 13px;">				
							</tbody>
					  </table>
					</div>
				</div>
				
				<div class="box box-warning">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="glyphicon glyphicon-qrcode"></i> Return Tags <small><font style="color: red; font-size:12px;">*คืนสินค้าในส่วนนี้</font></small></h3>
					</div>
					
					<div class="box-header with-border">
						<div class="col-md-4"><img src="<?=$CFG->iconsdir;?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> Scan Tags:
						<input type="text" id="txt_return_scan_tags" name="txt_return_scan_tags" onKeyPress="if (event.keyCode==13){ return _onScan_TagsID(); }" class="form-control input-md" placeholder="Scan Tags ID." autocomplete="off" autocorrect="off"  spellcheck="false"></div>
					</div>
					
					<div class="box-body table-responsive padding">
					  <table id="tbl_tags_return_list" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 17px;">
							<th colspan="8" class="bg-warning"><b><i class="glyphicon glyphicon-qrcode"></i>&nbsp;Tags list</b> <small><font style="color: red; font-size:12px;">*รายการ Tags ที่มีการคืนเข้า Stock | (ประจำวันที่ <?=date('Y-m-d');?>)</font></small></th>
						</tr>
						<tr style="font-size: 13px;">
						  <th style="width: 30px;">No.</th>
						  <th>Project</th>
						  <th style="color: indigo;">Tags ID</th>
						  <th>FG Code GDJ</th>
						  <th>Component Code</th>
						  <th>Part customer</th>
						  <th style="color: indigo;">Quantity (Pcs.)</th>
						</tr>
						</thead>
						<tbody style="font-size: 13px;">				
						</tbody>
					  </table>
					</div>		
				</div>	
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
	//toUpperCase
	//$('#txt_scn_put_tag_id').keyup(function() { this.value = this.value.toUpperCase(); });
	//$('#txt_scn_put_pallet').keyup(function() { this.value = this.value.toUpperCase(); });

	//Initialize Select2 Elements
    //$('.select2').select2();

	_load_stock_fg("<?=$objResult_authorized['user_type'];?>");
	_load_tags_return_list();
	
});

function _load_stock_fg(id)
{
	//load data json
	$.ajax({
		type: 'POST',
		url: "<?=$CFG->src_wms_usage_conf;?>/_load_stock_bal_fg_outlet.php",
		data: {
				str_user_type: id
		},
		success: function(respone) {
			//console.log(respone);
			var result = JSON.parse(respone);
			callinTable(result);
		}
	});
	
	//plot data
	function callinTable(data) 
	{
		var table = $("#tbl_wms_stock_fg").DataTable({
			"bDestroy": true,
			rowReorder: true,
			"aLengthMenu": [[5, 10, 15], [5, 10, 15]],
			"iDisplayLength": 5,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6 ] },
				{ orderable: false, targets: '_all' }
			],
			orderCellsTop: true,
			fixedHeader: true,
			pagingType: "full_numbers",
			responsive: true,
			autoFill: true,
			colReorder: true,
			keys: true,
			rowReorder: true,
			select: true,
			processing: true,
			serverside: true,
			data: data,
			columns: [{
					data: 'row_no'
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<center><button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["var_encode_ps_t_fg_code_gdj"] + "#####" + data["var_encode_ps_t_part_customer"] + "#####" + data["var_encode_ps_t_pj_name"] + "#####" + data["var_encode_bom_sku_code"] + "' onclick='openRePrintAllTagsOnFGCode(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print ALL Tags</span></button>&nbsp;<button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["ps_t_fg_code_gdj"] + "#####" + data["ps_t_part_customer"] + "#####" + data["ps_t_pj_name"] + "' onclick='openFuncTagsDetailsByFG(this.id);'><i class='fa fa-search fa-lg'></i><span class='custom_tooltiptext'>Tags Details</span></button>&nbsp;<button type='button' class='btn btn-success btn-sm custom_tooltip' id='" + data["ps_t_fg_code_gdj"] + "#####" + data["ps_t_part_customer"] + "#####" + data["ps_t_pj_name"] + "' onclick='openFuncWatchPicture(this.id);'><i class='fa fa-eye fa-lg'></i><span class='custom_tooltiptext'>Picture Box</span></button></center>"
					}
				},
				{
					data: 'ps_t_pj_name'
				},
				{
					data: 'ps_t_fg_code_gdj'
				},
				{
					data: 'bom_sku_code'
				},
				{
					data: 'ps_t_part_customer'
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["total_QTY"] + "</font> "
					}
				}
			]
		});
	}
}

function openFuncTagsDetailsByFG(id)
{
	$('#modal-tags-details').modal('show');
	_open_tags_details(id);
}

function openFuncTagsDetailsByFG(id)
{
	$('#modal-tags-details').modal('show');
	
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_fg_code = str_split_result[0];
	var t_part_customer = str_split_result[1];
	var t_pj_name = str_split_result[2];
	
	//load data json
	$.ajax({
		type: 'POST',
		url: "<?=$CFG->src_wms_usage_conf;?>/_load_tags_details.php",
		data: {
				t_fg_code: t_fg_code
				,t_part_customer: t_part_customer
				,t_pj_name: t_pj_name
		},
		success: function(respone) {
			//console.log(respone);
			var result = JSON.parse(respone);
			callinTableTags(result);
		}
	});
	
	//plot data
	function callinTableTags(data) 
	{
		var table = $("#tbl_tags_details").DataTable({
			"bDestroy": true,
			rowReorder: true,
			"aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
			"iDisplayLength": 10,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5,6 ] },
				{ orderable: false, targets: '_all' }
			],
			orderCellsTop: true,
			fixedHeader: true,
			pagingType: "full_numbers",
			responsive: true,
			autoFill: true,
			colReorder: true,
			keys: true,
			rowReorder: true,
			select: true,
			processing: true,
			serverside: true,
			data: data,
			columns: [{
					data: 'row_no'
				},
				{
					data: 'receive_status'
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["tags_code"] + " </font>"
					}
				},
				{
					data: 'tags_fg_code_gdj'
				},
				{
					data: 'bom_part_customer'
				},
				{
					data: 'bom_sku_code'
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["tags_packing_std"] + " </font>"
					}
				}
			]
		});
	}
}

function openFuncWatchPicture(id)
{
	$('#modal-picture_re').modal('show');
	
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_fg_code = str_split_result[0];
	var t_part_customer = str_split_result[1];
	var t_pj_name = str_split_result[2];

	// document.getElementById("picture_box").src="/images_outlet/"+ t_part_customer + ".PNG";

	var pictureImage= `${'<?=$CFG->src_b2b_sale;?>' + '/' + t_part_customer  + '.JPG'}`;

	$('#picture_box').attr('src', pictureImage);


}

function openRePrintAllTagsOnFGCode(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var fg_code = str_split_result[0];
	var part_customer = str_split_result[1];
	var pj_name = str_split_result[2];
	var sku_code = str_split_result[3];
	
	window.open("<?=$CFG->src_mPDF;?>/print_all_tags_customer?fg_code="+ fg_code +"&part_customer="+ part_customer +"&pj_name="+ pj_name +"&sku_code="+ sku_code +"","_blank");
}

function _clear()
{
	_load_stock_fg("<?=$objResult_authorized['user_type'];?>");
	$("#txt_return_scan_tags").val('');
	_load_tags_return_list();
}

//tbl_tags_return_list
function _load_tags_return_list()
{
	$("#checkall").prop("checked", false);
	
	//load data json
	$.ajax({
		type: 'POST',
		url: "<?=$CFG->src_wms_usage_conf;?>/_load_return_tags.php",
		data: {
				str_user_type: "<?=$objResult_authorized['user_type'];?>"
		},
		success: function(respone) {
			//console.log(respone);
			var result = JSON.parse(respone);
			callinTable(result);
		}
	});
	
	//plot data
	function callinTable(data) 
	{
		var table = $("#tbl_tags_return_list").DataTable({
			"bDestroy": true,
			rowReorder: true,
			"aLengthMenu": [[5, 10, 15], [5, 10, 15]],
			"iDisplayLength": 5,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [ 0,1,2,3,4,5,6 ] },
				{ orderable: false, targets: '_all' }
			],
			orderCellsTop: true,
			fixedHeader: true,
			pagingType: "full_numbers",
			responsive: true,
			autoFill: true,
			colReorder: true,
			keys: true,
			rowReorder: true,
			select: true,
			processing: true,
			serverside: true,
			data: data,
			columns: [{
					data: 'row_no'
				},
				{
					data: 're_terminal_name'
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["re_tags_code"] + "</font> "
					}
				},
				{
					data: 're_fg_code_gdj'
				},
				{
					data: 'bom_sku_code'
				},
				{
					data: 're_part_customer'
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["tags_packing_std"] + "</font> "
					}
				}
			]
		});
	}
}

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

function _onScan_TagsID()
{
	if($("#txt_return_scan_tags").val() != "")
	{
		if(isEnglishchar($("#txt_return_scan_tags").val())==false)
		{
			//dialog ctrl
			swal({
			  html: true,
			  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
			  text: "<span style='font-size: 13px; color: #000;'>[C001] --- โปรดเปลี่ยนภาษา ไปเป็นภาษาอังกฤษ</span>",
			  type: "warning",
			  timer: 2000,
			  showConfirmButton: false,
			  allowOutsideClick: false
			});
			
			//hide
			setTimeout(function(){
				$("#txt_return_scan_tags").val('');
				$("#txt_return_scan_tags").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			//insert to temp pre
			$.ajax({
			  type: 'POST',
			  url: '<?=$CFG->src_wms_usage_conf;?>/_ReturnTags.php',
			  data: { 
						txt_return_scan_tags: $("#txt_return_scan_tags").val()
						,str_user_type: "<?=$objResult_authorized['user_type'];?>"
					},
					success: function(response){
					
					//check alert
					if(response == "not match")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 13px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_return_scan_tags").val() +"</b> pattern not match ! This Tags ID is not data available in Master Tags or Wrong Tags ?</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_return_scan_tags").val('');
							$("#txt_return_scan_tags").focus();
						}, 500);
					}
					else if(response == "wrong pj")
					{
						//dialog ctrl
						swal({
						  html: true,
						  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
						  text: "<span style='font-size: 13px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_return_scan_tags").val() +"</b> Wrong Project ?</span>",
						  type: "error",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_return_scan_tags").val('');
							$("#txt_return_scan_tags").focus();
						}, 500);
					}
					else if(response == "success")
					{	
						//dialog ctrl
						swal({
						  html: true,
						  title: "<br><span style='font-size: 15px; font-weight: bold;'>Success !!!</span>",
						  text: "<span style='font-size: 13px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_return_scan_tags").val() +"</b> Return success.</span>",
						  type: "success",
						  timer: 3000,
						  showConfirmButton: false,
						  allowOutsideClick: false
						});
						
						//clear step
						setTimeout(function(){
							$("#txt_return_scan_tags").val('');
							$("#txt_return_scan_tags").focus();
							
							_load_stock_fg("<?=$objResult_authorized['user_type'];?>");
							_load_tags_return_list();
						}, 500);
					}
					
				  },
				error: function(){
					//dialog ctrl
					swal({
					  html: true,
					  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
					  text: "<span style='font-size: 13px; color: #000;'>[D002] --- Ajax Error !!! Cannot operate</span>",
					  type: "warning",
					  timer: 3000,
					  showConfirmButton: false,
					  allowOutsideClick: false
					});
				}
			});
		}
	}
}
</script>
</body>
</html>

<!--------------------dlg tags-details-------------------->
<div class="modal fade" id="modal-tags-details" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-search"></i> Tags Details</h4>
	  </div>
		
	  <div class="modal-body">
		<div class="box-body table-responsive padding">
		  <table id="tbl_tags_details" class="table table-bordered table-hover table-striped nowrap" width="100%">
			<thead>
			  <tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th>Project</th>
				<th style="color: indigo;">Tags ID</th>
				<th>FG Code GDJ</th>
				<th>Part customer</th>
				<th>Component Code</th>
				<th style="color: indigo;">Quantity (Pcs.)</th>
			  </tr>
			</thead>
			<tbody style="font-size: 13px;">				
			</tbody>
		  </table>
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
	  </div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!--------------------dlg tags-details-------------------->
<div class="modal fade" id="modal-picture_re" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body">
		<div class="box-body table-responsive padding" style="text-align: center;">
			<img src=""  id="picture_box" style="width: 550px;height: 400px">
		</div>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
	  </div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>