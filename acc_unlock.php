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
      <h1><i class="fa fa-caret-right"></i>&nbsp;Unlock Users<small>List of locked account</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=$CFG->wwwroot;?>/home"><i class="fa fa-home"></i>Home</a></li><li class="active">Unlock Users</li>
      </ol>
    </section>
	
	<!-- Main content -->
    <section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
					  <h3 class="box-title">Account is locked</h3>
					</div>
					<!-- /.box-header -->
					
					<div class="box-header">
						<button type="button" class="btn btn-danger btn-sm" onclick="unlockUserAll();"><i class="fa fa-unlock-alt fa-lg"></i> Unlock All</button>&nbsp;&nbsp;/&nbsp;&nbsp;<button type="button" class="btn btn-primary btn-sm" onclick="javascript:location.reload();"><i class="fa fa-refresh fa-lg"></i> Refresh</button>
					</div>
					<div class="box-header">
						<i class="fa fa-filter" style="color: #00F;"></i><font style="color: #00F;">SQL >_ SELECT TOP 1000 ROWS</font>
					</div>
					
					<!-- /.box-header -->
					<div class="box-body table-responsive padding">
					  <table id="tbl_user_unlock" class="table table-bordered table-hover table-striped nowrap">
						<thead>
						<tr style="font-size: 13px;">
						  <th>No.</th>
						  <th>Actions</th>
						  <th>Status</th>
						  <th>Login failed</th>
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
					$strSql = " 
						select 
						failed_login_ip_address,
						count(failed_login_ip_address) AS ColCnt 
						from tbl_user_failed_login
						left join
						tbl_user 
						on tbl_user_failed_login.failed_login_ip_address = tbl_user.user_code 
						WHERE failed_login_date BETWEEN dateadd(minute, -10, getdate()) AND getdate()
						group by failed_login_ip_address having count(failed_login_ip_address) >= '3' 
					";
					
					$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
					$num_row = sqlsrv_num_rows($objQuery);

					$row_id = 0;
					$user_name_th = "";	

					while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
					{
						$row_id++;
						
						$failed_login_ip_address = $objResult['failed_login_ip_address'];
						$ColCnt = $objResult['ColCnt'];
						
						//check lock/unlock
						// fa-unlock-alt
						// fa-lock
						
						//status
						if($ColCnt >= 3)
						{
							$str_status = "Locked";
						}
						
						//user last login
						$strSql_last_login = " select * from tbl_user_login_log where user_code_login_log = '$failed_login_ip_address' order by user_id_login_log desc ";
						$objQuery_last_login = sqlsrv_query($db_con, $strSql_last_login);
						$objResult_last_login = sqlsrv_fetch_array($objQuery_last_login, SQLSRV_FETCH_ASSOC);
						$user_issue_date_login_log = $objResult_last_login['user_issue_date_login_log'];
						$user_issue_time_login_log = $objResult_last_login['user_issue_time_login_log'];
						
						$full_date_last_login = $user_issue_date_login_log." ".substr($user_issue_time_login_log,0,8);
						
						//get user details
						$strSql_user_details = " select * from tbl_user_failed_login
						left join
						tbl_user 
						on tbl_user_failed_login.failed_login_ip_address = tbl_user.user_code
						where tbl_user_failed_login.failed_login_ip_address = '$failed_login_ip_address'
						";
						$objQuery_user_details = sqlsrv_query($db_con, $strSql_user_details);
						$objResult_user_details = sqlsrv_fetch_array($objQuery_user_details, SQLSRV_FETCH_ASSOC);
						
						$UserCode = $objResult_user_details['failed_login_ip_address'];
						$user_name_th = $objResult_user_details['user_name_th'];
						
						if($user_name_th != "")
						{
							$user_name_th = $objResult_user_details['user_name_th'];
							$user_name_en = $objResult_user_details['user_name_en'];
							$user_type = $objResult_user_details['user_type'];
							$user_email = $objResult_user_details['user_email'];
							$user_tel = $objResult_user_details['user_tel'];
							$user_section = $objResult_user_details['user_section'];
							
							$t_underline = "";
							$t_color = "";
						}
						else
						{
							$user_name_th = "undefined account";
							$user_name_en = "-";
							$user_type_code = "-";
							$user_email = "-";
							$user_tel = "-";
							$user_type = "-";
							$user_section = "-";
							
							$t_underline = "";
							$t_color = "#F00";
						}
					?>
						<tr style="font-size: 13px;">
						  <td><?=$row_id;?></td>
						  <td style="text-align:center;"><button type="button" class="btn btn-primary btn-sm" id="<?=$UserCode;?>" onclick="unlockUser(this.id);" title="Unlock"><i class="fa fa-lock fa-lg"></i></button></td>
						  <td style="text-decoration: <?=$t_underline;?>; color: #F00;"><?=$str_status;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$ColCnt;?></td>
						  <td style="text-decoration: <?=$t_underline;?>"><?=$failed_login_ip_address;?></td>
						  <td style="text-decoration: <?=$t_underline;?>; color: <?=$t_color?>"><?=$user_name_th;?></td>
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
	//active link
	$('#menu_acc09').addClass('active');
	
	//search
    /*$('#tbl_user_unlock').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });*/
	
	<!--datatable search paging-->
	$('#tbl_user_unlock').DataTable( {
        rowReorder: true,
        columnDefs: [
            { orderable: true, className: 'reorder', targets: [ 0,2,3,4,5,6,7,8,9,10,11 ] },
            { orderable: false, targets: '_all' }
        ],
		pagingType: "full_numbers",
    });
	
});

<!--unlock all user-->
function unlockUserAll()
{
	var result = confirm("[C003] --- Do you want to unlock all account ??? ");
	if(result)
	{
		$.ajax({
		  type: 'POST',
		  url: '<?=$CFG->src_user_mst;?>/userUnlockAll.php',
		  data: {
					cmd_ok: "unlockAll"
				},
			success: function(response){
				
				//success
				setTimeout(function(){
					alert(response);
				}, 600);
				
				//refresh
				setTimeout(function(){
					location.reload();
				}, 1000);
				
			  },
			error: function(){
				
				setTimeout(function(){
					alert("[D002] --- Ajax Error !!! Cannot operate");
				}, 600);
				
			}
		});
	}
}

<!--unlock user-->
function unlockUser(id)
{
	var result = confirm("[C003] --- Do you want to unlock this account ("+ id +") ??? ");
	if(result)
	{
		$.ajax({
		  type: 'POST',
		  url: '<?=$CFG->src_user_mst;?>/userUnlock.php',
		  data: {
					t_acc: id,
					cmd_ok: "unlock"
				},
			success: function(response){
				
				//success
				setTimeout(function(){
					alert(response);
				}, 600);
				
				//refresh
				setTimeout(function(){
					location.reload();
				}, 1000);
				
			  },
			error: function(){
				
				setTimeout(function(){
					alert("[D002] --- Ajax Error !!! Cannot operate");
				}, 600);
				
			}
		});
	}
}
</script>
</body>
</html>