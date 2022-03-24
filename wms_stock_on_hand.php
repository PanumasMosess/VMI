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
      <h1><i class="fa fa-caret-right"></i>&nbsp;WMS Stock<small>Fulfillment Center</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Fulfillment Center</li>
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
						<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_pallet()"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Pallet</button>&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_tags();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by Tags</button>&nbsp;<button type="button" class="btn btn-default btn-sm" onclick="_export_stock_by_FgCode();"><i class="fa fa-bar-chart fa-lg"></i> Export Stock by FG Code</button>&nbsp;/&nbsp;<button type="button" class="btn btn-info btn-sm" onclick="window.location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div style="padding-left: 8px;">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT * ROWS</font>
					</div>
					<div class="box-body table-responsive padding">
					  <table id="tbl_stock_on_hand" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 13px;">
						  <th style="width: 30px;">No.</th>
						  <th style="text-align: center;">Actions/Details</th>
						  <th>Pallet ID</th>
						  <th>FG Code GDJ</th>
						  <th>Project</th>
						  <th>FG Code GDJ Desc.</th>
						  <th>Location</th>
						  <th style="color: indigo;">Quantity (Pcs.)</th>
						  <th>Status</th>
						  <th>Receive Date</th>
						  <th>Stock Aging (Day)</th>
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

	_load_stock_list();
	
	//Initialize Select2 Elements
    $('.select2').select2()
	
	//server side
	$('.c_txt_fulfillment_status').select2({
		placeholder: 'Select',
		ajax: {
			url: "<?=$CFG->src_put_away;?>/_load_fulfillment_status_list.php",
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
});

//load data json
function _load_stock_list()
{
	$.ajax({
		url: "<?=$CFG->src_put_away;?>/_load_stock_no_hand.php",
		success: function(data) {
			//console.log(data);
			var result = JSON.parse(data);
			callinTable(result);
		}
	});

	//plot data
	function callinTable(data) 
	{
		// Setup - add a text input to each footer cell
		$('#tbl_stock_on_hand thead tr').clone(true).appendTo( '#tbl_stock_on_hand thead' );
		$('#tbl_stock_on_hand thead tr:eq(1) th').each( function (i) {
			var title = $(this).text();
			
			//sel columnDefs
			if(i != 0 && i != 1 && i != 8)
			{
				$(this).html( '<input type="text" placeholder="Search '+title+'" />' );
		 
				$( 'input', this ).on( 'keyup change', function () {
					if ( table.column(i).search() !== this.value ) {
						table
							.column(i)
							.search( this.value )
							.draw();
					}
				} );
			}
			else
			{
				$(this).html( '' );
			}
		} );
	
		var table = $("#tbl_stock_on_hand").DataTable({
			"bDestroy": true,
			rowReorder: true,
			"aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
			"iDisplayLength": 10,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9,10 ] },
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
						return "<button type='button' class='btn btn-primary btn-sm custom_tooltip' id='" + data["var_encode_receive_pallet_code"] + "#####" + data["tags_fg_code_gdj"] + "' onclick='openRePrintPalletID(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print this Pallet ID</span></button>&nbsp;<button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["var_encode_receive_pallet_code"] + "' onclick='openRePrintAllTagsOnPalletID(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print ALL Tags On Pallet</span></button>&nbsp;<button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["receive_pallet_code"] + "#####" + data["tags_fg_code_gdj"] + "#####" + data["receive_location"] + "#####" + data["receive_date"] + "' onclick='openFuncDetails(this.id);'><i class='glyphicon glyphicon-qrcode'></i><span class='custom_tooltiptext'>Tags Details</span></button>"
					},
					"targets": -1
				},
				{
					data: 'receive_pallet_code'
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["tags_fg_code_gdj"] + " </font>"
					}
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["tags_project_name"] + " </font>"
					}
				},
				{
					data: 'tags_fg_code_gdj_desc'
				},
				{
					data: 'receive_location'
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["tags_packing_std"] + " </font>"
					}
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: green;'> " + data["receive_status"] + " </font>"
					}
				},
				{
					data: 'receive_date'
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["diff_aging"] + " </font>"
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

function openRePrintIndividual(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_tags?tag="+ id +"","_blank");
}

function openRePrintPalletID(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var _id = str_split_result[0];
	var t_tags_fg = str_split_result[1];

	window.open("<?=$CFG->src_mPDF;?>/print_pallet_tags?tag="+ _id +"&fg="+ t_tags_fg +"","_blank");
}

function openRePrintAllTagsOnPalletID(id)
{
	window.open("<?=$CFG->src_mPDF;?>/print_all_tags_on_pallet?tag="+ id +"","_blank");
}

function openRefill(id)
{
	$('#modal-refill-putaway').modal('show');
}

function openFuncDetails(id)
{
	$('#modal-pallet-details').modal('show');
	$('#hdn_pk_chng_status').val(id);
	
	_open_pallet_details(id);
}

function _open_pallet_details(id)
{
	//split id
	var str_split = id;
	var str_split_result = str_split.split("#####");

	var t_receive_pallet_code = str_split_result[0];
	var t_tags_fg_code_gdj = str_split_result[1];
	var t_receive_location = str_split_result[2];
	var t_receive_date = str_split_result[3];
	var t_receive_status = "";
	
	//load data json
	$.ajax({
		type: 'POST',
		url: "<?=$CFG->src_put_away;?>/_load_data_on_pallet_details.php",
		data: {
				t_receive_pallet_code: t_receive_pallet_code
				,t_tags_fg_code_gdj: t_tags_fg_code_gdj
				,t_receive_location: t_receive_location
				,t_receive_date: t_receive_date
				,t_receive_status: t_receive_status
		},
		success: function(respone) {
			//console.log(respone);
			var result = JSON.parse(respone);
			callinTableMailList(result);
		}
	});
	
	//plot data
	function callinTableMailList(data) 
	{
		var table = $("#tbl_pallet_details").DataTable({
			"bDestroy": true,
			rowReorder: true,
			"aLengthMenu": [[10, 25, 50, 75, 100, -1], [10, 25, 50, 75, 100, "All"]],
			"iDisplayLength": 10,
			columnDefs: [
				{ orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8 ] },
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
						return "<input type='checkbox' id='" + data["receive_tags_code"] + "#####" + data["receive_status"] + "' class='largerRadio'/>"
					},
					"targets": -1
				},
				{
					data: 'receive_pallet_code'
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["receive_tags_code"] + " </font>"
					}
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["tags_fg_code_gdj"] + " </font>"
					}
				},
				{
					"data": null,
					render: function(data, type, row) {
						return "<font style='color: indigo;'> " + data["tags_project_name"] + " </font>"
					}
				},
				{
					data: 'receive_location'
				},
				{
					"data": null,
					render: function ( data, type, row ) {
						
						if(data["receive_status"] == "Received")
						{
							return " <font style='color: green'>" + data["receive_status"] + "</font> "
						}
						else
						{
							return " <font style='color: red'>" + data["receive_status"] + "</font> "
						}
					}
				},
				{
					data: 'receive_date'
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
	
	$("#checkall").on('click', function () {
		 $('#tbl_pallet_details').DataTable()
			.column(1)
			.nodes()
			.to$()
			.find('input[type=checkbox]')
			.prop('checked', this.checked);
	});
}

function _export_stock_by_pallet()
{
	//href
	window.open('<?=$CFG->src_put_away;?>/excel_stock_by_pallet','_blank');
}

function _export_stock_by_tags()
{
	//href
	window.open('<?=$CFG->src_put_away;?>/excel_stock_by_tags','_blank');
}

function _export_stock_by_FgCode()
{
	//href
	window.open('<?=$CFG->src_put_away;?>/excel_stock_by_FgCode','_blank');
}

function _conf_chng_status()
{
	let table = $('#tbl_pallet_details').DataTable();
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
	else if($('#txt_fulfillment_status').val() == "")
	{
		//dialog ctrl
		swal({
		  html: true,
		  title: "<span style='font-size: 15px; font-weight: bold;'>Warning !!!</span>",
		  text: "<span style='font-size: 15px; color: #000;'>[C001] --- Please select Status</span>",
		  type: "warning",
		  timer: 2000,
		  showConfirmButton: false,
		  allowOutsideClick: false
		});
		
		//hide
		setTimeout(function(){
			$('#txt_fulfillment_status').select2('open');
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
	
	$.ajax({
	  type: 'POST',
	  url: '<?=$CFG->src_put_away;?>/_confirm_move_status.php',
	  data: {
				var_arr_list: arr_list
				,var_status: $("#txt_fulfillment_status").val()
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
					_open_pallet_details($('#hdn_pk_chng_status').val());
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
					_open_pallet_details($('#hdn_pk_chng_status').val());
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

<!--------------------------------------------------------------------------------------------------------------------->
<!----------------dialog console--------------------------------------------------------------------------------------->
<!--------------------------------------------------------------------------------------------------------------------->
<!--------------------dlg putaway-------------------->
<!--$('#modal-refill-putaway').modal('show');-->
<div class="modal fade" id="modal-refill-putaway" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-exclamation-triangle"></i> xxxx !!!</h4>
	  </div>
	  <div class="modal-body">
		<p>5555</p>
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-primary btn-sm">xxxx</button>
		<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
		</div>
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!--------------------dlg pallet-details-------------------->
<div class="modal fade" id="modal-pallet-details" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title"><i class="fa fa-search"></i> Tags Details</h4><input type="hidden" id="hdn_pk_chng_status">
	  </div>
		<div class="box-header with-border">
			<div class="col-md-4"> Select Status: <font style="color: red; font-size:12px;">* เลือก Status ที่จะเปลี่ยน</font><select id="txt_fulfillment_status" name="txt_fulfillment_status" class="c_txt_fulfillment_status form-control input-sm select2" style="width: 100%;">
				<option value="" selected="selected">Select</option>
			</select></div>
			<div class="col-md-3"><font style="color: red; font-size:12px;">* กดปุ่ม Confirm เพื่อเปลี่ยนสถานะ</font><button type="button" class="btn btn-primary btn-sm" onclick="_conf_chng_status()"><i class="fa fa-check"></i> Confirm</button></div>
		</div>
					
	  <div class="modal-body">
		<div class="box-body table-responsive padding">
		  <table id="tbl_pallet_details" class="table table-bordered table-hover table-striped nowrap" width="100%">
			<thead>
			  <tr style="font-size: 13px;">
				<th style="width: 30px;">No.</th>
				<th style="width: 80px;"><input type="checkbox" id="checkall" class="largerRadio"/>&nbsp;&nbsp;ALL</th>
				<th>Pallet ID</th>
				<th style="color: indigo;">Tags ID</th>
				<th style="color: indigo;">FG Code GDJ</th>
				<th style="color: indigo;">Project</th>
				<th>Location</th>
				<th style="color: indigo;">Status</th>
				<th>Receive Date</th>
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
<!-- /.modal -->