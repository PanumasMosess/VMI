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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Move Project (Search FG Code)<small>Multiple Check list</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Move Project (Search FG Code)</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box box-success">
					<div class="box-header with-border">
					  <h3 class="box-title"><i class="fa fa-cubes"></i> Function List</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-info btn-sm" onclick="window.location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
					</div>
					
					<div class="box-header with-border">
					<div class="col-md-4"><i class="fa fa-search"></i> Select FG Code: <font style="color: red; font-size:12px;">* ค้นหา FG Code ที่ต้องการย้าย</font>
					<select id="txt_move_pj_fg_code" name="txt_move_pj_fg_code" onchange="onchange_load_tags_details(this.value);" class="c_txt_move_pj_fg_code form-control input-sm select2">
						<option value="" selected="selected">Select</option>
					</select></div>
					<div class="col-md-4"> Select Project: <font style="color: red; font-size:12px;">* เลือก Project ที่จะย้ายไป</font><select id="txt_move_pj_project_name" name="txt_move_pj_project_name" class="c_txt_move_pj_project_name form-control input-sm select2">
						<option value="" selected="selected">Select</option>
					</select></div>
					<div class="col-md-3"><font style="color: red; font-size:12px;">* กดปุ่ม Confirm เพื่อยืนยันการย้าย Project</font><button type="button" class="btn btn-primary btn-sm" onclick="_conf_move_pj()"><i class="fa fa-check"></i> Confirm</button></div>
					</div>
					
					<div class="box-body table-responsive padding">
					  <table id="tbl_tags_details" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 13px;">
						  <th style="width: 30px;">No.</th>
						  <th style="width: 80px;"><input type="checkbox" id="checkall" class="largerRadio"/>&nbsp;&nbsp;ALL</th>
						  <th style="color: indigo;">Tags ID</th>
						  <th>Pallet ID</th>
						  <th style="color: indigo;">FG Code GDJ</th>
						  <th style="color: indigo;">Project</th>
						  <th>Location</th>
						  <th style="color: indigo;">Quantity (Pcs.)</th>
						  <th style="color: green;">Status</th>
						  <th>Receive Date</th>
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
    $('.select2').select2()
	
	//server side
	$('.c_txt_move_pj_fg_code').select2({
		placeholder: 'Select',
		ajax: {
			url: "<?=$CFG->src_put_away;?>/_load_fg_list.php",
			dataType: 'json',
			delay: 250,
			data: function (data) {
				return {
					searchTerm: data.term // search term
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
	
	$('.c_txt_move_pj_project_name').select2({
		placeholder: 'Select',
		ajax: {
			url: "<?=$CFG->src_put_away;?>/_load_project_name.php",
			dataType: 'json',
			delay: 250,
			data: function (data) {
				return {
					searchTerm: data.term // search term
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
	
	_load_tags_list($('#txt_move_pj_fg_code').val());
	
});

function onchange_load_tags_details(id)
{
	$('#txt_move_pj_project_name').val(null).trigger('change');
	$("#checkall").prop('checked',false);
	
	_load_tags_list(id);
}

function _load_tags_list(id)
{
	//load data json
	$.ajax({
		type: 'POST',
		url: "<?=$CFG->src_put_away;?>/_load_tags_details.php",
		data: {
				t_fg_code: id
		},
		success: function(respone) {
			//console.log(respone);
			var result = JSON.parse(respone);
			callinTableJobsList(result);
		}
	});
	
	//plot data
	function callinTableJobsList(data) 
	{
		var table = $("#tbl_tags_details").DataTable({
			"bDestroy": true,
			rowReorder: true,
			"aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
			"iDisplayLength": 10,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9 ] },
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
						return "<input type='checkbox' id='" + data["receive_tags_code"] + "#####" + data["tags_project_name"] + "' class='largerRadio'/>"
					},
					"targets": -1
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["receive_tags_code"] + "</font> "
					}
				},
				{
					data: 'receive_pallet_code'
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["tags_fg_code_gdj"] + "</font> "
					}
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["tags_project_name"] + "</font> "
					}
				},
				{
					data: 'receive_location'
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: indigo'>" + data["tags_packing_std"] + "</font> "
					}
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						return " <font style='color: green'>" + data["receive_status"] + "</font> "
					}
				},
				{
					data: 'receive_date'
				}
			]
		});
	}
	
	$("#checkall").on('click', function () {
		 $('#tbl_tags_details').DataTable()
			.column(1)
			.nodes()
			.to$()
			.find('input[type=checkbox]')
			.prop('checked', this.checked);
	});
}

function _conf_move_pj()
{
	let table = $('#tbl_tags_details').DataTable();
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
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please select at least one item.</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		return false;
	}
	else if($('#txt_move_pj_fg_code').val() == "")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please select FG Code</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$('#txt_move_pj_fg_code').select2('open');
		}, 2500);
		
		return false;
	}
	else if($('#txt_move_pj_project_name').val() == "")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please select Project</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$('#txt_move_pj_project_name').select2('open');
		}, 2500);
		
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

	//gen token
    var str_token = tokenGen(30);
			
	$.ajax({
	  type: 'POST',
	  url: '<?=$CFG->src_put_away;?>/_confirm_move_project.php',
	  data: {
				var_arr_list: arr_list
				,var_project_name: $("#txt_move_pj_project_name").val()
				,str_token: str_token
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
					window.location.reload();
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
					//print tags
					window.open("<?=$CFG->src_mPDF;?>/print_tags_move_pj?token="+ str_token +"","_blank");
					window.location.reload();
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

function tokenGen(len)
{
	var text = "";
	var charset = "abcdefghijklmnopqrstuvwxyz0123456789";
	for (var i = 0; i < len; i++)
		text += charset.charAt(Math.floor(Math.random() * charset.length));

	return text;
}
</script>
</body>
</html>