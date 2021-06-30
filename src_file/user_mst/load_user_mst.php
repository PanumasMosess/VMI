<?
require_once("../../application.php");
require_once("../../js_css_header.php");

$buffer_date = date("Y-m-d");

/**********************************************************************************/
/*current user ********************************************************************/
$t_cur_user_code_VMI_GDJ = isset($_SESSION['t_cur_user_code_VMI_GDJ']) ? $_SESSION['t_cur_user_code_VMI_GDJ'] : '';
$t_cur_user_type_VMI_GDJ = isset($_SESSION['t_cur_user_type_VMI_GDJ']) ? $_SESSION['t_cur_user_type_VMI_GDJ'] : '';

?>

<div class="box-body table-responsive padding">
    <table id="tbl_detail_driver" class="table table-bordered table-hover table-striped nowrap">
        <thead>
            <tr style="font-size: 18px;">
                <th colspan="13" class="bg-light-blue"><b>
                        <font style="color: #FFF;"><i class="fa fa-users fa-lg"></i>&nbsp;User Master Data</font>
                    </b>
                </th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px; text-align: center;">No.</th>
                <th style="text-align: center;">Actions.</th>
                <th style="text-align: center;">User Code</th>
                <th style="text-align: center;">User Name (TH)</th>
                <th style="text-align: center;">User Name (EN)</th>
                <th style="text-align: center;">User Company</th>
                <th style="text-align: center;">User Section</th>
                <th style="text-align: center;">User Email</th>
                <th style="text-align: center;">User Tel.</th>
                <th style="text-align: center;">User Type</th>
                <th style="text-align: center;">User Issue By</th>             
                <th style="text-align: center;">User Issue Date</th>        
            </tr>
        </thead>
        <tbody>
            <?
$strSql = " 
SELECT [user_id]
      ,[user_code]
      ,[user_pass_md5]
      ,[user_name_th]
      ,[user_name_en]
      ,[user_company]
      ,[user_section]
      ,[user_email]
      ,[user_tel]
      ,[user_type]
      ,[user_level]
      ,[user_enable]
      ,[user_force_pass_chg]
      ,[user_issue_by]
      ,[user_issue_date]
      ,[user_issue_time]
      ,[user_issue_datetime]
  FROM [tbl_user]
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

    $user_id = $objResult['user_id'];
    $user_code = $objResult['user_code'];
    $user_name_th = $objResult['user_name_th'];
    $user_name_en = $objResult['user_name_en'];
    $user_company = $objResult['user_company'];
    $user_section = $objResult['user_section'];
    $user_email = $objResult['user_email'];
    $user_tel = $objResult['user_tel'];
    $user_type = $objResult['user_type'];
    $user_enable = $objResult['user_enable'];
    $user_issue_by = $objResult['user_issue_by'];
    $user_issue_date = $objResult['user_issue_date'];
   
?>
            <tr style="font-size: 13px;">
                <td align="center"><?=$row_id; ?></td>
                <td>
                <?
                    if($user_enable == "1"){ ?>  
                        <button type="button" class="btn btn-primary btn-sm" id="" onclick="openUpdateUser('<?=$user_id;?>','<?=$user_code;?>','<?=$user_name_th;?>', '<?=$user_name_en;?>', '<?=$user_company;?>', '<?=$user_section;?>', '<?=$user_email;?>', 
                        '<?=$user_tel;?>', '<?=$user_type;?>');" data-placement="top" data-toggle="tooltip" data-original-title="Update Driver"><i class="fa fa-pencil-square-o fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="" onclick="inActiveDriver('<?=$user_id;?>');" data-placement="top" data-toggle="tooltip" data-original-title="Delete Driver"><i class="fa fa-user-times fa-lg"></i></button>&nbsp;
                        <!-- <button type="button" class="btn btn-warning btn-sm" id="" onclick="openChangePassDriver('<?=$driver_id;?>');" data-placement="top" data-toggle="tooltip" data-original-title="Change Password"><i class="fa fa-key fa-lg"></i></button> -->
                  <?  }else{ ?>

                  <?  } ?>       
                </td>
                <td align="center"><?=$user_code; ?></td>
                <td align="center"><?=$user_name_th; ?></td>
                <td align="center"><?=$user_name_en; ?></td>
                <td align="center"><?=$user_company; ?></td>
                <td align="center"><?=$user_section; ?></td>
                <td align="center"><?=$user_email; ?></td>
                <td align="center"><?=$user_tel; ?></td>
                <td align="center"><?=$user_type; ?></td>
                <td align="center"><?=$user_issue_by; ?></td>
                <td align="center"><?=$user_issue_date; ?></td>
            </tr>
            <?
}
?>
        </tbody>
    </table>
</div>


<!-- /.box-body -->
<?
require_once("../../js_css_footer_noConflict.php");
?>

<script language="javascript">
    $(document).ready(function() {
        var check_data = $('#tbl_detail_driver').DataTable({
            rowReorder: true,
            "aLengthMenu": [[10,25, 50, 75, 100, -1], [10,25, 50, 75, 100, "All"]],
		    "iDisplayLength": 10,
            columnDefs: [{
                    orderable: true,
                    className: 'reorder',
                    targets: [0, 2, 3, 4, 5, 6, 7]
                },
                {
                    orderable: false,
                    targets: '_all'
                },
                {
                    targets: 10,
                    render: function(data, type, row) {
                        var color = 'black';
                        if (data == "Active") {
                            color = 'green';
                        }
                        else {
                            color = 'red';
                         }
                        return '<span style="color:' + color + '">' + data + '</span>';
                    }
                }
            ],
            pagingType: "full_numbers",
        });
        if (check_data.rows().count() == 0) {

            $('#remove_temp').prop('disabled', true);
        } else {

            $('#remove_temp').prop('disabled', false);
        }
    });
</script>