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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Usage Confirm (Pick)<small>สแกน Tags เพื่อเบิกไปใช้</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Usage Confirm (Pick)</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-info">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-cubes"></i> Stock Blance <small><font style="color: red; font-size:12px;">*Stock ล่าสุดที่ไซต์งานลูกค้า</font></small></h3>
					</div>
					<!-- /.box-header -->
					
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
					</div>
					
					<div class="box-body table-responsive padding">
						<table id="tbl_wms_stock_fg" class="table table-bordered table-hover table-striped nowrap">
							<thead>
								<tr style="font-size: 17px;">
									<th colspan="7" class="bg-light-blue"><b><i class="fa fa-bar-chart fa-lg"></i>&nbsp;Stock Blance By FG Code</b><span class="pull-right"><button type="button" class="btn btn-info btn-sm" onclick="_clear()"><i class="fa fa-refresh fa-lg"></i> Refresh</button></span></th>
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
					  <h3 class="box-title"><i class="glyphicon glyphicon-qrcode"></i> Usage confirm <small><font style="color: red; font-size:12px;">*ลูกค้าเบิกสินค้าไปใช้งานในส่วนนี้</font></small></h3>
					</div>
					
					<div class="box-header with-border">
						<div class="col-md-4"><i class="fa fa-search"></i> Select Project:
						<select id="txt_usage_conf_pj" name="txt_usage_conf_pj" onchange="_on_load_pj(this.value)" class="c_txt_usage_conf_pj form-control input-sm select2" style="width: 100%">
							<option value="" selected="selected">Select</option>
						</select></div>
						
						<div class="col-md-4"><i class="fa fa-search"></i> Select Component Code/FG Code:
						<select id="txt_usage_conf_part_name" name="txt_usage_conf_part_name" onchange="_on_load_part_name(this.value)" class="c_txt_usage_conf_part_name form-control input-sm select2" style="width: 100%">
							<option value="" selected="selected">Select</option>
						</select></div>
					</div>
					
					<div class="box-header with-border">
						<div class="col-md-4"><img src="<?=$CFG->iconsdir;?>/Ecommerce-Barcode-Scanner-icon.png" height="16px"> Scan Tags:
						<input type="text" id="txt_usage_conf_scan_tags" name="txt_usage_conf_scan_tags" onKeyPress="if (event.keyCode==13){ return _onScan_TagsID(); }" class="form-control input-lg" placeholder="Scan Tags ID." autocomplete="off" autocorrect="off"  spellcheck="false"></div>
					</div>
					
					<div class="box-body table-responsive padding">
					  <table id="tbl_tags_usage_conf_pre" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 17px;">
							<th colspan="8" class="bg-warning"><b><i class="glyphicon glyphicon-qrcode"></i>&nbsp;Usage confirm tags list</b> <small><font style="color: red; font-size:12px;">*เมื่อสแกน Tags ตามที่ต้องการแล้ว ให้กดปุ่ม Confirm เพื่อยืนยันการเบิกไปใช้</font></small><span class="pull-right"><button type="button" class="btn btn-primary btn-sm" onclick="_usageConfirmPre()"><i class="fa fa-check"></i> Confirm</button> | <button type="button" class="btn btn-danger btn-sm" onclick="_DelUsageConfirmPre()"><i class="fa fa-trash"></i> Delete</button></span></th>
						</tr>
						<tr style="font-size: 13px;">
						  <th style="width: 30px;">No.</th>
						  <th style="width: 80px;"><input type="checkbox" id="checkall" class="largerRadio"/>&nbsp;&nbsp;ALL</th>
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
	//toUpperCase
	//$('#txt_scn_put_tag_id').keyup(function() { this.value = this.value.toUpperCase(); });
	//$('#txt_scn_put_pallet').keyup(function() { this.value = this.value.toUpperCase(); });

	//Initialize Select2 Elements
    $('.select2').select2();
	
	//server side
	$('.c_txt_usage_conf_pj').select2({
		placeholder: 'Select',
		ajax: {
			type: 'POST',
			url: "<?=$CFG->src_wms_usage_conf;?>/_load_project_name.php",
			dataType: 'json',
			delay: 250,
			data: function (data) {
				return {
					searchTerm: data.term // search term
					,str_user_type: "<?=$objResult_authorized['user_type'];?>"
				};
			},
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});
	
	$('.c_txt_usage_conf_part_name').select2({
		placeholder: 'Select',
		ajax: {
			type: 'POST',
			url: "<?=$CFG->src_wms_usage_conf;?>/_load_part_name.php",
			dataType: 'json',
			delay: 250,
			data: function (data) {
				return {
					searchTerm: data.term // search term
					,str_user_type: "<?=$objResult_authorized['user_type'];?>"
					,str_pj: $('#txt_usage_conf_pj').val()
				};
			},
			processResults: function (response) {
				return {
					results:response
				};
			},
			cache: true
		}
	});
	
	_load_stock_fg("<?=$objResult_authorized['user_type'];?>");
	_load_tags_usage_conf_pre();
	
});

function _load_stock_fg(id)
{
	//load data json
	$.ajax({
		type: 'POST',
		url: "<?=$CFG->src_wms_usage_conf;?>/_load_stock_bal_fg.php",
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
						return "<center><button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["var_encode_ps_t_fg_code_gdj"] + "#####" + data["var_encode_ps_t_part_customer"] + "#####" + data["var_encode_ps_t_pj_name"] + "#####" + data["var_encode_bom_sku_code"] + "' onclick='openRePrintAllTagsOnFGCode(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print ALL Tags</span></button>&nbsp;<button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["ps_t_fg_code_gdj"] + "#####" + data["ps_t_part_customer"] + "#####" + data["ps_t_pj_name"] + "' onclick='openFuncTagsDetailsByFG(this.id);'><i class='fa fa-search fa-lg'></i><span class='custom_tooltiptext'>Tags Details</span></button></center>"
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

function _on_load_pj(val)
{
	if($('#txt_usage_conf_pj').val() != "")
	{
		$('#txt_usage_conf_part_name').val(null).trigger('change');
		$('#txt_usage_conf_part_name').select2('open');
		$('#txt_usage_conf_scan_tags').val('');
	}
}

function _on_load_part_name(val)
{
	if($('#txt_usage_conf_part_name').val() != "")
	{
		setTimeout(function(){
			$("#txt_usage_conf_scan_tags").val('');
			$("#txt_usage_conf_scan_tags").focus();
		}, 500);
	}
}

function _clear()
{
	$('#txt_usage_conf_pj').val(null).trigger('change');
	$('#txt_usage_conf_part_name').val(null).trigger('change');
	$('#txt_usage_conf_scan_tags').val('');
	_load_stock_fg("<?=$objResult_authorized['user_type'];?>");
	_load_tags_usage_conf_pre();
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
	if($("#txt_usage_conf_scan_tags").val() != "")
	{
		if(isEnglishchar($("#txt_usage_conf_scan_tags").val())==false)
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
				$("#txt_usage_conf_scan_tags").val('');
				$("#txt_usage_conf_scan_tags").focus();
			}, 2500);
			
			return false;
		}
		else
		{
			if($("#txt_usage_conf_pj").val() == "")
			{
				//dialog ctrl
				swal({
				  html: true,
				  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
				  text: "<span style='font-size: 13px; color: #000;'>[C001] --- โปรดเลือก Project</span>",
				  type: "warning",
				  timer: 2000,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//hide
				setTimeout(function(){
					$("#txt_usage_conf_scan_tags").val('');
					$('#txt_usage_conf_pj').val(null).trigger('change');
					$('#txt_usage_conf_pj').select2('open');
				}, 2500);
				
				return false;
			}
			else if($("#txt_usage_conf_part_name").val() == "")
			{
				//dialog ctrl
				swal({
				  html: true,
				  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
				  text: "<span style='font-size: 13px; color: #000;'>[C001] --- โปรดเลือก Component Code/FG Code</span>",
				  type: "warning",
				  timer: 2000,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//hide
				setTimeout(function(){
					$("#txt_usage_conf_scan_tags").val('');
					$('#txt_usage_conf_part_name').val(null).trigger('change');
					$('#txt_usage_conf_part_name').select2('open');
				}, 2500);
				
				return false;
			}
			else
			{
				//insert to temp pre
				$.ajax({
				  type: 'POST',
				  url: '<?=$CFG->src_wms_usage_conf;?>/insert_usage_confirm_qc_pre.php',
				  data: { 
							txt_usage_conf_pj: $("#txt_usage_conf_pj").val()
							,txt_usage_conf_part_name: $("#txt_usage_conf_part_name").val()
							,txt_usage_conf_scan_tags: $("#txt_usage_conf_scan_tags").val()
						},
						success: function(response){
						
						//check alert
						if(response == "duplicate")
						{
							//dialog ctrl
							swal({
							  html: true,
							  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
							  text: "<span style='font-size: 13px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_usage_conf_scan_tags").val() +"</b> is already exists !</span>",
							  type: "warning",
							  timer: 3000,
							  showConfirmButton: false,
							  allowOutsideClick: false
							});
							
							//clear step
							setTimeout(function(){
								$("#txt_usage_conf_scan_tags").val('');
								$("#txt_usage_conf_scan_tags").focus();
							}, 500);
						}
						else if(response == "not match")
						{
							//dialog ctrl
							swal({
							  html: true,
							  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
							  text: "<span style='font-size: 13px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_usage_conf_scan_tags").val() +"</b> pattern not match ! This Tags ID is not data available in Master Tags or Wrong Tags ?</span>",
							  type: "error",
							  timer: 3000,
							  showConfirmButton: false,
							  allowOutsideClick: false
							});
							
							//clear step
							setTimeout(function(){
								$("#txt_usage_conf_scan_tags").val('');
								$("#txt_usage_conf_scan_tags").focus();
							}, 500);
						}
						else if(response == "wrong model")
						{
							//dialog ctrl
							swal({
							  html: true,
							  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
							  text: "<span style='font-size: 13px; color: #000;'>[C003] --- Tags ID: <b>"+ $("#txt_usage_conf_scan_tags").val() +"</b> Wrong model ?</span>",
							  type: "error",
							  timer: 3000,
							  showConfirmButton: false,
							  allowOutsideClick: false
							});
							
							//clear step
							setTimeout(function(){
								$("#txt_usage_conf_scan_tags").val('');
								$("#txt_usage_conf_scan_tags").focus();
							}, 500);
						}
						else
						{	
							//clear step
							setTimeout(function(){
								$("#txt_usage_conf_scan_tags").val('');
								$("#txt_usage_conf_scan_tags").focus();
							}, 500);
					
							//refresh
							_load_tags_usage_conf_pre();
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
}

//tbl_tags_usage_conf_pre
function _load_tags_usage_conf_pre()
{
	$("#checkall").prop("checked", false);
	
	//load data json
	$.ajax({
		type: 'POST',
		url: "<?=$CFG->src_wms_usage_conf;?>/_load_usage_confirm_qc_pre.php",
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
		var table = $("#tbl_tags_usage_conf_pre").DataTable({
			"bDestroy": true,
			rowReorder: true,
			"aLengthMenu": [[5, 10, 15], [5, 10, 15]],
			"iDisplayLength": 5,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7 ] },
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
						return "<input type='checkbox' id='" + data["conf_qc_tags_code"] + "' class='largerRadio'/>"
					},
					"targets": -1
				},
				{
					data: 'conf_qc_terminal_name'
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["conf_qc_tags_code"] + "</font> "
					}
				},
				{
					data: 'conf_qc_fg_code_gdj'
				},
				{
					data: 'bom_sku_code'
				},
				{
					data: 'conf_qc_part_customer'
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
	
	$("#checkall").on('click', function () {
		 $('#tbl_tags_usage_conf_pre').DataTable()
			.column(1)
			.nodes()
			.to$()
			.find('input[type=checkbox]')
			.prop('checked', this.checked);
	});
}

function _DelUsageConfirmPre()
{
	let table = $('#tbl_tags_usage_conf_pre').DataTable();
	let arr_list = [];
	let arr_length = 0;
	let checkedvalues = table.$('input[type=checkbox]:checked').each(function () {
		arr_list.push($(this).attr('id')),
		arr_length = arr_list.length
	});
	arr_list = arr_list.toString();
	//alert(arr_list);
	
	//check validate incase not select
	if(arr_length == 0)
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- โปรดเลือกอย่างน้อย 1 รายการ</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		$("#checkall").prop("checked", false);
		return false;
	}
	
	swal({
	  html: true,
	  title: "<br>",
	  text: "<span style='font-size: 15px; color: #000;'>Please wait for a while...</span>",
	  type: "warning",
	  showConfirmButton: false,
	  allowOutsideClick: false
	});

	$.ajax({
	  type: 'POST',
	  url: '<?=$CFG->src_wms_usage_conf;?>/_DelUsageConfirmPre.php',
	  data: {
				var_arr_list: arr_list
			},
			success: function(response){
			
			//split result
			var str_split = response;
			var str_split_result = str_split.split("#####");

			var t_command = str_split_result[0];
			var t_info = str_split_result[1];
	
			//dialog ctrl
			if(t_command == 'ERR')
			{
				swal({
				  html: true,
				  title: "<br>",
				  text: "<span style='font-size: 15px; color: #000;'>" + t_info + "</span>",
				  type: "error",
				  timer: 4000,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//refresh
				setTimeout(function(){
					_load_tags_usage_conf_pre();
				}, 4100);
			}
			else if(t_command == 'SUC')
			{
				swal({
				  html: true,
				  title: "<br>",
				  text: "<span style='font-size: 15px; color: #000;'>" + t_info + "</span>",
				  type: "success",
				  timer: 4000,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//refresh
				setTimeout(function(){
					_load_tags_usage_conf_pre();
				}, 4100);
			}

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

function _usageConfirmPre()
{
	let table = $('#tbl_tags_usage_conf_pre').DataTable();
	let arr_list = [];
	let arr_length = 0;
	let checkedvalues = table.$('input[type=checkbox]:checked').each(function () {
		arr_list.push($(this).attr('id')),
		arr_length = arr_list.length
	});
	arr_list = arr_list.toString();
	//alert(arr_list);
	
	//check validate incase not select
	if(arr_length == 0)
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- โปรดเลือกอย่างน้อย 1 รายการ</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		$("#checkall").prop("checked", false);
		return false;
	}
	
	swal({
	  html: true,
	  title: "<br>",
	  text: "<span style='font-size: 15px; color: #000;'>Please wait for a while...</span>",
	  type: "warning",
	  showConfirmButton: false,
	  allowOutsideClick: false
	});

	$.ajax({
	  type: 'POST',
	  url: '<?=$CFG->src_wms_usage_conf;?>/_UsageConfirm.php',
	  data: {
				var_arr_list: arr_list
			},
			success: function(response){
			
			//split result
			var str_split = response;
			var str_split_result = str_split.split("#####");

			var t_command = str_split_result[0];
			var t_info = str_split_result[1];
	
			//dialog ctrl
			if(t_command == 'ERR')
			{
				swal({
				  html: true,
				  title: "<br>",
				  text: "<span style='font-size: 15px; color: #000;'>" + t_info + "</span>",
				  type: "error",
				  timer: 4000,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//refresh
				setTimeout(function(){
					_clear();
				}, 4100);
			}
			else if(t_command == 'SUC')
			{
				swal({
				  html: true,
				  title: "<br>",
				  text: "<span style='font-size: 15px; color: #000;'>" + t_info + "</span>",
				  type: "success",
				  timer: 4500,
				  showConfirmButton: false,
				  allowOutsideClick: false
				});
				
				//refresh
				setTimeout(function(){
					_clear();
				}, 4100);
			}

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