<?
require_once("application.php");
require_once("get_authorized.php");
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
      <h1><i class="fa fa-caret-right"></i>&nbsp;User online<small>Track and display currently online users</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">User online</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
					  <h3 class="box-title">User online list</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-primary btn-sm" onclick="javascript:location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div class="box-header">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT TOP 1000 ROWS</font>
					</div>
					
					<!-- /.box-header -->
					<div class="box-body table-responsive padding">
					  <table id="tbl_user_online" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 13px;">
						  <th>No.</th>
						  <th>Status</th>
						  <th>User</th>
						  <th>Name-TH</th>
						  <th>Name-EN</th>
						  <th>Tel.</th>
						  <th>Email</th>
						  <th>Group</th>
						  <th>Authorize</th>
						  <th>Last online time</th>
						</tr>
						</thead>
						<tbody>
					<?
					$strSql = " select top 1000 * from tbl_user_online 
					left join 
					tbl_user 
					on tbl_user_online.UserCode = tbl_user.user_code 
					order by tbl_user_online.UserCode asc ";
					
					$objQuery = sqlsrv_query($db_con, $strSql);

					$row_id = 0;			
					while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
					{
						$row_id++;
						
						$UserCode = $objResult['UserCode'];
						$UserSection = $objResult['UserSection'];
						$OnlineLastTime = $objResult['OnlineLastTime'];
						$user_name_th = $objResult['user_name_th'];
						$user_name_en = $objResult['user_name_en'];
						$user_company = $objResult['user_company'];
						$user_email = $objResult['user_email'];
						$user_tel = $objResult['user_tel'];
						$user_section = $objResult['user_section'];
						$user_type = $objResult['user_type'];
						
						//your self
						if($t_cur_user_code_VMI_GDJ == $UserCode)
						{
							$t_underline = "underline";
						}
						else
						{
							$t_underline = "";
						}
						
						//user online
						$user_status = "<span style='color: #00F' class='fa fa-signal'></span> Online";
						
						//user last login
						$strSql_last_login = " select * from tbl_user_login_log where user_code_login_log = '$UserCode' order by user_id_login_log desc ";
						$objQuery_last_login = sqlsrv_query($db_con, $strSql_last_login);
						$objResult_last_login = sqlsrv_fetch_array($objQuery_last_login, SQLSRV_FETCH_ASSOC);
						$user_issue_date_login_log = $objResult_last_login['user_issue_date_login_log'];
						$user_issue_time_login_log = $objResult_last_login['user_issue_time_login_log'];
						
						$full_date_last_login = $user_issue_date_login_log." ".substr($user_issue_time_login_log,0,8);
					?>
						<tr style="font-size: 13px;">
						  <td><?=$row_id;?></td>
						  <td><?=$user_status;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$UserCode;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$user_name_th;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$user_name_en;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$user_tel;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$user_email;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$user_section;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$user_type;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$full_date_last_login;?></td>
						</tr>
					<?
					}
					sqlsrv_close($db_con);
					?>						
						</tbody>
					  </table>
					</div>
					<!-- /.box-body -->
					
					<!--alert no item-->
					<input type="hidden" name="hdn_row" id="hdn_row" value="<?=$row_id;?>" />
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
<!--Onload this page-->
$(document).ready(function()
{
	//search
    /*$('#tbl_user_online').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_user_online').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9 ] },
            { orderable: false, targets: '_all' },
			{
				render: function (data, type, full, meta) {
					return "<div class='dt-text-wrap dt-width-td-wrap-400px'>" + data + "</div>";
				},
				targets: 6
			}
        ],
		pagingType: "full_numbers",
    });
	
});
</script>
</body>
</html>