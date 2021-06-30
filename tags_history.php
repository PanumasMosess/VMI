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
				<h1><i class="fa fa-caret-right"></i>&nbsp;Master Tags History</h1>
				<ol class="breadcrumb">
					<li><a href="<?= $CFG->wwwroot; ?>/home"><i class="fa fa-home"></i>Home</a></li>
					<li class="active">Master Tags History</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">

				<div class="row">
					<div class="col-xs-12">
						<div class="box box-warning">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-qrcode"></i> Master Tags List (All History)</h3>
							</div>
							<!-- /.box-header -->

							<div class="box-header">
								<button type="button" class="btn btn-info btn-sm" onclick="_load_tags_details();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
							</div>
							<div style="padding-left: 8px;">
								<i class="fa fa-filter" style="color: #00F;"></i>
								<font style="color: #00F;">SQL >_ SELECT Date - 7 Days</font>
							</div>
							<!-- /.box-header -->
							<!-- <span id="spn_load_tags_details"></span> -->
							<div class="box-body table-responsive padding">
								<table id="tbl_tags_his" class="table table-bordered table-hover table-striped nowrap">
									<thead>
										<tr style="font-size: 13px;">
											<th style="width: 30px;">No.</th>
											<th style="text-align: center;">Actions/Details</th>
											<th>Put away</th>
											<th>Tags ID</th>
											<th>FG Code GDJ</th>
											<th>Description</th>
											<th>Customer Code</th>
											<th>Production Plan Qty.</th>
											<th style="color: indigo;">Packing STD Qty.(Pcs.)</th>
											<th>Total Tags Qty</th>
											<th>Lot Token</th>
											<th>Issue By</th>
											<th>Issue Datetime</th>
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
		$(document).ready(function() {

			//load tags
			_load_tags_details();

		});


		function openRePrintIndividual(id) {
			window.open("<?= $CFG->src_mPDF; ?>/print_tags?tag=" + id + "", "_blank");
		}

		function openRePrintSet(id) {
			window.open("<?= $CFG->src_mPDF; ?>/print_tags?token=" + id + "", "_blank");
		}

		function _load_tags_details() {
			//Load data
			// setTimeout(function(){
			// 	//$("#spn_load_tags_details").html(""); //clear span
			// 	$("#spn_load_tags_details").load("<?= $CFG->src_print_tags; ?>/load_tags_history.php");
			// },300);
			$.ajax({
				url: "<?= $CFG->src_print_tags; ?>/load_tags_history.php",
				success: function(data) {
					//console.log(data);
					var result = JSON.parse(data);
					callinTableDetail(result);
				}
			});

			function callinTableDetail(data) {
                var table = $("#tbl_tags_his").DataTable({
                    bDestroy: true,
                    rowReorder: true,
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
                    columns: [
						{
                            data: 'no'
                        },
						{
                            "data": null,
                            render: function(data, type, row) {
                                return "<button type='button' class='btn btn-primary btn-sm custom_tooltip' id='" + data["tags_code_endcode"] + "' onclick='openRePrintIndividual(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print this Tag ID</span></button>&nbsp;&nbsp;<button type='button' class='btn btn-info btn-sm custom_tooltip' id='" + data["tags_token_endcode"] + "' onclick='openRePrintSet(this.id);'><i class='fa fa-print fa-lg'></i><span class='custom_tooltiptext'>Re-Print this Lot Token</span></button>"
                            },
                            "targets": -1
                        },
                        {
                            "data": null,
                            render: function(data, type, row) {
                                if(data["receive_status"] != ""){
                                    return "<div style='text-align: center; vertical-align: middle; color: green;'>Received</div>"
                                }else{
                                    return " "
                                }
                               
                            },
                            "targets": -1
                        },
                        {
                            data: 'tags_code'
                        },
                        {
                            data: 'tags_fg_code_gdj',                 
                        },
                        {
                            data: 'tags_fg_code_gdj_desc'
                        },
						{
                            data: 'customer_code'
                        },
                        {
                            data: 'tags_prod_plan'
                        },
                        {
                            data: 'tags_packing_std'
                        },
                        {
                            data: 'tags_total_qty'
                        },
						{
                            data: 'tags_token'
                        },
                        {
                            data: 'tags_issue_by'
                        },
                        {
                            data: 'tags_issue_datetime'
                        },
                    ]
                });

            }
		}
	</script>
</body>

</html>