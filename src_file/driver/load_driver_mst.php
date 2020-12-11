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
                        <font style="color: #FFF;"><i class="fa fa-car fa-lg"></i>&nbsp;Driver Master Data</font>
                    </b>
                </th>
            </tr>
            <tr style="font-size: 13px;">
                <th style="width: 30px; text-align: center;">No.</th>
                <th style="text-align: center;">Actions.</th>
                <th style="text-align: center;">Driver Code</th>
                <th style="text-align: center;">Driver Name Th</th>
                <th style="text-align: center;">Driver Name EN</th>
                <th style="text-align: center;">Driver Company</th>
                <th style="text-align: center;">Driver Section</th>
                <th style="text-align: center;">Head Vehicle Registration Number</th>
                <th style="text-align: center;">Tail Vehicle Registration Number</th>
                <th style="text-align: center;">Tuck Type</th>             
                <th style="text-align: center;">Driver Status</th>
                <th style="text-align: center;">Issue By</th>
                <th style="text-align: center;">Issue Datetime</th>             
            </tr>
        </thead>
        <tbody>
            <?
$strSql = " 
SELECT 
       [driver_id]
      ,[driver_code]    
      ,[driver_name_th]
      ,[driver_name_en]
      ,[driver_company]
      ,[driver_section]
      ,[driver_truck_head_no]
      ,[driver_truck_tail_no]
      ,[driver_truck_type]
      ,[driver_sign]
      ,[driver_status]
      ,[driver_issue_by]
      ,[driver_issue_datetime]
  FROM [tbl_driver_mst]
";

$objQuery = sqlsrv_query($db_con, $strSql, $params, $options);
$num_row = sqlsrv_num_rows($objQuery);

$row_id = 0;
while($objResult = sqlsrv_fetch_array($objQuery, SQLSRV_FETCH_ASSOC))
{
	$row_id++;

    $driver_id = $objResult['driver_id'];
    $driver_code = $objResult['driver_code'];
    $driver_name_th = $objResult['driver_name_th'];
    $driver_name_en = $objResult['driver_name_en'];
    $driver_company = $objResult['driver_company'];
    $driver_section = $objResult['driver_section'];
    $driver_truck_head_no = $objResult['driver_truck_head_no'];
    $driver_truck_tail_no = $objResult['driver_truck_tail_no'];
    $driver_truck_type = $objResult['driver_truck_type'];
    $driver_sign = $objResult['driver_sign'];
    $driver_status = $objResult['driver_status'];
    $driver_issue_by = $objResult['driver_issue_by'];
    $driver_issue_datetime = $objResult['driver_issue_datetime'];
   
?>
            <tr style="font-size: 13px;">
                <td align="center"><?=$row_id; ?></td>
                <td>
                <?
                    if($driver_status == "Active"){ ?>  
                        <button type="button" class="btn btn-primary btn-sm" id="" onclick="openUpdateDriver('<?=$driver_id;?>','<?=$driver_code;?>','<?=$driver_name_th;?>', '<?=$driver_name_en;?>', '<?=$driver_company;?>', '<?=$driver_section;?>', '<?=$driver_truck_head_no;?>', 
                        '<?=$driver_truck_head_no;?>', '<?=$driver_truck_tail_no;?>', '<?=$driver_truck_type;?>' );" data-placement="top" data-toggle="tooltip" data-original-title="Update Driver"><i class="fa fa-check-square-o fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-danger btn-sm" id="" onclick="inActiveDriver('<?=$driver_id;?>');" data-placement="top" data-toggle="tooltip" data-original-title="Delete Driver"><i class="fa fa-times fa-lg"></i></button>&nbsp;&nbsp;<button type="button" class="btn btn-warning btn-sm" id="" onclick="openChangePassDriver('<?=$driver_id;?>');" data-placement="top" data-toggle="tooltip" data-original-title="Change Password"><i class="fa fa-key fa-lg"></i></button>
                  <?  }else{ ?>

                  <?  } ?>       
                </td>
                <td align="center"><?=$driver_code; ?></td>
                <td align="center"><?=$driver_name_th; ?></td>
                <td align="center"><?=$driver_name_en; ?></td>
                <td align="center"><?=$driver_company; ?></td>
                <td align="center"><?=$driver_section; ?></td>
                <td align="center"><?=$driver_truck_head_no; ?></td>
                <td align="center"><?=$driver_truck_tail_no; ?></td>
                <td align="center"><?=$driver_truck_type; ?></td>
                <td align="center"><?=$driver_status; ?></td>
                <td align="center"><?=$driver_issue_by; ?></td>
                <td align="center"><?=$driver_issue_datetime; ?></td>
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
            "aLengthMenu": [
                [25, 50, 75, 100, -1],
                [25, 50, 75, 100, "All"]
            ],
            "iDisplayLength": -1,
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